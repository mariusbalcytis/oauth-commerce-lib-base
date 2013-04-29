<?php

namespace Maba\OAuthCommerceClient\Plugin;

use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials;
use Maba\OAuthCommerceClient\MacSignature\AlgorithmManager;
use Maba\OAuthCommerceClient\MacSignature\ExtensionProviderInterface;
use Maba\OAuthCommerceClient\MacSignature\TokenExtensionProvider;
use Maba\OAuthCommerceClient\Random\DefaultRandomProvider;
use Maba\OAuthCommerceClient\Random\RandomProviderInterface;
use Maba\OAuthCommerceClient\Time\DefaultTimeProvider;
use Maba\OAuthCommerceClient\Time\TimeProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MacSignatureProvider implements EventSubscriberInterface
{
    const DEFAULT_NONCE_LENGTH = 16;

    /**
     * @var array available character ranges for header values.
     * From http://tools.ietf.org/html/draft-ietf-oauth-v2-http-mac-02#section-3.1
     */
    static protected $characterRanges = array(
        array('from' => 0x20, 'to' => 0x21),
        array('from' => 0x23, 'to' => 0x5b),
        array('from' => 0x5d, 'to' => 0x7e),
    );

    /**
     * @var SignatureCredentials
     */
    protected $signatureCredentials;

    /**
     * @var TimeProviderInterface
     */
    protected $timeProvider;

    /**
     * @var RandomProviderInterface
     */
    protected $randomProvider;

    /**
     * @var integer
     */
    protected $nonceLength = self::DEFAULT_NONCE_LENGTH;

    /**
     * @var ExtensionProviderInterface
     */
    protected $extensionProvider;

    /**
     * @var AlgorithmManager
     */
    protected $algorithmManager;

    /**
     * @param SignatureCredentials $signatureCredentials
     * @param AlgorithmManager     $algorithmManager
     */
    public function __construct(SignatureCredentials $signatureCredentials, AlgorithmManager $algorithmManager)
    {
        $this->signatureCredentials = $signatureCredentials;
        $this->algorithmManager = $algorithmManager;
        $this->timeProvider = new DefaultTimeProvider();
        $this->randomProvider = new DefaultRandomProvider();
        $this->extensionProvider = new TokenExtensionProvider($algorithmManager);
    }

    /**
     * @param \Maba\OAuthCommerceClient\MacSignature\ExtensionProviderInterface $extensionProvider
     *
     * @return $this
     */
    public function setExtensionProvider($extensionProvider)
    {
        $this->extensionProvider = $extensionProvider;

        return $this;
    }

    /**
     * @param int $nonceLength
     *
     * @return $this
     */
    public function setNonceLength($nonceLength)
    {
        $this->nonceLength = $nonceLength;

        return $this;
    }

    /**
     * @param \Maba\OAuthCommerceClient\Random\RandomProviderInterface $randomProvider
     *
     * @return $this
     */
    public function setRandomProvider($randomProvider)
    {
        $this->randomProvider = $randomProvider;

        return $this;
    }

    /**
     * @param \Maba\OAuthCommerceClient\Time\TimeProviderInterface $timeProvider
     *
     * @return $this
     */
    public function setTimeProvider($timeProvider)
    {
        $this->timeProvider = $timeProvider;

        return $this;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => 'onBeforeSend'
        );
    }

    /**
     * Adds MAC Authorization header to request before sending
     *
     * @param Event $event
     */
    public function onBeforeSend(Event $event)
    {
        /** @var $request Request */
        $request = $event['request'];

        $id = $this->signatureCredentials->getMacId();
        $ts = $this->timeProvider->getCurrentTime()->getTimestamp();
        $nonce = $this->randomProvider->generateStringForRanges($this->nonceLength, self::$characterRanges);
        $ext = $this->extensionProvider->getExtensionParameters($request, $this->signatureCredentials, $ts, $nonce);
        $ext = http_build_query($ext, null, '&');
        $mac = $this->algorithmManager->generateMac($request, $this->signatureCredentials, $ts, $nonce, $ext);

        $params = array(
            'id' => $id,
            'ts' => $ts,
            'nonce' => $nonce,
            'ext' => $ext,
            'mac' => $mac,
        );

        $list = array();
        foreach ($params as $key => $value) {
            $list[] = $key . '="' . $value . '"';
        }
        $header = 'MAC ' . implode(', ', $list);

        $request->setHeader('Authorization', $header);
    }
}
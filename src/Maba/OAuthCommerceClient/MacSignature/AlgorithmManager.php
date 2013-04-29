<?php


namespace Maba\OAuthCommerceClient\MacSignature;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Url;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials;
use Maba\OAuthCommerceClient\Registry;

class AlgorithmManager
{
    /**
     * @var \Maba\OAuthCommerceClient\Registry
     */
    protected $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param Request              $request
     * @param SignatureCredentials $signatureCredentials
     * @param integer              $ts
     * @param string               $nonce
     * @param string               $ext
     *
     * @throws \RuntimeException
     * @internal param array $params
     * @return string
     */
    public function generateMac(Request $request, SignatureCredentials $signatureCredentials, $ts, $nonce, $ext)
    {
        $algorithm = $this->getAlgorithmForCredentials($signatureCredentials);

        $urlObject = $request->getUrl(true);
        $url = Url::buildUrl(array(
            'path' => $urlObject->getPath(),
            'query' => (string) $urlObject->getQuery() ?: null,
        ));

        $normalizedRequestParts = array(
            $ts,
            $nonce,
            $request->getMethod(),
            $url,
            strtolower($request->getHost()),
            $request->getPort(),
            $ext,
        );

        $normalizedRequestString = implode("\n", $normalizedRequestParts) . "\n";

        return $algorithm->sign($normalizedRequestString, $signatureCredentials);
    }

    /**
     * @param SignatureCredentials $signatureCredentials
     *
     * @throws \RuntimeException
     * @return string
     */
    public function getHashAlgorithm(SignatureCredentials $signatureCredentials)
    {
        return $this->getAlgorithmForCredentials($signatureCredentials)->getHashAlgorithm();
    }

    /**
     * @param SignatureCredentials $signatureCredentials
     *
     * @return SignatureAlgorithmInterface
     * @throws \RuntimeException
     */
    protected function getAlgorithmForCredentials(SignatureCredentials $signatureCredentials)
    {
        return $this->registry->getSignatureAlgorithm($signatureCredentials->getAlgorithm());
    }
}
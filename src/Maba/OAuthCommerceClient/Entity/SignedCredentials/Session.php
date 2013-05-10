<?php


namespace Maba\OAuthCommerceClient\Entity\SignedCredentials;


use Guzzle\Service\Command\OperationCommand;

class Session
{
    /**
     * @var Certificate
     */
    protected $certificate;

    /**
     * @var KeyExchange
     */
    protected $keyExchange;

    /**
     * @var Cipher
     */
    protected $cipher;

    /**
     * @var string
     */
    protected $sessionId;

    public static function create()
    {
        return new static();
    }

    /**
     * @param \Maba\OAuthCommerceClient\Entity\SignedCredentials\Certificate $certificate
     *
     * @return $this
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;

        return $this;
    }

    /**
     * @return \Maba\OAuthCommerceClient\Entity\SignedCredentials\Certificate
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @param \Maba\OAuthCommerceClient\Entity\SignedCredentials\Cipher $cipher
     *
     * @return $this
     */
    public function setCipher($cipher)
    {
        $this->cipher = $cipher;

        return $this;
    }

    /**
     * @return \Maba\OAuthCommerceClient\Entity\SignedCredentials\Cipher
     */
    public function getCipher()
    {
        return $this->cipher;
    }

    /**
     * @param \Maba\OAuthCommerceClient\Entity\SignedCredentials\KeyExchange $keyExchange
     *
     * @return $this
     */
    public function setKeyExchange($keyExchange)
    {
        $this->keyExchange = $keyExchange;

        return $this;
    }

    /**
     * @return \Maba\OAuthCommerceClient\Entity\SignedCredentials\KeyExchange
     */
    public function getKeyExchange()
    {
        return $this->keyExchange;
    }

    /**
     * @param string $sessionId
     *
     * @return $this
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }


}
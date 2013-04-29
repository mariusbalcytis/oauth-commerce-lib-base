<?php


namespace Maba\OAuthCommerceClient\MacSignature;

use Maba\OAuthCommerceClient\Entity\SignatureCredentials;

class HmacAlgorithm implements SignatureAlgorithmInterface
{
    /**
     * @var string
     */
    protected $hashAlgorithm;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $hashAlgorithm
     * @param string $type
     */
    public function __construct($hashAlgorithm, $type)
    {
        $this->hashAlgorithm = $hashAlgorithm;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getHashAlgorithm()
    {
        return $this->hashAlgorithm;
    }

    /**
     * @param string               $text
     * @param SignatureCredentials $credentials
     *
     * @return string
     */
    public function sign($text, SignatureCredentials $credentials)
    {
        return base64_encode(hash_hmac($this->getHashAlgorithm(), $text, $credentials->getMacKey(), true));
    }

}
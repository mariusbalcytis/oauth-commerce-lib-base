<?php

namespace Maba\OAuthCommerceClient\MacSignature;

use Maba\OAuthCommerceClient\Entity\SignatureCredentials;

class RsaAlgorithm implements SignatureAlgorithmInterface
{
    const PADDING_PKCS1 = 'pkcs1';

    /**
     * @var string
     */
    protected $hashAlgorithm;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $padding;

    /**
     * @param string $hashAlgorithm
     * @param string $type
     * @param string $padding
     */
    public function __construct($hashAlgorithm, $type, $padding = self::PADDING_PKCS1)
    {
        $this->hashAlgorithm = $hashAlgorithm;
        $this->type = $type;
        $this->padding = $padding;
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
        $privateKey = openssl_pkey_get_private($credentials->getMacKey());
        openssl_sign($text, $signature, $privateKey, $this->getHashAlgorithm());
        openssl_free_key($privateKey);
        return base64_encode($signature);
    }

}
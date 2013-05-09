<?php

namespace Maba\OAuthCommerceClient\MacSignature;

use Maba\OAuthCommerceClient\Entity\SignatureCredentials;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials\AsymmetricCredentials;

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
     * @throws \InvalidArgumentException
     * @return string
     */
    public function sign($text, SignatureCredentials $credentials)
    {
        if (!$credentials instanceof AsymmetricCredentials) {
            throw new \InvalidArgumentException('RsaAlgorithms works with asymmetric credentials');
        }
        $privateKey = openssl_pkey_get_private($credentials->getPrivateKey());
        openssl_sign($text, $signature, $privateKey, $this->getHashAlgorithm());
        openssl_free_key($privateKey);
        return base64_encode($signature);
    }

    /**
     * @param array $data
     *
     * @return SignatureCredentials
     */
    public function createSignatureCredentials(array $data)
    {
        $credentials = new AsymmetricCredentials();
        $credentials->setMacId($data['access_token']);
        $credentials->setAlgorithm($data['mac_algorithm']);
        $credentials->setPrivateKey($data['mac_key']);
        $credentials->setPublicKey($data['public_key']);
        return $credentials;
    }
    /**
     * @param SignatureCredentials $credentials
     *
     * @throws \InvalidArgumentException
     * @return array
     */
    public function normalizeSignatureCredentials(SignatureCredentials $credentials)
    {
        if (!$credentials instanceof AsymmetricCredentials) {
            throw new \InvalidArgumentException('RsaAlgorithms works with asymmetric credentials');
        }
        return array(
            'access_token' => $credentials->getMacId(),
            'mac_algorithm' => $credentials->getAlgorithm(),
            'public_key' => $credentials->getPublicKey(),
        );
    }


}
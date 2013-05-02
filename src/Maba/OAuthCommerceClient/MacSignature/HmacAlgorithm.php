<?php


namespace Maba\OAuthCommerceClient\MacSignature;

use Maba\OAuthCommerceClient\Entity\SignatureCredentials;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials\SymmetricCredentials;

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
     * @throws \InvalidArgumentException
     * @return string
     */
    public function sign($text, SignatureCredentials $credentials)
    {
        if (!$credentials instanceof SymmetricCredentials) {
            throw new \InvalidArgumentException('HmacAlgorithms works with symmetric credentials');
        }
        return base64_encode(hash_hmac($this->getHashAlgorithm(), $text, $credentials->getSharedKey(), true));
    }

    /**
     * @param array $data
     *
     * @return SignatureCredentials
     */
    public function createSignatureCredentials(array $data)
    {
        $credentials = new SymmetricCredentials();
        $credentials->setMacId($data['mac_id']);
        $credentials->setAlgorithm($data['mac_algorithm']);
        $credentials->setSharedKey($data['mac_key']);
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
        if (!$credentials instanceof SymmetricCredentials) {
            throw new \InvalidArgumentException('HmacAlgorithms works with symmetric credentials');
        }
        return array(
            'mac_id' => $credentials->getMacId(),
            'mac_algorithm' => $credentials->getAlgorithm(),
        );
    }

}
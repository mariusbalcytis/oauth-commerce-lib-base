<?php


namespace Maba\OAuthCommerceClient;

use Maba\OAuthCommerceClient\Hash\HasherInterface;
use Maba\OAuthCommerceClient\MacSignature\SignatureAlgorithmInterface;
use Maba\OAuthCommerceClient\KeyExchange\KeyExchangeInterface;
use Maba\OAuthCommerceClient\SymmetricEncrypting\EncryptingInterface;

class Registry
{
    /**
     * @var array|SignatureAlgorithmInterface[]
     */
    protected $signatureAlgorithms = array();

    /**
     * @var array|HasherInterface[]
     */
    protected $hashers = array();

    /**
     * @var array|KeyExchangeInterface[]
     */
    protected $keyExchanges = array();

    /**
     * @var array|EncryptingInterface[]
     */
    protected $encryptings = array();


    static public function create()
    {
        return new static();
    }

    /**
     * @param HasherInterface $hasher
     *
     * @return $this
     */
    public function addHasher(HasherInterface $hasher)
    {
        $this->hashers[$hasher->getType()] = $hasher;
        return $this;
    }

    /**
     * @param KeyExchangeInterface $keyExchange
     *
     * @return $this
     */
    public function addKeyExchange(KeyExchangeInterface $keyExchange)
    {
        $this->keyExchanges[$keyExchange->getType()] = $keyExchange;
        return $this;
    }

    /**
     * @param EncryptingInterface $encrypting
     *
     * @return $this
     */
    public function addEncrypting(EncryptingInterface $encrypting)
    {
        $this->encryptings[$encrypting->getType()] = $encrypting;
        return $this;
    }

    /**
     * @param SignatureAlgorithmInterface $algorithm
     *
     * @return $this
     */
    public function addSignatureAlgorithm(SignatureAlgorithmInterface $algorithm)
    {
        $this->signatureAlgorithms[$algorithm->getType()] = $algorithm;
        return $this;
    }

    /**
     * @param string $type
     *
     * @return HasherInterface
     * @throws \RuntimeException
     */
    public function getHasher($type)
    {
        if (!isset($this->hashers[$type])) {
            throw new \RuntimeException('Hasher not configured: ' . $type);
        }
        return $this->hashers[$type];
    }

    /**
     * @param string $type
     *
     * @return KeyExchangeInterface
     * @throws \RuntimeException
     */
    public function getKeyExchange($type)
    {
        if (!isset($this->keyExchanges[$type])) {
            throw new \RuntimeException('Key exchange not configured: ' . $type);
        }
        return $this->keyExchanges[$type];
    }

    /**
     * @param string $type
     *
     * @return EncryptingInterface
     * @throws \RuntimeException
     */
    public function getEncrypting($type)
    {
        if (!isset($this->encryptings[$type])) {
            throw new \RuntimeException('Encrypting not configured: ' . $type);
        }
        return $this->encryptings[$type];
    }

    /**
     * @param string $type
     *
     * @return SignatureAlgorithmInterface
     * @throws \RuntimeException
     */
    public function getSignatureAlgorithm($type)
    {
        if (!isset($this->signatureAlgorithms[$type])) {
            throw new \RuntimeException('Algorithm not configured: ' . $type);
        }
        return $this->signatureAlgorithms[$type];
    }
}
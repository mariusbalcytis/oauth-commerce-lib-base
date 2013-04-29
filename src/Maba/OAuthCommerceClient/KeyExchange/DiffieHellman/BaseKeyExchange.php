<?php

namespace Maba\OAuthCommerceClient\KeyExchange\DiffieHellman;

use Maba\OAuthCommerceClient\KeyExchange\KeyExchangeInterface;
use Zend\Crypt\PublicKey\DiffieHellman;
use Guzzle\Common\Collection;

abstract class BaseKeyExchange implements KeyExchangeInterface
{
    /**
     * @param array      $keyExchangeParameters parameters, passed from remote server
     * @param string     $serverCertificate     remote server certificate
     * @param Collection $additionalParameters  additional parameters to add to request
     * @param integer    $sharedKeyLength       in bytes
     *
     * @throws \InvalidArgumentException
     * @return string binary shared key for symmetric algorithm
     */
    public function generateCommonKey(
        array $keyExchangeParameters,
        $serverCertificate,
        Collection $additionalParameters,
        $sharedKeyLength
    ) {
        if (!isset($keyExchangeParameters['public_key'])) {
            throw new \InvalidArgumentException('Parameter public_key is required');
        }
        $serverPublicKey = base64_decode($keyExchangeParameters['public_key']);

        $diffieHellman = new DiffieHellman($this->getPrime(), $this->getGenerator());
        $diffieHellman->generateKeys();
        $secretKey = $diffieHellman->computeSecretKey(
            $serverPublicKey,
            DiffieHellman::FORMAT_BINARY,
            DiffieHellman::FORMAT_BINARY
        );

        $clientPublicKey = $diffieHellman->getPublicKey(DiffieHellman::FORMAT_BINARY);

        openssl_public_encrypt($clientPublicKey, $encryptedPublicKey, $serverCertificate, OPENSSL_PKCS1_PADDING);
        $additionalParameters->add('encrypted_public_key', base64_encode($encryptedPublicKey));

        return substr(hash('sha256', $secretKey, true), 0, $sharedKeyLength);
    }

    /**
     * @return string
     */
    public abstract function getType();

    /**
     * @return string big decimal
     */
    protected abstract function getPrime();

    /**
     * @return string big decimal
     */
    protected abstract function getGenerator();
}
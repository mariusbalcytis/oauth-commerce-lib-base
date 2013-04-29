<?php

namespace Maba\OAuthCommerceClient;

use Guzzle\Common\Collection;
use Guzzle\Http\Client;
use Maba\OAuthCommerceClient\Entity\AccessToken;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials;
use Maba\OAuthCommerceClient\Entity\SignedCredentials\Session;
use Maba\OAuthCommerceClient\Entity\UserCredentials\CredentialsInterface;
use Maba\OAuthCommerceClient\Hash\Hasher;
use Maba\OAuthCommerceClient\MacSignature\AlgorithmManager;
use Maba\OAuthCommerceClient\MacSignature\HmacAlgorithm;
use Maba\OAuthCommerceClient\MacSignature\RsaAlgorithm;
use Maba\OAuthCommerceClient\Plugin\MacSignatureProvider;
use Maba\OAuthCommerceClient\KeyExchange\DiffieHellman\Group16KeyExchange;
use Maba\OAuthCommerceClient\SymmetricEncrypting\Encrypting;

class OAuthCommerceClient
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var \Guzzle\Http\Client
     */
    protected $client;


    public function __construct($baseUrl = '', $config = null)
    {
        $default = array('credentials_algorithm' => 'hmac-sha-256', 'version' => 'v1');
        $required = array('base_url', 'credentials_algorithm', 'credentials_id', 'credentials_key');
        $config = Collection::fromConfig($config, $default, $required);

        $this->client = new Client($baseUrl, $config);

        $this->registry = Registry::create()
            ->addHasher(new Hasher('sha256', 'sha-256'))
            ->addHasher(new Hasher('sha512', 'sha-512'))
            ->addSignatureAlgorithm(new HmacAlgorithm('sha256', 'hmac-sha-256'))
            ->addSignatureAlgorithm(new HmacAlgorithm('sha512', 'hmac-sha-512'))
            ->addSignatureAlgorithm(new RsaAlgorithm('sha256', 'rsa-pkcs1-sha-256', RsaAlgorithm::PADDING_PKCS1))
            ->addSignatureAlgorithm(new RsaAlgorithm('sha512', 'rsa-pkcs1-sha-512', RsaAlgorithm::PADDING_PKCS1))
            ->addKeyExchange(new Group16KeyExchange())
            ->addEncrypting(new Encrypting('rijndael-128', 'aes-128-cbc'))
            ->addEncrypting(new Encrypting('rijndael-256', 'aes-256-cbc'))
        ;

        $signatureCredentials = new SignatureCredentials();
        $signatureCredentials
            ->setAlgorithm($config->get('credentials_algorithm'))
            ->setMacId($config->get('credentials_id'))
            ->setMacKey($config->get('credentials_key'))
        ;
        $this->client->addSubscriber(
            new MacSignatureProvider($signatureCredentials, new AlgorithmManager($this->registry))
        );
    }

    /**
     * @return \Maba\OAuthCommerceClient\Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }


    public function createSecretCredentialsToken(CredentialsInterface $credentials)
    {
        $response = $this->client
            ->post('auth/secret-credentials/session', null, $credentials->toPublicArray())
            ->send()
            ->json()
        ;
        $session = Session::fromArray($response);
        // do something with $session

        $response = $this->client
            ->post('auth/secret-credentials/token', null, array('some' => 'data'))
            ->send()
            ->json()
        ;
        return AccessToken::fromArray($response);
    }

}
<?php

namespace Maba\OAuthCommerceClient;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Maba\OAuthCommerceClient\Entity\SignedCredentials\Session;
use Maba\OAuthCommerceClient\Entity\UserCredentials\CredentialsInterface;
use Maba\OAuthCommerceClient\Exception\InvalidHashException;

/**
 * Class AuthClient
 */
class AuthClient extends BaseClient
{

    public function createSecretCredentialsToken(CredentialsInterface $credentials, array $scopes = array())
    {
        /** @var Command $createSession */
        $createSession = $this->createCommand()
            ->setRequest($this->client->post('encrypted-credentials/session'))
            ->setBodyEntity($credentials->toPublicArray(), 'urlencoded')
            ->setResponseClass('Maba\OAuthCommerceClient\Entity\SignedCredentials\Session')
        ;

        $httpClient = $this->client;
        return $this->createCommand()
            ->setBeforeExecute(function(Command $command) use ($createSession, $credentials, $scopes, $httpClient) {
                /** @var Session $session */
                $session = $createSession->getResult();

                /** @var Registry $registry */
                $registry = null;
                $keyExchange = $registry->getKeyExchange($session->getKeyExchange()->getType());
                $encrypting = $registry->getEncrypting($session->getCipher()->getType());
                $hasher = $registry->getHasher($session->getCertificate()->getHashType());

                $serverCertificate = (string)$httpClient->get($session->getCertificate()->getUrl())->getResponseBody();

                $hash = $hasher->hash($serverCertificate);
                if ($session->getCertificate()->getHash() !== $hash) {
                    throw new InvalidHashException(sprintf(
                        'Provided hash (%s) does is not as calculated from downloaded certificate (%s)',
                        $session->getCertificate()->getHash(),
                        $hash
                    ));
                }

                $additionalParameters = new Collection();
                $commonKey = $keyExchange->generateCommonKey(
                    $session->getKeyExchange()->getParameters(),
                    $serverCertificate,
                    $additionalParameters,
                    $encrypting->getKeyLength()
                );
                $encoded = http_build_query($credentials->toPrivateArray(), null, '&');
                $encrypted = $encrypting->encrypt($encoded, $session->getCipher()->getIv(), $commonKey);

                $command->setBodyEntity(array(
                    'grant_type' => 'urn:marius-balcytis:oauth:grant-type:encrypted-credentials',
                    'scope' => implode(' ', $scopes),
                    'session_id' => $session->getSessionId(),
                    'encrypted_credentials' => $encrypted,
                ) + $additionalParameters->getAll(), 'urlencoded');
            })
            ->setRequest($this->client->post('secret-credentials/token'))
            ->setResponseClass('Maba\OAuthCommerceClient\Entity\AccessToken')
        ;
    }

    /**
     * @param string $codeValue
     * @param string $redirectUri
     *
     * @return Command<AccessToken>
     */
    public function exchangeCodeForToken($codeValue, $redirectUri)
    {
        return $this->createCommand()
            ->setRequest($this->client->post('token'))
            ->setBodyEntity(array(
                'grant_type' => 'authorization_code',
                'code' => $codeValue,
                'redirect_uri' => $redirectUri,
            ), 'urlencoded')
            ->setResponseClass('Maba\OAuthCommerceClient\Entity\AccessToken')
        ;
    }

    /**
     * @return Command
     */
    public function revokeCurrentCredentials()
    {
        return $this->createCommand()->setRequest($this->client->delete('client'));
    }

    /**
     * @param string $applicationId
     * @param string $applicationSecret
     *
     * @return Command<SignatureCredentials>
     */
    public function createAnonymousClient($applicationId, $applicationSecret)
    {
        return $this->createCommand()
            ->setRequest($this->client->post('client'))
            ->setBodyEntity(array(
                'application_id' => $applicationId,
                'application_secret' => $applicationSecret,
            ), 'urlencoded')
            ->setResponseClass('Maba\OAuthCommerceClient\Entity\SignatureCredentials')
        ;
    }

}
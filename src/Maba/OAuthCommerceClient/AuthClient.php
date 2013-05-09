<?php

namespace Maba\OAuthCommerceClient;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Maba\OAuthCommerceClient\Entity\UserCredentials\CredentialsInterface;

/**
 * Class AuthClient
 */
class AuthClient extends BaseClient
{

    public function createSecretCredentialsToken(CredentialsInterface $credentials)
    {
        /** @var Command $createSessionCommand */
        $createSessionCommand = $this->createCommand()
            ->setRequest($this->client->post('secret-credentials/session'))
            ->setBodyEntity($credentials, 'urlencoded')
            ->setResponseClass('Maba\OAuthCommerceClient\Entity\SignedCredentials\Session')
        ;

        return $this->createCommand()
            ->setBeforeExecute(function(Command $command) use ($createSessionCommand) {
//                $createSessionCommand->getResult();
                $command->setBodyEntity(null, 'urlencoded');
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
<?php

namespace Maba\OAuthCommerceClient;

use Guzzle\Common\Collection;
use Guzzle\Service\Client;
use Maba\OAuthCommerceClient\Entity\AccessToken;
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
            ->setRequest($this->client->post('auth/secret-credentials/session'))
            ->setBodyEntity($credentials, 'urlencoded')
            ->setResponseClass('Maba\OAuthCommerceClient\Entity\SignedCredentials\Session')
        ;

        return $this->createCommand()
            ->setBeforeExecute(function(Command $command) use ($createSessionCommand) {
//                $createSessionCommand->getResult();
                $command->setBodyEntity(null, 'urlencoded');
            })
            ->setRequest($this->client->post('auth/secret-credentials/token'))
            ->setResponseClass('Maba\OAuthCommerceClient\Entity\AccessToken')
        ;
    }

}
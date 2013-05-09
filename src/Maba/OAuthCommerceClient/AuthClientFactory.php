<?php


namespace Maba\OAuthCommerceClient;

use Guzzle\Service\Client;

class AuthClientFactory extends BaseClientFactory
{
    protected function constructClient(Client $guzzleClient)
    {
        return new AuthClient($guzzleClient, $this->serializer);
    }

}
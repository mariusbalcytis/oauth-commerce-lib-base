<?php


namespace Maba\OAuthCommerceClient;

use Guzzle\Service\Client;

class AuthClientFactory extends BaseClientFactory
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param \Maba\OAuthCommerceClient\Registry $registry
     */
    public function setRegistry($registry)
    {
        $this->registry = $registry;
    }

    protected function constructClient(Client $guzzleClient)
    {
        return new AuthClient($guzzleClient, $this->serializer, $this->registry);
    }

}
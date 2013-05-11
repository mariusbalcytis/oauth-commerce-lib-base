<?php


namespace Maba\OAuthCommerceClient;

use Guzzle\Service\Client;
use Guzzle\Service\Command\CommandInterface;
use Maba\OAuthCommerceClient\Entity\AccessToken;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseClient
{

    /**
     * @var \Guzzle\Service\Client
     */
    protected $client;

    /**
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected $serializer;


    public function __construct(Client $client, SerializerInterface $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * Execute one or more commands
     *
     * @param CommandInterface|array $command Command or array of commands to execute
     *
     * @return mixed Returns the result of the executed command or an array of commands if executing multiple commands
     */
    public function execute($command)
    {
        $this->client->execute($command);
    }

    /**
     * @return \Maba\OAuthCommerceClient\Command
     */
    protected function createCommand()
    {
        return Command::create()
            ->setSerializer($this->serializer)
            ->setClient($this->client)
        ;
    }
}
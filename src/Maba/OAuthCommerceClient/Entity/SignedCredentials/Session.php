<?php


namespace Maba\OAuthCommerceClient\Entity\SignedCredentials;


use Guzzle\Service\Command\OperationCommand;

class Session
{
    /**
     * @var Certificate
     */
    protected $certificate;

    /**
     * @var KeyExchange
     */
    protected $keyExchange;

    /**
     * @var Cipher
     */
    protected $cipher;

    /**
     * @var string
     */
    protected $sessionId;


    /**
     * @param $data
     *
     * @throws \RuntimeException
     * @return static
     */
    public static function fromArray($data)
    {
        $a = new static();
        $a->sessionId = 1;
        return $a;
    }
}
<?php


namespace Maba\OAuthCommerceClient\Entity\SignedCredentials;


class Certificate
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $hashType;

    /**
     * @var string
     */
    protected $hash;
}
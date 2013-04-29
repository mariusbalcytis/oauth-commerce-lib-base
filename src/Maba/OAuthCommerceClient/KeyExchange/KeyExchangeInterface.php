<?php


namespace Maba\OAuthCommerceClient\KeyExchange;

use Guzzle\Common\Collection;

interface KeyExchangeInterface
{
    /**
     * @param array      $keyExchangeParameters parameters, passed from remote server
     * @param string     $serverCertificate     remote server certificate
     * @param Collection $additionalParameters  additional parameters to add to request
     * @param integer    $sharedKeyLength       in bytes
     *
     * @return string binary shared key for symmetric algorithm
     */
    public function generateCommonKey(
        array $keyExchangeParameters,
        $serverCertificate,
        Collection $additionalParameters,
        $sharedKeyLength
    );

    /**
     * @return string
     */
    public function getType();
}
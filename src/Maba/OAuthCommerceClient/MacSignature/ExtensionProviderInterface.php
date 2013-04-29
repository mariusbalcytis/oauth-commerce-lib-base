<?php

namespace Maba\OAuthCommerceClient\MacSignature;

use Guzzle\Http\Message\Request;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials;

interface ExtensionProviderInterface
{
    /**
     * @param Request                                               $request
     * @param \Maba\OAuthCommerceClient\Entity\SignatureCredentials $credentials
     * @param integer                                               $timestamp
     * @param string                                                $nonce
     *
     * @return array
     */
    public function getExtensionParameters(Request $request, SignatureCredentials $credentials, $timestamp, $nonce);
}
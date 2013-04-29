<?php

namespace Maba\OAuthCommerceClient\MacSignature;

use Guzzle\Http\Message\Request;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials;

class TokenExtensionProvider extends BodyHashExtensionProvider
{

    /**
     * @param Request                                               $request
     * @param \Maba\OAuthCommerceClient\Entity\SignatureCredentials $credentials
     * @param integer                                               $timestamp
     * @param string                                                $nonce
     *
     * @throws \RuntimeException
     * @return array
     */
    public function getExtensionParameters(Request $request, SignatureCredentials $credentials, $timestamp, $nonce)
    {
        $params = parent::getExtensionParameters($request, $credentials, $timestamp, $nonce);
        $tokenCredentials = $request->getParams()->get('oauth_commerce.token');
        if ($tokenCredentials === null) {
            return $params;
        } elseif (!$tokenCredentials instanceof SignatureCredentials) {
            throw new \RuntimeException('Request param oauth_commerce.token must be of type SignatureCredentials');
        }
        $mac = $this->algorithmManager->generateMac($request, $tokenCredentials, $timestamp, $nonce, '');
        return $params + array(
            'access_token_id' => $tokenCredentials->getMacId(),
            'access_token_mac' => $mac,
        );
    }


}
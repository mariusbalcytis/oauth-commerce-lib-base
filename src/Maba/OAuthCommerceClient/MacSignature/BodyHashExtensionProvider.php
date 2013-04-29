<?php

namespace Maba\OAuthCommerceClient\MacSignature;

use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Http\Message\Request;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials;

class BodyHashExtensionProvider implements ExtensionProviderInterface
{
    /**
     * @var AlgorithmManager
     */
    protected $algorithmManager;

    /**
     * @param AlgorithmManager $algorithmManager
     */
    public function __construct(AlgorithmManager $algorithmManager)
    {
        $this->algorithmManager = $algorithmManager;
    }


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
        if ($request instanceof EntityEnclosingRequestInterface) {
            if ($request->getBody() === null) {
                if (count($request->getPostFiles()) > 0) {
                    throw new \RuntimeException('Cannot get hash for file uploads');
                } elseif (count($request->getPostFields()) > 0) {
                    $body = (string) $request->getPostFields()->useUrlEncoding(true);
                } else {
                    $body = '';
                }
            } elseif (!$request->getBody()->isSeekable()) {
                throw new \RuntimeException('Request body must be known before making request to sign it');
            } else {
                $body = $request->getBody();
            }
        } else {
            $body = '';
        }
        $hashAlgorithm = $this->algorithmManager->getHashAlgorithm($credentials);
        $hash = hash($hashAlgorithm, $body, true);
        return array('bodyhash' => base64_encode($hash));
    }


}
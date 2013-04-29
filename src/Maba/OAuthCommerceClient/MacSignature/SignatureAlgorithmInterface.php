<?php

namespace Maba\OAuthCommerceClient\MacSignature;

use Maba\OAuthCommerceClient\Entity\SignatureCredentials;

interface SignatureAlgorithmInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getHashAlgorithm();

    /**
     * @param string               $text
     * @param SignatureCredentials $credentials
     *
     * @return string
     */
    public function sign($text, SignatureCredentials $credentials);
}
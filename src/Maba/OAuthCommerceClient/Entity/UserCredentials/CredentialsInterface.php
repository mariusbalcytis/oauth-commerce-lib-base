<?php


namespace Maba\OAuthCommerceClient\Entity\UserCredentials;

interface CredentialsInterface
{
    /**
     * Returns only public properties to include in first request
     *
     * @return array
     */
    public function toPublicArray();

    /**
     * Returns only private properties that must be encrypted before sending
     *
     * @return array
     */
    public function toPrivateArray();
}
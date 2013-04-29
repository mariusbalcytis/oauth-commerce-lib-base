<?php


namespace Maba\OAuthCommerceClient\Hash;


interface HasherInterface
{
    /**
     * @param string $data
     *
     * @return string
     */
    public function hash($data);

    /**
     * @return string
     */
    public function getType();
}
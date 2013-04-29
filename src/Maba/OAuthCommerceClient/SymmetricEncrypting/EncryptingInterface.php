<?php

namespace Maba\OAuthCommerceClient\SymmetricEncrypting;

interface EncryptingInterface
{
    /**
     * @param string $data
     * @param string $iv
     * @param string $key
     *
     * @return string
     */
    public function encrypt($data, $iv, $key);

    /**
     * @return integer
     */
    public function getKeyLength();

    /**
     * @return string
     */
    public function getType();
}
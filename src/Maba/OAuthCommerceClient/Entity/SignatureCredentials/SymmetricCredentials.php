<?php


namespace Maba\OAuthCommerceClient\Entity\SignatureCredentials;

use Maba\OAuthCommerceClient\Entity\SignatureCredentials;

class SymmetricCredentials extends SignatureCredentials
{
    /**
     * @var string
     */
    protected $sharedKey;

    /**
     * @param string $sharedKey
     *
     * @return $this
     */
    public function setSharedKey($sharedKey)
    {
        $this->sharedKey = $sharedKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getSharedKey()
    {
        return $this->sharedKey;
    }


}
<?php


namespace Maba\OAuthCommerceClient\Entity;


class SignatureCredentials
{
    /**
     * @var string
     */
    protected $macId;

    /**
     * @var string
     */
    protected $macKey;

    /**
     * @var string
     */
    protected $algorithm;

    /**
     * @param string $algorithm
     *
     * @return $this
     */
    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @param string $macId
     *
     * @return $this
     */
    public function setMacId($macId)
    {
        $this->macId = $macId;

        return $this;
    }

    /**
     * @return string
     */
    public function getMacId()
    {
        return $this->macId;
    }

    /**
     * @param string $macKey
     *
     * @return $this
     */
    public function setMacKey($macKey)
    {
        $this->macKey = $macKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getMacKey()
    {
        return $this->macKey;
    }


}
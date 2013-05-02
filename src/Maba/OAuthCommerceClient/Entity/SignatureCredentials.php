<?php


namespace Maba\OAuthCommerceClient\Entity;


abstract class SignatureCredentials
{
    /**
     * @var string
     */
    protected $macId;

    /**
     * @var string
     */
    protected $algorithm;


    public static function create()
    {
        return new static();
    }

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


}
<?php


namespace Maba\OAuthCommerceClient\Entity\SignedCredentials;


class KeyExchange
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $parameters;

    public static function create()
    {
        return new static();
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


}
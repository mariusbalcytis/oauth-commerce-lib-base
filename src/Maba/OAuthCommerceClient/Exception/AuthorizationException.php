<?php


namespace Maba\OAuthCommerceClient\Exception;


class AuthorizationException extends \Exception
{
    /**
     * @var string
     */
    protected $errorCode;

    /**
     * @var string
     */
    protected $errorDescription;

    /**
     * @var string
     */
    protected $errorUri;



    public static function create($message = '', $previous = null)
    {
        return new static($message, 0, $previous);
    }

    /**
     * @param string $errorCode
     *
     * @return $this
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorDescription
     *
     * @return $this
     */
    public function setErrorDescription($errorDescription)
    {
        $this->errorDescription = $errorDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorDescription()
    {
        return $this->errorDescription;
    }

    /**
     * @param string $errorUri
     *
     * @return $this
     */
    public function setErrorUri($errorUri)
    {
        $this->errorUri = $errorUri;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorUri()
    {
        return $this->errorUri;
    }

}
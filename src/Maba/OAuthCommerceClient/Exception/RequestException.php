<?php


namespace Maba\OAuthCommerceClient\Exception;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;

class RequestException extends \RuntimeException
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

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var RequestInterface
     */
    protected $request;


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

    /**
     * @param \Guzzle\Http\Message\RequestInterface $request
     *
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return \Guzzle\Http\Message\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param \Guzzle\Http\Message\Response $response
     *
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return \Guzzle\Http\Message\Response
     */
    public function getResponse()
    {
        return $this->response;
    }


}
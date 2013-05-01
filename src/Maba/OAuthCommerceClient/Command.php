<?php


namespace Maba\OAuthCommerceClient;

use Guzzle\Http\Message\EntityEnclosingRequestInterface;
use Guzzle\Service\Command\AbstractCommand;
use Guzzle\Common\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class Command extends AbstractCommand
{
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';
    const FORMAT_URL_ENCODED = 'urlencoded';

    const CONTENT_TYPE_JSON = 'application/json';
    const CONTENT_TYPE_XML = 'text/xml';
    const CONTENT_TYPE_URL_ENCODED = 'application/x-www-form-urlencoded';

    /**
     * @var SerializerInterface|DecoderInterface
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $responseClass;

    /**
     * @var object
     */
    protected $bodyEntity;

    /**
     * @var string
     */
    protected $requestFormat = self::FORMAT_JSON;

    /**
     * @var string
     */
    protected $requestContentType = self::CONTENT_TYPE_JSON;

    /**
     * @var callable
     */
    protected $beforeExecute = null;


    public static function create($parameters = null)
    {
        return new static($parameters);
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
     * @param object $bodyEntity
     * @param string $requestFormat
     *
     * @return $this
     */
    public function setBodyEntity($bodyEntity, $requestFormat = null)
    {
        $this->bodyEntity = $bodyEntity;
        if ($requestFormat !== null) {
            $this->setRequestFormat($requestFormat);
        }

        return $this;
    }

    /**
     * @param string $requestContentType
     *
     * @return $this
     */
    public function setRequestContentType($requestContentType)
    {
        $this->requestContentType = $requestContentType;

        return $this;
    }

    /**
     * @param string $requestFormat
     *
     * @return $this
     */
    public function setRequestFormat($requestFormat)
    {
        $this->requestFormat = $requestFormat;
        switch ($requestFormat) {
            case self::FORMAT_JSON:
                $this->setRequestContentType(self::CONTENT_TYPE_JSON);
                break;
            case self::FORMAT_XML:
                $this->setRequestContentType(self::CONTENT_TYPE_XML);
                break;
            case self::FORMAT_URL_ENCODED:
                $this->setRequestContentType(self::CONTENT_TYPE_URL_ENCODED);
                break;
        }

        return $this;
    }

    /**
     * @param string $responseClass
     *
     * @return $this
     */
    public function setResponseClass($responseClass)
    {
        $this->responseClass = $responseClass;

        return $this;
    }

    /**
     * @param SerializerInterface|DecoderInterface $serializer
     *
     * @return $this
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return $this
     * @throws \Guzzle\Common\Exception\InvalidArgumentException
     */
    public function setBeforeExecute($callable)
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException('The beforeExecute function must be callable');
        }

        $this->beforeExecute = $callable;

        return $this;
    }


    protected function process()
    {
        $response = $this->request->getResponse();
        if ($this->get(self::RESPONSE_PROCESSING) === self::TYPE_RAW) {
            $this->result = $response;
        } else {
            $contentType = (string) $response->getHeader('Content-Type');
            $this->result = null;
            if (stripos($contentType, 'xml') !== false) {
                $format = 'xml';
            } else {
                $format = 'json';
            }
            if ($this->responseClass !== null) {
                $this->result = $this->serializer->deserialize(
                    (string)$response->getBody(),
                    $this->responseClass,
                    $format
                );
            } else {
                $this->result = $this->serializer->decode((string)$response->getBody(), $format);
            }
        }
    }

    /**
     * Create the request object that will carry out the command
     */
    protected function build()
    {
        if ($this->beforeExecute) {
            call_user_func($this->beforeExecute, $this);
        }
        if ($this->bodyEntity !== null) {
            if ($this->request instanceof EntityEnclosingRequestInterface) {
                $this->request->setBody(
                    $this->serializer->serialize($this->bodyEntity, $this->requestFormat),
                    $this->requestContentType
                );
            } else {
                throw new \LogicException(
                    'Request must implement EntityEnclosingRequestInterface when command has body'
                );
            }
        }
    }


}
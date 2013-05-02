<?php


namespace Maba\OAuthCommerceClient\Plugin;

use Guzzle\Common\Event;
use Guzzle\Common\Exception\RuntimeException;
use Guzzle\Http\Message\Response;
use Maba\OAuthCommerceClient\Exception\RequestException;
use Maba\OAuthCommerceClient\Exception\ServerErrorException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ErrorProvider implements EventSubscriberInterface
{

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'request.error' => 'onRequestError'
        );
    }

    public function onRequestError(Event $event)
    {
        /** @var Response $response */
        $response = $event['response'];

        $statusCode = $response->getStatusCode();
        try {
            $data = $response->json();
            $error = isset($data['error']) ? $data['error'] : null;
            $errorDescription = isset($data['error_description']) ? $data['error_description'] : null;
            $errorUri = isset($data['error_uri']) ? $data['error_uri'] : null;

            $message = 'Remote server returned error: ' . $error . ' ' . $errorDescription
                . ($errorUri ? ' (more information at ' . $errorUri . ')' : '');

        } catch (RuntimeException $exception) {
            $error = 'invalid_content';
            $errorDescription = null;
            $errorUri = null;

            $message = 'Invalid content returned from remote server: ' . (string)$response->getBody();
        }

        $message .= ' [status code: ' . $statusCode . ' ' . $response->getReasonPhrase() . ']';

        /** @var RequestException $exception */
        if (substr($statusCode, 0, 1) === '5') {
            $exception = ServerErrorException::create($message);
        } else {
            $exception = RequestException::create($message);
        }

        $exception
            ->setErrorCode($error)
            ->setErrorDescription($errorDescription)
            ->setErrorUri($errorUri)
            ->setResponse($response)
            ->setRequest($response->getRequest())
        ;

        throw $exception;
    }
}

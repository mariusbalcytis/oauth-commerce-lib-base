<?php

namespace Maba\OAuthCommerceClient;

use Maba\OAuthCommerceClient\Exception\AuthorizationException;
use Maba\OAuthCommerceClient\Random\RandomProviderInterface;

class CodeGrantHandler
{
    protected $randomProvider;
    protected $credentialsId;
    protected $authEndpoint;


    public function __construct(RandomProviderInterface $randomProvider, $credentialsId, $authEndpoint)
    {
        $this->randomProvider = $randomProvider;
        $this->credentialsId = $credentialsId;
        $this->authEndpoint = $authEndpoint;
    }

    /**
     * @param array  $params
     * @param string $expectedState
     *
     * @throws AuthorizationException
     * @return string|null
     */
    public function getCodeFromParameters($params, $expectedState)
    {
        if (!empty($params['code']) || !empty($params['error'])) {
            $givenState = !empty($params['state']) ? $params['state'] : '';
            if ($expectedState !== $givenState) {
                throw AuthorizationException::create(
                    'Invalid state parameter passed in OAuth authentication'
                )->setErrorCode('invalid_state');
            }

            if (!empty($params['error'])) {
                $message = 'Error in authentication: ' . $params['error'];
                $message .= !empty($params['error_description']) ? '. ' . $params['error_description'] : '';
                throw AuthorizationException::create($message)
                    ->setErrorCode($params['error'])
                    ->setErrorDescription(isset($params['error_description']) ? $params['error_description'] : null)
                    ->setErrorUri(isset($params['error_uri']) ? $params['error_uri'] : null)
                ;
            } else {
                return $params['code'];
            }
        } else {
            return null;
        }
    }

    /**
     * @param string $state
     * @param string $redirectUri
     * @param array  $scopes
     *
     * @return string
     */
    public function getAuthUri($state, $redirectUri, array $scopes = array())
    {
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->credentialsId,
            'scope' => implode(' ', $scopes),
            'redirect_uri' => $redirectUri,
            'state' => $state,
        );
        return $this->authEndpoint . (strpos($this->authEndpoint, '?') === false ? '?' : '&')
            . http_build_query($params, null, '&');
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateState($length = 16)
    {
        return $this->randomProvider->generateStringForRanges($length, array(
            array('from' => ord('a'), 'to' => ord('z')),
            array('from' => ord('0'), 'to' => ord('9')),
            array('from' => ord('A'), 'to' => ord('Z')),
        ));
    }
}
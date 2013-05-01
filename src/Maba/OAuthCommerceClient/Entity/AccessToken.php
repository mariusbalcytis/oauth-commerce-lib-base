<?php


namespace Maba\OAuthCommerceClient\Entity;

use Guzzle\Service\Command\OperationCommand;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials\SymmetricCredentials;

class AccessToken extends SymmetricCredentials
{
    const TOKEN_TYPE = 'urn:marius-balcytis:oauth:token-type:mac-extended';

    /**
     * @var \DateTime
     */
    protected $expiresAt;

    /**
     * @var string
     */
    protected $refreshToken;

    /**
     * @param $data
     *
     * @throws \RuntimeException
     * @return static
     */
    public static function fromArray($data)
    {
        if ($data['token_type'] !== self::TOKEN_TYPE) {
            throw new \RuntimeException('Unsupported token_type parameter');
        }
        /** @var $token AccessToken */
        $token = new static();
        $token->macId = $data['access_token'];
        $token->sharedKey = $data['mac_key'];
        $token->algorithm = $data['mac_algorithm'];
        if (isset($data['refresh_token'])) {
            $token->refreshToken = $data['refresh_token'];
        }
        if (isset($data['expires_in'])) {
            $token->expiresAt = new \DateTime('@' . (time() + (int) $data['expires_in']));
        } elseif (isset($data['expires_at'])) {
            $token->expiresAt = new \DateTime('@' . (int) $data['expires_at']);
        }
        return $token;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = array(
            'access_token' => $this->getMacId(),
            'token_type' => self::TOKEN_TYPE,
            'mac_algorithm' => $this->getAlgorithm(),
            'mac_key' => $this->getSharedKey(),
        );
        if ($this->expiresAt !== null) {
            $data['expires_at'] = $this->expiresAt->getTimestamp();
        }
        if ($this->refreshToken !== null) {
            $data['refresh_token'] = $this->refreshToken;
        }
        return $data;
    }

    /**
     * @param \DateTime $expiresAt
     *
     * @return $this
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return integer
     */
    public function getExpiresIn()
    {
        return $this->expiresAt === null ? null : $this->expiresAt->getTimestamp() - time();
    }

    /**
     * @param string $refreshToken
     *
     * @return $this
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

}
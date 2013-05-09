<?php


namespace Maba\OAuthCommerceClient\Entity;

use Guzzle\Service\Command\OperationCommand;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials\SymmetricCredentials;

class AccessToken
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
     * @var string[]
     */
    protected $scopes = array();

    /**
     * @var \Maba\OAuthCommerceClient\Entity\SignatureCredentials
     */
    protected $signatureCredentials;


    public static function create()
    {
        return new static();
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

    /**
     * @param string[] $scopes
     *
     * @return $this
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param string $scope
     *
     * @return bool
     */
    public function hasScope($scope)
    {
        return in_array($scope, $this->scopes);
    }

    /**
     * @param \Maba\OAuthCommerceClient\Entity\SignatureCredentials $signatureCredentials
     *
     * @return $this
     */
    public function setSignatureCredentials($signatureCredentials)
    {
        $this->signatureCredentials = $signatureCredentials;

        return $this;
    }

    /**
     * @return \Maba\OAuthCommerceClient\Entity\SignatureCredentials
     */
    public function getSignatureCredentials()
    {
        return $this->signatureCredentials;
    }



}
<?php


namespace Maba\OAuthCommerceClient\Entity\UserCredentials;


class Password implements CredentialsInterface
{
    const CREDENTIALS_TYPE = 'password';

    /**
     * @var string
     */
    protected $site;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $site
     *
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns only public properties to include in first request
     * @return array
     */
    public function toPublicArray()
    {
        return array(
            'site' => $this->getSite(),
            'credentials_type' => self::CREDENTIALS_TYPE,
        );
    }

    /**
     * Returns only private properties that must be encrypted before sending
     * @return array
     */
    public function toPrivateArray()
    {
        return array(
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
        );
    }

}
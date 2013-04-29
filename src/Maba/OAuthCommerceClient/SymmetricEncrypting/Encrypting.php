<?php


namespace Maba\OAuthCommerceClient\SymmetricEncrypting;

use Zend\Crypt\Symmetric\Mcrypt;

class Encrypting implements EncryptingInterface
{
    /**
     * @var \Zend\Crypt\Symmetric\Mcrypt
     */
    protected $crypt;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $algorithm
     * @param string $type
     */
    public function __construct($algorithm, $type)
    {
        $this->crypt = new Mcrypt();
        $this->crypt->setAlgorithm($algorithm);
        $this->crypt->setMode('cbc');
        $this->type = $type;
    }

    /**
     * @param string $data
     * @param string $iv
     * @param string $key
     *
     * @return string
     */
    public function encrypt($data, $iv, $key)
    {
        $this->crypt->setKey($key);
        $this->crypt->setSalt($iv);
        $encrypted = $this->crypt->encrypt($data);
        return substr($encrypted, strlen($iv));
    }

    /**
     * @return integer
     */
    public function getKeyLength()
    {
        return $this->crypt->getKeySize();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


}
<?php


namespace Maba\OAuthCommerceClient\Entity\UserCredentials;


class CreditCard implements CredentialsInterface
{
    const CREDENTIALS_TYPE = 'credit_card';

    const TYPE_VISA = 'visa';
    const TYPE_MASTER_CARD = 'master_card';
    const TYPE_AMERICAN_EXPRESS = 'american_express';
    const TYPE_DISCOVER = 'discover';

    /**
     * @var string
     */
    protected $cardType;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $holderName;

    /**
     * @var integer
     */
    protected $expirationYear;

    /**
     * @var integer
     */
    protected $expirationMonth;

    /**
     * @var string
     */
    protected $securityCode;


    public static function create()
    {
        return new static();
    }

    /**
     * @param string $cardType
     *
     * @return $this
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param int $expirationMonth
     *
     * @return $this
     */
    public function setExpirationMonth($expirationMonth)
    {
        $this->expirationMonth = $expirationMonth === null ? null : (int) $expirationMonth;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationMonth()
    {
        return $this->expirationMonth;
    }

    /**
     * @param int $expirationYear
     *
     * @return $this
     */
    public function setExpirationYear($expirationYear)
    {
        if ($expirationYear !== null) {
            $expirationYear = $expirationYear < 100 ? 2000 + $expirationYear : $expirationYear;
        }
        $this->expirationYear = $expirationYear;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationYear()
    {
        return $this->expirationYear;
    }

    /**
     * @param string $holderName
     *
     * @return $this
     */
    public function setHolderName($holderName)
    {
        $this->holderName = $holderName;

        return $this;
    }

    /**
     * @return string
     */
    public function getHolderName()
    {
        return $this->holderName;
    }

    /**
     * @param string $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $securityCode
     *
     * @return $this
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * Returns only public properties to include in first request
     * @return array
     */
    public function toPublicArray()
    {
        return array(
            'card_type ' => $this->getCardType(),
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
            'number' => $this->getNumber(),
            'holder_name' => $this->getHolderName(),
            'expiration_year' => $this->getExpirationYear(),
            'expiration_month' => $this->getExpirationMonth(),
            'security_code' => $this->getSecurityCode(),
        );
    }

}
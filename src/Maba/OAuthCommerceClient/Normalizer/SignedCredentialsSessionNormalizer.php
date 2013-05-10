<?php


namespace Maba\OAuthCommerceClient\Normalizer;

use Maba\OAuthCommerceClient\Entity\SignedCredentials\Certificate;
use Maba\OAuthCommerceClient\Entity\SignedCredentials\Cipher;
use Maba\OAuthCommerceClient\Entity\SignedCredentials\KeyExchange;
use Maba\OAuthCommerceClient\Entity\SignedCredentials\Session;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class SignedCredentialsSessionNormalizer implements DenormalizerInterface
{

    /**
     * Denormalizes data back into an object of the given class
     *
     * @param mixed  $data    data to restore
     * @param string $class   the expected class to instantiate
     * @param string $format  format the given data was extracted from
     * @param array  $context options available to the denormalizer
     *
     * @return object
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return Session::create()
            ->setCertificate(
                Certificate::create()
                    ->setUrl($data['certificate']['url'])
                    ->setHash($data['certificate']['hash'])
                    ->setHashType($data['certificate']['hash_type'])
            )
            ->setCipher(
                Cipher::create()
                    ->setIv($data['cipher']['iv'])
                    ->setType($data['cipher']['type'])
            )
            ->setKeyExchange(
                KeyExchange::create()
                    ->setParameters($data['key_exchange']['parameters'])
                    ->setType($data['key_exchange']['type'])
            )
            ->setSessionId($data['session_id'])
        ;
    }

    /**
     * Checks whether the given class is supported for denormalization by this normalizer
     *
     * @param mixed  $data   Data to denormalize from.
     * @param string $type   The class to which the data should be denormalized.
     * @param string $format The format being deserialized from.
     *
     * @return Boolean
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === 'Maba\OAuthCommerceClient\Entity\SignedCredentials\Session';
    }
}
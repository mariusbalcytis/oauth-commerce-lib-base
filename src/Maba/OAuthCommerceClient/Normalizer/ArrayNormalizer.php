<?php


namespace Maba\OAuthCommerceInternalClient\Normalizer;

use Maba\OAuthCommerceClient\MacSignature\AlgorithmManager;
use Maba\OAuthCommerceInternalClient\Entity\ClientCredentials;
use Symfony\Component\Serializer\Encoder\NormalizationAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ArrayNormalizer implements DenormalizerInterface, SerializerAwareInterface
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * Sets the owning Serializer object
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }


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
        $result = array();
        foreach ($data as $item) {
            $result[] = $this->serializer->denormalize($item, substr($class, 0, -2), $format, $context);
        }
        return $result;
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
        return (is_array($data) || $data instanceof \Traversable) && substr($type, -2) === '[]';
    }

}
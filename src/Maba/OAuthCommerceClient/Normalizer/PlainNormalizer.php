<?php


namespace Maba\OAuthCommerceClient\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PlainNormalizer implements DenormalizerInterface
{
    const TYPE = 'plain';

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
        return $data;
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
        return $type === self::TYPE;
    }


}
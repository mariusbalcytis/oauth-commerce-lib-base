<?php


namespace Maba\OAuthCommerceClient\Normalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ChainNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var NormalizerInterface[]
     */
    protected $normalizers = array();

    /**
     * @var DenormalizerInterface[]
     */
    protected $denormalizers = array();

    /**
     * @param NormalizerInterface|DenormalizerInterface $normalizer
     *
     * @return $this
     */
    public function addNormalizer($normalizer)
    {
        if ($normalizer instanceof NormalizerInterface) {
            $this->normalizers[] = $normalizer;
        }
        if ($normalizer instanceof DenormalizerInterface) {
            $this->denormalizers[] = $normalizer;
        }
        if ($normalizer instanceof SerializerAwareInterface && $this->serializer) {
            $normalizer->setSerializer($this->serializer);
        }
        return $this;
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
        return $this->getDenormalizer($data, $class, $format)->denormalize($data, $class, $format, $context);
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
        return $this->getDenormalizer($data, $type, $format) !== null;
    }

    /**
     * Normalizes an object into a set of arrays/scalars
     *
     * @param object $object  object to normalize
     * @param string $format  format the normalization result will be encoded as
     * @param array  $context Context options for the normalizer
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $this->getNormalizer($object, $format)->normalize($object, $format, $context);
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer
     *
     * @param mixed  $data   Data to normalize.
     * @param string $format The format being (de-)serialized from or into.
     *
     * @return Boolean
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->getNormalizer($data, $format) !== null;
    }

    /**
     * Sets the owning Serializer object
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer instanceof SerializerAwareInterface) {
                $normalizer->setSerializer($serializer);
            }
        }
        foreach ($this->denormalizers as $normalizer) {
            if ($normalizer instanceof SerializerAwareInterface) {
                $normalizer->setSerializer($serializer);
            }
        }
    }

    /**
     * @param $data
     * @param $format
     *
     * @return null|NormalizerInterface
     */
    protected function getNormalizer($data, $format)
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($data, $format)) {
                return $normalizer;
            }
        }
        return null;
    }

    /**
     * @param $data
     * @param $type
     * @param $format
     *
     * @return null|DenormalizerInterface
     */
    protected function getDenormalizer($data, $type, $format)
    {
        foreach ($this->denormalizers as $denormalizer) {
            if ($denormalizer->supportsDenormalization($data, $type, $format)) {
                return $denormalizer;
            }
        }
        return null;
    }

}
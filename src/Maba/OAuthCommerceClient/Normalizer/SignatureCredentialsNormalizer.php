<?php


namespace Maba\OAuthCommerceClient\Normalizer;

use Maba\OAuthCommerceClient\MacSignature\AlgorithmManager;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class SignatureCredentialsNormalizer implements DenormalizerInterface
{
    /**
     * @var AlgorithmManager
     */
    protected $algorithmManager;


    public function __construct(AlgorithmManager $algorithmManager)
    {
        $this->algorithmManager = $algorithmManager;
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
        return $this->algorithmManager->createSignatureCredentials($data);
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
        return $type === 'Maba\OAuthCommerceClient\Entity\SignatureCredentials';
    }
}
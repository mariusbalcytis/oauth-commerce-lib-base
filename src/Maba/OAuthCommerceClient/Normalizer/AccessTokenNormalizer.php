<?php


namespace Maba\OAuthCommerceClient\Normalizer;

use Maba\OAuthCommerceClient\Entity\AccessToken;
use Maba\OAuthCommerceClient\MacSignature\AlgorithmManager;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class AccessTokenNormalizer implements DenormalizerInterface
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
        $token = AccessToken::create()
            ->setSignatureCredentials(
                $this->algorithmManager->createSignatureCredentials($data)
            )
        ;

        if (isset($data['scope'])) {
            $token->setScopes(explode(' ', $data['scope']));
        }
        if (isset($data['refresh_token'])) {
            $token->setRefreshToken($data['refresh_token']);
        }
        if (isset($data['expires_in'])) {
            $token->setExpiresAt(new \DateTime('@' . (time() + (int) $data['expires_in'])));
        } elseif (isset($data['expires_at'])) {
            $token->setExpiresAt(new \DateTime('@' . (int) $data['expires_at']));
        }

        return $token;
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
        return (
            $type === 'Maba\OAuthCommerceClient\Entity\AccessToken'
            && isset($data['token_type'])
            && $data['token_type'] === AccessToken::TOKEN_TYPE
        );
    }
}
<?php


namespace Maba\OAuthCommerceClient\DependencyInjection;

use Maba\OAuthCommerceClient\MacSignature\RsaAlgorithm;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class BaseClientExtension implements ExtensionInterface
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $container->setParameter('maba_oauth_commerce.signature_credentials', array());
        $container->setParameter('maba_oauth_commerce.guzzle_client.base_url', null);
        $container->setParameter('maba_oauth_commerce.guzzle_client.config', null);

        $container->register('maba_oauth_commerce.registry', 'Maba\OAuthCommerceClient\Registry');

        $container
            ->register('maba_oauth_commerce.hasher.sha-256', 'Maba\OAuthCommerceClient\Hash\Hasher')
            ->setArguments(array('sha256', 'sha-256'))
            ->addTag('maba_oauth_commerce.hasher')
            ->setPublic(false)
        ;
        $container
            ->register('maba_oauth_commerce.hasher.sha-512', 'Maba\OAuthCommerceClient\Hash\Hasher')
            ->setArguments(array('sha512', 'sha-512'))
            ->addTag('maba_oauth_commerce.hasher')
            ->setPublic(false)
        ;
        $container
            ->register(
                'maba_oauth_commerce.signature_algorithm.hmac-sha-256',
                'Maba\OAuthCommerceClient\MacSignature\HmacAlgorithm'
            )
            ->setArguments(array('sha256', 'hmac-sha-256'))
            ->addTag('maba_oauth_commerce.signature_algorithm')
            ->setPublic(false)
        ;
        $container
            ->register(
                'maba_oauth_commerce.signature_algorithm.hmac-sha-512',
                'Maba\OAuthCommerceClient\MacSignature\HmacAlgorithm'
            )
            ->setArguments(array('sha512', 'hmac-sha-512'))
            ->addTag('maba_oauth_commerce.signature_algorithm')
            ->setPublic(false)
        ;
        $container
            ->register(
                'maba_oauth_commerce.signature_algorithm.rsa-pkcs1-sha-256',
                'Maba\OAuthCommerceClient\MacSignature\RsaAlgorithm'
            )
            ->setArguments(array('sha256', 'rsa-pkcs1-sha-256', RsaAlgorithm::PADDING_PKCS1))
            ->addTag('maba_oauth_commerce.signature_algorithm')
            ->setPublic(false)
        ;
        $container
            ->register(
                'maba_oauth_commerce.signature_algorithm.rsa-pkcs1-sha-512',
                'Maba\OAuthCommerceClient\MacSignature\RsaAlgorithm'
            )
            ->setArguments(array('sha512', 'rsa-pkcs1-sha-512', RsaAlgorithm::PADDING_PKCS1))
            ->addTag('maba_oauth_commerce.signature_algorithm')
            ->setPublic(false)
        ;
        $container
            ->register(
                'maba_oauth_commerce.key_exchange.dh_group16',
                'Maba\OAuthCommerceClient\KeyExchange\DiffieHellman\Group16KeyExchange'
            )
            ->addTag('maba_oauth_commerce.key_exchange')
            ->setPublic(false)
        ;
        $container
            ->register(
                'maba_oauth_commerce.encrypting.aes-128-cbc',
                'Maba\OAuthCommerceClient\SymmetricEncrypting\Encrypting'
            )
            ->setArguments(array('rijndael-128', 'aes-128-cbc'))
            ->addTag('maba_oauth_commerce.encrypting')
            ->setPublic(false)
        ;
        $container
            ->register(
                'maba_oauth_commerce.encrypting.aes-256-cbc',
                'Maba\OAuthCommerceClient\SymmetricEncrypting\Encrypting'
            )
            ->setArguments(array('rijndael-256', 'aes-256-cbc'))
            ->addTag('maba_oauth_commerce.encrypting')
            ->setPublic(false)
        ;


        $container
            ->register(
                'maba_oauth_commerce.algorithm_manager',
                'Maba\OAuthCommerceClient\MacSignature\AlgorithmManager'
            )
            ->setArguments(array(new Reference('maba_oauth_commerce.registry')))
        ;
        $container
            ->register(
                'maba_oauth_commerce.mac_signature_provider',
                'Maba\OAuthCommerceClient\Plugin\MacSignatureProvider'
            )
            ->setArguments(array(
                new Reference('maba_oauth_commerce.signature_credentials'),
                new Reference('maba_oauth_commerce.algorithm_manager'),
            ))
        ;
        $container
            ->register(
                'maba_oauth_commerce.signature_credentials',
                'Maba\OAuthCommerceClient\Entity\SignatureCredentials'
            )
            ->setFactoryService('maba_oauth_commerce.algorithm_manager')
            ->setFactoryMethod('createSignatureCredentials')
            ->setArguments(array('%maba_oauth_commerce.signature_credentials%'))
            ->setPublic(false)
        ;

        $container
            ->register('maba_oauth_commerce.guzzle_client', 'Guzzle\Service\Client')
            ->setArguments(array(
                '%maba_oauth_commerce.guzzle_client.base_url%',
                '%maba_oauth_commerce.guzzle_client.config%',
            ))
            ->addMethodCall('addSubscriber', array(new Reference('maba_oauth_commerce.mac_signature_provider')))
            ->addMethodCall('addSubscriber', array(new Definition('Maba\OAuthCommerceClient\Plugin\ErrorProvider')))
        ;

        $container->register(
            'maba_oauth_commerce.chain_normalizer',
            'Maba\OAuthCommerceClient\Normalizer\ChainNormalizer'
        )->addMethodCall('addNormalizer', array(new Definition('Maba\OAuthCommerceClient\Normalizer\ArrayNormalizer')));

        $container
            ->register('maba_oauth_commerce.serializer', 'Symfony\Component\Serializer\Serializer')
            ->setArguments(array(
                array(
                    new Reference('maba_oauth_commerce.chain_normalizer'),
                    new Definition('Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer'),
                ),
                array(
                    new Definition('Symfony\Component\Serializer\Encoder\JsonEncoder'),
                    new Definition('Symfony\Component\Serializer\Encoder\XmlEncoder'),
                    new Definition('Maba\OAuthCommerceClient\Encoder\UrlEncoder'),
                ),
            ))
        ;

        $container
            ->register('maba_oauth_commerce.base_client', 'Maba\OAuthCommerceClient\BaseClient')
            ->setArguments(array(
                new Reference('maba_oauth_commerce.guzzle_client'),
                new Reference('maba_oauth_commerce.serializer'),
            ))
            ->setAbstract(true)
        ;
    }

    public function addCompilerPasses(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddTaggedCompilerPass(
            'maba_oauth_commerce.chain_normalizer',
            'maba_oauth_commerce.normalizer',
            'addNormalizer'
        ));
        $container->addCompilerPass(new AddTaggedCompilerPass(
            'maba_oauth_commerce.registry',
            'maba_oauth_commerce.hasher',
            'addHasher'
        ));
        $container->addCompilerPass(new AddTaggedCompilerPass(
            'maba_oauth_commerce.registry',
            'maba_oauth_commerce.signature_algorithm',
            'addSignatureAlgorithm'
        ));
        $container->addCompilerPass(new AddTaggedCompilerPass(
            'maba_oauth_commerce.registry',
            'maba_oauth_commerce.key_exchange',
            'addKeyExchange'
        ));
        $container->addCompilerPass(new AddTaggedCompilerPass(
            'maba_oauth_commerce.registry',
            'maba_oauth_commerce.encrypting',
            'addEncrypting'
        ));
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     * @return string The XML namespace
     * @api
     */
    public function getNamespace()
    {
        return $this->getAlias();
    }

    /**
     * Returns the base path for the XSD files.
     * @return string The XSD base path
     * @api
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }

    /**
     * Returns the recommended alias to use in XML.
     * This alias is also the mandatory prefix to use when using YAML.
     * @return string The alias
     * @api
     */
    public function getAlias()
    {
        return 'maba_oauth_commerce_base_client';
    }
}
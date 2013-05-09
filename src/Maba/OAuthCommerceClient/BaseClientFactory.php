<?php


namespace Maba\OAuthCommerceClient;

use Guzzle\Service\Client;
use Maba\OAuthCommerceClient\Entity\SignatureCredentials;
use Maba\OAuthCommerceClient\MacSignature\AlgorithmManager;
use Maba\OAuthCommerceClient\Plugin\ErrorProvider;
use Maba\OAuthCommerceClient\Plugin\MacSignatureProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseClientFactory
{
    /**
     * @var AlgorithmManager
     */
    protected $algorithmManager;

    /**
     * @var ErrorProvider
     */
    protected $errorProvider;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var string|null
     */
    protected $defaultBaseUrl = null;

    /**
     * @var array
     */
    protected $defaultConfig = array();


    public function __construct(
        AlgorithmManager $algorithmManager,
        SerializerInterface $serializer,
        ErrorProvider $errorProvider
    ) {
        $this->algorithmManager = $algorithmManager;
        $this->serializer = $serializer;
        $this->errorProvider = $errorProvider;
    }

    /**
     * @param array $defaultConfig
     *
     * @return $this
     */
    public function setDefaultConfig($defaultConfig)
    {
        $this->defaultConfig = $defaultConfig;

        return $this;
    }

    /**
     * @param null|string $defaultBaseUrl
     *
     * @return $this
     */
    public function setDefaultBaseUrl($defaultBaseUrl)
    {
        $this->defaultBaseUrl = $defaultBaseUrl;

        return $this;
    }

    /**
     * @param SignatureCredentials|array $signatureCredentials
     * @param string|null                $baseUrl
     * @param array                      $config
     *
     * @return BaseClient
     */
    public function createClient($signatureCredentials, $baseUrl = null, $config = array())
    {
        if (!$signatureCredentials instanceof SignatureCredentials) {
            $signatureCredentials = $this->algorithmManager->createSignatureCredentials($signatureCredentials);
        }

        $signatureProvider = new MacSignatureProvider($signatureCredentials, $this->algorithmManager);
        $guzzleClient = new Client($baseUrl ?: $this->defaultBaseUrl, $config + (array)$this->defaultConfig);
        $guzzleClient->addSubscriber($signatureProvider)->addSubscriber($this->errorProvider);

        return $this->constructClient($guzzleClient);
    }

    abstract protected function constructClient(Client $guzzleClient);
}
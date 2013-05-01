<?php


namespace Maba\OAuthCommerceClient\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

class UrlEncoder implements EncoderInterface, DecoderInterface
{
    const FORMAT = 'urlencoded';

    /**
     * Decodes a string into PHP data
     *
     * @param scalar $data    Data to decode
     * @param string $format  Format name
     * @param array  $context options that decoders have access to.
     *
     * @return mixed
     */
    public function decode($data, $format, array $context = array())
    {
        $result = null;
        parse_str($data, $result);
        return $result;
    }

    /**
     * Checks whether the serializer can decode from given format
     *
     * @param string $format format name
     *
     * @return Boolean
     */
    public function supportsDecoding($format)
    {
        return $format === self::FORMAT;
    }

    /**
     * Encodes data into the given format
     *
     * @param mixed  $data    Data to encode
     * @param string $format  Format name
     * @param array  $context options that normalizers/encoders have access to.
     *
     * @return scalar
     */
    public function encode($data, $format, array $context = array())
    {
        return http_build_query($data, null, '&');
    }

    /**
     * Checks whether the serializer can encode to given format
     *
     * @param string $format format name
     *
     * @return Boolean
     */
    public function supportsEncoding($format)
    {
        return $format === self::FORMAT;
    }

}
<?php


namespace Maba\OAuthCommerceClient\Random;


interface RandomProviderInterface
{
    /**
     * @param integer $length
     * @param array   $availableCharacterRanges each item is assoc array of 2 elements: from and to as integer char number
     *
     * @return string
     */
    public function generateStringForRanges($length, array $availableCharacterRanges);
}
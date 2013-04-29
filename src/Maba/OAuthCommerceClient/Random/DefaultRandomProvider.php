<?php


namespace Maba\OAuthCommerceClient\Random;

use Zend\Math\Rand;

class DefaultRandomProvider implements RandomProviderInterface
{
    /**
     * @param integer $length
     * @param array   $availableCharacterRanges each item is assoc array of 2 elements: from and to as integer char number
     *
     * @return string
     */
    public function generateStringForRanges($length, array $availableCharacterRanges)
    {
        $count = 0;
        foreach ($availableCharacterRanges as $key => $range) {
            $availableCharacterRanges[$key]['offset'] = $count;
            $count += $range['to'] - $range['from'] + 1;
            $availableCharacterRanges[$key]['max'] = $count;
        }
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $rand = Rand::getInteger(0, $count - 1);
            foreach ($availableCharacterRanges as $range) {
                if ($rand < $range['max']) {
                    $result .= chr($rand - $range['offset'] + $range['from']);
                    break;
                }
            }
        }
        return $result;
    }

}
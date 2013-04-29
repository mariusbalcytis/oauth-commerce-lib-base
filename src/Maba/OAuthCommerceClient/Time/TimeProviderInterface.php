<?php


namespace Maba\OAuthCommerceClient\Time;


interface TimeProviderInterface
{
    /**
     * @return \DateTime
     */
    public function getCurrentTime();
}
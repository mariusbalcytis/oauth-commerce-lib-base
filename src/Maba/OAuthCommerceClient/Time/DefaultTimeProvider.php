<?php


namespace Maba\OAuthCommerceClient\Time;


class DefaultTimeProvider implements TimeProviderInterface
{
    /**
     * @return \DateTime
     */
    public function getCurrentTime()
    {
        return new \DateTime();
    }

}
<?php

namespace gorriecoe\Meta\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Member;

/**
 * MetaSiteTreeExtension
 *
 * @package silverstripe-meta
 */
class MetaSiteTreeExtension extends DataExtension
{
    /**
     * Ensure public URLs are re-scraped by Facebook after publishing.
     */
    public function onAfterPublish()
    {
        $this->owner->clearFacebookCache();
    }

    /**
     * Ensure public URLs are re-scraped by Facebook after writing.
     */
    public function onAfterWrite()
    {
        $owner = $this->owner;
        if (!$owner->hasMethod('doPublish')) {
            $owner->clearFacebookCache();
        }
    }

    /**
     * Tell Facebook to re-scrape this URL, if it is accessible to the public.
     * @return String|false
     */
    public function clearFacebookCache()
    {
        $owner = $this->owner;
        if (!$owner->hasMethod('AbsoluteLink')) {
            return false;
        }
        $anonymousUser = Member::create();
        if ($owner->can('View', $anonymousUser)) {
            $curlRequest = curl_init();
            curl_setopt_array(
                $curlRequest,
                array(
                    CURLOPT_URL => 'https://graph.facebook.com/v1.0/?id='. urlencode($owner->AbsoluteLink()). '&scrape=1',
                    CURLOPT_POST => 1,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_SSL_VERIFYPEER => false,
                )
            );
            $response = curl_exec($curlRequest);
            $headers = curl_getinfo($curlRequest);
            if(!$response || $headers['http_code'] !== 200) {
                return false;
            }
            return $response;
        }
    }
}

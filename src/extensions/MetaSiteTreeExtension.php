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
     * @return RestfulService_Response
     */
    public function clearFacebookCache()
    {
        $owner = $this->owner;
        if (!$owner->hasMethod('AbsoluteLink')) {
            return false;
        }
        $anonymousUser = Member::create();
        if ($owner->can('View', $anonymousUser)) {
            $fetch = new RestfulService('https://graph.facebook.com/');
            $fetch->setQueryString(
                array(
                    'id' => $owner->AbsoluteLink(),
                    'scrape' => true,
                )
            );
            return $fetch->request();
        }
    }
}

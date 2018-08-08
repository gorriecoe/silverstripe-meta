<?php

namespace gorriecoe\Meta\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Member;

/**
 * MetaSiteTreeConfigExtension
 *
 * @package silverstripe-meta
 */
class MetaSiteTreeConfigExtension extends DataExtension
{
    public function updateMetaDescriptionData(&$values)
    {
        $values[] = 'SiteConfig.MetaDescription';
    }

    public function updateTwitterTitleData(&$values)
    {
        $values[] = 'SiteConfig.TwitterTitle';
        $values[] = 'SiteConfig.MetaTitle';
    }

    public function updateTwitterDescriptionData(&$values)
    {
        $values[] = 'SiteConfig.TwitterDescription';
        $values[] = 'SiteConfig.MetaDescription';
    }

    public function updateTwitterImageData(&$values)
    {
        $values[] = 'SiteConfig.TwitterCustomImage';
        $values[] = 'SiteConfig.MetaCustomImage';
    }

    public function updateTwitterSiteData(&$values)
    {
        $values[] = 'SiteConfig.TwitterUsername';
    }

    public function updateTwitterCreatorData(&$values)
    {
        $values[] = 'SiteConfig.TwitterUsername';
    }

    public function updateOGTitleData(&$values)
    {
        $values[] = 'SiteConfig.OGTitle';
        $values[] = 'SiteConfig.MetaTitle';
    }

    public function updateOGImageData(&$values)
    {
        $values[] = 'SiteConfig.OGCustomImage';
        $values[] = 'SiteConfig.MetaCustomImage';
    }

    public function updateOGImageTypeData(&$values)
    {
        $values[] = 'SiteConfig.OGCustomImage.MimeType';
        $values[] = 'SiteConfig.MetaCustomImage.MimeType';
    }

    public function updateOGDescriptionData(&$values)
    {
        $values[] = 'SiteConfig.OGDescription';
        $values[] = 'SiteConfig.MetaDescription';
    }
}

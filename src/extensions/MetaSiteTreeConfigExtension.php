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
    public function updateMetaDescription(&$values)
    {
        $values[] = 'SiteConfig.MetaDescription|SiteConfig.Content.Summary(160)';
    }

    public function updateTwitterTitle(&$values)
    {
        $values[] = 'SiteConfig.TwitterTitle|SiteConfig.MetaTitle';
    }

    public function updateTwitterDescription(&$values)
    {
        $values[] = 'SiteConfig.TwitterDescription|SiteConfig.MetaDescription';
    }

    public function updateTwitterImage(&$values)
    {
        $values[] = 'SiteConfig.TwitterCustomImage|SiteConfig.MetaCustomImage';
    }

    public function updateTwitterSite(&$values)
    {
        $values[] = 'SiteConfig.TwitterUsername';
    }

    public function updateTwitterCreator(&$values)
    {
        $values[] = 'SiteConfig.TwitterUsername';
    }

    public function updateOGTitle(&$values)
    {
        $values[] = 'SiteConfig.OGTitle|SiteConfig.MetaTitle';
    }

    public function updateOGImage(&$values)
    {
        $values[] = 'SiteConfig.OGCustomImage|SiteConfig.MetaCustomImage';
    }

    public function updateOGImageType(&$values)
    {
        $values[] = 'SiteConfig.OGCustomImage.MimeType|SiteConfig.MetaCustomImage.MimeType';
    }

    public function updateOGDescription(&$values)
    {
        $values[] = 'SiteConfig.OGDescription|SiteConfig.MetaDescription';
    }
}

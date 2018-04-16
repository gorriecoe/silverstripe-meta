<?php

namespace gorriecoe\Meta\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Control\ContentNegotiator;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;

/**
 * MetaTagExtension
 *
 * @package silverstripe-meta
 */
class MetaTagExtension extends DataExtension
{
    /**
     * @var array
     */
    protected $meta_tags = [
        'MetaTitle' => '{$MetaTitle|Title|SiteConfig.MetaTitle} &raquo; {$SiteConfig.Title}',
        'MetaCharset' => null,
        'MetaGenerator' => null,
        'MetaDescription' => 'MetaDescription|Content.Summary(160)',
        'MetaRobots' => null,
        'MetaResponsive' => null,
        'TwitterTitle' => 'TwitterTitle|MetaTitle|Title',
        'TwitterDescription' => 'TwitterDescription|MetaDescription|Content.Summary(160)',
        'TwitterCard' => null,
        'TwitterImage' => 'TwitterCustomImage|MetaCustomImage',
        'TwitterSite' => 'TwitterUsername',
        'TwitterCreator' => 'TwitterUsername',
        'OGTitle' => 'OGTitle|MetaTitle|Title',
        'OGType' => null,
        'OGImage' => 'OGCustomImage|MetaCustomImage',
        'OGImageType' => 'OGCustomImage.MimeType|MetaCustomImage.MimeType',
        'OGUrl' => 'AbsoluteLink',
        'OGDescription' => 'OGDescription|MetaDescription|Content',
        'OGSiteName' => 'SiteConfig.Title'
    ];

    /**
     * Extension hook to change all tags
     */
    public function MetaTags(&$tags)
    {
        $meta = [];
        $owner = $this->owner;
        $meta_tags = array_merge(
            $this->meta_tags,
            $owner->config()->get('meta_tags') ? : []
        );

        foreach ($meta_tags as $call => $values) {
            if ($values) {
                if (is_string($values)) {
                    $values = [$values];
                }

                $owner->extend('update' . $call, $values);

                foreach ($values as $value) {
                    if ($value = $owner->sreg($value)) {
                        break;
                    }
                }

                $meta[$call] = str_replace(
                    '$Value',
                    $value,
                    $this->{$call . 'Tag'}()
                );
            } else {
                $meta[$call] = $this->{$call . 'Tag'}();
            }
        }

        $tags = implode("\n", $meta);
    }

    /**
     * @return string
     */
    public function MetaCharsetTag()
    {
        $charset = ContentNegotiator::config()->uninherited('encoding');
        $tags[] = "<meta charset='$charset'>";
        $tags[] = "<meta http-equiv='Content-type' content='text/html; charset=$charset' />";
        return implode("\n", $tags);
    }

    /**
     * @return string
     */
    public function MetaTitleTag()
    {
        return '<title>$Value</title>';
    }

    /**
     * @return string
     */
    public function MetaGeneratorTag()
    {
        return '<meta generator="SilverStripe" />';
    }

    public function MetaResponsiveTag()
    {
        return '<meta viewport="width=device-width, initial-scale=1.0" />';
    }

    /**
     * @return string
     */
    public function MetaDescriptionTag()
    {
        return '<meta description="$Value" />';
    }

    /**
     * @return string
     */
    public function MetaRobotsTag()
    {
        $owner = $this->owner;
        // Force settings on test and dev enviroment
        if (!Director::isLive()) {
            $robots = [
                'noindex',
                'nofollow',
                'nosnippet',
                'noindex',
                'nocache',
                'noarchive'
            ];
        } else {
            $robots = [];
            $robots[] = ($owner->NoIndex) ? 'noindex' : 'index';
            $robots[] = ($owner->NoFollow) ? 'nofollow' : 'follow';
            if ($owner->NoSnippet) {
                $robots[] = 'nosnippet';
            }
            if ($owner->NoCache) {
                $robots[] = 'nocache';
                $robots[] = 'noarchive';
            }
        }

        return '<meta robots="' . implode(', ', $robots) . '" />';
    }

    /**
     * @return string
     */
    public function TwitterTitleTag()
    {
        return '<meta twitter:title="$Value" />';
    }

    /**
     * @return string
     */
    public function TwitterDescriptionTag()
    {
        return '<meta twitter:description="$Value" />';
    }

    /**
     * @return string
     */
    public function TwitterCardTag()
    {
        return '<meta twitter:card="summary_large_image" />';
    }

    /**
     * @return string
     */
    public function TwitterImageTag()
    {
        return '<meta twitter:image="$Value" />';
    }

    /**
     * @return string
     */
    public function TwitterSiteTag()
    {
        return '<meta twitter:site="@$Value" />';
    }

    /**
     * @return string
     */
    public function TwitterCreatorTag()
    {
        return '<meta twitter:creator="@$Value" />';
    }

    /**
     * @return string
     */
    public function OGTitleTag()
    {
        return '<meta property="og:title" content="$Value" />';
    }

    /**
     * @return string
     */
    public function OGTypeTag()
    {
        return '<meta property="og:type" content="website" />';
    }

    public function OGImageTag()
    {
        return '<meta property="og:image" content="$Value" />';
    }

    /**
     * @return string
     */
    public function OGImageTypeTag()
    {
        return '<meta property="og:image:type" content="$Value" />';
    }

    public function OGUrlTag()
    {
        return '<meta property="og:url" content="$Value" />';
    }

    /**
     * @return string
     */
    public function OGDescriptionTag()
    {
        return '<meta property="og:description" content="$Value" />';
    }

    /**
     * @return string
     */
    public function OGSiteNameTag()
    {
        return '<meta property="og:site_name" content="$Value" />';
    }
}

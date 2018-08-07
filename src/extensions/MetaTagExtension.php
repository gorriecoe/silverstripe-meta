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
    protected $meta_data_defaults = [
        'MetaTitle' => [
            'MetaTitle',
            'Title'
        ],
        'MetaCharset' => 'MetaCharset',
        'MetaDescription' => [
            'MetaDescription',
            'Content.Summary(160)'
        ],
        'MetaRobots' => 'MetaRobots',
        'TwitterTitle' => [
            'TwitterTitle',
            'MetaTitle',
            'Title'
        ],
        'TwitterDescription' => [
            'TwitterDescription',
            'MetaDescription',
            'Content.Summary(160)'
        ],
        'TwitterImage' => [
            'TwitterCustomImage',
            'MetaCustomImage'
        ],
        'TwitterSite' => 'TwitterUsername',
        'TwitterCreator' => 'TwitterUsername',
        'OGTitle' => [
            'OGTitle',
            'MetaTitle',
            'Title'
        ],
        'OGImage' => [
            'OGCustomImage',
            'MetaCustomImage'
        ],
        'OGUrl' => 'AbsoluteLink',
        'OGDescription' => [
            'OGDescription',
            'MetaDescription',
            'Content'
        ],
        'OGSiteName' => 'SiteConfig.Title',
        'FBAuthor' => 'FBAuthorlink',
        'FBPublisher' => 'FBPublisherlink',
        'GplusAuthor' => 'GplusAuthorlink',
        'GplusPublisher' => 'GplusPublisherlink'
    ];

    /**
     * Extension hook to change all tags
     */
    public function MetaTags(&$tags)
    {
        $owner = $this->owner;
        $metaData = array_merge(
            $this->meta_data_defaults,
            $owner->config()->get('meta_data') ? : []
        );
        $renderData = [];

        foreach ($metaData as $call => $values) {
            if ($values) {
                if (is_string($values)) {
                    $values = [$values];
                }

                $owner->extend('update' . $call . 'Data', $values);

                foreach ($values as $value) {
                    if ($value = $owner->relField($value)) {
                        if (is_object($value)) {
                            if ($value->exists()) {
                                $renderData[$call] = $value;
                                break;
                            }
                        } else {
                            $renderData[$call] = $value;
                            break;
                        }
                    }
                }
            }
        }

        $tags = $owner->renderWith([
            'type' => 'Includes',
            0 => 'MetaTags'
        ], $renderData);
    }

    /**
     * @return string
     */
    public function MetaCharset()
    {
        return ContentNegotiator::config()->uninherited('encoding');
    }

    /**
     * @return string
     */
    public function MetaRobots()
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

        return implode(', ', $robots);
    }
}

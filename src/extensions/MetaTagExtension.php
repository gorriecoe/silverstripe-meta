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
        if (!$this->owner->hasMethod('doPublish')) {
            $this->owner->clearFacebookCache();
        }
    }

    /**
     * Tell Facebook to re-scrape this URL, if it is accessible to the public.
     *
     * @return RestfulService_Response
     */
    public function clearFacebookCache()
    {
        if (!$this->owner->hasMethod('AbsoluteLink')) {
            return false;
        }
        $anonymousUser = new Member();
        if ($this->owner->can('View', $anonymousUser)) {
            $fetch = new RestfulService('https://graph.facebook.com/');
            $fetch->setQueryString(
                array(
                    'id' => $this->owner->AbsoluteLink(),
                    'scrape' => true,
                )
            );
            return $fetch->request();
        }
    }

    /**
     * Extension hook to change all tags
     */
    public function MetaTags(&$tags)
    {
        $meta = [];
        $owner = $this->owner;
        $config = $owner->config();
        $meta_content = $config->get('meta_content');

        $tagTypes = [
            'MetaTitle' => [
                'DefaultPattern' => '{$MetaTitle|Title|SiteConfig.MetaTitle} &raquo; {$SiteConfig.Title}',
                'Markup' => '<title>$Pattern</title>',
            ],
            'MetaCharset' => null,
            'MetaGenerator' => [
                'Markup' => '<meta generator="SilverStripe" />'
            ],
            'MetaDescription' => 'MetaDescription|Content',
            'MetaRobots' => null,
            'MetaResponsive' => [
                'Markup' => '<meta viewport="width=device-width, initial-scale=1.0" />'
            ],
            'TwitterTitle' => [
                'DefaultPattern' => 'TwitterTitle|MetaTitle|Title',
                'Markup' => '<meta twitter:title="$Pattern" />'
            ],
            'TwitterDescription' => [
                'DefaultPattern' => 'TwitterDescription|MetaDescription|Description|SiteConfig.TwitterDescription|SiteConfig.MetaDescription|SiteConfig.Description',
                'Markup' => '<meta twitter:description="$Pattern" />'
            ],
            'TwitterCard' => [
                'Markup' => '<meta twitter:card="summary_large_image" />'
            ],
            'TwitterImage' => [
                'DefaultPattern' => 'TwitterCustomImage|MetaCustomImage|SiteConfig.TwitterCustomImage|SiteConfig.MetaCustomImage',
                'Markup' => '<meta twitter:image="$Pattern" />'
            ],
            'TwitterSite' => [
                'DefaultPattern' => 'TwitterUsername|SiteConfig.TwitterUsername',
                'Markup' => '<meta twitter:site="@$Pattern" />'
            ],
            'TwitterCreator' => [
                'DefaultPattern' => 'TwitterUsername|SiteConfig.TwitterUsername',
                'Markup' => '<meta twitter:creator="@$Pattern" />'
            ],
            'OGTitle' => [
                'DefaultPattern' => 'OGTitle|MetaTitle|Title',
                'Markup' => '<meta property="og:title" content="$Pattern" />'
            ],
            'OGType' => [
                'Markup' => '<meta property="og:type" content="website" />'
            ],
            'OGImage' => [
                'DefaultPattern' => 'OGCustomImage|MetaCustomImage',
                'Markup' => '<meta property="og:image" content="$Pattern" />'
            ],
            'OGImageType' => [
                'DefaultPattern' => 'OGCustomImage.MimeType|MetaCustomImage.MimeType',
                'Markup' => '<meta property="og:image:type" content="$Pattern" />'
            ],
            'OGUrl' => [
                'DefaultPattern' => 'AbsoluteLink',
                'Markup' => '<meta property="og:url" content="$Pattern" />'
            ],
            'OGDescription' => [
                'DefaultPattern' => 'OGDescription|MetaDescription|Content',
                'Markup' => '<meta property="og:description" content="$Pattern" />'
            ],
            'OGSiteName' => [
                'DefaultPattern' => 'SiteConfig.Title',
                'Markup' => '<meta property="og:site_name" content="$Pattern" />'
            ]
        ];

        foreach ($tagTypes as $call => $default) {
            if (is_array($default) && !isset($default['DefaultPattern']) && isset($default['Markup'])) {
                $meta[$call] = $default['Markup'];
            } elseif (isset($meta_content[$call]) && is_array($default)) {
                // Use markup variable and insert the pattern defined in the extended object.
                $meta[$call] = str_replace(
                    '$Pattern',
                    $owner->sreg($meta_content[$call]),
                    $default['Markup']
                );
            } elseif (isset($meta_content[$call]) && method_exists($this, $call)) {
                // Call function in this class and pass it the pattern defined in the extended object.
                $meta[$call] = $this->{$call}($owner->sreg($meta_content[$call]));
            } elseif ($owner->hasMethod($call)) {
                // Call function in the extended object.
                $meta[$call] = $owner->{$call}();
            } elseif (is_array($default)) {
                // Use markup variable and insert the default pattern defined above.
                $meta[$call] = str_replace(
                    '$Pattern',
                    $owner->sreg($default['DefaultPattern']),
                    $default['Markup']
                );
            } elseif (method_exists($this, $call)) {
                // Call function in this class and pass it the default pattern defined above.
                $meta[$call] = $this->{$call}($owner->sreg($default));
            }
        }

        foreach ($meta as $key => $tag) {
            if (is_object($tag) && $tag instanceof HTMLTag) {
                $meta[$key] = $tag->Render();
            }
        }

        $tags = implode("\n", $meta);
    }

    private function MetaCharset($value = null)
    {
        $charset = ContentNegotiator::config()->uninherited('encoding');
        $tags[] = "<meta charset='$charset'>";
        $tags[] = "<meta http-equiv='Content-type' content='text/html; charset=$charset' />";
        return implode("\n", $tags);
    }

    private function MetaDescription($value = null)
    {
        $owner = $this->owner;
        $value = Convert::raw2att(strip_tags($value));
        $value = substr($value, 0, 160);
        $matches = [];
        $regex = preg_match('(.*[\.\?!])', $value, $matches);
        if(isset($matches[0])) {
            $metaDescription = $matches[0];
        } else {
            $metaDescription = $value;
        }
        return '<meta description="' . $metaDescription . '" />';
    }

    private function MetaRobots($value = null)
    {
        $owner = $this->owner;
        $robots = [];

        // Force settings on test and dev enviroment
        if (!Director::isLive()) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
            $robots[] = 'nosnippet';
            $robots[] = 'noindex';
            $robots[] = 'nocache';
            $robots[] = 'noarchive';
        } else {
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
}

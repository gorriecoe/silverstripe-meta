<?php

namespace gorriecoe\Meta\Extensions;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Control\ContentNegotiator;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Config\Config;
use gorriecoe\HTMLTag\View\HTMLTag;

/**
 * MetaExtension
 *
 * @package silverstripe-meta
 */
class MetaExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'MetaTitle' => 'Varchar(255)',
        'TwitterTitle' => 'Varchar(255)',
        'TwitterDescription' => 'Varchar(255)',
        'TwitterUsername' => 'Varchar(255)',
        'OGTitle' => 'Varchar(100)',
        'OGDescription' => 'Varchar(150)',
        'FBPublisherlink' => 'Varchar(255)',
        'FBAuthorlink' => 'Varchar(255)',
        'GplusAuthorlink' => 'Varchar(255)',
        'GplusPublisherlink' => 'Varchar(255)',
        'NoFollow' => 'Boolean',
        'NoVisit' => 'Boolean',
        'NoSnippet' => 'Boolean',
        'NoCache' => 'Boolean'
    ];

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = [
        'MetaCustomImage' => Image::class,
        'TwitterCustomImage' => Image::class,
        'OGCustomImage' => Image::class,
        'PinterestCustomImage' => Image::class,
        'BreadcrumbIcon' => Image::class
    ];

    /**
     * Twitter username to be attributed as owner/author of this page.
     * Example: 'mytwitterhandle'.
     *
     * @var string
     * @config
     */
    private static $twitter_username = '';

    /**
     * Whether or not to generate a twitter card for this page.
     * More info: https://dev.twitter.com/cards/overview.
     *
     * @var bool
     * @config
     */
    private static $twitter_card = true;

    /**
     * Whether or not to enable a Pinterest preview and fields.
     * You need to be using the $PinterestShareLink for this to be useful.
     *
     * @var bool
     * @config
     */
    private static $pinterest = false;

    /**
     * Update Fields
     * @return FieldList
     */
    public function updateCMSFields(FieldList $fields)
    {
        $owner = $this->owner;
        $config = $owner->config();
        $tab = $config->get('meta_tab');

        // Prevent field scaffolding from adding these fields.
        $fields->removeByName([
            'Metadata',
            'MetaTitle',
            'MetaDescription',
            'MetaCustomImage',
            'RobotsHeader',
            'NoIndex',
            'NoFollow',
            'NoVisit',
            'NoSnippet',
            'NoCache',
            'SocialHeader',
            'TwitterTitle',
            'TwitterDescription',
            'TwitterUsername',
            'TwitterCustomImage',
            'OGTitle',
            'OGDescription',
            'OGCustomImage',
            'FBPublisherlink',
            'FBAuthorlink',
            'GplusAuthorlink',
            'GplusPublisherlink',
            'PinterestCustomImage',
            'RichSnippetsHeader',
            'BreadcrumbIcon'
        ]);

        $fields->addFieldsToTab(
            'Root.MetaContent',
            array(
                TextField::create(
                    'MetaTitle',
                    _t(__CLASS__ . 'METATITLE', 'Meta title')
                ),
                TextareaField::create(
                    'MetaDescription',
                    _t(__CLASS__ . 'METADESCRIPTION', 'Meta description')
                ),
                UploadField::create(
                    'MetaCustomImage',
                    _t(__CLASS__ . 'METAIMAGECUSTOM', 'Meta image')
                )
                ->setAllowedFileCategories('image')
                ->setAllowedMaxFileNumber(1),
                HeaderField::create(
                    'RobotsHeader',
                    _t(__CLASS__ . 'ROBOTSHEADER', 'Search engine')
                ),
                CheckboxField::create(
                    'NoIndex',
                    _t(__CLASS__ . 'NOINDEX', 'Prevent indexing of this page')
                ),
                CheckboxField::create(
                    'NoFollow',
                    _t(__CLASS__ . 'NOFOLLOW', 'Prevent following links on this page')
                ),
                CheckboxField::create(
                    'NoSnippet',
                    _t(__CLASS__ . 'NOSNIPPET', 'Prevent showing a snippet of this page in the search results')
                ),
                CheckboxField::create(
                    'NoCache',
                    _t(__CLASS__ . 'NOCACHE', 'Prevent caching a version of this page')
                ),
                HeaderField::create(
                    'SocialHeader',
                    _t(__CLASS__ . 'SOCIALHEADER', 'Social')
                ),
                TextField::create(
                    'OGTitle',
                    _t(__CLASS__ . 'SHARETITLE', 'Share title')
                )
                ->setMaxLength(90),
                TextAreaField::create(
                    'OGDescription',
                    _t(__CLASS__ . 'SHAREDESCRIPTION', 'Share description')
                )
                ->setRows(2),
                UploadField::create(
                    'OGCustomImage',
                    _t(__CLASS__ . 'SHAREIMAGE', 'Share image')
                )
                ->setAllowedFileCategories('image')
                ->setAllowedMaxFileNumber(1)
                ->setDescription('<a href="https://developers.facebook.com/docs/sharing/best-practices#images" target="_blank">Optimum image ratio</a> is 1.91:1. (1200px wide by 630px tall or better)'),
                // Facebook
                TextField::create(
                    "FBAuthorlink",
                    _t(__CLASS__ . 'FBAUTHORLINK', 'Facebook author')
                )
                ->setRightTitle(_t(__CLASS__ . 'FBAUTHORLINKHELP', 'Author Facebook PROFILE URL')),
                TextField::create(
                    "FBPublisherlink",
                    _t(__CLASS__ . 'FBPUBLISHERLINK', 'Facebook publisher')
                )
                ->setRightTitle(_t(__CLASS__ . 'FBPUBLISHERLINKHELP', 'Publisher Facebook PAGE URL')),
                // Twitter
                TextField::create(
                    'TwitterTitle',
                    _t(__CLASS__ . 'TwitterTitle', 'Twitter title')
                ),
                TextField::create(
                    'TwitterDescription',
                    _t(__CLASS__ . 'TwitterDescription', 'Twitter description')
                ),
                TextField::create(
                    'TwitterUsername',
                    _t(__CLASS__ . 'TwitterUsername', 'Twitter username')
                ),
                UploadField::create(
                    'TwitterCustomImage',
                    _t(__CLASS__ . 'TwitterCustomImage', 'Twitter image')
                ),
                // Google plus
                TextField::create(
                    "GplusAuthorlink",
                    _t(__CLASS__ . 'GPLUSAUTHORLINK', 'Google+ author')
                )
                ->setRightTitle(_t(__CLASS__ . 'GPLUSAUTHORLINKHELP', 'Author Google+ PROFILE URL')),
                TextField::create(
                    "GplusPublisherlink",
                    _t(__CLASS__ . 'GPLUSPUBLISHERLINK', 'Google+ publisher')
                )
                ->setRightTitle(_t(__CLASS__ . 'GPLUSPUBLISHERLINKHELP', 'Publisher Google+ PAGE URL')),
                HeaderField::create(
                    'RichSnippetsHeader',
                    _t(__CLASS__ . 'RICHSNIPPETSHEADER', 'Rich snippets')
                ),
                UploadField::create(
                    'BreadcrumbIcon',
                    _t(__CLASS__ . 'BREADCRUMBICON', 'Breadcrumb Icon')
                )
                ->setAllowedFileCategories('image')
                ->setAllowedMaxFileNumber(1)
            )
        );

        if ($config->get('pinterest')) {
            $fields->addFieldToTab(
                "Root.$tabName",
                UploadField::create(
                    'PinterestCustomImage',
                    _t(__CLASS__ . 'PINTERESTIMAGE', 'Pinterest image')
                )
                ->setAllowedFileCategories('image')
                ->setAllowedMaxFileNumber(1)
                ->setDescription('Square/portrait or taller images look best on Pinterest. This image should be at least 750px wide.'));
        }

        $fields->fieldByName('Root.MetaContent')->setTitle(_t(__CLASS__ . '.TABLABEL', 'Meta Content'));

        return $fields;
    }

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
            'MetaTitle' => '{$MetaTitle|Title} &raquo; {$SiteConfig.Title}',
            'MetaCharset' => null,
            'MetaGenerator' => 'SilverStripe',
            'MetaDescription' => 'MetaDescription|Content',
            'MetaRobots' => null,
            'MetaResponsive' => null,
            'TwitterTitle' => 'TwitterTitle|MetaTitle|Title',
            'TwitterDescription' => 'TwitterDescription|MetaDescription|Description',
            'TwitterCard' => null,
            'TwitterImage' => 'TwitterCustomImage|MetaCustomImage',
            'TwitterSite' => 'TwitterUsername|SiteConfig.TwitterUsername',
            'TwitterCreator' => 'TwitterUsername|SiteConfig.TwitterUsername',
            'OGTitle' => 'OGTitle|MetaTitle|Title',
            'OGDescription' => 'OGDescription|MetaDescription|Content',
            'SchemaTagWebsite' => null,
            'SchemaTagBreadcrumbs' => null,
            'SchemaTagContactPoints' => null,
            'SchemaTagLocalBusiness' => null
        ];

        foreach ($tagTypes as $call => $default) {
            if (isset($meta_content[$call]) && method_exists($this, $call)) {
                $meta[$call] = $this->{$call}($owner->sreg($meta_content[$call]));
            } elseif ($owner->hasMethod($call)) {
                $meta[$call] = $owner->{$call}();
            } elseif (method_exists($this, $call)) {
                $meta[$call] = $this->{$call}($owner->sreg($default));
            }
        }

        foreach ($meta as $key => $tag) {
            if (is_object($tag) && $tag instanceof HTMLTag) {
                $meta[$key] = $tag->Render();
            }
        }

        $tags = implode("\n", $meta);
        var_dump($tags);
    }

    private function MetaTitle($value = null)
    {
        return HTMLTag::create($value, 'title');
    }

    private function MetaCharset($value = null)
    {
        $charset = ContentNegotiator::config()->uninherited('encoding');
        $tags[] = "<meta charset='$charset'>";
        $tags[] = "<meta http-equiv='Content-type' content='text/html; charset=$charset' />";
        return implode("\n", $tags);
    }

    private function MetaGenerator($value = null)
    {
        return HTMLTag::create()->setTag('meta')->setAttribute('generator', $value);
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
        return HTMLTag::create()->setTag('meta')->setAttribute('description', $metaDescription);
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

        return HTMLTag::create()->setTag('meta')->setAttribute('robots', implode(', ', $robots));
    }

    private function MetaResponsive($value = null)
    {
        return HTMLTag::create()->setTag('meta')->setAttribute('viewport', 'width=device-width, initial-scale=1.0');
    }

    private function TwitterTitle($value = null)
    {
        return HTMLTag::create()->setTag('meta')->setAttribute('twitter:title', $value);
    }

    private function TwitterDescription($value = null)
    {
        return HTMLTag::create()->setTag('meta')->setAttribute('twitter:description', $value);
    }

    private function TwitterCard($value = null)
    {
        return HTMLTag::create()->setTag('meta')->setAttribute('twitter:card', 'summary_large_image');
    }

    private function TwitterImage($value = null)
    {
        if ($value) {
            return HTMLTag::create()->setTag('meta')->setAttribute('twitter:image', $value);
        }
    }

    private function TwitterSite($value = null)
    {
        return HTMLTag::create()->setTag('meta')->setAttribute('twitter:site', '@' . $value);
    }

    private function TwitterCreator($value = null)
    {
        return HTMLTag::create()->setTag('meta')->setAttribute('twitter:creator', '@' . $value);
    }

    // public function getSchemaTagWebsite()
    // {
    //     $siteconfig = SiteConfig::current_site_config();
    //     $sitename = array(
    //         "@context" => "http://schema.org",
    //         "@type" => "WebSite",
    //         "name" => $siteconfig->Title,
    //         "url" => Director::AbsoluteBaseURL()
    //     );
    //     return MetaHelper::SchemaTag($sitename);
    // }
    //
    // public function getSchemaTagBreadcrumbs()
    // {
    //     $pages = $this->owner->getBreadcrumbItems();
    //     if ($pages->Count() > 1) {
    //         $breadcrumbsSchema = array(
    //             "@context" => "http://schema.org",
    //             "@type" => "BreadcrumbList"
    //         );
    //         $position = 1;
    //         foreach ($pages as $page) {
    //             $breadcrumbsSchema['itemListElement'][] = array(
    //                 "@type" => "ListItem",
    //                 "position" => $position,
    //                 "item" => array(
    //                     "@id" => $page->AbsoluteLink(),
    //                     "name" => $page->Title,
    //                     "image" => $page->BreadcrumbIcon()->Link()
    //                 )
    //             );
    //             $position++;
    //         }
    //         return MetaHelper::SchemaTag($breadcrumbsSchema);
    //     }
    //     return false;
    // }
    //
    // public function getSchemaTagContactPoints($value='')
    // {
    //     $siteconfig = SiteConfig::current_site_config();
    //     if ($contactPoints = $siteconfig->ContactPoints()) {
    //         $contactPointsSchema = array(
    //             "@context" => "http://schema.org",
    //             "@type" => "Organization",
    //             "url" => Director::absoluteBaseURL()
    //         );
    //         foreach ($contactPoints as $contactPoint) {
    //             $contactPointsSchema['contactPoint'][] = $contactPoint->buildSchemaArray();
    //         }
    //         return MetaHelper::SchemaTag($contactPointsSchema);
    //     }
    // }
    //
    // public function getSchemaTagLocalBusiness()
    // {
    //     $siteconfig = SiteConfig::current_site_config();
    //     if ($localBusiness = $siteconfig->LocalBusiness()) {
    //         $localBusinessSchema = array(
    //             "@context" => "http://schema.org",
    //             "@type" => "Organization"
    //         );
    //         foreach ($localBusiness as $localBusinessItem) {
    //             $localBusinessSchema['department'][] = $localBusinessItem->buildSchemaArray();
    //         }
    //         return MetaHelper::SchemaTag($localBusinessSchema);
    //     }
    // }
}

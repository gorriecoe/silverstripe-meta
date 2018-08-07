<?php

namespace gorriecoe\Meta\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Core\Config\Config;

/**
 * MetaFieldsExtension
 *
 * @package silverstripe-meta
 */
class MetaFieldsExtension extends DataExtension
{
    /**
     * Database fields
     * @var array
     */
    private static $db = [
        'MetaTitle' => 'Varchar',
        'TwitterTitle' => 'Varchar',
        'TwitterDescription' => 'Varchar',
        'TwitterUsername' => 'Varchar',
        'OGTitle' => 'Varchar(100)',
        'OGDescription' => 'Varchar(150)',
        'FBAuthorlink' => 'Varchar',
        'FBPublisherlink' => 'Varchar',
        'GplusAuthorlink' => 'Varchar',
        'GplusPublisherlink' => 'Varchar',
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
        'PinterestCustomImage' => Image::class
    ];

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
            'PinterestCustomImage'
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
                ->setRightTitle(_t(__CLASS__ . 'GPLUSPUBLISHERLINKHELP', 'Publisher Google+ PAGE URL'))
            )
        );

        if ($config->get('pinterest')) {
            $fields->addFieldToTab(
                'Root.MetaContent',
                UploadField::create(
                    'PinterestCustomImage',
                    _t(__CLASS__ . 'PINTERESTIMAGE', 'Pinterest image')
                )
                ->setAllowedFileCategories('image')
                ->setAllowedMaxFileNumber(1)
                ->setDescription('Square/portrait or taller images look best on Pinterest. This image should be at least 750px wide.')
            );
        }

        $fields->fieldByName('Root.MetaContent')->setTitle(_t(__CLASS__ . '.TABLABEL', 'Meta Content'));

        return $fields;
    }
}

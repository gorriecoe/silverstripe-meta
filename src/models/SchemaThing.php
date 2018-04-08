<?php

namespace gorriecoe\Meta\Models;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\AssetAdmin\Forms\UploadField;
/**
 * Description
 *
 * @package silverstripe
 * @subpackage mysite
 */
class SchemaThing extends SchemaObject
{
    /**
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'SchemaThing';

    /**
     * Singular name for CMS
     * @var string
     */
    private static $singular_name = 'Thing';

    /**
     * Plural name for CMS
     * @var string
     */
    private static $plural_name = 'Things';

    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'AdditionalType' => 'Text',
        'AlternativeName' => 'Text',
        'Description' => 'Text',
        'DisambiguatingDescription' => 'Text',
        'Name' => 'Text',
        'SameAs' => 'Text',
        'Url' => 'Text'
    );

    /**
     * Has_one relationship
     * @var array
     */
    private static $has_one = array(
        'Image' => Image::class
    );

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create(
                    'AdditionalType',
                    _t('SchemaThing.ADDITIONALTYPE', 'Additional Type')
                ),
                TextField::create(
                    'AlternativeName',
                    _t('SchemaThing.ALTERNATIVENAME', 'Alternative Name')
                ),
                TextareaField::create(
                    'Description',
                    _t('SchemaThing.DESCRIPTION', 'Description')
                ),
                TextareaField::create(
                    'DisambiguatingDescription',
                    _t('SchemaThing.DISAMBIGUATINGDESCRIPTION', 'Disambiguating Description')
                ),
                TextField::create(
                    'Name',
                    _t('SchemaThing.NAME', 'Name')
                ),
                TextField::create(
                    'SameAs',
                    _t('SchemaThing.SAMEAS', 'Same as')
                ),
                TextField::create(
                    'Url',
                    _t('SchemaThing.URL', 'URL')
                ),
                UploadField::create(
                    Image::class,
                    _t('SchemaThing.IMAGE', Image::class)
                )
            )
        );
        return $fields;
    }

    /**
     * Builds array ready to be converted into json schema
     * @return array
     */
    public function buildSchemaArray()
    {
        $array = parent::buildSchemaArray();
        if ($this->AdditionalType) {
            $array['additionalType'] = "$this->AdditionalType";
        }
        if ($this->AlternativeName) {
            $array['alternativeName'] = "$this->AlternativeName";
        }
        if ($this->Description) {
            $array['description'] = "$this->Description";
        }
        if ($this->DisambiguatingDescription) {
            $array['disambiguatingDescription'] = "$this->DisambiguatingDescription";
        }
        if ($this->Name) {
            $array['name'] = "$this->Name";
        }
        if ($this->SameAs) {
            $array['sameAs'] = "$this->SameAs";
        }
        if ($this->Url) {
            $array['url'] = "$this->Url";
        }
        if ($this->Image()->exists()) {
            $link = $this->Image()->Link();
            $array['image'] = "$link";
        }
        return $array;
    }
}

<?php

namespace gorriecoe\Meta\Models;

use SilverStripe\Forms\TextField;
use SilverStripe\Control\Email\Email;
use SilverStripe\TagField\StringTagField;
use SilverStripe\Forms\TextareaField;
/**
 * Description
 *
 * @package silverstripe
 * @subpackage mysite
 */
class SchemaContactPoint extends SchemaThing
{
    /**
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'SchemaContactPoint';

    /**
     * Singular name for CMS
     * @var string
     */
    private static $singular_name = 'Contact point';

    /**
     * Plural name for CMS
     * @var string
     */
    private static $plural_name = 'Contact points';

    /**
     * Defines the current schema type
     * @var string
     */
    private static $schema_type = 'ContactPoint';

    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'ContactType' => 'Varchar(100)', // contactType
        'Telephone' => 'Varchar(100)',
        'Email' => 'Varchar(100)',
        'FaxNumber' => 'Varchar(100)',
        'ContactOption' => 'Text', // HearingImpairedSupported, TollFree, etc
        'AreaServed' => 'Text', // NZ, US, etc
        'AvailableLanguage' => 'Text',
        'HoursAvailable' => 'Text'
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
                    'ContactType',
                    _t('SchemaContactPoint.CONTACTTYPE', 'Contact Type')
                ),
                TextField::create(
                    'Telephone',
                    _t('SchemaContactPoint.TELEPHONE', 'Telephone')
                ),
                TextField::create(
                    Email::class,
                    _t('SchemaContactPoint.EMAIL', Email::class)
                ),
                TextField::create(
                    'FaxNumber',
                    _t('SchemaContactPoint.FAXNUMBER', 'Fax number')
                ),
                TextField::create(
                    'Telephone',
                    _t('SchemaContactPoint.TELEPHONE', 'Telephone')
                ),
                StringTagField::create(
                    'ContactOption',
                    _t('SchemaContactPoint.CONTACTOPTION', 'Contact Option'),
                    array('HearingImpairedSupported', 'TollFree'),
                    explode(',', $this->ContactOption)
                ),
                AreaServedField::create(
                    'AreaServed',
                    _t('SchemaContactPoint.AREASERVED', 'Area Served')
                ),
                TextareaField::create(
                    'HoursAvailable',
                    _t('SchemaContactPoint.HOURSAVAILABLE', 'Hours Available')
                )
                    ->setDescription(
                        _t(
                            'SchemaContactPoint.HOURSAVAILABLEDESCIPTION',
                            'The general opening hours for a business. Opening hours can be specified as a weekly time range, starting with days, then times per day. Multiple days can be listed with commas \',\' separating each day. Day or time ranges are specified using a hyphen \'-\'.
                            Days are specified using the following two-letter combinations: Mo, Tu, We, Th, Fr, Sa, Su.
                            Times are specified using 24:00 time. For example, 3pm is specified as 15:00.
                            Here is an example: <time itemprop="openingHours" datetime="Tu,Th 16:00-20:00">Tuesdays and Thursdays 4-8pm</time>.
                            If a business is open 7 days a week, then it can be specified as <time itemprop="openingHours" datetime="Mo-Su">Monday through Sunday, all day</time>.'
                        )
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
        if ($this->ContactType) {
            $array['contactType'] = "$this->ContactType";
        }
        if ($this->Telephone) {
            $array['telephone'] = "$this->Telephone";
        }
        if ($this->Email) {
            $array['email'] = "$this->Email";
        }
        if ($this->FaxNumber) {
            $array['faxNumber'] = "$this->FaxNumber";
        }
        if ($contactOption = $this->ContactOption) {
            if (strpos($contactOption, ',') !== false) {
                $array['contactOption'] = explode(',', $contactOption);
            } else {
                $array['contactOption'] = "$this->ContactOption";
            }
        }
        if ($areaServed = $this->AreaServed) {
            if (strpos($areaServed, ',') !== false) {
                $array['areaServed'] = explode(',', $areaServed);
            } else {
                $array['areaServed'] = "$this->AreaServed";
            }
        }
        if ($this->HoursAvailable) {
            $array['hoursAvailable'] = "$this->HoursAvailable";
        }
        return $array;
    }
}

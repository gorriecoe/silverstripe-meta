<?php

namespace gorriecoe\Meta\Models;

use SilverStripe\Forms\TextField;
/**
 * Description
 *
 * @package silverstripe
 * @subpackage mysite
 */
class SchemaPlaceLocalBusiness extends SchemaPlace
{
    /**
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'SchemaPlaceLocalBusiness';

    /**
     * Singular name for CMS
     * @var string
     */
    private static $singular_name = 'Local business';

    /**
     * Plural name for CMS
     * @var string
     */
    private static $plural_name = 'Local business';

    /**
     * Defines the current schema type
     * @var string
     */
    private static $schema_type = 'LocalBusiness';

    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'CurrenciesAccepted' => 'Text',
        'PaymentAccepted' => 'Text',
        'PriceRange' => 'Text',
        'OpenHours' => 'Text'
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
                CurrencySelectField::create(
                    'CurrenciesAccepted',
                    _t('SchemaLocalBusiness.CURRENCIESACCEPTED', 'Currencies accepted')
                ),
                TextField::create(
                    'PaymentAccepted',
                    _t('SchemaLocalBusiness.PAYMENTACCEPTED', 'Payment accepted')
                ),
                TextField::create(
                    'PriceRange',
                    _t('SchemaLocalBusiness.PRICERANGE', 'Price range')
                ),
                TextField::create(
                    'OpenHours',
                    _t('SchemaLocalBusiness.OPENHOURS', 'Open hours')
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
        if ($this->CurrenciesAccepted) {
            $array['currenciesAccepted'] = "$this->CurrenciesAccepted";
        }
        if ($this->PaymentAccepted) {
            $array['paymentAccepted'] = "$this->PaymentAccepted";
        }
        if ($this->PriceRange) {
            $array['priceRange'] = "$this->PriceRange";
        }
        if ($this->OpenHours) {
            $array['openHours'] = "$this->OpenHours";
        }
        return $array;
    }
}

<?php

namespace gorriecoe\Meta\Models;

use SilverStripe\Forms\TextField;
/**
 * Description
 *
 * @package silverstripe
 * @subpackage mysite
 */
class SchemaOpeningHours extends SchemaObject
{
    /**
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'SchemaOpeningHours';

    /**
     * Singular name for CMS
     * @var string
     */
    private static $singular_name = 'Open hours';

    /**
     * Plural name for CMS
     * @var string
     */
    private static $plural_name = 'Open hours';

    /**
     * Defines the current schema type
     * @var string
     */
    private static $schema_type = 'OpeningHoursSpecification';

    /**
     * Database fields
     * @var array
     */
    private static $db = array(
        'DayOfWeek' => 'Text',
        'Opens' => 'Text',
        'Closes' => 'Text',
        'ValidFrom' => 'Date',
        'ValidThrough' => 'Date'
    );

    /**
     * Defines summary fields commonly used in table columns
     * as a quick overview of the data for this dataobject
     * @var array
     */
    private static $summary_fields = array(
        'DayOfWeek',
        'Opens',
        'Closes',
        'ValidFrom',
        'ValidThrough'
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
                DaysOfWeekField::create(
                    'DayOfWeek',
                    _t('SchemaOpeningHours.DAYOFWEEK', 'Days of the week')
                ),
                TextField::create(
                    'Opens',
                    _t('SchemaOpeningHours.OPENS', 'Opens')
                ),
                TextField::create(
                    'Closes',
                    _t('SchemaOpeningHours.CLOSES', 'Closes')
                ),
                TextField::create(
                    'ValidFrom',
                    _t('SchemaOpeningHours.VALIDFROM', 'Valid from')
                ),
                TextField::create(
                    'ValidThrough',
                    _t('SchemaOpeningHours.VALIDTROUGH', 'Valid through')
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
        if ($dayOfWeek = $this->DayOfWeek) {
            if (strpos($dayOfWeek, ',') !== false) {
                $array['dayOfWeek'] = explode(',', $dayOfWeek);
            } else {
                $array['dayOfWeek'] = "$this->ContactOption";
            }
        }
        if ($this->Opens) {
            $array['opens'] = "$this->Opens";
        }
        if ($this->Closes) {
            $array['closes'] = "$this->Closes";
        }
        if ($this->ValidFrom) {
            $array['validFrom'] = "$this->ValidFrom";
        }
        if ($this->ValidThrough) {
            $array['ValidThrough'] = "$this->ValidThrough";
        }
        return $array;
    }
}

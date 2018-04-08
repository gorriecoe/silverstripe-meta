<?php

namespace gorriecoe\Meta\Models;

use SilverStripe\Security\Permission;
use SilverStripe\ORM\DataObject;
/**
 * Description
 *
 * @package silverstripe
 * @subpackage mysite
 */
class SchemaObject extends DataObject
{
    /**
     * Defines the database table name
     * @var string
     */
    private static $table_name = 'SchemaObject';

    /**
     * Singular name for CMS
     * @var string
     */
    private static $singular_name = 'Schema';

    /**
     * Plural name for CMS
     * @var string
     */
    private static $plural_name = 'Schema';

    private static $title_pattern = "";

    /**
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        return $fields;
    }

    /**
     * CMS Title
     * @return string
     */
    public function getTitle()
    {
        $title = parent::getTitle();
        if ($this->Config()->title_pattern) {
            $title = $this->Config()->title_pattern;
        }
        return $title;
    }

    /**
     * Builds array ready to be converted into json schema
     * @return array
     */
    public function buildSchemaArray()
    {
        $array = array(
            "@type" => $this->Config()->schema_type
        );
        return $array;
    }

    /**
     * Creating Permissions
     * @return boolean
     */
    public function canCreate($member = null, $context = [])
    {
        return Permission::check('SITETREE_EDIT_ALL', 'any', $member);;
    }

    /**
     * Editing Permissions
     * @return boolean
     */
    public function canEdit($member = null)
    {
        return Permission::check('SITETREE_EDIT_ALL', 'any', $member);;
    }

    /**
     * Deleting Permissions
     * @return boolean
     */
    public function canDelete($member = null)
    {
        return Permission::check('SITETREE_EDIT_ALL', 'any', $member);;
    }

    /**
     * Viewing Permissions
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::check('SITETREE_VIEW_ALL', 'any', $member);
    }
}

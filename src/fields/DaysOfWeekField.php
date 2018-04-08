<?php

namespace gorriecoe\Meta\Fields;

use SilverStripe\TagField\StringTagField;

/**
 * Provides a custom tagging field for days of the week
 *
 * @package silverstripe
 * @subpackage mysite
 */
class DaysOfWeekField extends StringTagField
{
    /**
     * @param string $name
     * @param string $title
     */
    public function __construct($name, $title = '')
    {
        $source = array(
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        );
        $value = explode(',', $this->{$name});
        parent::__construct($name, $title, $source, $value);
    }
}

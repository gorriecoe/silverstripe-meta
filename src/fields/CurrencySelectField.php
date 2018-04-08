<?php

namespace gorriecoe\Meta\Fields;

use SilverStripe\TagField\StringTagField;

/**
 * Provides a custom tagging field for currencies
 *
 * @package silverstripe
 * @subpackage mysite
 */
class CurrencySelectField extends StringTagField
{
    /**
     * @param string $name
     * @param string $title
     */
    public function __construct($name, $title = '')
    {
        $source = array(
            'AFA','ALL','DZD','AOA','ARA','AMD','AWG','AZM','BSD','BHD','BDT','BBD',
            'BYR','BZD','XAF','BMD','BTN','BOB','BAM','BWP','BRL','GBP','BND','BGN',
            'XAF','BIF','KHR','XAF','CAD','CVE','KYD','XAF','XAF','CLF','CNY','COP',
            'KMF','CDZ','XAF','CRC','HRK','CUP','EUR','CZK','DKK','DJF','DOP','TPE',
            'EGP','XAF','ERN','EEK','ETB','FKP','FJD','XPF','XAF','GMD','GEL','NZD',
            'GHC','GIP','GTQ','GNS','GWP','GYD','HTG','HNL','HKD','HUF','ISK','NOK',
            'INR','IDR','IRR','IQD','ILS','XAF','JMD','JPY','JOD','KZT','KES','KPW',
            'KRW','KWD','KGS','LAK','LVL','LBP','LSL','LRD','LYD','CHF','LTL','MOP',
            'MKD','MGF','MWK','MYR','MVR','XAF','MRO','MUR','MXN','MDL','MNT','MAD',
            'MZM','MMK','NAD','NPR','ANG','XPF','NIC','XOF','NGN','OMR','PKR','PAB',
            'PGK','PYG','PEI','PHP','PLN','QAR','ROL','RUB','RWF','WST','STD','SAR',
            'XOF','SCR','SLL','SGD','SBD','SOS','ZAR','GBP','LKR','SHP','SDG','SRG',
            'SZL','SEK','CHF','SYP','TWD','TJR','TZS','THB','XAF','TOP','TTD','TND',
            'TRY','TMM','UGS','UAH','SUR','AED','GBP','UYU','UZS','VUV','VEF','VND',
            'XPF','XOF','MAD','ZMK','USD','XCD','AUD'
        );
        $value = explode(',', $this->{$name});
        parent::__construct($name, $title, $source, $value);
    }
}

<?php
defined('SYS_ENTRY') or die('Access Denied!');


final class Common_Data_Field_Type
{
    const TEXT          = 'text';
    const TEXT_LONG     = 'text_long';
    const TEXT_SUMMARY  = 'text_summary';

    //const LIST          = 'list';
    const LIST_BOOL     = 'list_bool';
    const LIST_NUMBER   = 'list_number';
    const LIST_TEXT     = 'list_text';

    const NUMBER_INTEGER= 'number_integer';
    const NUMBER_DECIMAL= 'number_decimal';
    const NUMBER_FLOAT  = 'number_float';

    const TERM_SELECT   = 'term_select';
    const TERM_BUTTON   = 'term_button';
    const TERM_AUTO     = 'term_auto';

    const OPTION_SELECT = 'option_select';  // Select list
    const OPTION_BUTTON = 'option_button';  // Check boxes/radio buttons
    const OPTION_ONOFF  = 'option_onoff';   // Single on/off checkbox
    
    
    public static function inTypes($type)
    {
        if (in_array($type, array(self::TEXT))) {
            //
        }
    }
}

<?php
function FormatTotals($level, $max, $negative = false) {
    if ((!$negative && $level < $max) || ($negative && $level > $max)) {
        return '<span class="blue">'.$level.'</span>';
    } elseif ((!$negative && $level > $max) || ($negative && $level < $max)) {
        return '<span class="red"><strong>'.$level.'</strong></span>';
    } else {
        return $level;
    }
}


function FormatSession($session) {
    switch ($session) {
        case 'A':
            return 'AM';
            break;
        case 'P':
            return 'PM';
            break;
        default:
            return 'Day';
            break;
    }
}


function FormatText($value, $default = '&nbsp;') {
    return !empty($value) ? nl2br($value) : $default;
}


function FormatPostcode($value, $default = '&nbsp;') {
    return !empty($value) ? sprintf('<a href="http://maps.google.co.uk/?q=%s" title="Lookup this postcode on Google Maps">%s</a>', str_replace(' ', '+', $value), $value) : $default;
}


function FormatEmail($value, $default = '&nbsp;') {
    return !empty($value) ? sprintf('<a href="mailto:%s" title="Send an email to %s">%s</a>', $value, $value, $value) : $default;
}


function FormatBoolean($value) {
    return $value=='Y' || $value=='t' ? 'Yes' : 'No';
}


function FormatNumeric($value, $default = '0') {
    return is_numeric($value) ? floatval($value) : $default;
}


function FormatDate($value, $format = 'd-m-Y', $default = '&nbsp;') {
    if (empty($value) || (count(explode('-',$value))!=3)) {
        return $default;
    } else {
        $dd = null;
        $mm = null;
        $yyyy = null;
        list($dd, $mm, $yyyy) = explode('-',$value);
        return date($format, mktime(1, 1, 1, $mm, $dd, $yyyy));
    }
}
?>
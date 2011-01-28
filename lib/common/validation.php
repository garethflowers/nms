<?php
/*
 * check for a valid date
 */
function IsDate($date) {
    if (!empty($date) && count(explode('-',$date)) == 3) {
        $dd = null;
        $mm = null;
        $yyyy = null;
        list($dd,$mm,$yyyy) = explode('-',$date);

        if (is_numeric($dd) && is_numeric($mm) && is_numeric($yyyy) && intval($yyyy)>1900 && intval($yyyy)<2500) {
            return checkdate(intval($mm), intval($dd), intval($yyyy));
        }
    }

    return false;
}


/*
 * check for a valid boolean
 */
function IsBool($boolean) {
    return $boolean===true || $boolean=='t' ? true : false;
}


/*
 * check for a valid email
 */
function IsEmail($email) {
    return eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $email);
}
?>
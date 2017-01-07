<?php

function GetDictionaryMonth() {
    $dictionary = array(
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    );
    return $dictionary;
}

function GetDictionaryYear() {
    $dictionary = array();
    $from = intval(date('Y')) - 5;
    $to = $from + 20;

    for ($i = $from; $i < $to; $i++) {
        $dictionary[$i] = $i;
    }

    return $dictionary;
}

function GetExcludedRooms() {
    return array(
        't' => 'Included Rooms',
        'f' => 'Excluded Rooms Only'
    );
}

?>

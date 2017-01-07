<?php

/*
 * Javascript - Popup Calendar
 */

function JsCalendar($id) {
    $output = 'new vlaDatePicker(\'' . $id . '\',{';
    $output .= 'offset:{y:6},';
    $output .= 'format:\'d-m-y\',';
    $output .= 'separator:\'-\',';
    $output .= 'ieTransitionColor:\'\',';
    $output .= 'alignX:\'left\',';
    $output .= 'alignY:\'bottom\',';
    $output .= 'prefillDate:false';
    $output .= '});';
    return $output;
}

/*
 * Javascript - Sorting Table
 */

function JsSortingTable($id) {
    $output = 'new SortingTable(\'' . $id . '\',{});';
    return $output;
}

/*
 * Javascript - Html Javascript Block
 */

function JsBlock($javascript, $domready = false) {
    $output = '<script type="text/javascript">/* <![CDATA[ */';

    if ($domready) {
        $output .= 'window.addEvent(\'domready\', function() {';
    }

    $output .= $javascript;

    if ($domready) {
        $output .= '});';
    }

    $output .= '/* ]]> */</script>';

    return $output;
}

?>
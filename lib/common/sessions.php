<?php
/*
 * Gets the number of children in the specified session
 */
function SessionNumbers($date, $session, $room) {
    $query = 'select session_numbers';
    $query .= '(' . Db::SqlFormat($date, 'date');
    $query .= ',' . Db::SqlFormat($session, 'string');
    $query .= ',' . Db::SqlFormat($room, 'int');
    $query .= ');';
    return Db::GetScalar($query);
}


/*
 * Gets a list of children in the specified session
 */
function SessionNames($date, $session, $room, $total) {
    $count = 0;

    $query = 'select * from session_names';
    $query .= '(' . Db::SqlFormat($date, 'date');
    $query .= ',' . Db::SqlFormat($session, 'string');
    $query .= ',' . Db::SqlFormat($room, 'int');
    $query .= ');';
    $data = Db::GetDataArray($query);

    echo '<table class="results">';
    echo '<tr>';
    echo '<th>'.FormatDate($date, 'l').' '.FormatSession($session).'</th>';
    echo '</tr>';

    foreach ($data as $row) {
        $count++;

        if ($count > $total) {
            echo '<tr class="red">';
        } else {
            echo '<tr class="blue">';
        }
        echo '<td class="nowrap hac">'.$row['age'].' '.$row['name'].'</td>';
        echo '</tr>';
    }

    for ($i = $count; $i < $total; $i++) {
        echo '<tr>';
        echo '<td>&nbsp;</td>';
        echo '</tr>';
    }

    echo '<tr>';
    echo '<td class="hac"><strong>'.$count.'</strong></td>';
    echo '</tr>';
    echo '</table>';
}


/*
 * Get the first day of the week as an offset of todays date
 */
function GetFirstDayOfWeek($year, $month, $day) {
    return  $day - (intval(date('N', mktime(0,0,0,$month,$day,$year))) - 1);
}
?>
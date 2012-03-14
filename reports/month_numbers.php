<?php
require_once('../lib/base.php');

// set variables
$include = isset($_REQUEST['i']) && $_REQUEST['i'] == 'f' ? false : true;
$month = isset($_REQUEST['m']) && intval($_REQUEST['m']) >= 1 && intval($_REQUEST['m']) <= 12 ? intval($_REQUEST['m']) : date('m');
$year = isset($_REQUEST['y']) && intval($_REQUEST['y']) >= 1900 && intval($_REQUEST['y']) <= 2500 ? intval($_REQUEST['y']) : date('Y');

if ($month > 12) {
    $year++;
    $month = 1;
} elseif ($month < 1) {
    $year--;
    $month = 12;
}

// generate required dictionaries
$dictionary_month = GetDictionaryMonth();
$dictionary_year = GetDictionaryYear();

// vars
$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));

$query = 'select id,name,total from rooms where include=' . Db::SqlFormat($include, 'bool') . ' order by min';
$rooms = Db::GetDataArray($query);

PageHeader();
?>

<h1 class="links">
    <a href="<?php echo PHP_SELF . '?m=' . ($month - 1) . '&amp;y=' . $year; ?>">Prev</a>
    - <a href="<?php echo PHP_SELF . '?m=' . ($month + 1) . '&amp;y=' . $year; ?>">Next</a>
</h1>
<?php TitleReports('Monthly Numbers - ' . date('F Y', mktime(1, 1, 1, $month, 1, $year))); ?>

<p>&nbsp;</p>

<form action="<?php echo PHP_SELF; ?>" method="get" id="formreport">
    <div class="har">
        <?php echo FormCombo('m', $dictionary_month, $month, ''); ?>
        <?php echo FormCombo('y', $dictionary_year, $year, ''); ?>
        <?php echo FormCombo('i', GetExcludedRooms(), $include ? 't' : 'f', ''); ?>
        <?php echo FormSubmit('process', 'View', 'formreport'); ?>
    </div>
</form>

<p>&nbsp;</p>

<table class="results">
    <tr>
        <td rowspan="2" class="hal" style="width:1%;">&nbsp;</td>
        <?php foreach ($rooms as $row_rooms) { ?>
            <th colspan="2"><?php echo $row_rooms['name']; ?></th>
        <?php } ?>
        <th colspan="2">Total</th>
    </tr>
    <tr>
        <?php $column_span = (2 * count($rooms)) + 3; ?>
        <?php foreach ($rooms as $row_rooms) { ?>
            <th class="row_heading">AM</th>
            <th class="row_heading">PM</th>
        <?php }; ?>
        <th class="row_heading">AM</th>
        <th class="row_heading">PM</th>
    </tr>
    <?php
    $weekend = false;
    $room_total = 0;

    for ($day = 1; $day <= $days_in_month; $day++) {

        $date = mktime(0, 0, 0, $month, $day, $year);

        if ((date('w', $date) == 0) || (date('w', $date) == 6)) {
            $weekend = $weekend && $day > 2 ? false : true;
            if ($weekend) {
                ?>
                <tr>
                    <td class="small" colspan="<?php echo $column_span; ?>">&nbsp;</td>
                </tr>
                <?php
            }
        } else {

            if (Db::GetScalar('select date_open(' . Db::SqlFormat($date, 'date') . ');') == 0) {
                ?>
                <tr>
                    <th class="row_heading nowrap hal"><?php
            $morning = 0;
            $afternoon = 0;
            $room_total = 0;
            echo date('d, l', $date);
                ?>
                    </th>
                    <?php foreach ($rooms as $row_rooms) { ?>
                        <td class="hac"><?php
                $num = SessionNumbers(date('d-m-Y', $date), 'A', $row_rooms['id']);
                $morning += $num;
                echo FormatTotals($num, $row_rooms['total']);
                        ?></td>
                        <td class="hac"><?php
                $num = SessionNumbers(date('d-m-Y', $date), 'P', $row_rooms['id']);
                $afternoon += $num;
                echo FormatTotals($num, $row_rooms['total']);
                        ?></td>
                        <?php
                        $room_total += $row_rooms['total'];
                    }
                    ?>
                    <td class="hac"><?php echo FormatTotals($morning, $room_total); ?></td>
                    <td class="hac"><?php echo FormatTotals($afternoon, $room_total); ?></td>
                </tr>
            <?php } else { ?>
                <tr>
                    <th class="row_heading nowrap hal"><?php echo date('d, l', $date); ?></th>
                    <td class="hac" colspan="<?php echo $column_span - 1; ?>">(Closed)</td>
                </tr>
                <?php
            }
        }
    }
    ?>
</table>

<?php PageFooter(); ?>
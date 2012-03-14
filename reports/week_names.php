<?php
require_once('../lib/base.php');

// set variables
if (isset($_GET['d1']))
    $_GET['d'] = $_GET['d1'];
$room = isset($_GET['r']) && is_numeric($_GET['r']) ? $_GET['r'] : 0;
$date = isset($_GET['d']) && IsDate($_GET['d']) ? $_GET['d'] : date('d-m-Y');

list($day, $month, $year) = explode('-', $date);
$day = GetFirstDayOfWeek($year, $month, $day);

// get data
$query = 'select id as k,name as v';
$query .= ' from rooms';
$query .= ' order by min;';
$dictionary_room = Db::Dictionary($query);

$query = 'select id,name,total';
$query .= ' from rooms';
$query .= $room == 0 ? ' order by min;' : ' where id=' . $room . ' limit 1;';
$rooms = Db::GetDataArray($query);

PageHeader();
?>

<h1 class="links">
    <a href="<?php echo PHP_SELF . '?d=' . date('d-m-Y', mktime(1, 1, 1, $month, $day - 7, $year)) . '&amp;r=' . $room; ?>">Prev</a>
    - <a href="<?php echo PHP_SELF . '?d=' . date('d-m-Y', mktime(1, 1, 1, $month, $day + 7, $year)) . '&amp;r=' . $room; ?>">Next</a>
</h1>
<?php TitleReports('Week Names - ' . date('jS F Y', mktime(1, 1, 1, $month, $day, $year))); ?>

<p>&nbsp;</p>

<form action="<?php echo PHP_SELF; ?>" method="get" id="formreport">
    <div class="har">
        <?php echo FormDate('d', $date, ''); ?>
        <?php echo FormCombo('r', array('0' => 'All Rooms') + $dictionary_room, $room, ''); ?>
        <?php echo FormSubmit('process', 'View', 'formreport'); ?>
    </div>
</form>

<p>&nbsp;</p>

<?php
$first = true;
foreach ($rooms as $row) {
    if (!$first) {
        echo '<br clear="all" style="page-break-before:always" />';
    } else {
        $first = false;
    }
    ?>

    <h2><?php echo $row['name']; ?> (<?php echo date('d-m-Y', mktime(0, 0, 0, $month, $day, $year)); ?>)</h2>

    <table class="fw vat">
        <tr>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day, $year)), 'A', $row['id'], $row['total']); ?></td>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day + 1, $year)), 'A', $row['id'], $row['total']); ?></td>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day + 2, $year)), 'A', $row['id'], $row['total']); ?></td>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day + 3, $year)), 'A', $row['id'], $row['total']); ?></td>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day + 4, $year)), 'A', $row['id'], $row['total']); ?></td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day, $year)), 'P', $row['id'], $row['total']); ?></td>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day + 1, $year)), 'P', $row['id'], $row['total']); ?></td>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day + 2, $year)), 'P', $row['id'], $row['total']); ?></td>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day + 3, $year)), 'P', $row['id'], $row['total']); ?></td>
            <td class="vat"><?php SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day + 4, $year)), 'P', $row['id'], $row['total']); ?></td>
        </tr>
    </table>

    <?php
}

PageFooter();
?>
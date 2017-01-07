<?php
require_once('../lib/base.php');

// set variables
if (isset($_GET['d0']))
    $_GET['d'] = $_GET['d0'];
$room = isset($_GET['r']) && is_numeric($_GET['r']) ? $_GET['r'] : 0;
$date = isset($_GET['d']) && IsDate($_GET['d']) ? $_GET['d'] : date('d-m-Y');
$session = isset($_GET['s']) ? $_GET['s'] : 'D';

list($day, $month, $year) = explode('-', $date);

// get data
$query = 'select id as k,name as v';
$query .= ' from rooms';
$query .= ' order by min;';
$dictionary_room = Db::Dictionary($query);

$query = 'select id,name,total,min,max';
$query .= ' from rooms';
$query .= $room == 0 ? ' order by min;' : ' where id=' . $room . ' limit 1;';
$rooms = Db::GetDataArray($query);

PageHeader();
?>

<h1 class="links"><a href="<?php echo PHP_SELF . '?d=' . date('d-m-Y', mktime(1, 1, 1, $month, $day - 1, $year)) . '&amp;r=' . $room . '&amp;s=' . $session; ?>">Prev</a> - <a href="<?php echo PHP_SELF . '?d=' . date('d-m-Y', mktime(1, 1, 1, $month, $day + 1, $year)) . '&amp;r=' . $room . '&amp;s=' . $session; ?>">Next</a></h1>

<?php TitleReports('Sessions - ' . date('l, jS F Y', mktime(1, 1, 1, $month, 1, $year)) . ', ' . FormatSession($session)); ?>

<p>&nbsp;</p>

<form action="<?php echo PHP_SELF; ?>" method="get" id="formreport">
    <div class="har"><?php echo FormDate('d', $date, ''); ?> <?php echo FormCombo('r', array('0' => 'All Rooms') + $dictionary_room, $room, ''); ?> <?php echo FormCombo('s', array('D' => 'Day', 'A' => 'AM', 'P' => 'PM'), $session, ''); ?> <?php echo FormSubmit('process', 'View', 'formreport'); ?></div>
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
    echo '<h2>' . $row['name'] . ' (' . date('D, d-M-Y', mktime(0, 0, 0, $month, $day, $year)) . ', ' . FormatSession($session) . ')</h2>';

    SessionNames(date('d-m-Y', mktime(0, 0, 0, $month, $day, $year)), 'A', $row['id'], $row['total']);
}

PageFooter();
?>
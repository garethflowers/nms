<?php
require_once('../lib/base.php');

// set variables
if (isset($_GET['d3']))
    $_GET['d'] = $_GET['d3'];
$room = isset($_GET['r']) && is_numeric($_GET['r']) ? $_GET['r'] : 0;
$date = isset($_GET['d']) && IsDate($_GET['d']) ? $_GET['d'] : date('d-m-Y');

list($day, $month, $year) = explode('-', $date);
$day = GetFirstDayOfWeek($year, $month, $day);

// get data
$query = 'select id as k,name as v';
$query .= ' from rooms';
$query .= ' order by min;';
$dictionary_room = Db::Dictionary($query);

$query = 'select id,name,max,min,total';
$query .= ' from rooms';
$query .= $room == 0 ? ' order by min;' : ' where id=' . $room . ' limit 1;';
$rooms = Db::GetDataArray($query);

PageHeader();
?>

<h1 class="links">
    <a href="<?php echo PHP_SELF . '?d=' . date('d-m-Y', mktime(1, 1, 1, $month, $day - 7, $year)) . '&amp;r=' . $room; ?>">Prev</a>
    - <a href="<?php echo PHP_SELF . '?d=' . date('d-m-Y', mktime(1, 1, 1, $month, $day + 7, $year)) . '&amp;r=' . $room; ?>">Next</a>
</h1>
<?php TitleReports('Register - ' . date('jS F Y', mktime(1, 1, 1, $month, $day, $year))); ?>

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
foreach ($rooms as $row_room) {
    if (!$first) {
        echo '<br clear="all" style="page-break-before:always" />';
    } else {
        $first = false;
    }
    ?>

    <h2>Register, <?php echo $row_room['name'] . ', ' . date('d-m-Y', mktime(0, 0, 0, $month, $day, $year)); ?></h2>

    <table class="results">
        <tr>
            <th style="width:1%;">&nbsp;</th>
            <th colspan="2" class="row_heading">Mon</th>
            <th colspan="2" class="row_heading">Tues</th>
            <th colspan="2" class="row_heading">Wed</th>
            <th colspan="2" class="row_heading">Thu</th>
            <th colspan="2" class="row_heading">Fri</th>
        </tr>
        <?php
        $date_from = date('d-m-Y', mktime(0, 0, 0, $month, $day, $year));
        $date_to = date('d-m-Y', mktime(0, 0, 0, $month, $day + 4, $year));
        $query = 'select distinct child.id,child.forename,child.surname';
        $query .= ' from child_session';
        $query .= ' inner join child on child_session.child_id=child.id';
        $query .= ' where start<=' . Db::SqlFormat($date_to, 'date');
        $query .= ' and finish>=' . Db::SqlFormat($date_from, 'date');
        $query .= ' and date<=' . Db::SqlFormat($date_to, 'date');
        $query .= ' and date_till>=' . Db::SqlFormat($date_from, 'date');
        $query .= ' and ((age_months(dob,' . Db::SqlFormat($date_to, 'date') . ')>=' . Db::SqlFormat($row_room['min'], 'int');
        $query .= ' and age_months(dob,' . Db::SqlFormat($date_from, 'date') . ')<' . Db::SqlFormat($row_room['max'], 'int');
        $query .= ' and (room_override=0 or room_override is null))';
        $query .= ' or room_override=' . Db::SqlFormat($row_room['id'], 'int') . ')';
        $query .= ' order by surname,forename;';
        $child = Db::GetDataArray($query);
        foreach ($child as $row_child) {
            ?>
            <tr>
                <th class="hal nowrap"><?php echo strtoupper($row_child['surname']) . ' ' . $row_child['forename']; ?></th>
                <?php
                for ($c = 0; $c < 5; $c++) {
                    $date = date('d-m-Y', mktime(0, 0, 0, $month, $day + $c, $year));
                    if (Db::GetScalar('select date_open(' . Db::SqlFormat($date, 'date') . ');') == 0) {
                        $query = 'select session';
                        $query .= ' from child_session';
                        $query .= ' inner join child on child.id=child_session.child_id';
                        $query .= ' where extract(dow from date)=extract(dow from ' . Db::SqlFormat($date, 'date') . ')';
                        $query .= ' and start<=' . Db::SqlFormat($date, 'date');
                        $query .= ' AND finish>=' . Db::SqlFormat($date, 'date');
                        $query .= ' and date<=' . Db::SqlFormat($date, 'date');
                        $query .= ' AND date_till>=' . Db::SqlFormat($date, 'date');
                        $query .= ' and child_session.child_id=' . Db::SqlFormat($row_child['id'], 'int');
                        $query .= ' limit 1;';
                        $session = strtoupper(Db::GetScalar($query));
                        ?>
                        <td style="width:10%" class="hac">
                            <?php echo $session == 'D' || $session == 'A' ? '&nbsp;' : '- - -'; ?>
                        </td>
                        <td style="width:10%" class="hac">
                            <?php echo $session == 'D' || $session == 'P' ? '&nbsp;' : '- - -'; ?>
                        </td>
                        <?php
                    }
                }
                ?>
            </tr>
            <?php
        }
        $query = 'select forename,surname';
        $query .= ' from staff';
        $query .= ' where assigned_room=' . Db::SqlFormat($row_room['id'], 'int');
        $query .= ' order by surname,forename';
        $data_staff = Db::GetDataArray($query);
        if (count($data_staff) > 0) {
            ?>
            <tr>
                <td colspan="11">&nbsp;</td>
            </tr>
            <?php
        }
        foreach ($data_staff as $row_staff) {
            ?>
            <tr>
                <th class="hal nowrap"><?php echo strtoupper($row_staff['surname']) . ', ' . $row_staff['forename']; ?></th>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        <?php } ?>
    </table>
    <?php
}

PageFooter();
?>
<?php
require_once('../lib/base.php');

$dob = isset($_GET['d']) && IsDate($_GET['d']) ? $_GET['d'] : date('d-m-Y');
$mon_a = isset($_GET['mon_a']) && IsBool($_GET['mon_a']) ? true : false;
$mon_p = isset($_GET['mon_p']) && IsBool($_GET['mon_p']) ? true : false;
$tue_a = isset($_GET['tue_a']) && IsBool($_GET['tue_a']) ? true : false;
$tue_p = isset($_GET['tue_p']) && IsBool($_GET['tue_p']) ? true : false;
$wed_a = isset($_GET['wed_a']) && IsBool($_GET['wed_a']) ? true : false;
$wed_p = isset($_GET['wed_p']) && IsBool($_GET['wed_p']) ? true : false;
$thu_a = isset($_GET['thu_a']) && IsBool($_GET['thu_a']) ? true : false;
$thu_p = isset($_GET['thu_p']) && IsBool($_GET['thu_p']) ? true : false;
$fri_a = isset($_GET['fri_a']) && IsBool($_GET['fri_a']) ? true : false;
$fri_p = isset($_GET['fri_p']) && IsBool($_GET['fri_p']) ? true : false;
$from = null;
$to = null;

if (isset($_GET['f']) && IsDate($_GET['f'])) {
    list($day, $month, $year) = explode('-', $_GET['f']);
    $from = date('d-m-Y', mktime(1,1,1,$month, GetFirstDayOfWeek($year, $month, $day), $year));
} else {
    $day = date('d');
    $month = date('m');
    $year = date('Y');
    $from = date('d-m-Y', mktime(1,1,1, $month, GetFirstDayOfWeek($year, $month, $day), $year));
}

if (isset($_GET['t']) && IsDate($_GET['t'])) {
    list($day, $month, $year) = explode('-', $_GET['t']);
    $to = date('d-m-Y', mktime(1,1,1, $month, GetFirstDayOfWeek($year, $month, $day), $year));
} else {
    $day = date('d');
    $month = date('m');
    $year = date('Y') + 1;
    $to = date('d-m-Y', mktime(1,1,1, $month, GetFirstDayOfWeek($year, $month, $day), $year));
}

$query = 'select m.session,age_months(%s,m.session) as age';
$query .= $mon_a ? ',session_numbers(m.session,\'A\',rooms.id)-rooms.total as mon_a' : '';
$query .= $mon_p ? ',session_numbers(m.session,\'P\',rooms.id)-rooms.total as mon_p' : '';
$query .= $tue_a ? ',session_numbers(m.session+1,\'A\',rooms.id)-rooms.total as tue_a' : '';
$query .= $tue_p ? ',session_numbers(m.session+1,\'P\',rooms.id)-rooms.total as tue_p' : '';
$query .= $wed_a ? ',session_numbers(m.session+2,\'A\',rooms.id)-rooms.total as wed_a' : '';
$query .= $wed_p ? ',session_numbers(m.session+2,\'P\',rooms.id)-rooms.total as wed_p' : '';
$query .= $thu_a ? ',session_numbers(m.session+3,\'A\',rooms.id)-rooms.total as thu_a' : '';
$query .= $thu_p ? ',session_numbers(m.session+3,\'P\',rooms.id)-rooms.total as thu_p' : '';
$query .= $fri_a ? ',session_numbers(m.session+4,\'A\',rooms.id)-rooms.total as fri_a' : '';
$query .= $fri_p ? ',session_numbers(m.session+4,\'P\',rooms.id)-rooms.total as fri_p' : '';
$query .= ',rooms.name,rooms.id,format_date(m.session) as date
from (select current_date+generate_series as session from generate_series((current_date-%s)*-1,(current_date-%s)*-1,7)) as m
inner join rooms on rooms.min<=age_months(%s,m.session) and rooms.max>age_months(%s+5,m.session)
';

$query = sprintf($query,
    Db::SqlFormat($dob, 'date'),
    Db::SqlFormat($from, 'date'),
    Db::SqlFormat($to, 'date'),
    Db::SqlFormat($dob, 'date'),
    Db::SqlFormat($dob, 'date')
);
$data = Db::GetDataArray($query);

PageHeader();

TitleReports('New Child Session Query');
?>

<p>&nbsp;</p>

<form action="<?php echo PHP_SELF; ?>" method="get" id="formreport">
    <table>
        <tr>
            <td>
                Child Dob : <?php echo FormDate('d', $dob, ''); ?>
                &nbsp; Start Date : <?php echo FormDate('f', $from, ''); ?>
                &nbsp; Finish Date : <?php echo FormDate('t', $to, ''); ?>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                Mon AM : <?php echo FormCheck('mon_a', $mon_a, '', ''); ?>
                &nbsp; Mon PM : <?php echo FormCheck('mon_p', $mon_p, '', ''); ?>
                &nbsp; Tue AM : <?php echo FormCheck('tue_a', $tue_a, '', ''); ?>
                &nbsp; Tue PM : <?php echo FormCheck('tue_p', $tue_p, '', ''); ?>
                &nbsp; Wed AM : <?php echo FormCheck('wed_a', $wed_a, '', ''); ?>
                &nbsp; Wed PM : <?php echo FormCheck('wed_p', $wed_p, '', ''); ?>
                &nbsp; Thu AM : <?php echo FormCheck('thu_a', $thu_a, '', ''); ?>
                &nbsp; Thu PM : <?php echo FormCheck('thu_p', $thu_p, '', ''); ?>
                &nbsp; Fri AM : <?php echo FormCheck('fri_a', $fri_a, '', ''); ?>
                &nbsp; Fri PM : <?php echo FormCheck('fri_p', $fri_p, '', ''); ?>
            </td>
            <td class="har" style="padding:0 10px;">
                <?php echo FormSubmit('process', 'View', 'formreport'); ?>
            </td>
        </tr>
    </table>
</form>

<p>&nbsp;</p>

<p class="har"><em>N.B. Session numbers shown include the child queried.</em></p>

<table class="results" id="ts1">
    <thead>
        <tr>
            <th>Date</th>
            <th>Room</th>
            <th>Age (months)</th>
            <?php
            echo $mon_a ? '<th>Mon AM</th>' : '';
            echo $mon_p ? '<th>Mon PM</th>' : '';
            echo $tue_a ? '<th>Tue AM</th>' : '';
            echo $tue_p ? '<th>Tue PM</th>' : '';
            echo $wed_a ? '<th>Wed AM</th>' : '';
            echo $wed_p ? '<th>Wed PM</th>' : '';
            echo $thu_a ? '<th>Thu AM</th>' : '';
            echo $thu_p ? '<th>Thu PM</th>' : '';
            echo $fri_a ? '<th>Fri AM</th>' : '';
            echo $fri_p ? '<th>Fri PM</th>' : '';
            ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row) { ?>
        <tr>
            <td><a href="week_numbers.php?d=<?php echo FormatDate($row['date']); ?>"><?php echo FormatDate($row['date']); ?></a></td>
            <td><a href="week_names.php?d=<?php echo FormatDate($row['date']); ?>&amp;r=<?php echo FormatText($row['id']); ?>"><?php echo FormatText($row['name']); ?></a></td>
             <td><?php echo FormatNumeric($row['age']); ?></td>
               <?php
                echo $mon_a ? '<td>'.FormatTotals($row['mon_a'] * -1, 0, true).'</td>' : '';
                echo $mon_p ? '<td>'.FormatTotals($row['mon_p'] * -1, 0, true).'</td>' : '';
                echo $tue_a ? '<td>'.FormatTotals($row['tue_a'] * -1, 0, true).'</td>' : '';
                echo $tue_p ? '<td>'.FormatTotals($row['tue_p'] * -1, 0, true).'</td>' : '';
                echo $wed_a ? '<td>'.FormatTotals($row['wed_a'] * -1, 0, true).'</td>' : '';
                echo $wed_p ? '<td>'.FormatTotals($row['wed_p'] * -1, 0, true).'</td>' : '';
                echo $thu_a ? '<td>'.FormatTotals($row['thu_a'] * -1, 0, true).'</td>' : '';
                echo $thu_p ? '<td>'.FormatTotals($row['thu_p'] * -1, 0, true).'</td>' : '';
                echo $fri_a ? '<td>'.FormatTotals($row['fri_a'] * -1, 0, true).'</td>' : '';
                echo $fri_p ? '<td>'.FormatTotals($row['fri_p'] * -1, 0, true).'</td>' : '';
                ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php echo JsBlock(JsSortingTable('ts1')); ?>

	<?php PageFooter(); ?>
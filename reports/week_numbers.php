<?php
require_once('../lib/base.php');

// set variables
if (isset($_GET['d2'])) $_GET['d'] = $_GET['d2'];
$date = isset($_GET['d']) && IsDate($_GET['d']) ? $_GET['d'] : date('d-m-Y');

list($day, $month, $year) = explode('-', $date);
$day = GetFirstDayOfWeek($year,$month,$day);

// get data
$query = 'select id,name,total';
$query .= ' from rooms';
$query .= ' order by min;';
$rooms = Db::GetDataArray($query);

PageHeader();
?>

<h1 class="links">
    <a href="<?php echo PHP_SELF.'?d='.date('d-m-Y',mktime(1,1,1,$month,$day-7,$year)); ?>">Prev</a>
    - <a href="<?php echo PHP_SELF.'?d='.date('d-m-Y',mktime(1,1,1,$month,$day+7,$year)); ?>">Next</a>
</h1>
<?php TitleReports('Week Numbers - '.date('jS F Y',mktime(1,1,1,$month,$day,$year))); ?>

<p>&nbsp;</p>

<form action="<?php echo PHP_SELF; ?>" method="get" id="formreport">
    <div class="har">
        <?php echo FormDate('d', $date, ''); ?>
        <?php echo FormSubmit('process', 'View', 'formreport'); ?>
    </div>
</form>

<p>&nbsp;</p>

<table class="results">
    <tr>
        <td colspan="2" style="width:1%;">&nbsp;</td>
        <th>Monday</th>
        <th>Tuesday</th>
        <th>Wednesday</th>
        <th>Thursday</th>
        <th>Friday</th>
    </tr>
    <?php foreach ($rooms as $row) {  ?>
    <tr>
        <th rowspan="2"><?php echo $row['name']; ?></th>
        <th>AM</th>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day,$year)),'A',$row['id']), $row['total']); ?></td>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day+1,$year)),'A',$row['id']), $row['total']); ?></td>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day+2,$year)),'A',$row['id']), $row['total']); ?></td>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day+3,$year)),'A',$row['id']), $row['total']); ?></td>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day+4,$year)),'A',$row['id']), $row['total']); ?></td>
    </tr>
    <tr>
        <th class="row_heading">PM</th>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day,$year)),'P',$row['id']), $row['total']); ?></td>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day+1,$year)),'P',$row['id']), $row['total']); ?></td>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day+2,$year)),'P',$row['id']), $row['total']); ?></td>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day+3,$year)),'P',$row['id']), $row['total']); ?></td>
        <td class="hac"><?php echo FormatTotals(SessionNumbers(date('d-m-Y',mktime(0,0,0,$month,$day+4,$year)),'P',$row['id']), $row['total']); ?></td>
    </tr>
    <?php } ?>
</table>

<?php PageFooter(); ?>
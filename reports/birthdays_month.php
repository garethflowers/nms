<?php
require_once('../lib/base.php');

// set dates
$month = isset($_REQUEST['m']) && intval($_REQUEST['m'])>=1 && intval($_REQUEST['m'])<=12 ? intval($_REQUEST['m']) : date('m');
$year = isset($_REQUEST['y']) && intval($_REQUEST['y'])>=1900 && intval($_REQUEST['y'])<=2500 ? intval($_REQUEST['y']) : date('Y');

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

// get data
$query = 'select forename,surname,dob,age_months(dob) as age,id';
$query .= ' from child';
$query .= ' where finish>=current_date';
$query .= ' and start<=current_date';
$query .= ' and extract(month from dob)='.Db::SqlFormat($month,'int');
$query .= ' order by age';
$data = Db::GetDataArray($query);

PageHeader();
?>

<h1 class="links">
    <a href="<?php echo PHP_SELF.'?m='.($month-1).'&amp;y='.$year; ?>">Prev</a>
    - <a href="<?php echo PHP_SELF.'?m='.($month+1).'&amp;y='.$year; ?>">Next</a>
</h1>
<?php TitleReports('Birthdays (in '.date('F Y',mktime(1,1,1,$month,1,$year)).')'); ?>

<p>&nbsp;</p>

<form action="<?php echo PHP_SELF; ?>" method="get" id="formreport">
    <div class="har">
        <?php echo FormCombo('m', $dictionary_month, $month, ''); ?>
        <?php echo FormCombo('y', $dictionary_year, $year, ''); ?>
        <?php echo FormSubmit('process', 'View', 'formreport'); ?>
    </div>
</form>

<p>&nbsp;</p>

<table class="results" id="ts1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Day of Month</th>
            <th>Current Age (yrs)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row) { ?>
        <tr>
            <td><a href="/child/view.php?i=<?php echo $row['id']; ?>"><?php echo strtoupper($row['surname']).', '.$row['forename']; ?></a></td>
            <td><?php echo date('jS',mktime(0,0,0,substr($row['dob'],5,2),substr($row['dob'],8,2),substr($row['dob'],0,4))); ?></td>
            <td><?php echo floor($row['age']/12); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php echo JsBlock(JsSortingTable('ts1')); ?>

<?php PageFooter(); ?>
<?php
// include required
require_once('../lib/base.php');

// get data
$query = 'select forename,surname,format_date(dob) as dob,age_months(dob) as age,id';
$query .= ' from child';
$query .= ' where finish>=current_date';
$query .= ' and start<=current_date';
$query .= ' order by dob;';
$data = Db::GetDataArray($query);

PageHeader();

TitleReports('Birthdays (as of '.date('d-m-Y').')');
?>

<p>&nbsp;</p>

<table class="results" id="ts1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Current Age</th>
            <th>Date of Birth</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row) { ?>
        <tr>
            <td><a href="/child/view.php?i=<?php echo $row['id']; ?>"><?php echo strtoupper($row['surname']).', '.$row['forename']; ?></a></td>
            <td><?php echo floor($row['age']/12); ?></td>
            <td><?php echo FormatDate($row['dob'], 'jS F, Y'); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php echo JsBlock(JsSortingTable('ts1')); ?>

<?php PageFooter(); ?>
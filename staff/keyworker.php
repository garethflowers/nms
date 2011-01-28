<?php
require_once('../lib/base.php');

// checks
$id = CheckID($_GET['i']);
$staff = new Staff($id);

PageHeader();

TitleStaff($staff);
?>

<h2>Keyworker</h2>

<?php $query = 'select surname,forename,format_date(dob) as dob,id,age_months(dob) AS age';
$query .= ' from child';
$query .= ' where keyworker=' . Db::SqlFormat($staff->id,'int');
$query .= ' order by surname,forename';
$data = Db::GetDataArray($query);
if (count($data)>0) { ?>

<table class="results" id="ts1">
    <thead>
        <tr>
            <th>Surname</th>
            <th>Forename</th>
            <th>Date of Birth</th>
            <th>Age (months)</th>
            <th class="sortnone">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
            <?php foreach ($data as $row) { ?>
        <tr>
            <td class="har"><strong><a href="/child/view.php?i=<?php echo $row['id']; ?>"><?php echo strtoupper($row['surname']); ?></a></strong></td>
            <td class="hal"><a href="/child/view.php?i=<?php echo $row['id']; ?>"><?php echo $row['forename']; ?></a></td>
            <td><?php echo FormatDate($row['dob'], 'jS F, Y');?></td>
            <td><?php echo $row['age']; ?></td>
            <td><a href="../child/view.php?i=<?php echo intval($row['id']); ?>"><img src="/lib/images/view.png" alt="View" /></a></td>
        </tr>
            <?php } ?>
    </tbody>
</table>
    <?php echo JsBlock(JsSortingTable('ts1')); ?>

<?php } else { ?>

<p>This member of staff is not a keyworker for any children.</p>

<?php }

PageFooter();
?>
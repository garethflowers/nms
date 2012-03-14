<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// get data
$query = 'select id,"name"';
$query .= ' from nationality';
$query .= ' order by "name";';
$data = Db::GetDataArray($query);

PageHeader();

TitleSettings(false, false, false, true);
?>

<h2>Nationalities <a href="amend.php"><img src="/lib/images/add.png" alt="Add" /></a></h2>

<table class="results" id="ts1">
    <thead>
        <tr>
            <th>Name</th>
            <th class="sortnone">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><?php echo FormatText($row['name']); ?></td>
                <td>
                    <a href="amend.php?i=<?php echo $row['id']; ?>"><img src="/lib/images/edit.png" alt="Edit" /></a>
                    <a href="delete.php?i=<?php echo $row['id']; ?>"><img src="/lib/images/delete.png" alt="Delete" /></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php echo JsBlock(JsSortingTable('ts1')); ?>

<?php PageFooter(); ?>
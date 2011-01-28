<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// get data
$query = 'select id,name,total,min,max,include,price_day,price_session';
$query .= ' from rooms';
$query .= ' order by min;';
$data = Db::GetDataArray($query);

PageHeader();

TitleSettings(false, true);
?>

<h2>Rooms <a href="amend.php"><img src="/lib/images/add.png" alt="Add" /></a></h2>

<table class="results" id="ts1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Total Children</th>
            <th>Min Age (months)</th>
            <th>Max Age (months)</th>
            <th>Include in Monthly Totals</th>
            <th>Price per Session</th>
            <th>Price per Day</th>
            <th class="sortnone">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row) {  ?>
        <tr>
            <td><?php echo FormatText($row['name']); ?></td>
            <td class="har"><?php echo FormatNumeric($row['total']); ?></td>
            <td class="har"><?php echo FormatNumeric($row['min']); ?></td>
            <td class="har"><?php echo FormatNumeric($row['max']); ?></td>
            <td><?php echo FormatBoolean($row['include']); ?></td>
            <td class="har">&pound; <?php echo number_format($row['price_session'], 2); ?></td>
            <td class="har">&pound; <?php echo number_format($row['price_day'], 2); ?></td>
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
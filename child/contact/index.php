<?php
require_once('../../lib/base.php');

// checks
$id = CheckID($_GET['i']);
$child = new Child($id);

$query = 'select child_contact.*,relationship.name as relationship_name';
$query .= ' from child_contact';
$query .= ' left outer join relationship on child_contact.relationship=relationship.id';
$query .= ' where child_id=' . Db::SqlFormat($child->id,'int');
$query .= ' order by type,name;';
$contacts = Db::GetDataArray($query);

PageHeader();

TitleChildContact($child,null);
?>

<h2>Contacts</h2>

<?php foreach ($contacts as $row) { ?>

<h3><?php echo $row['type']=='C' ? 'Carer' : 'Emergency Contact'; ?>
        <?php if (LoginLevel(2)) { ?>
    <a href="amend.php?i=<?php echo $child->id.'&amp;c='.intval($row['id']); ?>"><img src="/lib/images/edit.png" alt="Edit" /></a>
    <a href="delete.php?i=<?php echo $child->id.'&amp;c='.intval($row['id']); ?>"><img src="/lib/images/delete.png" alt="Delete" /></a>
        <?php } ?>
</h3>

<table class="two-col">
    <tr>
        <td>
            <table class="details">
                <tr>
                    <th>Name :</th>
                    <td><?php echo FormatText($row['name']); ?></td>
                </tr>
                <tr>
                    <th>Telephone No. :</th>
                    <td><?php echo FormatText($row['telephone']); ?></td>
                </tr>
                <tr>
                    <th>Mobile No. :</th>
                    <td><?php echo FormatText($row['mobile']); ?></td>
                </tr>
                <tr>
                    <th>Work Tel No. :</th>
                    <td><?php echo FormatText($row['work_telephone']); ?></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <th>Occupation :</th>
                    <td><?php echo FormatText($row['occupation']); ?></td>
                </tr>
                <tr>
                    <th>Place of Work :</th>
                    <td><?php echo FormatText($row['place_work']); ?></td>
                </tr>
                <tr>
                    <th>Relationship :</th>
                    <td><?php echo FormatText($row['relationship_name']); ?></td>
                </tr>
            </table>
        </td>
        <td>
            <table class="details">
                <tr>
                    <th>Address :</th>
                    <td><?php echo FormatText($row['address']); ?></td>
                </tr>
                <tr>
                    <th>City :</th>
                    <td><?php echo FormatText($row['city']); ?></td>
                </tr>
                <tr>
                    <th>County :</th>
                    <td><?php echo FormatText($row['county']); ?></td>
                </tr>
                <tr>
                    <th>Postcode :</th>
                    <td><?php echo FormatPostcode(strtoupper($row['postcode'])); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<?php }

if (count($contacts) == 0) {
    echo '<p>No carer or emergency contact information available for this child.</p>';
}

PageFooter();
?>
<?php
require_once('../../lib/base.php');

// checks
$id = CheckID($_GET['i']);
$staff = new Staff($id);

$query = 'select staff_contact.*,relationship.name as relationship_name';
$query .= ' from staff_contact';
$query .= ' left outer join relationship on staff_contact.relationship=relationship.id';
$query .= ' where staff_id=' . Db::SqlFormat($staff->id,'int');
$query .= ' order by name;';
$contacts = Db::GetDataArray($query);

PageHeader();

TitleStaffContact($staff,null);
?>

<h2>Contacts</h2>

<?php foreach ($contacts as $row) { ?>

<h3>Emergency Contact
        <?php if (LoginLevel(2)) { ?>
    <a href="amend.php?i=<?php echo $staff->id.'&amp;c='.intval($row['id']); ?>"><img src="/lib/images/edit.png" alt="Edit" /></a>
    <a href="delete.php?i=<?php echo $staff->id.'&amp;c='.intval($row['id']); ?>"><img src="/lib/images/delete.png" alt="Delete" /></a>
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
                    <th>Relationship :</th>
                    <td><?php echo FormatText($row['relationship_name']); ?></td>
                </tr>
                <tr>
                    <th>Telephone No. :</th>
                    <td><?php echo FormatText($row['telephone']); ?></td>
                </tr>
                <tr>
                    <th>Mobile No. :</th>
                    <td><?php echo FormatText($row['mobile']); ?></td>
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
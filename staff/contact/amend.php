<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

$id = CheckID($_GET['i']);
$staff = new Staff($id);

$contact = new StaffContact();
$contact->id = isset($_GET['c']) && is_numeric($_GET['c']) ? intval($_GET['c']) : 0;

if (isset($_POST['process'])) {
	$contact->staff_id = $staff->id;
	$contact->Update($_POST);
	$result = $contact->Save();
	if (count($result)==0) {
		header('location: index.php?i='.$staff->id);
	}
} elseif ($contact->id != 0) {
	$contact->Load($contact->id);
}

PageHeader();

TitleStaffContact($staff,$contact);
?>

<h2>Edit Contact Information</h2>

<form action="<?php echo $contact->id==0?PHP_SELF.'?i='.$staff->id:PHP_SELF.'?i='.$staff->id.'&amp;c='.$contact->id; ?>" method="post" id="formchildcontact">

    <table class="two-col">
        <tr>
            <td>
                <table class="details">
					<tr>
                        <td colspan="2">
                            <h3>Personal</h3>
                        </td>
                    </tr>
					<tr>
						<th><?php echo FormLabel('name', 'Name'); ?></th>
						<td><?php echo FormText('name', $contact->name, 'required', 100); ?></td>
					</tr>
					<tr>
						<th><?php echo FormLabel('relationship', 'Relationship'); ?></th>
						<td><?php $dictionary = Db::Dictionary('select id as k,name as v from relationship order by name;');
						echo FormCombo('relationship', array(''=>'')+$dictionary, $contact->relationship, ''); ?></td>
					</tr>
					<tr>
                        <td colspan="2">
                            <h3>Contact</h3>
                        </td>
                    </tr>
					<tr>
						<th><?php echo FormLabel('telephone', 'Telephone'); ?></th>
						<td><?php echo FormText('telephone', $contact->telephone, '', 11); ?></td>
					</tr>
					<tr>
						<th><?php echo FormLabel('mobile', 'Mobile'); ?></th>
						<td><?php echo FormText('mobile', $contact->mobile, '', 11); ?></td>
					</tr>
                </table>

            </td>
            <td>

                <table class="details">
                    <tr>
                        <td colspan="2">
                            <h3>Address</h3>
                        </td>
                    </tr>
					<tr>
						<th><?php echo FormLabel('address', 'Address'); ?></th>
						<td><?php echo FormTextArea('address', $contact->address, '', 3); ?></td>
					</tr>
					<tr>
						<th><?php echo FormLabel('city', 'City'); ?></th>
						<td><?php echo FormText('city', $contact->city, '', 100); ?></td>
					</tr>
					<tr>
						<th><?php echo FormLabel('county', 'County'); ?></th>
						<td><?php echo FormText('county', $contact->county, '', 100); ?></td>
					</tr>
					<tr>
						<th><?php echo FormLabel('postcode', 'Postcode'); ?></th>
						<td><?php echo FormText('postcode', $contact->postcode, '', 8); ?></td>
					</tr>
                </table>

            </td>
        </tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="hac"><?php echo FormSubmit('process', $contact->id==0?'Add':'Update', 'formstaffcontact'); ?></td>
		</tr>
    </table>

</form>

<?php PageFooter(); ?>
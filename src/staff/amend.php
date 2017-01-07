<?php
require_once('../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

$staff = new Staff();
$staff->id = isset($_GET['i']) && is_numeric($_GET['i']) ? intval($_GET['i']) : 0;

if (isset($_POST['process'])) {
    $staff->Update($_POST);
    $result = $staff->Save();
    if (is_array($result) && count($result) == 0) {
        header('location: view.php?i=' . $staff->id);
    }
} elseif ($staff->id != 0) {
    $staff->Load($staff->id);
}

PageHeader();

TitleStaff($staff);
?>

<h2><?php echo $staff->id == 0 ? 'Add' : 'Edit'; ?> Staff Information</h2>

<form action="<?php echo $staff->id == 0 ? PHP_SELF : PHP_SELF . '?i=' . $staff->id; ?>" method="post" id="formstaff">

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
                        <th><?php echo FormLabel('forename', 'Forename'); ?></th>
                        <td><?php echo FormText('forename', $staff->forename, 'required', 100); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('surname', 'Surname'); ?></th>
                        <td><?php echo FormText('surname', $staff->surname, 'required', 100); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('dob', 'Date of Birth'); ?></th>
                        <td><?php echo FormDate('dob', $staff->dob, ''); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Bank</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('bank_name', 'Bank Name'); ?></th>
                        <td><?php echo FormText('bank_name', $staff->bank_name, '', 200); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('bank_account', 'Bank Account'); ?></th>
                        <td><?php echo FormText('bank_account', $staff->bank_account, '', 20); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('bank_sort_code', 'Bank Sort Code'); ?></th>
                        <td><?php echo FormText('bank_sort_code', $staff->bank_sort_code, '', 20); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Doctor</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('doctor', 'Doctor'); ?></th>
                        <td><?php echo FormText('doctor', $staff->doctor, '', 200); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('doctor_address', 'Doctor Address'); ?></th>
                        <td><?php echo FormTextArea('doctor_address', $staff->doctor_address, '', 3); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Login</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('username', 'Username'); ?></th>
                        <td><?php echo FormText('username', $staff->username, 'required', 20); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('password', 'Password'); ?></th>
                        <td><?php echo FormText('password', $staff->password, 'required', 20); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('userlevel', 'User Level'); ?></th>
                        <td><?php echo FormCombo('userlevel', array('1' => 'General User', '2' => 'Administrator'), $staff->userlevel, 'required'); ?></td>
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
                        <td><?php echo FormTextArea('address', $staff->address, '', 3); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('city', 'City'); ?></th>
                        <td><?php echo FormText('city', $staff->city, '', 100); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('county', 'County'); ?></th>
                        <td><?php echo FormText('county', $staff->county, '', 100); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('postcode', 'Postcode'); ?></th>
                        <td><?php echo FormText('postcode', $staff->postcode, '', 8); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Contact</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('telephone', 'Telephone'); ?></th>
                        <td><?php echo FormText('telephone', $staff->telephone, '', 11); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('mobile', 'Mobile'); ?></th>
                        <td><?php echo FormText('mobile', $staff->mobile, '', 11); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('email', 'Email'); ?></th>
                        <td><?php echo FormText('email', $staff->email, '', 200); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Other</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('vehicle_registration', 'Vehicle Registration'); ?></th>
                        <td><?php echo FormText('vehicle_registration', $staff->vehicle_registration, '', 10); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('national_insurance', 'National Insurance'); ?></th>
                        <td><?php echo FormText('national_insurance', $staff->national_insurance, '', 15); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('next_of_kin', 'Next of Kin'); ?></th>
                        <td><?php echo FormText('next_of_kin', $staff->next_of_kin, '', 200); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('start', 'Start Date'); ?></th>
                        <td><?php echo FormDate('start', $staff->start, ''); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('finish', 'Leaving Date'); ?></th>
                        <td><?php echo FormDate('finish', $staff->finish, ''); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Nursery Information</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('assigned_room', 'Assigned Room'); ?></th>
                        <td><?php
$dictionary = Db::Dictionary('select id as k,name as v from rooms order by min;');
echo FormCombo('assigned_room', array('' => 'n/a') + $dictionary, $staff->assigned_room, '');
?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h3>Notes</h3>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?php echo FormTextArea('notes', $staff->notes, 'fw', 4); ?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="hac"><?php echo FormSubmit('process', $staff->id == 0 ? 'Add' : 'Update', 'formstaff'); ?></td>
        </tr>
    </table>

</form>

<?php PageFooter(); ?>

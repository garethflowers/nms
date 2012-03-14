<?php
require_once('../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

$child = new Child();
$child->id = isset($_GET['i']) && is_numeric($_GET['i']) ? intval($_GET['i']) : 0;

if (isset($_POST['process'])) {
    $child->Update($_POST);
    $result = $child->Save();
    if (is_array($result) && count($result) == 0) {
        header('location: view.php?i=' . $child->id);
    }
} elseif ($child->id != 0) {
    $child->Load($child->id);
}

PageHeader();

TitleChild($child);
?>

<h2><?php echo $child->id == 0 ? 'Add' : 'Edit'; ?> Child Information</h2>

<form action="<?php echo $child->id == 0 ? PHP_SELF : PHP_SELF . '?i=' . $child->id; ?>" method="post" id="formchild">

    <table class="two-col">
        <tr>
            <td>
                <table class="details">
                    <tr>
                        <td colspan="2">
                            <h3>Name</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('forename', 'Forename'); ?></th>
                        <td><?php echo FormText('forename', $child->forename, 'required', 100); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('surname', 'Surname'); ?></th>
                        <td><?php echo FormText('surname', $child->surname, 'required', 100); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Address</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('address', 'Address'); ?></th>
                        <td><?php echo FormTextArea('address', $child->address, '', 3); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('city', 'City'); ?></th>
                        <td><?php echo FormText('city', $child->city, '', 100); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('county', 'County'); ?></th>
                        <td><?php echo FormText('county', $child->county, '', 100); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('postcode', 'Postcode'); ?></th>
                        <td><?php echo FormText('postcode', $child->postcode, '', 8); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Contact</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('telephone', 'Telephone'); ?></th>
                        <td><?php echo FormText('telephone', $child->telephone, '', 11); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Security</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('pickup_password', 'Security Password'); ?></th>
                        <td><?php echo FormText('pickup_password', $child->pickup_password, '', 100); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Room Assignment</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('room_override', 'Room'); ?></th>
                        <td><?php
$dictionary = Db::Dictionary('select id as k,name as v from rooms order by min;');
echo FormCombo('room_override', array('' => 'Automatically Assigned') + $dictionary, $child->room_override, '');
?></td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="details">
                    <tr>
                        <td colspan="2">
                            <h3>Birth</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('dob', 'Date of Birth'); ?></th>
                        <td><?php echo FormDate('dob', $child->dob, 'required'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('unborn', 'Unborn'); ?></th>
                        <td><?php echo FormCheck('unborn', $child->unborn, '<span class="small">(tick to use D.o.B as an estimate)</span>', '') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Others</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('next_kin', 'Next of Kin'); ?></th>
                        <td><?php echo FormText('next_kin', $child->next_kin, '', 100); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('nationality', 'Nationality'); ?></th>
                        <td><?php
                            $dictionary = Db::Dictionary('select id as k,name as v from nationality order by name;');
                            echo FormCombo('nationality', array('' => '') + $dictionary, $child->nationality, '');
?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('religion', 'Religion'); ?></th>
                        <td><?php
                            $dictionary = Db::Dictionary('select id as k,name as v from religion order by name;');
                            echo FormCombo('religion', array('' => '') + $dictionary, $child->religion, '');
?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Nursery</h3>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('gradual_admission', 'Gradual Admission'); ?></th>
                        <td><?php echo FormDate('gradual_admission', $child->gradual_admission, 'required'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('start', 'Start Date'); ?></th>
                        <td><?php echo FormDate('start', $child->start, 'required'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('finish', 'Leave Date'); ?></th>
                        <td><?php echo FormDate('finish', $child->finish, 'required'); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('keyworker', 'Keyworker'); ?></th>
                        <td><?php
                            $dictionary = Db::Dictionary('select id as k,forename||\' \'||surname as v from staff where ((finish>=current_date or finish is null) and start<=current_date) or id=' . Db::SqlFormat($child->keyworker, 'int') . ' order by surname,forename');
                            echo FormCombo('keyworker', array('' => 'None Assigned') + $dictionary, $child->keyworker, '');
?></td>
                    </tr>
                    <tr>
                        <th><?php echo FormLabel('backup_keyworker', 'Backup Keyworker'); ?></th>
                        <td><?php
                            $dictionary = Db::Dictionary('select id as k,forename||\' \'||surname as v from staff where ((finish>=current_date or finish is null) and start<=current_date) or id=' . Db::SqlFormat($child->backup_keyworker, 'int') . ' order by surname,forename');
                            echo FormCombo('backup_keyworker', array('' => 'None Assigned') + $dictionary, $child->backup_keyworker, '');
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
            <td colspan="2"><?php echo FormTextArea('notes', $child->notes, 'fw', 4); ?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="hac"><?php echo FormSubmit('process', $child->id == 0 ? 'Add' : 'Update', 'formchild'); ?></td>
        </tr>
    </table>

</form>

<?php PageFooter(); ?>

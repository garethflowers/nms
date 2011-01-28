<?php
require_once('../lib/base.php');

// checks
$id = CheckID($_GET['i']);
$staff = new Staff($id);

PageHeader();

TitleStaff($staff);
?>

<h2>View Staff</h2>

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
                    <th>Forename :</th>
                    <td><?php echo FormatText($staff->forename); ?></td>
                </tr>
                <tr>
                    <th>Surname :</th>
                    <td><?php echo FormatText($staff->surname); ?></td>
                </tr>
                <tr>
                    <th>Date of Birth :</th>
                    <td><?php echo FormatDate($staff->dob); ?></td>
                </tr>
                <?php if (LoginLevel(2)) { ?>
                <tr>
                    <td colspan="2">
                        <h3>Bank</h3>
                    </td>
                </tr>
                <tr>
                    <th>Name of Bank :</th>
                    <td><?php echo FormatText($staff->bank_name); ?></td>
                </tr>
                <tr>
                    <th>Account Number :</th>
                    <td><?php echo FormatText($staff->bank_account); ?></td>
                </tr>
                <tr>
                    <th>Sort Code :</th>
                    <td><?php echo FormatText($staff->bank_sort_code); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="2">
                        <h3>Doctor</h3>
                    </td>
                </tr>
                <tr>
                    <th>Name :</th>
                    <td><?php echo FormatText($staff->doctor); ?></td>
                </tr>
                <tr>
                    <th>Address :</th>
                    <td><?php echo FormatText($staff->doctor_address); ?></td>
                </tr>
                <?php if (LoginLevel(2)) { ?>
                <tr>
                    <td colspan="2">
                        <h3>Login</h3>
                    </td>
                </tr>
                <tr>
                    <th>Username :</th>
                    <td><?php echo FormatText($staff->username); ?></td>
                </tr>
                <tr>
                    <th>Password :</th>
                    <td><?php echo FormatText($staff->password); ?></td>
                </tr>
                <?php } ?>
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
                    <th>Address1 :</th>
                    <td><?php echo FormatText($staff->address); ?></td>
                </tr>
                <tr>
                    <th>City :</th>
                    <td><?php echo FormatText($staff->city); ?></td>
                </tr>
                <tr>
                    <th>County :</th>
                    <td><?php echo FormatText($staff->county); ?></td>
                </tr>
                <tr>
                    <th>Postcode :</th>
                    <td><?php echo FormatPostcode($staff->postcode); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h3>Contact</h3>
                    </td>
                </tr>
                <tr>
                    <th>Telephone :</th>
                    <td><?php echo FormatText($staff->telephone); ?></td>
                </tr>
                <tr>
                    <th>Mobile :</th>
                    <td><?php echo FormatText($staff->mobile); ?></td>
                </tr>
                <tr>
                    <th>Email :</th>
                    <td><?php echo FormatEmail($staff->email); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h3>Other</h3>
                    </td>
                </tr>
                <tr>
                    <th>Vehicle Registration :</th>
                    <td><?php echo FormatText(strtoupper($staff->vehicle_registration)); ?></td>
                </tr>
                <?php if (LoginLevel(2)) { ?>
                <tr>
                    <th>National Insurance No. :</th>
                    <td><?php echo FormatText($staff->national_insurance); ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <th>Next of Kin :</th>
                    <td><?php echo FormatText($staff->next_of_kin); ?></td>
                </tr>
                <tr>
                    <th>Start Date :</th>
                    <td><?php echo FormatDate($staff->start); ?></td>
                </tr>
                <tr>
                    <th>Leaving Date :</th>
                    <td><?php echo FormatDate($staff->finish); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h3>Nursery Information</h3>
                    </td>
                </tr>
                <tr>
                    <th>Assigned Room :</th>
                    <td>
                        <?php  if (intval($staff->assigned_room)==0) {
                            echo 'n/a';
                        } else {
                            $query = 'select name';
                            $query .= ' from rooms';
                            $query .= ' where id=' . Db::SqlFormat($staff->assigned_room,'int');
                            $query .= ' limit 1;';
                            $data = Db::GetData($query);
                            echo '<a href="/reports/week_names.php?d='.date('d-m-Y').'&amp;r='.$staff->assigned_room.'">'.FormatText($data['name']).'</a>';
                        } ?>
                    </td>
                </tr>
                <tr>
                    <th>Keyworker :</th>
                    <td>
                        <?php $query = 'select count(id) as count';
                        $query .= ' from child';
                        $query .= ' where keyworker=' . Db::SqlFormat($staff->id, 'int');
                        $query .= ' limit 1;';
                        $data = Db::GetData($query);
                        echo intval($data['count'])==0 ? 'n/a' : '<a href="keyworker.php?i='.$staff->id.'">'.$data['count'].' Children</a>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Backup Keyworker :</th>
                    <td>
                        <?php $query = 'select count(id) as count';
                        $query .= ' from child';
                        $query .= ' where backup_keyworker=' . Db::SqlFormat($staff->id, 'int');
                        $query .= ' limit 1;';
                        $data = Db::GetData($query);
                        echo intval($data['count'])==0 ? 'n/a' : '<a href="backup_keyworker.php?i='.$staff->id.'">'.$data['count'].' Children</a>';
                        ?>
                    </td>
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
        <td colspan="2"><?php echo FormatText($staff->notes); ?></td>
    </tr>
</table>

<?php PageFooter(); ?>
<?php
require_once('../lib/base.php');

// checks
$id = CheckID($_GET['i']);
$child = new Child($id);

PageHeader();

TitleChild($child);
?>

<h2>View Child Information</h2>

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
                    <th>Forename :</th>
                    <td><?php echo FormatText($child->forename); ?></td>
                </tr>
                <tr>
                    <th>Surname :</th>
                    <td><?php echo FormatText($child->surname); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h3>Current Details</h3>
                    </td>
                </tr>
                <tr>
                    <th>Age :</th>
                    <td><?php echo $child->age >= 0 ? $child->age . ' months' : 'Child not born yet'; ?></td>
                </tr>
                <tr>
                    <th>Room :</th>
                    <td><?php
if (FormatDate($child->finish, 'U') >= date('U') && FormatDate($child->start, 'U') <= date('U')) {
    if ($child->room_override == 0) {
        $query = 'select name,id';
        $query .= ' from rooms';
        $query .= ' where min<=' . Db::SqlFormat($child->age, 'int');
        $query .= ' and max>=' . Db::SqlFormat($child->age, 'int');
        $query .= ' limit 1;';
        $data_room = Db::GetData($query);
        echo '<a href="/reports/week_names.php?d=' . date('d-m-Y') . '&amp;r=' . $data_room['id'] . '">' . FormatText($data_room['name']) . '</a>';
    } else {
        $query = 'select name';
        $query .= ' from rooms';
        $query .= ' where id<=' . Db::SqlFormat($child->room_override, 'int');
        $query .= ' limit 1;';
        $data_room = Db::GetData($query);
        echo '<a href="/reports/week_names.php?d=' . date('d-m-Y') . '&amp;r=' . $child->room_override . '">' . FormatText($data_room['name']) . ' (Manually Assigned)</a>';
    }
} else {
    echo 'n/a';
}
?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h3>Address</h3>
                    </td>
                </tr>
                <tr>
                    <th>Address :</th>
                    <td><?php echo FormatText($child->address); ?></td>
                </tr>
                <tr>
                    <th>City :</th>
                    <td><?php echo FormatText($child->city); ?></td>
                </tr>
                <tr>
                    <th>County :</th>
                    <td><?php echo FormatText($child->county); ?></td>
                </tr>
                <tr>
                    <th>Postcode :</th>
                    <td><?php echo FormatPostcode($child->postcode); ?></td>
                </tr>
                <tr>
                    <th>Telephone :</th>
                    <td><?php echo FormatText($child->telephone); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h3>Security</h3>
                    </td>
                </tr>
                <tr>
                    <th>Password :</th>
                    <td><?php echo FormatText($child->pickup_password); ?></td>
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
                    <th>Date of Birth :</th>
                    <td><?php echo FormatDate($child->dob); ?></td>
                </tr>
                <?php if ($child->unborn) { ?>
                    <tr>
                        <th>&nbsp;</th>
                        <td class="small">D.o.B is estimated. Child not born yet.</td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="2">
                        <h3>Other</h3>
                    </td>
                </tr>
                <tr>
                    <th>Next of Kin :</th>
                    <td><?php echo FormatText($child->next_kin); ?></td>
                </tr>
                <tr>
                    <th>Nationality :</th>
                    <td><?php
                $query = 'select name from nationality where id=%s limit 1;';
                $query = sprintf($query, Db::SqlFormat($child->nationality, 'int'));
                $data_lookup = Db::GetData($query);
                echo FormatText($data_lookup['name']);
                ?></td>
                </tr>
                <tr>
                    <th>Religion :</th>
                    <td><?php
                        $query = 'select name from religion where id=%s limit 1;';
                        $query = sprintf($query, Db::SqlFormat($child->religion, 'int'));
                        $data_lookup = Db::GetData($query);
                        echo FormatText($data_lookup['name']);
                ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h3>Nursery</h3>
                    </td>
                </tr>
                <tr>
                    <th>Gradual Admission :</th>
                    <td><?php echo FormatDate($child->gradual_admission); ?></td>
                </tr>
                <tr>
                    <th>Start Date :</th>
                    <td><?php echo FormatDate($child->start); ?></td>
                </tr>
                <tr>
                    <th>Leaving Date :</th>
                    <td><?php echo FormatDate($child->finish); ?></td>
                </tr>
                <tr>
                    <th>Keyworker :</th>
                    <td><?php
                        if (!empty($child->keyworker)) {
                            $query = 'select forename,surname from staff where id=%s limit 1;';
                            $query = sprintf($query, Db::SqlFormat($child->keyworker, 'int'));
                            $data_lookup = Db::GetData($query);
                            echo '<a href="/staff/view.php?i=' . $child->keyworker . '">' . $data_lookup['forename'] . ' ' . $data_lookup['surname'] . '</a>';
                        } else {
                            echo 'n/a';
                        }
                ?>
                    </td>
                </tr>
                <tr>
                    <th>BackUp Keyworker :</th>
                    <td><?php
                        if (!empty($child->keyworker)) {
                            $query = 'select forename,surname from staff where id=%s limit 1;';
                            $query = sprintf($query, Db::SqlFormat($child->backup_keyworker, 'int'));
                            $data_lookup = Db::GetData($query);
                            echo '<a href="/staff/view.php?i=' . $child->backup_keyworker . '">' . $data_lookup['forename'] . ' ' . $data_lookup['surname'] . '</a>';
                        } else {
                            echo 'n/a';
                        }
                ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">

            <h3>Current Regular Sessions</h3>

            <table class="results">
                <tr>
                    <td>&nbsp;</td>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                </tr>
                <tr>
                    <th>AM</th>
                    <?php
                    $day = GetFirstDayOfWeek(date('Y'), date('m'), date('d'));
                    for ($c = 0; $c < 5; $c++) {
                        $date = date('Y-m-d', mktime(0, 0, 0, date('m'), $day + $c, date('Y')));
                        $query = 'select session';
                        $query .= ' from child_session';
                        $query .= ' inner join child on child_session.child_id=child.id';
                        $query .= ' where extract(dow from date)=extract(dow from \'' . $date . '\'::date)';
                        $query .= ' and start<=\'' . $date . '\'';
                        $query .= ' and finish>=\'' . $date . '\'';
                        $query .= ' and date<=\'' . $date . '\'';
                        $query .= ' and date_till>=\'' . $date . '\'';
                        $query .= ' and child_session.child_id=' . $child->id;
                        $query .= ' and session<>\'P\'';
                        $query .= ' and exclude=\'N\'';
                        $data_session = Db::GetData($query);
                        ?>
                        <td class="hac"><input name="" type="checkbox" value=""
                                               <?php echo ($data_session['session'] == 'A' || $data_session['session'] == 'D') ? 'checked="checked" onclick="this.checked=true"' : 'disabled="disabled"'; ?> />
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <th>PM</th>
                    <?php
                    $day = GetFirstDayOfWeek(date('Y'), date('m'), date('d'));
                    for ($c = 0; $c < 5; $c++) {
                        $date = date('Y-m-d', mktime(0, 0, 0, date('m'), $day + $c, date('Y')));
                        $query = 'select session';
                        $query .= ' from child_session';
                        $query .= ' inner join child on child_session.child_id=child.id';
                        $query .= ' where extract(dow from date)=extract(dow from \'' . $date . '\'::date)';
                        $query .= ' and start<=\'' . $date . '\'';
                        $query .= ' and finish>=\'' . $date . '\'';
                        $query .= ' and date<=\'' . $date . '\'';
                        $query .= ' and date_till>=\'' . $date . '\'';
                        $query .= ' and child_session.child_id=' . $child->id;
                        $query .= ' and session<>\'A\'';
                        $query .= ' and exclude=\'N\'';
                        $data_session = Db::GetData($query);
                        ?>
                        <td class="hac"><input name="" type="checkbox" value=""
                                               <?php echo ($data_session['session'] == 'P' || $data_session['session'] == 'D') ? 'checked="checked" onclick="this.checked=true"' : 'disabled="disabled"'; ?> />
                        </td>
                    <?php } ?>
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
        <td colspan="2"><?php echo FormatText($child->notes); ?></td>
    </tr>
</table>

<?php PageFooter(); ?>

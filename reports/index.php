<?php
require_once('../lib/base.php');

// variables
$date = date('d-m-Y');
$month = intval(date('m'));
$year = intval(date('Y'));

// generate required dictionaries
$dictionary_month = GetDictionaryMonth();
$dictionary_year = GetDictionaryYear();

$query = 'select id as k,name as v';
$query .= ' from rooms';
$query .= ' order by min;';
$dictionary_room = Db::Dictionary($query);

PageHeader();

TitleReports();
?>

<p>&nbsp;</p>

<table class="two-col">
    <tr>
        <td>

            <h2>Session Reports</h2>

            <ul>
                <li><strong>Monthly Numbers</strong>
                    <form action="month_numbers.php" method="get" id="formreportmonthnumbers">
                        <div>
                            <?php echo FormCombo('m', $dictionary_month, $month, ''); ?>
                            <?php echo FormCombo('y', $dictionary_year, $year, ''); ?>
                            <?php echo FormCombo('i', GetExcludedRooms(), 't', ''); ?>
                            <?php echo FormSubmit('process1', 'View', 'formreportmonthnumbers'); ?>
                        </div>
                    </form>
                    <br />
                </li>
                <li><strong>Monthly Numbers Left</strong>
                    <form action="month_numbers_left.php" method="get" id="formreportmonthnumbersleft">
                        <div>
                            <?php echo FormCombo('m', $dictionary_month, $month, ''); ?>
                            <?php echo FormCombo('y', $dictionary_year, $year, ''); ?>
                            <?php echo FormCombo('i', GetExcludedRooms(), 't', ''); ?>
                            <?php echo FormSubmit('process2', 'View', 'formreportmonthnumbersleft'); ?>
                        </div>
                    </form>
                    <br />
                </li>
                <li><strong>Session</strong>
                    <form action="session.php" method="get" id="formreportsession">
                        <div>
                            <?php echo FormDate('d0', $date, ''); ?>
                            <?php echo FormCombo('r', array('0' => 'All Rooms') + $dictionary_room, 0, ''); ?>
                            <?php echo FormCombo('s', array('D' => 'Day', 'A' => 'AM', 'P' => 'PM'), 'D', ''); ?>
                            <?php echo FormSubmit('process3', 'View', 'formreportsession'); ?>
                        </div>
                    </form>
                    <br />
                </li>
                <li><strong>Week Names</strong>
                    <form action="week_names.php" method="get" id="formreportweeknames">
                        <div>
                            <?php echo FormDate('d1', $date, ''); ?>
                            <?php echo FormCombo('r', array('0' => 'All Rooms') + $dictionary_room, 0, ''); ?>
                            <?php echo FormSubmit('process4', 'View', 'formreportweeknames'); ?>
                        </div>
                    </form>
                    <br />
                </li>
                <li><strong>Week Numbers</strong>
                    <form action="week_numbers.php" method="get" id="formreportweeknumbers">
                        <div>
                            <?php echo FormDate('d2', $date, ''); ?>
                            <?php echo FormSubmit('process5', 'View', 'formreportweeknumbers'); ?>
                        </div>
                    </form>
                    <br />
                </li>
                <li><strong>Weekly Register</strong>
                    <form action="week_register.php" method="get" id="formreportregister">
                        <div>
                            <?php echo FormDate('d3', $date, ''); ?>
                            <?php echo FormCombo('r', array('0' => 'All Rooms') + $dictionary_room, 0, ''); ?>
                            <?php echo FormSubmit('process6', 'View', 'formreportregister'); ?>
                        </div>
                    </form>
                    <br />
                </li>

            </ul>

        </td>
        <td>

            <h2>General Reports</h2>

            <ul>
                <li><a href="birthdays.php">Birthdays</a></li>
                <li><a href="birthdays_month.php">Birthdays by Month</a></li>
                <li><a href="child_man_assigned.php">Children Manual Assigned to a Room</a></li>
            </ul>

            <p>&nbsp;</p>

            <h2>New Child Session Query</h2>

            <form action="/reports/query.php" method="get" id="formreport">
                <table class="details">
                    <tr>
                        <th>Child Dob :</th>
                        <td><?php echo FormDate('d', '', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Start Date :</th>
                        <td><?php echo FormDate('f', date('d-m-Y'), ''); ?></td>
                    </tr>
                    <tr>
                        <th>Finish Date :</th>
                        <td><?php echo FormDate('t', date('d-m-Y', mktime(1, 1, 1, date('m'), date('d'), date('Y') + 1)), ''); ?></td>
                    </tr>
                    <tr>
                        <th>Mon :</th>
                        <td><?php echo FormCheck('mon_a', false, 'AM', ''); ?>
                            <?php echo FormCheck('mon_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Tue :</th>
                        <td><?php echo FormCheck('tue_a', false, 'AM', ''); ?>
                            <?php echo FormCheck('tue_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Wed :</th>
                        <td><?php echo FormCheck('wed_a', false, 'AM', ''); ?>
                            <?php echo FormCheck('wed_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Thu :</th>
                        <td><?php echo FormCheck('thu_a', false, 'AM', ''); ?>
                            <?php echo FormCheck('thu_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <th>Fri :</th>
                        <td><?php echo FormCheck('fri_a', false, 'AM', ''); ?>
                            <?php echo FormCheck('fri_p', false, 'PM', ''); ?></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><?php echo FormSubmit('process7', 'Query Sessions', 'formreport'); ?></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>

<?php PageFooter(); ?>
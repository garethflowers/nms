<?php
require_once('../lib/base.php');

// do if form submitted
if (isset($_POST['process']) && is_array($_POST['reassign'])) {
    foreach ($_POST['reassign'] as $key=>$value) {
        $query = 'update child';
        $query .= ' set room_override='.Db::SqlFormat($value, 'int');
        $query .= ' where id='.Db::SqlFormat($key, 'int');
        $query .= ';';
        Db::ExecuteQuery($query);
    }
}


// get data
$query = 'select surname,forename,id,finish,start,format_date(dob) as dob,age_months(dob) as age,room_override';
$query .= ' from child';
$query .= ' where room_override>0;';
$data = Db::GetDataArray($query);

$query = 'select id,name,min,max,id as k,name as v from rooms order by min';
$rooms = Db::GetDataArray($query);
$dictionary = Db::Dictionary($query);

PageHeader();

TitleReports('Children Manually Assigned To Rooms');
?>

<p>&nbsp;</p>

<?php if (count($data)>0) { ?>

<form action="<?php echo PHP_SELF; ?>" method="post" id="formreport">
    <table class="results" id="ts1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date of Births</th>
                <th>Age (months)</th>
                <th>Auto Assigned</th>
                <th>Currently Assigned</th>
                <th>Re-Assign To...</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($data as $row) { ?>
            <tr>
                <td><a href="/child/view.php?i=<?php echo $row['id']; ?>"><?php echo strtoupper($row['surname']).', '.$row['forename']; ?></a></td>
                <td><?php echo FormatDate($row['dob'], 'jS F, Y'); ?></td>
                <td><?php echo $row['age']; ?></td>
                <td>
                            <?php if (($row['finish']>date('Y-m-d')) && ($row['start']<date('Y-m-d'))) {
                                foreach ($rooms as $row_rooms) {
                                    if (($row['age']>=$row_rooms['min']) && ($row['age']<$row_rooms['max'])) {
                                        echo $row_rooms['name'];
                                    }
                                }
                            } elseif ($row['finish']<date('Y-m-d')) {
                                echo 'Child has finished';
                            } else {
                                echo 'Child has not started';
                            } ?>
                </td>
                <td>
                            <?php foreach ($rooms as $row_rooms) {
                                if ($row['room_override']==$row_rooms['id']) {
                                    echo $row_rooms['name'];
                                }
                            } ?>
                </td>

                <td>
                            <?php echo FormCombo('reassign['.$row['id'].']', array(0=>'Auto')+$dictionary, $row['room_override'], ''); ?>
                </td>
            </tr>
                <?php } ?>
        </tbody>
    </table>
        <?php echo JsBlock(JsSortingTable('ts1')); ?>

    <p>&nbsp;</p>

    <div class="har">
            <?php echo FormSubmit('process', 'Update', 'formreport'); ?>
    </div>
</form>

<?php } else { ?>

<p>No children currently manually assigned!</p>

<?php }

PageFooter();
?>
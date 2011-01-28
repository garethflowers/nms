<?php
require_once('../lib/base.php');

// get variables
$mode = 'c';
$opt = "start<=current_date and (finish>=current_date or finish is null)";
if (isset($_GET['v'])) {
    switch ($_GET['v']) {
        case 'n':
            $mode = 'n';
            $opt = "start>current_date or start is null";
            break;
        case 'o':
            $mode = 'o';
            $opt = "finish<current_date";
            break;
        default:
            break;
    }
}

// get data
$query = 'select surname,forename,id,assigned_room';
$query .= ' from staff';
$query .= ' where '.$opt;
$query .= ' order by surname,forename;';
$data = Db::GetDataArray($query);

PageHeader();

TitleStaff(null);
?>

<h2><?php if ($mode=='n') {
        echo 'Future Staff';
    } elseif ($mode=='o') {
        echo 'Archive of Previous Staff';
    } else {
        echo 'Current Staff';
    } ?></h2>

<?php if (count($data)>0) { ?>
<table class="results" id="ts1">
    <thead>
        <tr>
            <th>Surname</th>
            <th>Forename</th>
            <th>Assigned Room</th>
            <th class="sortnone">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
            <?php foreach ($data as $row) { ?>
        <tr>
            <td class="har"><strong><a href="view.php?i=<?php echo $row['id']; ?>"><?php echo FormatText(strtoupper($row['surname'])); ?></a></strong></td>
            <td class="hal"><a href="view.php?i=<?php echo $row['id']; ?>"><?php echo FormatText($row['forename']); ?></a></td>
            <td><?php if ($row['assigned_room']==0) {
                            echo 'n/a';
                        } else {
                            $query = 'select name';
                            $query .= ' from rooms';
                            $query .= ' where id=' . Db::SqlFormat($row['assigned_room'], 'int');
                            $query .= ' limit 1';
                            $data_room = Db::GetData($query);
                            echo '<a href="/reports/week_names.php?d='.date('d-m-Y').'&amp;r='.$row['assigned_room'].'">'.FormatText($data_room['name']).'</a>';
                        } ?></td>
            <td><a href="view.php?i=<?php echo intval($row['id']); ?>"><img src="/lib/images/view.png" alt="View" /></a>
                        <?php if (LoginLevel(2)) { ?>
                <a href="amend.php?i=<?php echo intval($row['id']); ?>"><img src="/lib/images/edit.png" alt="Edit" /></a>
                <a href="delete.php?i=<?php echo intval($row['id']); ?>"><img src="/lib/images/delete.png" alt="Delete" /></a>
                        <?php } ?>
            </td>
        </tr>
            <?php } ?>
    </tbody>
</table>
    <?php echo JsBlock(JsSortingTable('ts1')); ?>

<?php } else { ?>

<p>&nbsp;</p>

<p>No staff information is currently available. Choose '<strong>Add Staff</strong>' in the menu bar to add a new staff to the database.</p>

<?php } ?>

	<?php PageFooter(); ?>
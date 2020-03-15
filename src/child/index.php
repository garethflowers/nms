<?php
require_once('../lib/base.php');

// get variables
$mode = 'c';
$opt = 'finish>=current_date and start <=current_date';
if (isset($_GET['v'])) {
    switch ($_GET['v']) {
        case 'n':
            $mode = 'n';
            $opt = '(start>current_date or start is null or finish is null)';
            break;
        case 'o':
            $mode = 'o';
            $opt = 'finish<current_date';
            break;
        default:
            break;
    }
}

// get data
$query = 'select rooms.name,rooms.id as roomid,child.forename,child.surname,format_date(child.dob) as dob,child.id,format_date(child.start) as start,format_date(child.finish) as finish,age_months(child.dob) as age';
$query .= ' from child';
$query .= ' left outer join rooms on rooms.min<=age_months(child.dob) and rooms.max>age_months(child.dob) and child.finish>=now() and child.start<=now()';
$query .= ' where ' . $opt;
$query .= ' order by child.surname,child.forename;';
$data = Db::GetDataArray($query);

PageHeader();

TitleChild(null);

echo '<h2>';
if ($mode == 'n') {
    echo 'Future Children';
} elseif ($mode == 'o') {
    echo 'Archive of Previous Children';
} else {
    echo 'Current Children';
}
echo '</h2>';

if (count($data) > 0) {
?>

    <table class="results" id="ts1">
        <thead>
            <tr>
                <th>Surname</th>
                <th>Forename</th>
                <th>Date of Birth</th>
                <th>Age (months)</th>
                <th>Age (years)</th>
                <th>Current Room</th>
                <?php if ($mode == 'n') { ?>
                    <th>Starting Date</th>
                <?php } elseif ($mode == 'o') { ?>
                    <th>Leaving Date</th>
                <?php } ?>
                <th class="sortnone">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td class="har"><strong><a href="/child/view.php?i=<?php echo $row['id']; ?>"><?php echo FormatText(strtoupper($row['surname'])); ?></a></strong></td>
                    <td class="hal"><a href="/child/view.php?i=<?php echo $row['id']; ?>"><?php echo FormatText($row['forename']); ?></a></td>
                    <td><?php echo FormatDate($row['dob']); ?></td>
                    <td><?php echo $months = $row['age']; ?></td>
                    <td><?php echo floor($months / 12) . ' yrs ' . ($months % 12) . ' mths'; ?></td>
                    <td><?php echo empty($row['name']) ? 'n/a' : '<a href="/reports/week_names.php?d=' . date('d-m-Y') . '&amp;r=' . $row['roomid'] . '">' . FormatText($row['name']) . '</a>'; ?></td>
                    <?php if ($mode == 'n') { ?>
                        <td><?php echo FormatDate($row['start']); ?></td>
                    <?php } elseif ($mode == 'o') { ?>
                        <td><?php echo FormatDate($row['finish']); ?></td>
                    <?php } ?>
                    <td><a href="/child/view.php?i=<?php echo intval($row['id']); ?>"><img src="/lib/images/view.png" alt="View" /></a>
                        <?php if (LoginLevel(2)) { ?>
                            <a href="/child/amend.php?i=<?php echo intval($row['id']); ?>"><img src="/lib/images/edit.png" alt="Edit" /></a>
                            <a href="/child/delete.php?i=<?php echo intval($row['id']); ?>"><img src="/lib/images/delete.png" alt="Delete" /></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php echo JsBlock(JsSortingTable('ts1')); ?>

<?php
} else {

    echo '<p>No child information is currently available.</p>';
}

PageFooter();
?>

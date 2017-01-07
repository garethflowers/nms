<?php
require_once('../../lib/base.php');

$id_child = CheckID($_GET['i']);
$data_child = new Child($id_child);

PageHeader();

TitleChildSession($data_child, null);
?>

<h2>Regular Sessions</h2>

<?php
$query = 'select format_date(date) as date,format_date(date_till) as date_till,session,extra,id';
$query .= ' from child_session';
$query .= ' where exclude=\'N\'';
$query .= ' and child_id=%s';
$query .= ' order by date desc;';
$query = sprintf($query, $data_child->id);
$data_session = Db::GetDataArray($query);

if (count($data_session) > 0) {
    ?>

    <table class="results" id="ts1">
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                <th>Until</th>
                <th>Session</th>
                <th>Extra Session</th>
                <?php if (LoginLevel(2)) { ?>
                    <th class="sortnone">&nbsp;</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_session as $row) { ?>
                <tr>
                    <td><?php echo FormatDate($row['date'], 'l'); ?></td>
                    <td><?php echo FormatDate($row['date']); ?></td>
                    <td><?php echo FormatDate($row['date_till']); ?></td>
                    <td><?php echo FormatSession($row['session']); ?></td>
                    <td><?php echo FormatBoolean($row['extra']); ?></td>
                    <?php if (LoginLevel(2)) { ?>
                        <td>
                            <a href="amend.php?i=<?php echo $id_child; ?>&amp;s=<?php echo $row['id']; ?>"><img src="/lib/images/edit.png" alt="edit" /></a>
                            <a href="delete.php?i=<?php echo $id_child; ?>&amp;s=<?php echo $row['id']; ?>"><img src="/lib/images/delete.png" alt="Delete" /></a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php echo JsBlock(JsSortingTable('ts1')); ?>

<?php } else { ?>

    <p>There are no regular sessions for this child.</p>

<?php } ?>

<p>&nbsp;</p>

<h2>Exlcuded Sessions</h2>

<?php
$query = 'select *';
$query .= ' from child_session';
$query .= ' where exclude=\'Y\'';
$query .= ' and child_id=%s';
$query .= ' order by date desc';
$query = sprintf($query, $data_child->id);
$data_session = Db::GetDataArray($query);

if (count($data_session) > 0) {
    ?>

    <table class="results" id="ts2">
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                <th>Until</th>
                <th>Session</th>
                <th>Extra Session</th>
                <?php if (LoginLevel(2)) { ?>
                    <th class="sortnone">&nbsp;</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_session as $row) { ?>
                <tr>
                    <td><?php echo FormatDate($row['date'], 'l'); ?></td>
                    <td><?php echo FormatDate($row['date']); ?></td>
                    <td><?php echo FormatDate($row['date_till']); ?></td>
                    <td><?php echo FormatSession($row['session']); ?></td>
                    <td><?php echo FormatBoolean($row['extra']); ?></td>
                    <?php if (LoginLevel(2)) { ?>
                        <td>
                            <a href="amend.php?i=<?php echo $id_child; ?>&amp;s=<?php echo $row['id']; ?>"><img src="/lib/images/edit.png" alt="edit" /></a>
                            <a href="delete.php?i=<?php echo $id_child; ?>&amp;s=<?php echo $row['id']; ?>"><img src="/lib/images/delete.png" alt="Delete" /></a>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php echo JsBlock(JsSortingTable('ts2')); ?>

<?php } else { ?>

    <p>There are no excluded sessions for this child.</p>

<?php } ?>

<?php PageFooter(); ?>
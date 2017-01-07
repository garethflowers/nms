<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

PageHeader();

TitleSettings(true, false);
?>

<table class="two-col">
    <tr>
        <td>

            <h2>Repeating Dates <a href="amend.php"><img src="/lib/images/add.png" alt="Add" /></a></h2>

            <?php
            $query = 'select id,format_date(date) as date';
            $query .= ' from dates';
            $query .= ' where repeat=\'Y\'';
            $query .= ' order by date desc;';
            $data = Db::GetDataArray($query);
            if (count($data) > 0) {
                ?>
                <table class="results" id="ts1">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Month</th>
                            <th class="sortnone">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row) { ?>
                            <tr>
                                <td><?php echo FormatDate($row['date'], 'd'); ?></td>
                                <td><?php echo FormatDate($row['date'], 'F'); ?></td>
                                <td><a href="amend.php?i=<?php echo $row['id']; ?>"><img src="/lib/images/edit.png" alt="Edit" /></a> <a href="delete.php?i=<?php echo $row['id']; ?>"><img src="/lib/images/delete.png" alt="Delete" /></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php echo JsBlock(JsSortingTable('ts1')); ?>
            <?php } else { ?>
                <p>There are no repeating dates currently set.</p>
            <?php } ?></td>
        <td>

            <h2>Individual Date <a href="amend.php"><img src="/lib/images/add.png" alt="Add" /></a></h2>

            <?php
            $query = 'select id,format_date(date) as date';
            $query .= ' from dates';
            $query .= ' where repeat=\'N\'';
            $query .= ' order by date desc;';
            $data = Db::GetDataArray($query);
            if (count($data) > 0) {
                ?>
                <table class="results" id="ts2">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Date</th>
                            <th class="sortnone">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row) { ?>
                            <tr>
                                <td><?php echo FormatDate($row['date'], 'l'); ?></td>
                                <td><?php echo FormatDate($row['date'], 'jS F, Y'); ?></td>
                                <td><a href="amend.php?i=<?php echo $row['id']; ?>"><img src="/lib/images/edit.png" alt="Edit" /></a> <a href="delete.php?i=<?php echo $row['id']; ?>"><img src="/lib/images/delete.png" alt="Delete" /></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php echo JsBlock(JsSortingTable('ts2')); ?>
                <?php
            } else {
                echo '<p>There are no individual dates currently set.</p>';
            }
            ?></td>
    </tr>
</table>

<?php PageFooter(); ?>
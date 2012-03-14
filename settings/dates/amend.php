<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

$dates = new Dates();
$dates->id = isset($_GET['i']) && is_numeric($_GET['i']) ? intval($_GET['i']) : 0;

if (isset($_POST['process'])) {
    $dates->Update($_POST);
    $result = $dates->Save();
    if (is_array($result) && count($result) == 0) {
        header('location: index.php?i=' . $dates->id);
    }
} elseif ($dates->id != 0) {
    $dates->Load($dates->id);
}

PageHeader();

TitleSettings(true, false);
?>

<h2><?php echo $dates->id == 0 ? 'Add' : 'Edit'; ?> Date</h2>

<form action="<?php echo $dates->id == 0 ? PHP_SELF : PHP_SELF . '?i=' . $dates->id; ?>" method="post" id="formdates">

    <table class="details">
        <tr>
            <td colspan="2">
                <h3>Date</h3>
            </td>
        </tr>
        <tr>
            <th><?php echo FormLabel('repeat', 'Repeat'); ?></th>
            <td><?php echo FormCheck('repeat', $dates->repeat, '', ''); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('date', 'Date'); ?></th>
            <td><?php echo FormDate('date', $dates->date, 'required'); ?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="hac"><?php echo FormSubmit('process', $dates->id == 0 ? 'Add' : 'Update', 'formdates'); ?></td>
        </tr>
    </table>

</form>

<?php PageFooter(); ?>
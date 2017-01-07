<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

$religion = new Religion();
$religion->id = isset($_GET['i']) && is_numeric($_GET['i']) ? intval($_GET['i']) : 0;

if (isset($_POST['process'])) {
    $religion->Update($_POST);
    $result = $religion->Save();
    if (is_array($result) && count($result) == 0) {
        header('location: index.php?i=' . $religion->id);
    }
} elseif ($religion->id != 0) {
    $religion->Load($religion->id);
}

PageHeader();

TitleSettings(false, false, true);
?>

<h2><?php echo $religion->id == 0 ? 'Add' : 'Edit'; ?> Religion</h2>

<form action="<?php echo $religion->id == 0 ? PHP_SELF : PHP_SELF . '?i=' . $religion->id; ?>" method="post" id="formamend">

    <table class="details">
        <tr>
            <td colspan="2">
                <h3>Date</h3>
            </td>
        </tr>
        <tr>
            <th><?php echo FormLabel('name', 'Name'); ?></th>
            <td><?php echo FormText('name', $religion->name, 'required', 100); ?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="hac"><?php echo FormSubmit('process', $religion->id == 0 ? 'Add' : 'Update', 'formamend'); ?></td>
        </tr>
    </table>

</form>

<?php PageFooter(); ?>
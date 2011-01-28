<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

$nationality = new Nationality();
$nationality->id = isset($_GET['i']) && is_numeric($_GET['i']) ? intval($_GET['i']) : 0;

if (isset($_POST['process'])) {
    $nationality->Update($_POST);
    $result = $nationality->Save();
    if (is_array($result) && count($result)==0) {
        header('location: index.php?i='.$nationality->id);
    }
}
elseif ($nationality->id != 0) {
    $nationality->Load($nationality->id);
}

PageHeader();

TitleSettings(false, false, false, true);
?>

<h2><?php echo $nationality->id==0?'Add':'Edit'; ?> Nationality</h2>

<form action="<?php echo $nationality->id==0?PHP_SELF:PHP_SELF.'?i='.$nationality->id; ?>" method="post" id="formamend">

    <table class="details">
        <tr>
            <td colspan="2">
                <h3>Date</h3>
            </td>
        </tr>
        <tr>
            <th><?php echo FormLabel('name', 'Name'); ?></th>
            <td><?php echo FormText('name', $nationality->name, 'required', 100); ?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="hac"><?php echo FormSubmit('process', $nationality->id==0?'Add':'Update', 'formamend'); ?></td>
        </tr>
    </table>

</form>

<?php PageFooter(); ?>
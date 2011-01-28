<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

$rooms = new Rooms();
$rooms->id = isset($_GET['i']) && is_numeric($_GET['i']) ? intval($_GET['i']) : 0;

if (isset($_POST['process'])) {
    $rooms->Update($_POST);
    $result = $rooms->Save();
    if (is_array($result) && count($result)==0) {
        header('location: index.php?i='.$rooms->id);
    }
}
elseif ($rooms->id != 0) {
    $rooms->Load($rooms->id);
}

PageHeader();

TitleSettings(false, true);
?>

<h2><?php echo $rooms->id==0?'Add':'Edit'; ?> Date</h2>

<form action="<?php echo $rooms->id==0?PHP_SELF:PHP_SELF.'?i='.$rooms->id; ?>" method="post" id="formdates">

    <table class="details">
        <tr>
            <td colspan="2">
                <h3>Date</h3>
            </td>
        </tr>
        <tr>
            <th><?php echo FormLabel('name', 'Name'); ?></th>
            <td><?php echo FormText('name', $rooms->name, 'required', 200); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('total', 'Total number of Children'); ?></th>
            <td><?php echo FormText('total', $rooms->total, 'required validate-integer', 4); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('min', 'Minimum Age'); ?></th>
            <td><?php echo FormText('min', $rooms->min, 'required validate-integer', 4); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('max', 'Maximum Age'); ?></th>
            <td><?php echo FormText('max', $rooms->max, 'required validate-integer', 4); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('include', 'Include in Monthly Totals'); ?></th>
            <td><?php echo FormCheck('include', $rooms->include, '', ''); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('price_session', 'Price per Session'); ?></th>
            <td><?php echo FormText('price_session', number_format($rooms->price_session,2), 'required validate-numeric', 11); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('price_day', 'Price per Day'); ?></th>
            <td><?php echo FormText('price_day', number_format($rooms->price_day,2), 'required validate-numeric', 11); ?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="hac"><?php echo FormSubmit('process', $rooms->id==0?'Add':'Update', 'formdates'); ?></td>
        </tr>
    </table>

</form>

<?php PageFooter(); ?>
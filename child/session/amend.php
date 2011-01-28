<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

$id = CheckID($_GET['i']);
$child = new Child($id);

$session = new ChildSession();
$session->id = isset($_GET['s']) && is_numeric($_GET['s']) ? intval($_GET['s']) : 0;

if (isset($_POST['process'])) {
    $session->child_id = $child->id;
    $session->Update($_POST);
    $result = $session->Save();
    if (count($result)==0) {
        header('location: index.php?i='.$child->id);
    }
} elseif ($session->id != 0) {
    $session->Load($session->id);
}

PageHeader();

TitleChildSession($child,$session);
?>

<h2><?php echo $session->id==0?'Add':'Edit'; ?> Session Information</h2>

<form action="<?php echo $session->id==0?PHP_SELF.'?i='.$id:PHP_SELF.'?i='.$id.'&amp;s='.$session->id; ?>" method="post" id="formchildsession">

    <table class="details">
        <tr>
            <td colspan="2">
                <h3>Session</h3>
            </td>
        </tr>
        <tr>
            <th><?php echo FormLabel('session', 'Session'); ?></th>
            <td><?php echo FormCombo('session', array('D'=>'Day','A'=>'Morning','P'=>'Afternoon'), $session->session, 'required'); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('date', 'Date'); ?></th>
            <td><?php echo FormDate('date', $session->date, 'required'); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('date_till', 'Until'); ?></th>
            <td><?php echo FormDate('date_till', $session->date_till, 'required'); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('extra', 'Extra Session?'); ?></th>
            <td><?php echo FormCheck('extra', $session->extra, '', 'required'); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('exclude', 'Exclude'); ?></th>
            <td><?php echo FormCheck('exclude', $session->exclude, '', 'required'); ?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" class="hac"><?php echo FormSubmit('process', $session->id==0?'Add':'Update', 'formchildsession'); ?></td>
        </tr>
    </table>

</form>

<?php PageFooter(); ?>
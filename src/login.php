<?php
require_once('lib/base.php');

$error = false;

if (isset($_POST['process']) && $_POST['process'] == 'Login') {
    $_SESSION['user'] = new User($_POST['username'], $_POST['password']);
    if (LoginCheck()) {
        header('location: /index.php');
    } else {
        $error = true;
    }
} else if (isset($_GET['o']) && $_GET['o'] == 't') {
    LogOut();
}

PageHeader();
?>

<h1>Login</h1>

<p>&nbsp;</p>

<p>&nbsp;</p>

<?php if ($error) { ?>

    <p class="error">You have entered incorrect details. Please try again.</p>

<?php } ?>

<form action="<?php PHP_SELF; ?>" method="post" id="formlogin">

    <table class="details">
        <tr>
            <th><?php echo FormLabel('username', 'Username'); ?></th>
            <td><?php echo FormText('username', '', 'required', 20); ?></td>
        </tr>
        <tr>
            <th><?php echo FormLabel('password', 'Password'); ?></th>
            <td><?php echo FormPassword('password', '', 'required', 20); ?></td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo FormSubmit('process', 'Login', 'formlogin'); ?></td>
        </tr>
    </table>

</form>

<?php PageFooter(); ?>

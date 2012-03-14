<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// checks
$id = CheckID($_GET['i']);
$child = new Child($id);

$id = CheckID($_GET['s']);
$session = new ChildSession($id);

// form submission
if (isset($_POST['process'])) {
    $session->Delete();
    header('location: index.php?i=' . $child->id);
}

PageHeader();

TitleChildSession($child, $session);
?>

<h2>Delete Session Information</h2>

<p>&nbsp;</p>

<p>Would you like to delete session details for '<?php echo FormatDate($session->date) . ' ' . FormatSession($session->session); ?>'?</p>

<p>&nbsp;</p>

<form method="post" action="<?php echo PHP_SELF . '?i=' . $child->id . '&amp;s=' . $session->id; ?>" id="formdelete">

    <div>
        <?php echo FormSubmit('process', 'Delete Session', 'formdelete'); ?>
    </div>

</form>

<?php PageFooter(); ?>

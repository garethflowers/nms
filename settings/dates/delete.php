<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// checks
$id = CheckID($_GET['i']);
$dates = new Dates($id);

// form submission
if (isset($_POST['process'])) {
    $dates->Delete();
    header('location: index.php');
}

PageHeader();

TitleSettings(true, false);
?>

<h2>Delete Session Information</h2>

<p>&nbsp;</p>

<p>Would you like to delete date details for '<?php echo FormatDate($dates->date); ?>'?</p>

<p>&nbsp;</p>

<form method="post" action="<?php echo PHP_SELF.'?i='.$dates->id; ?>" id="formdelete">

    <div>
        <?php echo FormSubmit('process', 'Delete Date', 'formdelete'); ?>
    </div>

</form>

<?php PageFooter(); ?>
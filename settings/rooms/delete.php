<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// checks
$id = CheckID($_GET['i']);
$rooms = new Rooms($id);

// form submission
if (isset($_POST['process'])) {
    $rooms->Delete();
    header('location: index.php');
}

PageHeader();

TitleSettings(false, true);
?>

<h2>Delete Room Information</h2>

<p>&nbsp;</p>

<p>Would you like to delete room details for '<?php echo $rooms->name; ?>'?</p>

<p>&nbsp;</p>

<form method="post" action="<?php echo PHP_SELF.'?i='.$rooms->id; ?>" id="formdelete">

    <div>
        <?php echo FormSubmit('process', 'Delete Rooms', 'formdelete'); ?>
    </div>

</form>

<?php PageFooter(); ?>
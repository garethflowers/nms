<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// checks
$id = CheckID($_GET['i']);
$religion = new Religion($id);

// form submission
if (isset($_POST['process'])) {
    $religion->Delete();
    header('location: index.php');
}

PageHeader();

TitleSettings(false, false, true);
?>

<h2>Delete Religion Information</h2>

<p>&nbsp;</p>

<?php
$query = 'select count(id) as count';
$query .= ' from child';
$query .= ' where religion=' . Db::SqlFormat($religion->id, 'int');
$query .= ';';
$data = Db::GetScalar($query);
if (intval($data['count']) > 0) { ?>

<p>This religion cannot be deleted because it is used for <?php echo intval($data['count']); ?> children.</p>

<?php } else { ?>

<p>Would you like to delete religion details for '<?php echo $religion->name; ?>'?</p>

<p>&nbsp;</p>

<form method="post" action="<?php echo PHP_SELF.'?i='.$religion->id; ?>" id="formdelete">

    <div>
            <?php echo FormSubmit('process', 'Delete Religion', 'formdelete'); ?>
    </div>

</form>

<?php } 

PageFooter();
?>
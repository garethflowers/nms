<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// checks
$id = CheckID($_GET['i']);
$nationality = new Nationality($id);

// form submission
if (isset($_POST['process'])) {
    $nationality->Delete();
    header('location: index.php');
}

PageHeader();

TitleSettings(false, false, true);
?>

<h2>Delete Nationality Information</h2>

<p>&nbsp;</p>

<?php
$query = 'select count(id) as count';
$query .= ' from child';
$query .= ' where nationality=' . Db::SqlFormat($nationality->id, 'int');
$query .= ';';
$data = Db::GetScalar($query);
if (intval($data['count']) > 0) {
    ?>

    <p>This nationality cannot be deleted because it is used for <?php echo intval($data['count']); ?> children.</p>

<?php } else { ?>

    <p>Would you like to delete nationality details for '<?php echo $nationality->name; ?>'?</p>

    <p>&nbsp;</p>

    <form method="post" action="<?php echo PHP_SELF . '?i=' . $nationality->id; ?>" id="formdelete">

        <div>
            <?php echo FormSubmit('process', 'Delete Nationality', 'formdelete'); ?>
        </div>

    </form>

    <?php
}

PageFooter();
?>
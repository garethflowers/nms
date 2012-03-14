<?php
require_once('../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// checks
$id = CheckID($_GET['i']);
$child = new Child($id);

// form submission
if (isset($_POST['process'])) {
    $child->Delete();
    header('location: index.php?i=' . $child->id);
}

PageHeader();

TitleChild($child);
?>

<h2>Delete Child Information</h2>

<p>&nbsp;</p>

<p>Would you like to delete all child details for '<?php echo $child->forename . ' ' . $child->surname; ?>'?</p>

<p>&nbsp;</p>

<form id="formdelete" method="post" action="<?php echo PHP_SELF . '?i=' . $child->id; ?>">

    <div>
        <?php echo FormSubmit('process', 'Delete Child', 'formdelete'); ?>
    </div>

</form>

<p>&nbsp;</p>

<p>&nbsp;</p>

<ul>
    <li><a href="view.php?i=<?php echo $child->id; ?>">View Child details</a></li>
</ul>

<?php PageFooter(); ?>

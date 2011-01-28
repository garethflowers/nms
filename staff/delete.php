<?php
require_once('../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// checks
$id = CheckID($_GET['i']);
$staff = new Staff($id);

// form submission
if (isset($_POST['process'])) {
	$staff->Delete();
	header('location: index.php?i='.$staff->id);
}

PageHeader();

TitleStaff($staff); ?>

<h2>Delete Child Information</h2>

<p>&nbsp;</p>

<?php
$query = 'select count(id) as count';
$query .= ' from child';
$query .= ' where keyworker=' . Db::SqlFormat($staff->id, 'int');
$query .= ' or backup_keyworker=' . Db::SqlFormat($staff->id, 'int');
$data = Db::GetData($query);
if (intval($data['count']) > 0) { ?>

<p>This staff member cannot be deleted because it is a keyworker/backup keyworker
	for <?php echo intval($data['count']); ?> children.</p>

<?php } else { ?>

<p>Would you like to delete all staff details for '<?php echo $staff->forename.' '.$staff->surname; ?>'?</p>

<p>&nbsp;</p>

<form id="formdelete" method="post" action="<?php echo PHP_SELF.'?i='.$staff->id; ?>">

    <div>
		<?php echo FormSubmit('process', 'Delete Staff', 'formdelete'); ?>
    </div>

</form>

<?php } ?>

<p>&nbsp;</p>

<p>&nbsp;</p>

<ul>
	<li><a href="view.php?i=<?php echo $staff->id; ?>">View Staff details</a></li>
</ul>

<?php PageFooter(); ?>
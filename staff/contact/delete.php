<?php
require_once('../../lib/base.php');

// check security level
if (!LoginLevel(2)) {
    header('location: /noaccess.php');
}

// checks
$id = CheckID($_GET['i']);
$staff = new Staff($id);

$id = CheckID($_GET['c']);
$contact = new StaffContact($id);

// form submission
if (isset($_POST['process'])) {
    $contact->Delete();
    header('location: index.php?i='.$staff->id);
}

PageHeader();

TitleStaffContact($staff,$contact);
?>

<h2>Delete Contact Information</h2>

<p>&nbsp;</p>

<p>Would you like to delete contact details for '<?php echo $contact->name; ?>'?</p>

<p>&nbsp;</p>

<form method="post" action="<?php echo PHP_SELF.'?i='.$staff->id.'&amp;c='.$contact->id; ?>" id="formdelete">

    <div>
        <?php echo FormSubmit('process', 'Delete Contact', 'formdelete'); ?>
    </div>

</form>

<p>&nbsp;</p>

<p>&nbsp;</p>

<ul>
	<li><a href=".?i=<?php echo $staff->id; ?>&amp;c=<?php echo $contact->id; ?>">View Contact details</a></li>
</ul>

<?php PageFooter(); ?>
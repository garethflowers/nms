<?php

function PageHeader() {
    require_once(DOCUMENT_ROOT . '/lib/template/header.php');
}

function PageFooter() {
    require_once(DOCUMENT_ROOT . '/lib/template/footer.php');
}

function TitleCommonChildLinks($child) {
    echo '<h1 class="links">';
    if (is_object($child)) {
        echo '<a href="/child/contact/?i=' . $child->id . '">Contacts</a>';
        echo ' - <a href="/child/session/?i=' . $child->id . '">Sessions</a>';
    } else {
        echo '<a href="/child">Current</a>';
        echo ' - <a href="/child/?v=n">New</a>';
        echo ' - <a href="/child/?v=o">Archive</a>';
    }
    echo '</h1>';
}

function TitleCommonChild($child) {
    echo '<a href="/child">Children</a>';

    if (LoginLevel(2)) {
        echo ' <a href="/child/amend.php"><img src="/lib/images/add.png" alt="Add" /></a>';
    }

    if (is_object($child) && $child->id != 0) {
        echo ' &nbsp; \ &nbsp; <a href="/child/view.php?i=' . $child->id . '">' . $child->forename . ' ' . $child->surname . '</a>';
        if (LoginLevel(2)) {
            echo ' <a href="/child/amend.php?i=' . $child->id . '"><img src="/lib/images/edit.png" alt="Edit" /></a>';
            echo ' <a href="/child/delete.php?i=' . $child->id . '"><img src="/lib/images/delete.png" alt="Delete" /></a>';
        }
    }
}

function TitleChild($child) {
    TitleCommonChildLinks($child);
    echo '<h1>';
    TitleCommonChild($child);
    echo '</h1>';
}

function TitleChildContact($child, $contact) {
    TitleCommonChildLinks($child);
    echo '<h1>';
    TitleCommonChild($child);
    echo ' &nbsp; \ &nbsp; <a href="/child/contact/index.php?i=' . $child->id . '">Contacts</a>';

    if (LoginLevel(2)) {
        echo ' <a href="/child/contact/amend.php?i=' . $child->id . '"><img src="/lib/images/add.png" alt="Add" /></a>';
    }

    if (is_object($contact) && $contact->id != 0 && LoginLevel(2)) {
        echo ' <a href="/child/contact/amend.php?i=' . $child->id . '&amp;c=' . $contact->id . '"><img src="/lib/images/edit.png" alt="Edit" /></a>';
        echo ' <a href="/child/contact/delete.php?i=' . $child->id . '&amp;c=' . $contact->id . '"><img src="/lib/images/delete.png" alt="Delete" /></a>';
    }
    echo '</h1>';
}

function TitleChildSession($child, $session) {
    TitleCommonChildLinks($child);
    echo '<h1>';
    TitleCommonChild($child);
    echo ' &nbsp; \ &nbsp; <a href="/child/session/index.php?i=' . $child->id . '">Sessions</a>';
    if (LoginLevel(2)) {
        echo ' <a href="/child/session/amend.php?i=' . $child->id . '"><img src="/lib/images/add.png" alt="Add" /></a>';
    }

    if (is_object($session) && $session->id != 0 && LoginLevel(2)) {
        echo ' <a href="/child/session/amend.php?i=' . $child->id . '&amp;s=' . $session->id . '"><img src="/lib/images/edit.png" alt="edit" /></a>';
        echo ' <a href="/child/session/delete.php?i=' . $child->id . '&amp;s=' . $session->id . '"><img src="/lib/images/delete.png" alt="Delete" /></a>';
    }
    echo '</h1>';
}

function TitleCommonStaffLinks($staff) {
    echo '<h1 class="links">';
    if (is_object($staff)) {
        echo '<a href="/staff/contact/index.php?i=' . $staff->id . '">Contacts</a>';
        echo ' - <a href="/staff/keyworker.php?i=' . $staff->id . '">Keyworker</a>';
        echo ' - <a href="/staff/backup_keyworker.php?i=' . $staff->id . '">BackUp Keyworker</a>';
    } else {
        echo '<a href="/staff/index.php">Current</a>';
        echo ' - <a href="/staff/index.php?v=n">New</a>';
        echo ' - <a href="/staff/index.php?v=o">Archive</a>';
    }
    echo '</h1>';
}

function TitleCommonStaff($staff) {
    echo '<a href="/staff">Staff</a>';

    if (LoginLevel(2)) {
        echo ' <a href="/staff/amend.php"><img src="/lib/images/add.png" /></a>';
    }

    if (is_object($staff)) {
        echo ' &nbsp; \ &nbsp; <a href="/staff/view.php?i=' . $staff->id . '">' . $staff->forename . ' ' . $staff->surname . '</a>';

        if (LoginLevel(2)) {
            echo ' <a href="/staff/amend.php?i=' . $staff->id . '"><img src="/lib/images/edit.png" alt="Edit" /></a>';
            echo ' <a href="/staff/delete.php?i=' . $staff->id . '"><img src="/lib/images/delete.png" alt="Delete" /></a>';
        }
    }
}

function TitleStaff($staff) {
    TitleCommonStaffLinks($staff);
    echo '<h1>';
    TitleCommonStaff($staff);
    echo '</h1>';
}

function TitleStaffContact($staff, $contact) {
    TitleCommonStaffLinks($staff);
    echo '<h1>';
    TitleCommonStaff($staff);
    echo ' &nbsp; \ &nbsp; <a href="/staff/contact/index.php?i=' . $staff->id . '">Contacts</a>';

    if (LoginLevel(2)) {
        echo ' <a href="/staff/contact/amend.php?i=' . $staff->id . '"><img src="/lib/images/add.png" /></a>';
    }

    if (is_object($contact) && $contact != 0 && LoginLevel(2)) {
        echo ' <a href="/staff/contact/amend.php?i=', $contact->id, '"><img src="/lib/images/edit.png" alt="Edit" /></a>';
        echo ' <a href="/staff/contact/delete.php?i=', $contact->id, '"><img src="/lib/images/delete.png" alt="Delete" /></a>';
    }
    echo '</h1>';
}

function TitleSettings($dates = false, $rooms = false, $religion = false, $nationality = false) {
    echo '<h1>';
    echo '<a href="/settings">Settings</a>';
    if ($dates) {
        echo ' &nbsp; \ &nbsp; <a href="/settings/dates">Dates</a>';
    } elseif ($rooms) {
        echo ' &nbsp; \ &nbsp; <a href="/settings/rooms">Rooms</a>';
    } elseif ($religion) {
        echo ' &nbsp; \ &nbsp; <a href="/settings/religion">Religions</a>';
    } elseif ($nationality) {
        echo ' &nbsp; \ &nbsp; <a href="/settings/nationality">Nationalities</a>';
    }
    echo '</h1>';
}

function TitleReports($report = null) {
    echo '<h1>';
    echo '<a href="/reports">Reports</a>';
    if (!empty($report)) {
        echo ' &nbsp; \ &nbsp; ' . $report;
    }
    echo '</h1>';
}

?>
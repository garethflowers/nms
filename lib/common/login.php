<?php
/*
 * check if a user is logged in at the specified level
 */
function LoginLevel($level) {
    global $config;

    if (($config['security_level']>=$level) || (LoginCheck() && $_SESSION['user']->GetLevel()>=$level)) {
        return true;
    } else {
        return false;
    }
}


/*
 * check to see if the user is logged in
 */
function LoginCheck() {
    if (isset($_SESSION['user']) && ($_SESSION['user'] instanceof User) && $_SESSION['user']->GetID()>0) {
        return true;
    } else {
        LogOut();
        return false;
    }
}


/*
 * logout a user
 */
function LogOut() {
    $_SESSION['user'] = null;
}
?>

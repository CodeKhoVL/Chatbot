<?php
if (isset($_COOKIE['session_token']) && $_COOKIE['session_token'] != '') {
    $check_ss = $duogxaolin->get_row("SELECT * FROM `user_devices` WHERE `token` = '" . $_COOKIE['session_token'] . "' AND `status` = 1 ");
    if (!$check_ss) {
        setcookie('session_token', '', time() - 3600, '/');
        session_start();
        session_destroy();
        if ($duogxaolin->home_uri() != '/') {
            header('location: /');
            die();
        }
    } else {
        $check_usn = $duogxaolin->get_row("SELECT * FROM users WHERE id = '" . $check_ss['user_id'] . "' AND `banned` = 0 ");
        if (!$check_usn) {
            $set =  $duogxaolin->update("user_devices", [
                'status' => 0
            ], "token = '" . $_COOKIE['session_token'] . "' ");
            setcookie('session_token', '', time() - 3600, '/');
            session_start();
            session_destroy();
            if ($duogxaolin->home_uri() != '/') {
            header('location: /');
            die();
        }
        } else {
            // if(password_verify($check_ss['password'], $check_ss['password']))
            $devices = Get_devices();
            // if($check_ss['device_id'] == $devices['device_id']){
            $_SESSION["username"] = $check_usn['username'];
            $duogxaolin->update("user_devices", [
                'update_date'       => time()
            ], " `token` = '" . $_COOKIE['session_token'] . "' ");
            $duogxaolin->update("users", [
                'update_date'       => time(),
            ], " `username` = '" . $check_usn['username'] . "' ");

            //  }

            $auth = $duogxaolin->auth();
            $getUser = $auth;
            $session = $duogxaolin->get_row("SELECT * FROM `user_devices` WHERE `token` = '" . $_COOKIE['session_token'] . "' AND `status` = 1 ");
        }
    }
} else {
    if (isset($_SESSION['username'])) {
        session_start();
        session_destroy();
        if ($duogxaolin->home_uri() != '/') {
            header('location: /');
            die();
        }
    }
}

/*
foreach ($duogxaolin->get_list(" SELECT * FROM `bill` WHERE  `status` = 0 ") as $row) {
    if (time() - $row['time'] > '7200') {
        $duogxaolin->update("bill", [
            'status'     => 1
        ], " `id` = '" . $row['id'] . "' ");
    }
} */

function checklogin()
{
    global $duogxaolin;
    if (!isset($_SESSION['username'])) {
        if ($duogxaolin->home_uri() != '/customer/login') {
            header('location:' . $duogxaolin->home_url() . '/customer/login');
            die();
        }
    }
}

function nonlogin()
{
    global $duogxaolin;
    if (isset($_SESSION['username'])) {
        if ($duogxaolin->home_uri() != '/') {
            header('location:' . $duogxaolin->home_url() . '/customer/login');
            die();
        }
    }
}
/*
if (isset($_SESSION['username'])) {
    if ($duogxaolin->home_uri() != '/checkpoint/mail') {
        if ($auth['verify_email'] == 0) {
            if ($duogxaolin->home_uri() != '/api/auth/verify') {
                if ($duogxaolin->home_uri() != '/api/check-profile') {
                header('location:' . $duogxaolin->home_url() . '/checkpoint/mail');
            }
            }
        }
    }
    if ($auth['verify_email'] == 1) {
        if ($session['verify'] == 0) {
            if ($auth['protect'] == 1) {
                if ($duogxaolin->home_uri() != '/checkpoint') {
                    if ($duogxaolin->home_uri() != '/api/auth/verify') {
                          if ($duogxaolin->home_uri() != '/api/check-profile') {
                        header('location:' . $duogxaolin->home_url() . '/checkpoint');
                    }
                    }
                }
            }
        }
    }
} */

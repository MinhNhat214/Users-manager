
<?php
    if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
        die('Access denied ...');
    }

    if(isLogin()){
        $token = getSession('tokenlogin');
        delete('tokenlogin', "token='$token'");
        removeSession('tokenlogin');
        redirect('?module=auth&action=login');
    }
?>

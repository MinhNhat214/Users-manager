<?php
session_start(); //khoi tao sesion cho project
require_once('config.php');
require_once('includes/connect.php');
// thu vien phpmailer
require_once('phpmailer/Exception.php');
require_once('phpmailer/PHPMailer.php');
require_once('phpmailer/SMTP.php');

require_once('includes/funtions.php');
require_once('includes/database.php');
require_once('includes/session.php');
    
    // echo '<i class="fa-solid fa-shield-halved"></i>';

    // $session_test = setSession('tendangnhap_nhat','gia tri cua session tendangnhap_nhat');
    // setFlashData('msg','cai dat thanh cong');
    // echo getFlashData('msg');
    // var_dump($session_test);
    // sendMail('nhattran200411@gmail.com', 'Dang ky tai khoan','Noi dung cua email');

    ////////////////////////////////////////////////////////////////////////////////////////////////
    $module =_MODULE;
    $action =_ACTION;

    if(!empty($_GET['module'])){
        if(is_string($_GET['module'])){
            $module = trim($_GET['module']);
        }
    }

    if(!empty($_GET['action'])){
        if(is_string($_GET['action'])){
            $action = trim($_GET['action']);
        }
    }


    $path = 'modules/' .$module. '/' .$action. '.php';
    
    if(file_exists($path)){
        require_once($path);
    }
    else{
        require_once('modules/error/404.php');
    }

?>
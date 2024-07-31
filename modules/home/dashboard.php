
<?php
    if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
        die('Access denied ...');
    }
    $data = [
        'pageTitle' => 'Trang Dashboard'
    ];
    layouts('header',$data);
    
    //Kiểm tra trạng thái đăng nhập
    if(!isLogin()){
        redirect('?module=auth&action=login');
    }

    // echo getSession('tokenlogin');
    if (isPost()) {
        redirect('?module=users&action=list');
    }
?>
<p class="text-primary">DASHBOARD</p>
<form action="" method="post">
    <button type="submit" class="btn btn-warning mg-btn">Quản lý người dùng</button>
</form>
<?php
    layouts('footer');
?>
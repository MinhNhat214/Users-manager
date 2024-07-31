<?php
if (!defined('_CODE')) {  //kiem tra bien code co ton tai hay khong
    die('Access denied ...');
}

$data = [
    'pageTitle' => 'Trang đăng nhập'
];

layouts('header-auth', $data);

//Kiểm tra trạng thái truy cập
if (isLogin()) {
    redirect('?module=home&action=dashboard');
}



if (isPost()) {
    $filterAll = filter();
    //Kiem tra dang nhap
    if (!empty(trim($filterAll['email'])) && !empty(trim($filterAll['password']))) {
        $email = $filterAll['email'];
        $password = $filterAll['password'];

        //Truy vấn lấy thông tin users theo email
        $userQuery = oneRaw("SELECT password, id FROM user WHERE email = '$email' ");


        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            if (password_verify($password, $passwordHash)) {
                //Trong bảng loginToken sẽ chứa token làm primary key
                //Khi người dùng đăng nhập vào hệ thống thì sẽ có 1 Token được sinh ra
                //và Insert vào bảng loginToken
                //Khi người dùng đăng xuất thì sẽ xóa token đi

                //Kiểm tra xem tài khoản đã đăng nhập chưa
                $userLogin = getRows('SELECT * FROM tokenlogin WHERE user_id =' . $userId);
                if ($userLogin > 0) {
                    setFlashData('msg', 'Không thể đăng nhập vui lòng thử lại sau.');
                    setFlashData('msg_type', 'danger');
                    redirect("?module=auth&action=login");
                } else {
                    //Tạo token login
                    $tokenLogin = sha1(uniqid() . time());

                    //Insert vào bảng LoginToken
                    $dataInsert = [
                        'user_Id' => $userId,
                        'token' => $tokenLogin,
                        'create_at' => date('Y-m-d H:i:s')
                    ];

                    $insertStatus = insert('tokenlogin', $dataInsert);

                    // echo $insertStatus;
                    // die();
                    //Nếu Insert thành công
                    if ($insertStatus) {

                        //Lưu cái loginToken vào session để kiểm tra
                        //xem người dùng có đang đăng nhập hay không
                        setSession('tokenlogin', $tokenLogin);

                        redirect('?module=home&action=dashboard');
                    } else {
                        setFlashData('msg', 'Không thể đang nhập vui lòng thử lại sau!!');
                        setFlashData('msg_type', 'danger ');
                    }
                }
            } else {
                setFlashData('msg', 'Mật khẩu không chính xác');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Email chưa được đăng ký!!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=login');
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');



?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <form action="" method="post">
            <h2 class="text-center"> Đăng nhập quản lý User</h2>
            <?php
            if (!empty($msg)) {
                getMsg($msg, $msg_type);
            }
            ?>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" name="" id="" class="form-control" placeholder="Địa chỉ email">
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input name="password" type="password" name="" id="" class="form-control" placeholder="Mật khẩu">
            </div>
            <button type="submit" class="btn-primary btn btn-block mg-form mg-btn"> Đăng nhập </button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>


<?php
layouts('footer-auth');

?>
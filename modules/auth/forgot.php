<?php
if (!defined('_CODE')) { //kiem tra bien code co ton tai hay khong
    die('Access denied ...');
}

$data = [
    'pageTitle' => 'Quên mật khẩu'
];

layouts('header-auth', $data);
//Kiểm tra trạng thái truy cập
if (isLogin()) {
    redirect('?module=home&action=dashboard');
}

if (isPost()) {
    $filterAll = filter();
    if (!empty($filterAll['email'])) {
        $email = $filterAll['email'];
        //Querry user để kiểm tra email có tồn tại trong db hay không
        $userQuerry = oneRaw("SELECT id FROM user WHERE email = '$email'");
        echo '<pre>';
        print_r($userQuerry);
        echo '</pre>';

        // die();
        if (!empty($userQuerry)) {
            //Phải dùng biến để lưu kết quả truy vấn
            $userId = $userQuerry['id'];
            //Tạo forgot token
            $forgotToken = sha1(uniqid() . time());

            //tạo giá trị mới lưu trong biến $dataUpdate để thay thế giá trị cũ
            $dataUpdate = [
                'forgotToken' => $forgotToken
            ];

            $updateStatus = update('user', $dataUpdate, "id='$userId'");

            if ($updateStatus) {
                //tạo link đến trang khôi pục mật khẩu
                $linkReset = _WEB_HOST . '?module=auth&action=reset&token=' . $forgotToken;

                //Gửi mail
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào ' . $filterAll['fullname'] . '<br>';
                $content .= 'Vui lòng click vào link dưới đây để khôi phục mật khẩu: <br>';
                $content .= $linkReset . '<br>';
                $content .= 'Trân trọng cảm ơn!!';

                $sendmail = sendMail($email, $subject, $content);

                if ($sendmail) {
                    setFlashData('msg', 'Vui lòng kiểm tra email để đặt lại mật khẩu!');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'Lỗi hệ thống vui lòng thử lại sau!()');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Lỗi hệ thống vui lòng thử lại sau!');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'Địa chỉ email không tồn tại trong hệ thống!');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng nhập địa chỉ email');
        setFlashData('msg_type', 'danger');
    }

    redirect('?module=auth&action=forgot');
}

$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <form action="" method="post">
            <h2 class="text-center">Quên mật khẩu</h2>
            <?php
            if (!empty($msg)) {
                getMsg($msg, $msg_type);
            }
            ?>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input name="email" type="email" name="" id="" class="form-control" placeholder="Địa chỉ email">
            </div>
            <button type="submit" class="btn-primary btn btn-block mg-form mg-btn">Gửi</button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng ký tài khoản</a></p>
        </form>
    </div>
</div>

<?php
layouts('footer-auth');

?>
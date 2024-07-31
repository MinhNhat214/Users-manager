<?php
if (!defined('_CODE')) { //kiem tra bien code co ton tai hay khong
    die('Access denied ...');
}

$token = filter()['token'];

$data = [
    'titlePage' => 'Đặt lại mật khẩu'
];

layouts('header', $data);



if (!empty($token)) {
    //Truy vấn database kiểm tra token
    $tokenQuerry = oneRaw("SELECT id, fullname, email FROM user WHERE forgotToken ='$token'");
    if (!empty($tokenQuerry)) {
        $userId = $tokenQuerry['id'];
        if (isPost()) {
            $filterAll = filter();
            $errors = [];

            //Validate password: bắt buộc phải nhập, >= 8 ký tự
            if (empty($filterAll['password'])) {
                $errors['password']['require'] = 'Mật khẩu bắt buộc phải nhập.';
            } else {
                if (strlen($filterAll['password']) < 8) {
                    $errors['password']['min'] = 'Mật khẩu lớn hơn hoặc bằng 8 ký tự.';
                }
            }
            //Validate password_confirm: bắt buộc phải nhập, giống password
            if (empty($filterAll['password_confirm'])) {
                $errors['password_confirm']['require'] = 'Bạn phải nhập Lại mật khẩu.';
            } else {
                if ($filterAll['password'] != $filterAll['password_confirm']) {
                    $errors['password_confirm']['match'] = 'Mật khẩu bạn nhập lại không đúng.';
                }
            }
            // input khong co loi
            if (empty($errors)) {

                // Xử lý update mật khẩu
                $passwordHash = password_hash($filterAll['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forgotToken' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];

                $updateStatus = update('user', $dataUpdate, "id='$userId'");

                if($updateStatus){
                    setFlashData('msg', 'Thay đổi mật khẩu thành công!');
                    setFlashData('msg_type', 'success');
                    redirect('?module=auth&action=login');
                }else{
                    setFlashData('msg', 'Lỗi hệ thống, vui lòng thử lại sau!');
                    setFlashData('msg_type', 'success');
                }
            
            } else {
                setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu!');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $errors);
                redirect('?module=auth&action=reset&token='.$token);
            }
        }
    $msg = getFlashData('msg');
    $msg_type = getFlashData ('msg_type');
    $errors = getFlashData ('errors');
    ?>
    <!-- form dat lai mat khau -->
        <div class="row">
            <div class="col-4" style="margin: 50px auto;">
                <h2 class="text-center">Đặt lại mật khẩu</h2>
                <?php
                if (!empty($smg)) {
                    getMsg($smg, $smg_type);
                }
                ?>
                <form action="" method="post">
                    <div class="form-group mg-form">
                        <label for="">Password</label>
                        <input type="password" name="password" id="" class="form-control" placeholder="Mật khẩu">
                        <?php
                        echo (form_errors('password', $errors));
                        ?>
                    </div>

                    <input type="hidden" name="token" value="<?php echo $token; ?>">

                    <div class="form-group mg-form">
                        <label for="">Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirm" id="" class="form-control" placeholder="Nhập lại mật khẩu">
                        <?php
                        echo (form_errors('password_confirm', $errors));
                        ?>
                    </div>
                    <button type="submit" class="btn-primary btn btn-block mg-form mg-btn"> Xác nhận </button>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=login">Đăng nhập tài khoản</a></p>
                </form>
            </div>
        </div>

<?php
}else{
        getMsg('liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
}else{
    getMsg('liên kết không tồn tại hoặc đã hết hạn', 'danger');
}

layouts('footer', $data);
?>


<h1>RESET</h1>
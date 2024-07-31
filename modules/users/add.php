<!-- Viet cac ham chung cua project -->
<?php
if (!defined('_CODE')) { //kiem tra bien code co ton tai hay khong
    die('Access denied ...');
}
$data = [
    'pageTitle' => 'Thêm người dùng'
];

layouts('header', $data);


if (isPost()) {
    $filterAll = filter();
    $errors = []; //Mảng chứa các lỗi
    //Validate fullname: bắt buộc phải nhập || min là 5 ký tự
    //Kiểm tra trường fullname (input) có rỗng hay không, nếu trường fullname rỗng,
    //thì thêm một thông báo lỗi vào mảng errors
    if (empty($filterAll['fullname'])) {
        $errors['fullname']['require'] = 'Họ tên bắt buộc phải nhập';
    } else {
        if (strlen($filterAll['fullname']) < 5) {
            $errors['fullname']['min'] = 'Họ tên phải có ít nhất 5 ký tự';
        }
    }
    //Validate email: bắt buộc phải nhập || có đúng định dạng hay không
    // || kiểm tra tồn tại trong csdl
    if (empty($filterAll['email'])) {
        $errors['email']['require'] = 'Email bắt buộc phải nhập';
    } else {
        $email = $filterAll['email'];
        $sql = "SELECT id FROM user WHERE email = '$email'";
        if (getRows($sql) > 0) {
            $errors['email']['unique'] = 'Email đã tồn tại.';
        }
    }
    //Validate sdt: bắt buộc phải nhập || đúng định dạng không 
    if (empty($filterAll['phone'])) {
        $errors['phone']['require'] = 'Số điện thoại bắt buộc phải nhập';
    } else {
        if (!isPhone($filterAll['phone'])) {
            $errors['phone']['isPhone'] = 'Số điện thoại không hợp lệ.';
        }
    }

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
        $dataInsert = [
            'fullname' =>  $filterAll['fullname'],
            'email' => $filterAll['email'],
            'phone' => $filterAll['phone'],
            'password' => password_hash($filterAll['password'], PASSWORD_DEFAULT),
            'status' => $filterAll['status'],
            'create_at' => date('Y-m-d H-i-s')
        ];

        $insertStatus = insert('user', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm mới thành công!');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang lỗi, vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
        redirect("?module=users&action=add");
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra lại dữ liệu!');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors);
        setFlashData('old', $filterAll);
        redirect('?module=users&action=add');
    }
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

// echo '<pre>';
// print_r ($errors);
// echo '</pre>';

// echo '<pre>';
// print_r ($old);
// echo '</pre>';
?>

<div class="container">
    <div class="row" style="margin: 50px auto;">
        <h2 class="text-center">Thêm tài khoản Users</h2>
        <?php
        if (!empty($msg)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Họ tên</label>
                        <input type="" name="fullname" id="" class="form-control" placeholder="Họ tên"value="<?php
                                                                                                                // echo (!empty($old['fullname'])) ? $old['fullname'] : null;
                                                                                                                echo oldData('fullname', $old);
                                                                                                                ?>">
                        <?php
                        // echo (!empty($errors['fullname'])) ?
                        //     '<span class="errors">'
                        //     .reset($errors['fullname']).'</span>' : null;
                        echo (form_errors('fullname', $errors));
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Email</label>
                        <input type="text" name="email" id="" class="form-control" placeholder="Địa chỉ email" value="<?php
                                                                                                                        echo oldData('email', $old);
                                                                                                                        ?>">
                        <?php
                        echo (form_errors('email', $errors));
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Số điện thoại</label>
                        <input type="number" name="phone" id="" class="form-control" placeholder="Số điện thoại" value="<?php
                                                                                                                        echo oldData('phone', $old);
                                                                                                                        ?>">
                        <?php
                        echo (form_errors('phone', $errors));
                        ?>
                    </div>
                    
                    <button type="submit" class="btn-primary btn btn-block mg-form mg-btn">Thêm người dùng</button>
                    <a href="?module=users&action=list" class="btn-warning btn btn-block mg-form mg-btn">Quay lại</a>
                </div>

                <div class="col">
                    <div class="form-group mg-form">
                        <label for="">Password</label>
                        <input type="password" name="password" id="" class="form-control" placeholder="Mật khẩu">
                        <?php
                        echo (form_errors('password', $errors));
                        ?>
                    </div>
                    <div class="form-group mg-form">
                        <label for="">Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirm" id="" class="form-control" placeholder="Nhập lại mật khẩu">
                        <?php
                        echo (form_errors('password_confirm', $errors));
                        ?>
                    </div>
                    <div class="form-control">
                        <label for="">Trạng thái</label>
                        <select name="status" id="" class="form-select">
                            <option value="0" <?php echo( oldData('status',$old) == 0) ? 'selected' : false; ?>>Chưa kích hoạt</option>
                            <option value="1" <?php echo( oldData('status',$old) == 1) ? 'selected' : false; ?>>Đã kích hoạt</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<h1>Trang ADD</h1>
<?php
layouts("footer");
?>
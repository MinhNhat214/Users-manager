<!-- Viet cac ham chung cua project -->
<?php
    if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
        die('Access denied ...');
    }
    $data = [
        'pageTitle' => 'Trang đăng ký'
    ];
    layouts('header-auth',$data);


    if(isPost()){
        $filterAll = filter();
        $errors = []; //Mảng chứa các lỗi
        //Validate fullname: bắt buộc phải nhập || min là 5 ký tự
        //Kiểm tra trường fullname (input) có rỗng hay không, nếu trường fullname rỗng,
        //thì thêm một thông báo lỗi vào mảng errors
        if(empty($filterAll['fullname'])){
            $errors['fullname']['require'] = 'Họ tên bắt buộc phải nhập';
        }
        else{
            if(strlen($filterAll['fullname'])<5){
                $errors['fullname']['min'] = 'Họ tên phải có ít nhất 5 ký tự';
            }
        }
        //Validate email: bắt buộc phải nhập || có đúng định dạng hay không
        // || kiểm tra tồn tại trong csdl
        if(empty($filterAll['email'])){
            $errors['email']['require'] = 'Email bắt buộc phải nhập';
        }
        else{
            $email = $filterAll['email'];
            $sql = "SELECT id FROM user WHERE email = '$email'";
            if(getRows($sql) > 0){
                $errors['email']['unique'] = 'Email đã tồn tại.';
            }
        }
        //Validate sdt: bắt buộc phải nhập || đúng định dạng không 
        if(empty($filterAll['phone'])){
            $errors['phone']['require'] = 'Số điện thoại bắt buộc phải nhập';
        }
        else{
            if(!isPhone($filterAll['phone'])){
                $errors['phone']['isPhone'] = 'Số điện thoại không hợp lệ.';
            }
        }

        //Validate password: bắt buộc phải nhập, >= 8 ký tự
        if(empty($filterAll['password'])){
            $errors['password']['require'] = 'Mật khẩu bắt buộc phải nhập.';
        }
        else{
            if(strlen($filterAll['password'])<8){
                $errors['password']['min'] = 'Mật khẩu lớn hơn hoặc bằng 8 ký tự.';
            }
        }
        //Validate password_confirm: bắt buộc phải nhập, giống password
        if(empty($filterAll['password_confirm'])){
            $errors['password_confirm']['require'] = 'Bạn phải nhập Lại mật khẩu.';
        }
        else{
            if($filterAll['password'] != $filterAll['password_confirm']){
                $errors['password_confirm']['match'] = 'Mật khẩu bạn nhập lại không đúng.';
            }
        }
        // input khong co loi
        if(empty($errors)){
            $activeToken = sha1(uniqid().time());
            // echo $$activeToken .'<br>';
            // var_dump ($activeToken);

            $dataInsert = [
                'fullname' =>  $filterAll['fullname'],
                'email' => $filterAll['email'],
                'phone' => $filterAll['phone'],
                'password' => password_hash($filterAll['password'],PASSWORD_DEFAULT),
                'activeToken' => $activeToken,
                'create_at' => date('Y-m-d H-i-s')
            ];

            $insertStatus = insert('user',$dataInsert);
            if($insertStatus){
                setFlashData('msg','Đăng ký thành công!');
                setFlashData('smg_type','success');

                //Tạo đường link kích hoạt
                $linkActive = _WEB_HOST .'?module=auth&action=active&token=' .$activeToken;
                // echo $linkActive .'<br>';

                //Thiết lập gửi mail
                $subject = $filterAll['fullname']. ' Vui lòng kích hoạt tài khoản!!';
                $content = 'Chào ' .$filterAll['fullname'] .'<br>';
                $content .= 'Vui lòng click vào link dưới đây để kích hoạt tài khoản: <br>';
                $content .= $linkActive .'<br>';
                $content .= 'Trân trọng cảm ơn!!';
                
                //Thực thi gửi mail
                $sendMail = sendMail($filterAll['email'], $subject, $content);
                // echo $sendMail .'<br>';
                // var_dump ($sendMail);
                // die();
                if($sendMail){
                    setFlashData('msg','Vui lòng kiểm tra email để kích hoạt tài khoản!');
                    setFlashData('msg_type','success');
                }
                else{
                    setFlashData('msg','Hệ thống đang gặp sự cố, vui lòng thử lại sau!');
                    setFlashData('msg_type','danger');
                }

            }
            else{
                setFlashData('msg','Đăng ký không thành công!!');
                setFlashData('msg_type','danger');
            }
            
        }
        else {
            setFlashData('msg','Vui lòng kiểm tra lại dữ liệu!');
            setFlashData('msg_type','danger');
            setFlashData('errors',$errors);
            setFlashData('old',$filterAll);
            // redirect('?module=auth&action=register');
        }
    }
    $msg = getFlashData('msg');
    $msg_type = getFlashData ('msg_type');
    $errors = getFlashData ('errors');
    $old = getFlashData ('old');

    // echo '<pre>';
    // print_r ($errors);
    // echo '</pre>';

    // echo '<pre>';
    // print_r ($old);
    // echo '</pre>';
?>
<div class="row">
    <div class="col-4" style="margin: 50px auto;">
        <h2 class="text-center">Đăng ký tài khoản User</h2>
        <?php
            if(!empty($msg)){
                getMsg($msg,$msg_type);
            }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label for="">Họ tên</label>
                <input type="" name="fullname" id="" class="form-control" placeholder="Họ tên"
                value="<?php
                    // echo (!empty($old['fullname'])) ? $old['fullname'] : null;
                    echo oldData('fullname',$old);
                ?>">
                <?php
                    // echo (!empty($errors['fullname'])) ?
                    //     '<span class="errors">'
                    //     .reset($errors['fullname']).'</span>' : null;
                    echo (form_errors('fullname',$errors));
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Email</label>
                <input type="text" name="email" id="" class="form-control" placeholder="Địa chỉ email" 
                value="<?php
                    echo oldData('email',$old);
                ?>">
                <?php
                    echo (form_errors('email',$errors));
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Số điện thoại</label>
                <input type="number" name="phone" id="" class="form-control" placeholder="Số điện thoại"
                value="<?php
                    echo oldData('phone',$old);
                ?>">
                <?php
                    echo (form_errors('phone',$errors));
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Password</label>
                <input type="password" name="password" id="" class="form-control" placeholder="Mật khẩu" >
                <?php
                    echo (form_errors('password',$errors));
                ?>
            </div>
            <div class="form-group mg-form">
                <label for="">Nhập lại mật khẩu</label>
                <input type="password" name="password_confirm" id="" class="form-control" placeholder="Nhập lại mật khẩu">
                <?php
                    echo (form_errors('password_confirm',$errors));
                ?>
            </div>
            <button type="submit" class="btn-primary btn btn-block mg-form mg-btn"> Đăng ký </button>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập tài khoản</a></p>
        </form>
    </div>
</div>


<?php
    layouts('footer');  
?>
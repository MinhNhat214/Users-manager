<?php
    if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
        die('Access denied ...');
    }
    $data = [
        'pageTitle' => 'Trang đăng ký tài khoản'
    ];
    layouts('header',$data);
    //Truy vấn để kiểm tra token với database
    $token = filter()['token'];
    if(!empty($token)){
        $tokenQuery = oneRaw("SELECT id FROM user WHERE activeToken = '$token'");
        if(!empty($tokenQuery)){
            $userId = $tokenQuery['id'];
            $dataUpdate = [
                'status' => 1,
                //cho active token = 0 sau khi kích hoạt tìa khoản để vô hiệu hóa link
                'activeToken' => NULL
            ];

            $updateStatus = update('user', $dataUpdate, "id='$userId'");

            if($updateStatus){
                setFlashData('msg','Kích hoạt tài khoản thành công!!');
                setFlashData('msg_type','success');
            }
            else{
                setFlashData('msg','Kích hoạt tài khoản không thành công, vui lòng liên hệ quản trị viên.');
                setFlashData('msg_type','danger');
            } 
            redirect('?module=auth&action=login');
        }
        else{
            getMsg('Liên kết không tồn tại hoặc đã hết hạn!','danger');
        }
    }
    else{
        getMsg('Liên kết không tồn tại hoặc đã hết hạn!','danger');
    }
?>
<h1>ACTIVE</h1>

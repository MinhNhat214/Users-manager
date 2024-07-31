<!-- Viet cac ham chung cua project -->
<?php
    if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
        die('Access denied ...');
    }
    //Kiểm tra id có trong database -> tồn tại -> tiến hành xóa
    //Xóa dữ liệu tokenlogin trước, sau đó xóa dữ liệu bảng user
    $fillterAll = filter();
    if(!empty($fillterAll['id'])){
        $userId = $fillterAll['id'];
        $usersDetail = getRows('SELECT * FROM user WHERE id = '.$userId);
        if($usersDetail > 0){
            //Thực thi xóa
            $deleteToken = delete("tokenlogin","user_id=".$userId);
            if($deleteToken){
                //Xóa users
                $deleteUser = delete("user","id=".$userId);
                if($delete){
                    setFlashData('smg','Xóa người dùng thành công.');
                    setFlashData('smg_type','success');
                }else{
                    setFlashData('smg','Lỗi hệ thống');
                    setFlashData('smg_type','danger');
                }
            }
        }else{
            setFlashData("msg", "Người dùng không tồn tại trong hệ thống.");
            setFlashData("msg_type", "danger");
        }
    }else{
        setFlashData("msg", "Liên kết không tồn tại");
        setFlashData("msg_type","danger");
    }

    redirect("?module=users&action=list");
?>

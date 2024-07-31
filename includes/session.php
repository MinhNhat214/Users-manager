<!-- ham lien quan den session hay cookie -->
<!-- Viet cac ham chung cua project -->
<?php
    if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
        die('Access denied ...');
    }

// session là cách để lưu trữ thông tin phiên làm việc của người dùng trên máy chủ

// Phiên (session) là một khoảng thời gian từ khi người dùng truy cập vào
// một trang web đến khi họ rời khỏi trang đó

// Các biến session có thể chứa thông tin như thông tin đăng nhập,
// giỏ hàng của người dùng, các cài đặt cá nhân, vv.

//Hàm gán session
function setSession($key, $value){
    return $_SESSION[$key] = $value ;
}

//Hàm đọc session
function getSession($key=''){
    //nếu $key rỗng
    if(empty ($key)){
        return $_SESSION;
    }
    else{
        //kiểm tra nếu $_SESSION[$key] được khởi tạo
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }
}

//Hàm xóa session
function removeSession($key = ''){
    if(empty ($key)){
        session_destroy();
        return true;
    }
    else{
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
            return true;
        }
    }
}
//Hàm gán flash data
//[Kỹ thuật] thường được sử dụng trong lập trình web
//để [truyền thông tin] tạm thời giữa các [request], thường được
//sử dụng để [hiển thị thông báo] hoặc [cảnh báo] cho người
//dùng [sau một hành động nhất định],
//chẳng hạn như sau khi họ [gửi một biểu mẫu].

function setFlashData($key, $value){
    $key = 'flash_'.$key ;
    return setSession($key, $value);
}
function getFlashData($key){
    $key = 'flash_'.$key ;
    $data = getSession($key);
    removeSession($key);
    return $data;
}
?>





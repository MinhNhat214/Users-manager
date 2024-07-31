<!-- Viet cac ham chung cua project -->
<?php

    // require_once('../config.php');

    // if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
    //     die('Access denied ...');
    // }

    try{
        if(class_exists('PDO')){
            // set DSN : chuỗi ký tự được dùng để xác định vị trí và cấu hình cảu nguồn dữ liệu (database)
            $dsn = 'mysql:dbname='._DB.';host='._HOST;

            $options = [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', //ho tro tieng viet
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //tao thong bao ngoai le khi gap loi
            ];
            // create a PDO in stance : đối tượng PDO bởi lớp PDO để kết nối và thao tác với CSDL
            // (cầu nối giữa PHP và hệ thống quản trị database)
            $conn = new PDO($dsn,_USER,_PASS,$options);
            
            // if($conn){
            //     echo'Kết nối thành công';
            // }
            // else{
            //     echo 'Kết nối không thành công';
            // }
            // var_dump($conn);
        }
    }
    catch(Exception $exception){
        echo $exception->getMessage().'<br>';
        die();
    }
?>

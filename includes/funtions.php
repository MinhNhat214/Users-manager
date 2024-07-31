<!-- Viet cac ham chung cua project -->
<?php
    if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
        die('Access denied ...');
    }
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function layouts($layoutName='header', $data=[]){
        if(file_exists(_WEB_PATH_TEMPLATES . '/layout/' . $layoutName .'.php' )){
            require_once _WEB_PATH_TEMPLATES . '/layout/' . $layoutName .'.php';
        }
    }

function sendMail($to,$subject,$content){
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'nhattran200411@gmail.com';                     //SMTP username
    $mail->Password   = 'vhkdppknhlrynhqh';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('nhattran200411@gmail.com', 'MinhNhat');
    $mail->addAddress($to);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $content;
    $mail-> CharSet = "UTF-8";

    //PHPMailer SSL certificare verify failed
    $mail -> SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $sendMail = $mail->send();
    if($sendMail){
        return $sendMail;
    }
    // echo 'Gửi thành công !!';
} 
catch (Exception $e) {
    echo "Gửi mail không thành công!! Lỗi: {$mail->ErrorInfo}";
}
}

    //Kiểm tra phương thức get
    function isGet(){
        if($_SERVER['REQUEST_METHOD']=='GET'){
            return true;
        }
        return false;
    }
    function isPost(){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            return true;
        }
        return false;
    }
    //Hàm filter lọc dữ liệu
    function filter(){
        $filterArr =[];
        if(isGet()){
            // Xử lý dữ liệu trước khi hiển thị ra
            // sử dụng để truyền dữ liệu từ trang web này sang trang web khác qua URL
            // dưới dạng cặp key-value, được phân cách bởi dấu &
            if(!empty($_GET)){
                foreach($_GET as $key => $value){
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_input(INPUT_GET,$key, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                    }
                    else{
                        $filterArr[$key] = filter_input(INPUT_GET,$key, FILTER_SANITIZE_SPECIAL_CHARS);                    
                    }
                }
            }
            return $filterArr;
        }
        if(isPost()){
            // sử dụng để truyền dữ liệu từ trang web này sang trang web khác
            // Dữ liệu được truyền trong body của request HTTP, không hiển thị trong URL.
            if(!empty($_POST)){
                foreach($_POST as $key => $value){
                    $key = strip_tags($key);
                    if(is_array($value)){
                        $filterArr[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                    }
                    else{
                        $filterArr[$key] = filter_input(INPUT_POST,$key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
            return $filterArr;
        }
    }
// Kiểm tra email
function isEmail ($email){
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}
// Kiểm tra số nguyên
function isNumberInt ($number){
    $checkNumber = filter_var($number,FILTER_VALIDATE_INT);
    return $checkNumber;
}
// Kiểm tra số thực
function isNumberFloat ($number){
    $checkNumber = filter_var($number,FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}
// Hàm kiểm tra số điện thoại
function isPhone ($phone){
    $checkZero = false;
    //điều kiện 1: ký tự đầu tiên là số 0
    if($phone[0] == '0'){
        $checkZero = true;
        $phone = substr($phone,1);
    }
    //9 số đằng sau số 0
    $checkNumber = false;
    if(isNumberInt($phone) && (strlen($phone)==9)){
        $checkNumber = true;
    }
    if($checkZero && $checkNumber == true){
        return true;
    }
    return false;
}
// Thong bao loi
function getMsg($msg, $type = 'success'){
    echo '<div class ="alert alert-'.$type.'">';
    echo $msg;
    echo '</div>';
}
//ham chuyen huong
function redirect($path = 'index.php'){
    // một trong những cách tiêu biểu để thực hiện chuyển hướng trong HTTP.
    // Nó không phải là một phương thức được định nghĩa trong PHP
    // mà là một phương thức thuộc giao thức HTTP.
    header("Location: $path");
    exit;
}
//Hàm thông báo lỗi
// function form_errors($fileName, $beforeHtml='',$afterHtml='',$errors){
//     return (!empty($errors['fullname'])) ? '<span class="errors">' .reset($errors['fullname']).'</span>' : null;
// }
//Hàm thông báo lỗi
function form_errors($fieldName, $errors = []) {
    return (!empty($errors[$fieldName])) ? '<span class="errors">' .reset($errors[$fieldName]) .'</span>' : null;
}
// echo form_errors('phone', '<span class="errors">', '</span>', $errors);
//Hàm hiển thị dữ liệu cũ
function oldData($fieldname, $olddata, $default = null){
    return (!empty($olddata[$fieldname])) ? $olddata[$fieldname] : $default;
}
//Hàm kiểm tra trạng thái đăng nhập
function isLogin (){
    $checkLogin = false;
    if(getSession('tokenlogin')){
        $tokenLogin = getSession('tokenlogin');
        
        // Kiểm tra xem token có giống trong db không
        $queryToken = oneRaw("SELECT user_Id from tokenlogin where token = '$tokenLogin'");
        if(!empty($queryToken)){
            $checkLogin = true;
        }else{
            removeSession('tokenlogin');
        }
    }
    return $checkLogin;
}
?>

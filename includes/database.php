<!-- Cac ham lien quan den CSDL -->
<?php
    if(!defined('_CODE')){ //kiem tra bien code co ton tai hay khong
        die('Access denied ...');
    }
    // chức năng thực thi các truy vấn SQL trên cơ sở dữ liệu bằng cách sử dụng PDO
    // $sql: Tham số này chứa câu truy vấn SQL mà bạn muốn thực thi.
    // $data: Tham số này là một mảng dữ liệu tùy chọn chứa các giá trị được sử dụng trong câu truy vấn
    
    // PDO Query // PHP Data Objects
    function query ($sql, $data=[], $check=false) {
        global $conn;  //tham chiếu đến kết nối đến cơ sở dữ liệu được lưu trong biến conn 
        $ketqua = false;
        try{
            // echo $sql;
            // die();
            // sử dụng phương thức prepare() của đối tượng kết nối PDO ($conn)
            // Phương thức prepare() nhận một chuỗi SQL làm đối số và trả về một đối tượng câu lệnh PDO ($statement),
                // mà sau này bạn có thể sử dụng để thực thi truy vấn.
            // Dấu "->" truy cập thuộc tính hoặc phương thức của 1 đối tượng, tương tự dấu "." trong C#, Java
            $statement = $conn ->prepare($sql);
            if(!empty($data)){
                $ketqua = $statement -> execute($data);
            }
            else{
                $ketqua = $statement -> execute();
            }
        }
        catch(Exception $exp){
            echo $exp -> getMessage().'<br>';
            echo 'File: '.$exp -> getFile().'<br>';
            echo 'Line: '.$exp -> getLine();
            die(); 
        }
        if($check){
            return $statement;
        }
        return $ketqua;
    }
// Hàm insert
function insert ($table, $data){
    $key = array_keys($data); //lấy ra danh sách key (trường trong bảng)
    // nối các key lại với nhau
    // ví dụ: [name, age, gmail]
    $truong = implode(',',$key);
    // vì key và value giống nhau nên dùng chung biến $key
    $valuetb = ':'.implode(',:',$key);
    //Tạo chuỗi mảng được ngăn cách bởi dấu ',': vd: [name, value, key]
    //để cho việc select

    $sql = 'INSERT INTO ' .$table .'(' .$truong .')' .'VALUES(' .$valuetb .')';  
    // insert into user (ten, gmail, sdt) Values (:ten, :gmail, :sdt)
    // Gọi lại hàm query để tương tác với cơ sở dữ liệu;
    $kq = query($sql, $data);
    return $kq;
}

// Hàm update
function update ($table, $data, $condition =''){
    $update = '';
    //duyệt phần tử trong mảng $data
    //duyệt qua $key lấy key sau đó trỏ qua $value tương ứng để lấy value
    foreach ($data as $key => $value) {
        $update .= $key .'= :' .$key .',';
        //biến $update sẽ lưu cặp key và value tương ứng 
        //$update = fullname = :fullname, email = :email, phone = :phone; 
        //mỗi 1 lần lặp biến $update sẽ đại diện cho 1 cặp key value được duyệt qua
        //và mỗi lần lặp là 1 cặp key-value khác nhau
    }
    //trim() loại bỏ các ký tự không mong muốn của đầu hoặc chuỗi
    $update = trim($update,',');

    if(!empty ($condition)){
        $sql = 'UPDATE ' .$table .' SET ' .$update .' WHERE '. $condition;
    }
    else{
        $sql = 'UPDATE ' .$table .' SET ' .$update;
    }
    $kq = query($sql, $data);
    return $kq;
}

//Hàm delete
function delete ($table, $condition =''){
    if(empty($condition)){
        $sql = 'DELETE FROM ' .$table; 
    }
    else{
        $sql = 'DELETE FROM ' .$table .' WHERE ' .$condition; 
    }

    $kq = query($sql);
    return $kq;
}

//Lấy nhiều dòng dữ liệu
function getRaw($sql){
    $kq = query($sql,'',true);
    //kiểm tra xem một biến có phải là một đối tượng (object) hay không
    if(is_object($kq)){
        //lấy tất cả các hàng dữ liệu từ kết quả của một truy vấn SQL
        $dataFetch = $kq -> fetchAll(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
//Lấy 1 dòng dữ liệu
function oneRaw($sql){
    $kq = query($sql,'',true);
    if(is_object($kq)){
        // lấy một hàng dữ liệu từ kết quả của một truy vấn SQL
        $dataFetch = $kq -> fetch(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
//Đếm số dòng dữ liệu
function getRows($sql){
    $kq = query($sql,'',true);
    if(!empty($kq)){
        return $kq -> rowCount();
    }
}




<!-- Viet cac ham chung cua project -->
<?php
if (!defined('_CODE')) { //kiem tra bien code co ton tai hay khong
    die('Access denied ...');
}
$data = [
    'pageTilte' => 'Danh sách người dùng'
];
layouts('header', $data);

//Kiểm tra trạng thái đăng nhập
if (!isLogin()) {
    redirect('?module=auth&action=login');
}

//Truy vấn vào bảng user

$listUserQuerry = getRaw('SELECT * FROM user ORDER BY update_at');
// echo '<pre>';
// print_r ($listUserQuerry);
// echo '</pre>';
$msg = getFlashData('msg');
$msg_type = getFlashData ('msg_type');
$errors = getFlashData ('errors');
$old = getFlashData ('old');
?>

<div class="container">
    <h1>Quản lý người dùng</h1>
    <hr>
    <a href="?module=users&action=add" class="btn btn-sm btn-success" style="margin-bottom: 10px;">Thêm người dùng <i class="fa-solid fa-plus"></i></a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th>Sửa</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($listUserQuerry)) :
                $count = '0';
                foreach ($listUserQuerry as $item) :
                    $count++;
            ?>
                    <tr>
                        <td><?php echo $count ?></td>
                        <td><?php echo $item['fullname'] ?></td>
                        <td><?php echo $item['email'] ?></td>
                        <td><?php echo $item['phone'] ?></td>
                        <td><?php echo $item['status'] == 1 ? '<button class="btn btn-success btn-sm">Đã kích hoạt</button>'
                                : '<button class="btn btn-danger btn-sm">Chưa kích hoạt</button>' ?></td>
                        <td><a href="<?php echo _WEB_HOST?>?module=users&action=edit&id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
                        
                        <td><a href="<?php echo _WEB_HOST?>?module=users&action=delete&id=<?php echo $item['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>
                <?php
                endforeach;?>
            <?php else : ?>
                <tr>
                    <td colspan="7">Không có dữ liệu</td>
                </tr>
            <?php
            endif;
            ?>
        </tbody>
    </table>
</div>
<?php
    layouts('footer');
?>

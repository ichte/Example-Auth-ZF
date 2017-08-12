<?php
/**
 * @file createdatabase.php
 */
include "vendor/autoload.php";


// Tạo kết nối đến file sqlite database users.db, nếu file chưa có thì nó tự sinh ra.
$dbAdapter = new \Zend\Db\Adapter\Adapter([
    'driver'   => 'Pdo_Sqlite',
    'database' => 'users.db',
]);


// Câu lệnh tạo bảng users với các cột id, username ...
$sqlCreate = 'CREATE TABLE IF NOT EXISTS [users] ('
    . '[id] INTEGER  NOT NULL PRIMARY KEY, '
    . '[username] VARCHAR(50) UNIQUE NOT NULL, '
    . '[password] VARCHAR(32) NULL, '
    . '[real_name] VARCHAR(150) NULL)';

// Chạy lệnh tạo bảng
$dbAdapter->query($sqlCreate)->execute();


// Chèn dòng dữ liệu mẫ sau
$name = 'adminname';
$pass = 'admin'; //Vì core của Sqlite không có hàm MD5, với MySql bạn có thể mã hóa bằng $pass = md5('admin')
$realname = 'MY Real Nam';
$sqlInsert = "INSERT INTO users (username, password, real_name) "
    . "VALUES ('$name', '$pass','$realname')";

// Insert the data
$dbAdapter->query($sqlInsert)->execute();
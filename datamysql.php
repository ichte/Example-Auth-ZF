<?php
/**
 * @file createdatabase.php
 */
use Zend\Db\Sql\Ddl\CreateTable;
include "vendor/autoload.php";


// Tạo kết nối đến cơ sở dữ liệu MySQL: phải đảm bảo bạn kết nối đến được Server MySQL với username và password của bạn
$dbAdapter = new \Zend\Db\Adapter\Adapter([
    'driver'   => 'Pdo',
    'dsn'      => 'mysql:host=localhost',
    'username' => 'root',
    'password' => ''

]);
$dbAdapter->query('Drop database users')->execute();

/**
 * Tạo CSDL users nếu nó không tồn tại
 */
$dbAdapter->query('CREATE DATABASE IF NOT EXISTS users')->execute();

//Cho $dbAdater biết làm việc trên database tên users
$dbAdapter->query('use users')->execute();


/**
 * Để tạo dữ liệu mẫu, bạn có thể chạy các query dùng chính $dbAdapter theo cách giống Sqlite. Tuy nhiên ở ví dụ này
 * để nâng cao hơn và làm tiếp xúc sâu hơn với ZF thì sẽ dùng nhiều hơn các thư viện trợ giúp về SQL của zend-db
 */

/*@var $sql: Đối tượng dùng để thực hiện câu lệnh SQL tác động đến $dbAdapter*/
$sql = new \Zend\Db\Sql\Sql($dbAdapter);



//Tạo table user
$table = new CreateTable('users');

//Định nghĩa các cột dữ liệu
//COT ID
$table->addColumn(new \Zend\Db\Sql\Ddl\Column\Integer(
    'id',
    false,
    null,
    ['AUTO_INCREMENT'=>true]
));
$table->addConstraint(new Zend\Db\Sql\Ddl\Constraint\PrimaryKey('id'));
$table->addConstraint(new \Zend\Db\Sql\Ddl\Index\Index('id','id'));
$table->addColumn(new \Zend\Db\Sql\Ddl\Column\Char('username', 50, false));
$table->addColumn(new \Zend\Db\Sql\Ddl\Column\Char('password', 32, false));
$table->addColumn(new \Zend\Db\Sql\Ddl\Column\Char('real_name', 150, true));
//Tiến hành tạo bảng
$dbAdapter->query(
    $sql->buildSqlString($table),
    $dbAdapter::QUERY_MODE_EXECUTE
);

//Chèn dữ liệu mẫu vào bảng
$insert = new \Zend\Db\Sql\Insert('users');
$insert->columns([
    'user1'=>'username',
    md5('pass1')=> 'password' ,
    'Real Name 1' => 'real_name'
]);
$stmt = $sql->prepareStatementForSqlObject($insert);
$result = $stmt->execute();

$insert = new \Zend\Db\Sql\Insert('users');
$insert->columns([
    'user2'=>'username',
    md5('pass2')=> 'password' ,
    'Real Name 2' => 'real_name'
]);
$stmt = $sql->prepareStatementForSqlObject($insert);
$result = $stmt->execute();

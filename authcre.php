<?php
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;

include "vendor/autoload.php";

//Tạo Adapter kết nối dữ liệu Sqlite
$dbAdapter = new \Zend\Db\Adapter\Adapter([
    'driver'   => 'Pdo_Sqlite',
    'database' => 'users.db',
]);

// Khởi tạo Adapter xác thực
$authAdapter = new CredentialTreatmentAdapter(
    $dbAdapter,
    'users',
    'username',
    'password'
);


//Hoặc dùng cách sau thì cũng có được $authAdapter tương tự
/*
    $authAdapter = new CredentialTreatmentAdapter($dbAdapter);
    $authAdapter
        ->setTableName('users')
        ->setIdentityColumn('username')
        ->setCredentialColumn('password');
 */


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Lấy User, Pass khi người dùng POST lên
    $username = $_POST['username'];
    $password = $_POST['password'];
    $authAdapter
        ->setIdentity($username)
        ->setCredential($password);

    //Tiến hành xác thực
    $result = $authAdapter->authenticate();

    if ($result->isValid()) {
        echo $result->getIdentity().' : Bạn đã xác thực thành công';

        print_r($authAdapter->getResultRowObject());


    }
    else {
        echo "Xác thực thất bại : " . $result->getCode()."=>". implode($result->getMessages(), "|");
    }


}
else {
    //Tạo trang đăng nhập đơn giản
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>ĐĂNG NHẬP</title>
    </head>
    <body>
        <form method="post" action="#">
            <label> Nhập ID: <input type="text" name="username"></label>
            <label> Pass: <input type="password" name="password"></label>
            <input type="submit">
        </form>
    </body>
    </html>
    <?
}

$cols = ['username', 'real_name'];
$userinfo = $authAdapter->getResultRowObject($cols);
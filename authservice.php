<?php
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;

include "vendor/autoload.php";

$passwordValidation = function ($hash, $password) {
    $input = md5($password);
    return ($hash == $input);
};

// Tạo kết nối đến cơ sở dữ liệu MySQL: phải đảm bảo bạn kết nối đến được Server MySQL với username và password của bạn
$dbAdapter = new \Zend\Db\Adapter\Adapter([
    'driver'   => 'Pdo',
    'dsn'      => 'mysql:dbname=users; host=localhost',
    'username' => 'root',
    'password' => ''

]);

// Khởi tạo Adapter xác thực
$authAdapter = new CallbackCheckAdapter(
    $dbAdapter,
    'users',
    'username',
    'password',
    $passwordValidation
);

//Tạo ra Storage Session để lưu kết quả.
$sessionstore = new \Zend\Authentication\Storage\Session('XUANTHUSESSION');

//Khởi tạo dịch vụ xác thực
$authservice = new \Zend\Authentication\AuthenticationService($sessionstore, $authAdapter);

if ($authservice->hasIdentity()) {
    //Đã được xác thực trước rồi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $clearauth = $_POST['clearauth'];
        if ($clearauth == 'OK') {
            $authservice->clearIdentity();
            echo "ĐÃ ĐĂNG XUẤT";
            return;
        }
    }
    else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>ĐÃ ĐĂNG NHẬP</title>
        </head>
        <body>
        <?
            echo $authservice->getIdentity().' : Bạn đã xác thực rồi';
        ?>
        <form method="post" action="#">
            <label>Nhấn OK để đăng xuất <input type="submit" value="OK" name="clearauth"></label>
        </form>
        </body>
        </html>
        <?
        return;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Lấy User, Pass khi người dùng POST lên
    $username = $_POST['username'];
    $password = $_POST['password'];
    $authAdapter
        ->setIdentity($username)
        ->setCredential($password);

    //Tiến hành xác thực từ Service
    $result = $authservice->authenticate();

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
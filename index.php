<?php
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
include "vendor/autoload.php";

class MyAdapter implements AdapterInterface {

    protected $username;
    protected $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function setNewPass($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function authenticate()
    {
        if (($this->username == 'abc') & ($this->password == 'xyz')) {
            $user = [
                'user' => $this->username,
                'timelogin' => time()
            ];
            $result = new Result(Result::SUCCESS, $user);
        }
        else
            $result = new Result(Result::FAILURE, null, ['Sai ten hoac password']);


        return $result;
    }

}


//TEST1
$auth = new MyAdapter('xxx', 'yyy');
$result = $auth->authenticate();
echo 'Code : ' . $result->getCode() .'<br>';
if (!$result->isValid())
    print_r($result->getMessages());

//TEST2
$auth->setNewPass('abc', 'xyz');
$result = $auth->authenticate();
echo 'Code : ' . $result->getCode() .'<br>';
if ($result->isValid())
    print_r($result->getIdentity()).'<br>';

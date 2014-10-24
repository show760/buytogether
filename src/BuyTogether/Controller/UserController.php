<?php
namespace BuyTogether\Controller;

use Fruit\Seed;
use BuyTogether\Model\User;
use BuyTogether\Model\Img;
use BuyTogether\Model\UserImg;
use BuyTogether\Model\Buy;
use BuyTogether\Model\Join;
use BuyTogether\Library\ImgLibrary;
use Fruit\Session\PhpSession;

class UserController extends Seed
{
    public static function getAllUser()
    {
        $token = User::listAll();

        foreach ($token as $key => $value) {
            $user = User::load($value);
                
            $users[$key] = array(
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'birth' => $user->getBirth(),
                'power' => $user->getPower(),
                'address' => $user->getAddress(),
                'counties' => $user->getCounties(),
                'run' => $user->getRun(),
                'join' => $user->getJoin(),
                'main' => $user->getMain()
            );
        }
        var_dump($users);
    }

    public function view()
    {
        $session = new PhpSession;
        $token = $session->get('user');
        if ($_POST['info']) {
            $info = $_POST['info'];
            $user = User::load($token);
            $valid = $user->validatePassword($info['validpass']);
            if (!$valid) {
                $msg = array( 'status' => 2, 'string' => '請確認密碼，並再試一次', 'token' => $token);
            } else {
                $user->setName($info['name']);
                $user->setBirth($info['birth']);
                $user->setAddress($info['address']);
                $user->setCounties($info['counties']);
                if (($info['pass1'] !== '') and ($info['pass1'] === $info['pass2'])) {
                    $user->setPass($info['pass1']);
                } elseif (($info['pass1'] !== '') and ($info['pass1'] !== $info['pass2'])) {
                    $msg = array(
                        'status' => 3,
                        'string' => '新密碼前後不一致. 請再次確認',
                        'token' => $token
                    );
                    return self::getConfig()->getTmpl()->render('userview.html', $msg);
                }
                $user->save();
                $msg = array( 'status' => 0, 'string' => '修改資料成功', 'token' => $token);
                return self::getConfig()->getTmpl()->render('userview.html', $msg);
            }
            return self::getConfig()->getTmpl()->render('userview.html', $msg);
        } else {
            $user = User::load($token);
            if (!$user instanceof User) {
                $msg = array( 'status' => 1, 'string' => "Can not find User. Please Check it again");
            } else {
                $msg = array( 'status' => 0);
                $msg['user'] = array(
                        'token' => $token,
                        'name' => $user->getName(),
                        'email' => $user->getEmail(),
                        'birth' => $user->getBirth(),
                        'power' => $user->getPower(),
                        'address' => $user->getAddress(),
                        'counties' => $user->getCounties(),
                        'run' => $user->getRun(),
                        'join' => $user->getJoin(),
                        'main' => $user->getMain()
                );
            }
            return self::getConfig()->getTmpl()->render('userview.html', $msg);
        }
    }

    public function addUser()
    {
        if ($_POST['info']) {
            $info = $_POST['info'];
            if ($info['pass1'] === $info['pass2']) {
                $info['pass'] = $info['pass1'];
            } else {
                $msg = array( 'status' => false, 'string' => '密碼前後不一致,請進行確認.');
                return self::getConfig()->getTmpl()->render('register.html', $msg);
            }
            $info['email'] = htmlentities(strip_tags(trim($info['email'])), ENT_QUOTES, 'UTF-8');
            $info['name']  = htmlentities(strip_tags(trim($info['name'])), ENT_QUOTES, 'UTF-8');
            $info['pass']  = htmlentities(strip_tags(trim($info['pass'])), ENT_QUOTES, 'UTF-8');
            $info['birth']  = htmlentities(strip_tags(trim($info['birth'])), ENT_QUOTES, 'UTF-8');
            $info['address']  = htmlentities(strip_tags(trim($info['address'])), ENT_QUOTES, 'UTF-8');
            $info['counties']  = htmlentities(strip_tags(trim($info['counties'])), ENT_QUOTES, 'UTF-8');
            $emailrule = '/^[\S]+@[\S]+\.[\S]+$/';
            $namerule = '/^([\x7f-\xff0-9a-zA-z]{2,10})$/';
            $passrule = '/^([0-9a-zA-z]{3,20})$/';
            $birthrule = '/^([\d]{8})$/';

            if (!preg_match($emailrule, $info['email'])) {
                $msg = array( 'status' => false, 'string' => '信箱格式錯誤');
            } elseif (!preg_match($namerule, $info['name'])) {
                $msg = array( 'status' => false, 'string' => '姓名格式錯誤');
            } elseif (!preg_match($passrule, $info['pass'])) {
                $msg = array( 'status' => false, 'string' => '密碼格式錯誤');
            } elseif (!preg_match($birthrule, $info['birth'])) {
                $msg = array( 'status' => false, 'string' => '生日格式錯誤');
            } elseif ($info['address'] == '' or $info['counties'] == '') {
                $msg = array( 'status' => false, 'string' => '有欄位為空');
            } else {
                if (User::getIdByEmail($info['email'])) {
                    $msg = array( 'status' => false, 'string' => '該信箱已被註冊');
                } else {
                    if ($_FILES["file"]["error"] > 0) {
                        $op = '1';
                    } else {
                        $user = User::create($info);
                        if ($user instanceof User) {
                                $img = Img::create($_FILES["file"]["tmp_name"], 'upload_user');
                                $userimg = UserImg::create($user, $img);
                                $session = new PhpSession;
                                $session->set('user', $user->getToken());
                                $msg = array(
                                    'status' => true,
                                    'string' => '註冊會員成功',
                                    'user' => $session->get('token')
                                );
                        } else {
                            $msg = array( 'status' => false, 'Unknown error. please try again.');
                        }
                    }
                    if ($op !== null) {
                        $msg = ImgLibrary::imgError($op);
                    }
                }
            }
            return self::getConfig()->getTmpl()->render('register.html', $msg);
        } else {
            return self::getConfig()->getTmpl()->render('register.html');
        }
    }

    public function userList()
    {
        $session = new PhpSession;
        $msg = array('user' => $session->get('user'));
        return self::getConfig()->getTmpl()->render('userlist.html', $msg);
    }

    public function login()
    {
        $session = new PhpSession;
        if ($session->get('user')) {
            $msg = array( 'status' => false, 'string' => '已登入');
            return self::getConfig()->getTmpl()->render('login.html', $msg);
        }
        if ($_POST['email']) {
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            $id = User::getIdByEmail($email);
            if (!$id) {
                $msg = array( 'status' => false, 'string' => '帳號錯誤請進行確認');
            } else {
                $user = User::load($id);
                if ($user instanceof User) {
                    $valid = $user->validatePassword($pass);
                    if (!$valid) {
                        $msg = array( 'status' => false, 'string' => '密碼錯誤請進行確認');
                    } else {
                        $session->set('user', $user->getToken());
                        $msg = array( 'status' => true, 'string' => '登入成功', 'user' => $session->get('user'));
                    }
                }
            }
            return self::getConfig()->getTmpl()->render('login.html', $msg);
        } else {
            return self::getConfig()->getTmpl()->render('login.html');
        }
    }

    public function logout()
    {
        $session = new PhpSession;
        $session->destroy();
        if (!$session->get('user')) {
            $msg = array( 'status' => true, 'string' => '你已經成功登出');
            return self::getConfig()->getTmpl()->render('logout.html', $msg);
        } else {
            $msg = array( 'status' => false, 'string' => '登出失敗請稍後再試');
            return self::getConfig()->getTmpl()->render('logout.html', $msg);
        }
    }

    public function showUserImg($token = null)
    {
        $userimg = UserImg::loadByUid($token);
        $gid = $userimg->getGid();
        $img = Img::load($gid);
        if (!$img instanceof Img) {
            return json_encode(
                array(
                    'status' => false,
                    'msg'    => '查無照片'
                )
            );
        }

        header('Content-Type: '.$img->getMime());
        $img->readFile();
    }
}

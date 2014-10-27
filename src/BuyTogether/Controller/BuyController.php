<?php
namespace BuyTogether\Controller;

use Fruit\Seed;
use Fruit\Session\PhpSession;
use BuyTogether\Model\Buy;
use BuyTogether\Model\User;
use BuyTogether\Model\Img;
use BuyTogether\Model\Join;
use BuyTogether\Model\ImgPlus;
use BuyTogether\Model\Thread;
use BuyTogether\Model\ThreadPlus;
use BuyTogether\Library\ImgLibrary;

class BuyController extends Seed
{
    public function index()
    {
        if ($_GET['search']) {
            $msg = array( 'search' => true);
            $session = new PhpSession;
            if ($session->get('user')) {
                $msg['user'] = $session->get('user');
            }
            return self::getConfig()->getTmpl()->render('main.html', $msg);
        } else {
            $buy = Buy::getAll($_GET['class'], $_GET['area'], $_GET['methor'], $_GET['end']);
            $msg = array();
            if ($buy) {
                foreach ($buy as $key) {
                        $msg['buy'][$key] = Buy::view($key);
                }
            }
            $session = new PhpSession;
            if ($session->get('user')) {
                $msg['user'] = $session->get('user');
            }
            return self::getConfig()->getTmpl()->render('main.html', $msg);
        }
    }

    public function view($b)
    {
        $session = new PhpSession;
        $msg = array();
        $msg['buy'] = Buy::view($b);
        if ($session->get('user')) {
            $msg['user'] = $session->get('user');
            $user = User::load($session->get('user'));
            $msg['userRun'] = $user->getRun();
            $msg['userJoin'] = $user->getJoin();
        }
        return self::getConfig()->getTmpl()->render('buyview.html', $msg);
    }

    public function start()
    {
        $session = new PhpSession;
        if (!$_POST['info']) {
            $user = User::load($session->get('user'));
            if ($user instanceof User) {
                $msg['user'] =  $session->get('user');
                $msg['useremail'] = $user->getEmail();
            } else {
                $msg = array( 'status' => false, 'string' => '請先登入');
            }
            return self::getConfig()->getTmpl()->render('startform.html', $msg);
        } else {
            if ($_FILES["file"]["error"] > 0) {
                $op = '1';
            } else {
                $info = $_POST['info'];
                $buy = Buy::create($info);
                $img = Img::create($_FILES["file"]["tmp_name"], 'upload_group');
                if ($buy instanceof Buy and $img instanceof Img) {
                    $imgplus = ImgPlus::create($img, $buy);
                    if ($imgplus instanceof imgplus) {
                        $thread = Thread::create("{$buy->getName()}團購討論串，有問題可以在這邊發問");
                        $threadplus = ThreadPlus::create($thread, $buy);
                        $user = User::load($session->get('user'));
                        $user->setMain($user->getMain() + 1);
                        $user->save();
                        $msg = array( 'status' => true, 'string' => '新增團購成功');
                        return self::getConfig()->getTmpl()->render('startform.html', $msg);
                    } else {
                        $msg = array( 'status' => false, 'string' => '團購與圖片連結失敗');
                        return self::getConfig()->getTmpl()->render('startform.html', $msg);
                    }
                } else {
                    $op = '2';
                }
            }
            $msg = ImgLibrary::imgError($op);
            return self::getConfig()->getTmpl()->render('startform.html', $msg);
        }
    }
}

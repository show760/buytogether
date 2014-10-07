<?php
namespace BuyTogether\Controller;

use Fruit\Seed;
use Fruit\Model;
use Fruit\Session\PhpSession;
use BuyTogether\Model\Buy;
use BuyTogether\Model\User;
use BuyTogether\Model\Img;
use BuyTogether\Model\BuyImg;

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

    public function join($b)
    {
        $session = new PhpSession;
        $msg = array();
        $msg['buy'] = Buy::view($b);
        if ($session->get('user')) {
            $msg['user'] = $session->get('user');
            $user = User::load($session->get('user'));
            $msg['useremail'] = $user->getEmail();
        }
        return self::getConfig()->getTmpl()->render('join.html', $msg);
    }

    public function start()
    {
        $session = new PhpSession;
        if (!$_POST['info']) {
            $user = User::load($session->get('user'));
            if ($user instanceof User) {
                $msg['user'] =  $user->getEmail();
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
                    $buyimg = BuyImg::create($buy, $img);
                    if ($buyimg instanceof Buyimg) {
                        $msg = array( 'status' => true, 'string' => '新增團購成功');
                        return self::getConfig()->getTmpl()->render('startform.html', $msg);
                    }
                } else {
                    $op = '2';
                }
            }
            switch ($op) {
                case '1':
                    switch ($_FILES["file"]["error"]) {
                        case 1:
                            // 檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE
                            $msg = array(
                                'status' => false,
                                'string' => "The file is too large (server)."
                            );
                            break;
                        case 2:
                            // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE
                            $msg = array(
                                'status' => false,
                                'string' => 'The file is too large (form).'
                            );
                            break;
                        case 3:
                            // 檔案僅部分被上傳 UPLOAD_ERR_PARTIAL
                            $msg = array(
                                'status' => false,
                                'string' => 'The file was only partially uploaded.'
                            );
                            break;
                        case 4:
                            // 沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE
                            $msg = array(
                                'status' => false,
                                'string' => 'No file was uploaded.'
                            );
                            break;
                        case 5:
                            // 伺服器臨時檔案遺失
                            $msg = array(
                                'status' => false,
                                'string' => 'The servers temporary folder is missing.'
                            );
                            break;
                        case 6:
                            // 檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR
                            $msg = array(
                                'status' => false,
                                'string' => 'Failed to write to the temporary folder.'
                            );
                            break;
                        case 7:
                            // 無法寫入硬碟 UPLOAD_ERR_CANT_WRITE
                            $msg = array(
                                'status' => false,
                                'string' => 'Failed to write file to disk.'
                            );
                            break;
                        case 8:
                            // UPLOAD_ERR_EXTENSION
                            $msg = array(
                                'status' => false,
                                'string' => 'File upload stopped by extension.'
                            );
                            break;
                    }
                    return self::getConfig()->getTmpl()->render('startform.html', $msg);
                    break;
                case '2':
                    $msg = array(
                        'status' => false,
                        'string' => "輸入資料有誤,請進行確認"
                    );
                    return self::getConfig()->getTmpl()->render('startform.html', $msg);
                    break;
                default:
                    $msg = array(
                        'status' => false,
                        'string' => "Unknown error"
                    );
                    return self::getConfig()->getTmpl()->render('startform.html', $msg);
                    break;
            }
        }
    }

    /**
     * 用photoToken取得image
     *
     * @param  mixed buy token $token
     * @return image or null
     */
    public function showImg($token = null)
    {
        $buyimg = BuyImg::loadByBid($token);
        $gid = $buyimg->getGid();
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

<?php
namespace BuyTogether\Controller;

use Exception;
use Fruit\Seed;
use Fruit\Session\PhpSession;
use BuyTogether\Model\Buy;
use BuyTogether\Model\User;
use BuyTogether\Model\Join;
use BuyTogether\Library\ErrorLibrary;

class JoinController extends seed
{
    public function join($b)
    {
        $session = new PhpSession;
        if (!$_POST['quantity']) {
            $msg = array();
            $msg['buy'] = Buy::view($b);
            if ($session->get('user')) {
                $msg['user'] = $session->get('user');
                $user = User::load($session->get('user'));
                $msg['useremail'] = $user->getEmail();
            }
            return self::getConfig()->getTmpl()->render('join.html', $msg);
        } else {
            try {
                if (!preg_match('/\d+/', $_POST['quantity'])) {
                    throw new Exception('請輸入數字');
                }
                $buy = Buy::load($b);
                if (!$buy instanceof Buy) {
                    throw new Exception('查無團購資料');
                }
                if ($buy->getEnd() == 'before') {
                    throw new Exception('該團購已經結束');
                }
                $user = User::load($session->get('user'));
                if (!$user instanceof User) {
                    throw new Exception('請先登入');
                }
                $n = $buy->getNum() + $_POST['quantity'];
                if ($n > $buy->getQuantity()) {
                    throw new Exception('剩餘數量不足，請重新選擇數量！');
                }
                if ($buy->getConrun() < $user->getRun()) {
                    throw new Exception('跑團次數過多，禁止加入該團購！');
                }
                if ($buy->getConjoin() > $user->getJoin()) {
                    throw new Exception('參加團購次數不足，禁止加入該團購！');
                }
                $join = Join::create($buy, $user, $_POST['quantity']);
                if (!$join instanceof Join) {
                    throw new Exception('參與團購失敗，請再試一次！');
                }
                $buy->setNum($n);
                $buy->save();
                $user->setJoin($user->getJoin() + 1);
                $user->save();
                $msg = array( 'status' => true, 'string' => '你已經成功加入團購');
            } catch (Exception $e) {
                $msg = array('status' => false, 'string' => $e->getMessage(), 'token' => $b);
            }
            return self::getConfig()->getTmpl()->render('join.html', $msg);
        }
    }
    public function groupDelete($bid, $jid)
    {
        try {
            $session = new PhpSession;
            if (!$session->get('user')) {
                throw new Exception('請先登入!');
            }
            $join = Join::load($jid);
            if (!$join instanceof Join) {
                throw new Exception('查無訂單!');
            }
            $buy = Buy::load($join->getBid());
            if (!$buy instanceof Buy) {
                throw new Exception('查無團購!');
            }
            if ($buy->getToken() !== $bid) {
                throw new Exception('訂單資料與團購資料不符，請確認!');
            }
            $buyuser = User::load($join->getUid());
            if (!$buyuser instanceof User) {
                throw new Exception('查無該團員!');
            }
            if ($join->getHandle() !== '啾團中') {
                throw new Exception('該訂單狀態為—'.$join->getHandle().'—禁止刪除');
            }
            $count = $buy->getNum() - $join->getQuantity();
            $buy->setNum($count);
            $buy->save();
            $buyuser->setJoin($buyuser->getJoin() - 1);
            $buyuser->save();
            $join->setHandle('訂單被團主取消');
            $join->save();
            $msg = array(
                'status' => true,
                'string' => '已刪除該訂單！',
                'bid' => $buy->getToken()
            );
        } catch (Exception $e) {
            ErrorLibrary::setException($e);
        }
        if (ErrorLibrary::isException()) {
            $msg = array(
                    'status' => false,
                    'string' => ErrorLibrary::getAllExceptionMsg(),
                    'bid' => $bid
            );
        }
        return self::getConfig()->getTmpl()->render('mygroup.html', $msg);
    }
}

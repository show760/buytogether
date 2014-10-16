<?php
namespace BuyTogether\Controller;

use Fruit\Seed;
use Fruit\Model;
use Fruit\Session\PhpSession;
use BuyTogether\Model\Buy;
use BuyTogether\Model\User;
use BuyTogether\Model\Join;

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
            if (!preg_match('/\d+/', $_POST['quantity'])) {
                $msg = array( 'status' => false, 'string' => '請輸入數字', 'token' => $b);
                return self::getConfig()->getTmpl()->render('join.html', $msg);
            }
            $buy = Buy::load($b);
            $user = User::load($session->get('user'));
            if ($buy instanceof Buy and $user instanceof User) {
                $n = $buy->getNum() + $_POST['quantity'];
                if ($n > $buy->getQuantity()) {
                    $msg = array( 'status' => false, 'string' => '剩餘數量不足', 'token' => $b);
                } else {
                    $join = Join::create($buy, $user, $_POST['quantity']);
                    if ($join instanceof Join) {
                        $buy->setNum($n);
                        $buy->save();
                        $user->setJoin($user->getJoin() + 1);
                        $user->save();
                        $msg = array( 'status' => true, 'string' => '你已經成功加入團購');
                    }
                }
            } else {
                $msg = array( 'status' => false, 'string' => '資料有誤請確認', 'token' => $b);
            }
            return self::getConfig()->getTmpl()->render('join.html', $msg);
        }
    }
    public function delete($id)
    {
        $session = new PhpSession;
        if ($session->get('user')) {
            $join = Join::load($id);
            $buy = Buy::load($join->getBid());
            if ($join instanceof Join && $buy instanceof Buy) {
                $count = $buy->getNum() - $join->getQuantity();
                $buy->setNum($count);
                $buy->save();
                $join->delete();
                $msg = array(
                    'status' => true,
                    'string' => '已刪除該訂單！',
                    'bid' => $buy->getToken()
                );
            } else {
                $msg = array(
                    'status' => false,
                    'string' => '查無該訂單，請確認資料是否有誤',
                    'bid' => $buy->getToken()
                );
            }
            return self::getConfig()->getTmpl()->render('mygroup.html', $msg);
        }
    }
}

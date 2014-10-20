<?php
namespace BuyTogether\Controller;

use Fruit\Seed;
use BuyTogether\Model\User;
use BuyTogether\Model\Img;
use BuyTogether\Model\UserImg;
use BuyTogether\Model\Buy;
use BuyTogether\Model\Join;
use Fruit\Session\PhpSession;

class GroupController extends Seed
{
    public function myList()
    {
        $session = new PhpSession;
        if ($session->get('user')) {
            $msg = null;
            $msg = array('user' => $session->get('user'));
            if ($_POST['mode'] !== 'join' || !$_POST['mode']) {
                $user = User::load($session->get('user'));
                $email=$user->getEmail();
                $buyTokens = Buy::listByEmail($email);
                if ($buyTokens) {
                    foreach ($buyTokens as $key) {
                        $buy = Buy::load($key);
                        $msg['buy'][$key]['token'] = $buy->getToken();
                        $msg['buy'][$key]['name'] = $buy->getName();
                        $msg['buy'][$key]['identity'] = '團主';
                    }
                    $buy = null;
                }
            }
            if ($_POST['mode'] !== 'group' || !$_POST['mode']) {
                $joins = Join::listByUid($session->get('user'));
                if ($joins) {
                    foreach ($joins as $key) {
                        $join = Join::load($key);
                        $buy = Buy::load($join->getBid());
                        $msg['join'][$key]['token'] = $join->getToken();
                        $msg['join'][$key]['name'] = $buy->getName();
                        $msg['join'][$key]['identity'] = '團員';
                    }
                }
            }
            return self::getConfig()->getTmpl()->render('mylist.html', $msg);
        }
    }

    public function myGroup($bid)
    {
        $session = new PhpSession;
        if ($session->get('user')) {
            $user = User::load($session->get('user'));
            $buy = Buy::load($bid);
            if ($user->getEmail() == $buy->getOwner()) {
                $joins = Join::listByBid($bid);
                $msg['user'] = $session->get('user');
                $msg['buyname'] = $buy->getName();
                $msg['buyowner'] = $buy->getOwner();
                foreach ($joins as $key => $value) {
                    $join = Join::load($value);
                    $joinuser = User::load($join->getUid());
                    $msg['join'][$key]['token'] = $join->getToken();
                    $msg['join'][$key]['quantity'] = $join->getQuantity();
                    $msg['join'][$key]['email'] = $joinuser->getEmail();
                    $msg['join'][$key]['name'] = $joinuser->getName();
                    $msg['join'][$key]['run'] = $joinuser->getRun();
                    $msg['join'][$key]['join'] = $joinuser->getJoin();
                    $msg['join'][$key]['handle'] = $join->getHandle();
                }
            }
            return self::getConfig()->getTmpl()->render('mygroup.html', $msg);
        }
    }
    public function myJoin($jid)
    {
        $session = new PhpSession;
        if ($session->get('user')) {
            $user = User::load($session->get('user'));
            $j = Join::load($jid);
            if ($j->getUid() == $session->get('user')) {
                $msg['user'] = $session->get('user');
                $buy = Buy::load($j->getBid());
                if ($buy instanceof Buy) {
                    $msg['buy']['token'] = $buy->getToken();
                    $msg['buy']['name'] = $buy->getName();
                    $msg['buy']['onwer'] = $buy->getOwner();
                    $msg['buy']['price'] = $buy->getPrice();
                    $msg['buy']['price'] = $buy->getMethor();
                    if ($buy->getMethor() == 'remittance') {
                        $msg['buy']['gname'] = $buy->getGname();
                        $msg['buy']['gacc'] = $buy->getGacc();
                    }
                }
                $joins = Join::listByBid($j->getBid());
                foreach ($joins as $key => $value) {
                    $join = Join::load($value);
                    $msg['join'][$key]['token'] = $join->getToken();
                    $msg['join'][$key]['quantity'] = $join->getQuantity();
                    $msg['join'][$key]['handle'] = $join->getHandle();
                }
            }
        }
        var_dump($msg);
    }
}

<?php
namespace BuyTogether\Controller;

use Fruit\Seed;
use Fruit\Session\PhpSession;

class EoqController extends Seed
{
    public function eoqIntroduce()
    {
        $session = new PhpSession;
        $msg = null;
        if ($session->get('user')) {
            $msg['user'] = $session->get('user');
        }
        return self::getConfig()->getTmpl()->render('eoq.html', $msg);
    }
}

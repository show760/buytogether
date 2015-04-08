<?php
namespace BuyTogether\Controller;

use Fruit\Seed;
use Fruit\Session\PhpSession;
use BuyTogether\Model\Zvalue;

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
    public function conps()
    {
        $price = $_POST["price"];
        $lowprice = $_POST["lowprice"];
        $upprice = $_POST["upprice"];
        $mean = $_POST["mean"];
        $error = $_POST["error"];

        $co = $price - $lowprice;
        $cu = $upprice - $price;

        $value = round($cu / ($cu+$co), 4);
        $value1 = round((1 - $value), 4);

        $zvalue = Zvalue::zvalue($value);
        $qua = round($mean+ ($zvalue * $error));
        $msg = array(
            'co' => $co,
            'cu' => $cu,
            'mean' => (int)$mean,
            'error' => (int)$error,
            'qua' => $qua,
            'largevalue' => $value,
            'lowvalue' => $value1
            );
        return json_encode($msg);
    }
}

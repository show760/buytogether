<?php
namespace BuyTogether\Model;

use PDO;
use Fruit\Seed;

class Zvalue extends Seed
{
    public static function zvalue($value)
    {
        $sql="SELECT * FROM `zvalue` ORDER BY ABS({$value} - value)";
        $db = self::getConfig()->getDb();
        $stmt = $db->prepare($sql);
        $ret = null;
        if ($stmt->execute()) {
            $ret = $stmt->fetch(PDO::FETCH_COLUMN, 0);
            $stmt->closeCursor();
            return $ret;
        } else {
            return null;
        }
    }
}

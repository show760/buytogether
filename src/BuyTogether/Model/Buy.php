<?php
namespace BuyTogether\Model;

use PDO;
use DateTime;
use Fruit\Seed;

class Buy extends Seed
{
    private $id;
    private $time;
    private $name;
    private $price;
    private $oprice;
    private $com;
    private $det;
    private $owner;
    private $class;
    private $area;
    private $end;
    private $quantity;
    private $num;
    private $conrun;
    private $conjoin;
    private $methor;
    private $gname;
    private $gacc;

    protected function __construct($info)
    {
        $this->id = $info[0];
        $this->time = $info[1];
        $this->name = $info[2];
        $this->price = $info[3];
        $this->oprice = $info[4];
        $this->com = $info[5];
        $this->det = $info[6];
        $this->owner = $info[7];
        $this->class = $info[8];
        $this->area = $info[9];
        $this->end = $info[10];
        $this->quantity = $info[11];
        $this->num = $info[12];
        $this->conrun = $info[13];
        $this->conjoin = $info[14];
        $this->methor = $info[15];
        $this->gname = $info[16];
        $this->gacc = $info[17];
    }

    private static function bind($s, $p, $v = null, $t = PDO::PARAM_STR)
    {
        if ($v == null) {
            $s->bindValue($p, $v, PDO::PARAM_NULL);
        } else {
            $s->bindValue($p, $v, $t);
        }
    }

    public static function getAll($class = null, $area = null, $methor = null, $end = null)
    {
        $insert = null;
        if (is_null($end)) {
            $end = "open";
        }
        if ($class) {
            $insert .= " AND `buy_Class` = '".$class. "'";
        }
        if ($area) {
            $insert .= " AND `buy_Area` = '" .$area. "'";
        }
        if ($methor) {
            $insert .= " AND `buy_Methor` = '" .$methor. "'";
        }
        $sql = "SELECT `buy_Id` FROM `buy` WHERE `buy_End` = '".$end."'".$insert;
        
        $db = self::getConfig()->getDb();
        $stmt = $db->prepare($sql);
        $ret = null;
        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            $stmt->closeCursor();
            return $ret;
        } else {
            return null;
        }

    }

    public static function view($b = null)
    {
        if (is_null($b)) {
            return null;
        }
        $buy = self::load($b);
        if ($buy instanceof Buy) {
            $msg = array(
                'token' => $buy->getToken(),
                'time' => $buy->getTime()->format('Y-m-d H:i:s'),
                'name' => $buy->getName(),
                'price' => $buy->getPrice(),
                'oprice' => $buy->getOprice(),
                'com' => $buy->getCom(),
                'det' => $buy->getDet(),
                'owner' => $buy->getOwner(),
                'class' => $buy->getClass(),
                'area' => $buy->getArea(),
                'end' => $buy->getEnd(),
                'quantity' => $buy->getQuantity(),
                'num' => $buy->getNum(),
                'conrun' => $buy->getConrun(),
                'conjoin' => $buy->getConjoin(),
                'methor' => $buy->getMethor(),
                'gname' => $buy->getGname(),
                'gacc' => $buy->getGacc()
            );
            return $msg;
        }
        return null;
    }

    public static function create($info)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `buy` (buy_Name, buy_Price, buy_OPrice, buy_Com, buy_Det, ';
        $sql .= 'buy_Owner, buy_Class, buy_Area, buy_Q, buy_ConRun, buy_ConJoin, ';
        $sql .= 'buy_Methor, buy_Gname, buy_Gacc) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        if (empty($info['gname'])) {
            $info['gname'] = null;
            $info['gacc'] = null;
        }

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $info['name']);
        $stmt->bindValue(2, $info['price']);
        $stmt->bindValue(3, $info['oprice']);
        $stmt->bindValue(4, $info['com']);
        $stmt->bindValue(5, $info['det']);
        $stmt->bindValue(6, $info['owner']);
        $stmt->bindValue(7, $info['class']);
        $stmt->bindValue(8, $info['area']);
        $stmt->bindValue(9, $info['quantity']);
        $stmt->bindValue(10, $info['conrun']);
        $stmt->bindValue(11, $info['conjoin']);
        $stmt->bindValue(12, $info['methor']);
        self::bind($stmt, 13, $info['gname']);
        self::bind($stmt, 14, $info['gacc']);

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $stmt->closeCursor();
            return self::load($id);
        }

        $stmt->closeCursor();
        return null;
    }

    public static function load($id)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT buy_Id, UNIX_TIMESTAMP(buy_Time), buy_Name, buy_Price, buy_OPrice, buy_Com, ';
        $sql .= 'buy_Det, buy_Owner, buy_Class, buy_Area, buy_End, buy_Q, buy_Num, buy_ConRun, buy_ConJoin, ';
        $sql .= 'buy_Methor, buy_Gname, buy_Gacc FROM `buy` WHERE `buy_Id` = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res);
            }
        }

        $stmt->closeCursor();
        return $ret;
    }

    /**
    *   利用email得到Token
    */
    public static function listByEmail($email)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `buy_Id` FROM `buy` WHERE `buy_Owner` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $email);
        $ret = null;

        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }
    /**
     * 取得代碼
     *
     * @return mixed token
     */
    public function getToken()
    {
        return $this->id;
    }

    /**
     * 取得時間
     *
     * @return DateTime object
     */
    public function getTime()
    {
        return new DateTime('@' . $this->time);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getOprice()
    {
        return $this->oprice;
    }

    public function getCom()
    {
        return $this->com;
    }

    public function getDet()
    {
        return $this->det;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($e)
    {
        $this->end = $e;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getConrun()
    {
        return $this->conrun;
    }

    public function getConjoin()
    {
        return $this->conjoin;
    }

    public function getMethor()
    {
        return $this->methor;
    }

    public function getGacc()
    {
        return $this->gacc;
    }

    public function getGname()
    {
        return $this->gname;
    }

    public function getNum()
    {
        return $this->num;
    }

    public function setNum($n)
    {
        $this->num = $n;
    }

    /**
     * 刪除
     */
    public function delete()
    {
        if ($this->id == null) {
            return;
        }
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM `buy` WHERE buy_Id = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id);
        if ($stmt->execute()) {
            $this->id = null;
        }
        $stmt->closeCursor();
    }
    /**
    * 儲存
    */
    public function save()
    {
        $db = self::getConfig()->getDb();
        $sql = 'UPDATE `buy` SET `buy_Num` = ?, `buy_End` = ? WHERE `buy_Id` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->num);
        $stmt->bindValue(2, $this->end);
        $stmt->bindValue(3, $this->id);
        $stmt->execute();
        $stmt->closeCursor();
    }
}

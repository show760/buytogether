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
    private $path;
    private $mime;
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

    protected function __construct($id, $info)
    {
        $this->id = $id;
        $this->time = $info[0];
        $this->name = $info[1];
        $this->price = $info[2];
        $this->oprice = $info[3];
        $this->path = $info[4];
        $this->mime = $info[5];
        $this->com = $info[6];
        $this->det = $info[7];
        $this->owner = $info[8];
        $this->class = $info[9];
        $this->area = $info[10];
        $this->end = $info[11];
        $this->quantity = $info[12];
        $this->num = $info[13];
        $this->conrun = $info[14];
        $this->conjoin = $info[15];
        $this->methor = $info[16];
        $this->gname = $info[17];
        $this->gacc = $info[18];
    }

    private static function bind($s, $p, $v = null, $t = PDO::PARAM_STR)
    {
        if ($v == null) {
            $s->bindValue($p, $v, PDO::PARAM_NULL);
        } else {
            $s->bindValue($p, $v, $t);
        }
    }

    private static function generate($f)
    {
        $base_dir = self::getConfig()->get('dir', 'upload_group');
        $prefix = self::getConfig()->get('prefix', 'upload_group');
        return tempnam($base_dir, $prefix);
    }

    private static function fn($f)
    {
        $base_dir = self::getConfig()->get('dir', 'upload_group');
        return $base_dir . DIRECTORY_SEPARATOR . $f;
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

    public static function create($info, $path)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `buy` (buy_Name, buy_Price, buy_OPrice, buy_Path, buy_Mime, buy_Com, buy_Det, ';
        $sql .= 'buy_Owner, buy_Class, buy_Area, buy_Q, buy_ConRun, buy_ConJoin, ';
        $sql .= 'buy_Methor, buy_Gname, buy_Gacc) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        if (!is_file($path)) {
            return null;
        }
        if (empty($info['gname'])) {
            $info['gname'] = null;
            $info['gacc'] = null;
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);
        if (substr($mime, 0, 6) != 'image/') {
            return null;
        }
        $fn = self::generate($path);

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $info['name']);
        $stmt->bindValue(2, $info['price']);
        $stmt->bindValue(3, $info['oprice']);
        $stmt->bindValue(4, basename($fn));
        $stmt->bindValue(5, $mime);
        $stmt->bindValue(6, $info['com']);
        $stmt->bindValue(7, $info['det']);
        $stmt->bindValue(8, $info['owner']);
        $stmt->bindValue(9, $info['class']);
        $stmt->bindValue(10, $info['area']);
        $stmt->bindValue(11, $info['quantity']);
        $stmt->bindValue(12, $info['conrun']);
        $stmt->bindValue(13, $info['conjoin']);
        $stmt->bindValue(14, $info['methor']);
        self::bind($stmt, 15, $info['gname']);
        self::bind($stmt, 16, $info['gacc']);

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $stmt->closeCursor();
            copy($path, $fn);
            return self::load($id);
        }

        $stmt->closeCursor();
        return null;
    }

    public static function load($id)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT UNIX_TIMESTAMP(buy_Time), buy_Name, buy_Price, buy_OPrice, buy_Path, buy_Mime, buy_Com, ';
        $sql .= 'buy_Det, buy_Owner, buy_Class, buy_Area, buy_End, buy_Q, buy_Num, buy_ConRun, buy_ConJoin, ';
        $sql .= 'buy_Methor, buy_Gname, buy_Gacc FROM `buy` WHERE `buy_Id` = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($id, $res);
            }
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

    public function getPath()
    {
        return self::fn($this->path);
    }

    /**
     * 直接輸出檔案 (readfile)
     */
    public function readFile()
    {
        readfile(self::fn($this->path));
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

    /**
     * 取得 MIME
     *
     * @return string of mime type
     */
    public function getMime()
    {
        return $this->mime;
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
            unlink(self::fn($this->path));
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

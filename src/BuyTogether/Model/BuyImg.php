<?php
namespace BuyTogether\Model;

use PDO;
use Fruit\Seed;

class BuyImg extends Seed
{
    private $id;
    private $bid;
    private $gid;

    protected function __construct($i, $b, $g)
    {
        $this->id = $i;
        $this->bid = $b;
        $this->gid = $g;
    }
    /**
    *  新增一筆BuyImg資料
    *  @param Buy $b Buy objest
    *  @param Img $g Img object
    *  @return BuyImg objest, or null
    */
    public static function create($b, $g)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `buyimg` (bid, gid) VALUES (?,?)';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $b->getToken());
        $stmt->bindValue(2, $g->getToken());

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $stmt->closeCursor();
            return self::load($id);
        }

        $result->closeCursor();
        return null;
    }

    public static function load($id)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `bid`, `gid` FROM `buyimg` WHERE `id` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($id, $res[0], $res[1]);
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    public static function loadByBid($bid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `gid` FROM `buyimg` WHERE `bid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $bid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($$res[0], $bid, $res[1]);
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 刪除
     */
    public function delete()
    {
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM `buyimg` WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $this->id = null;
        }
        $stmt->closeCursor();
    }

    public function getBid()
    {
        return $this->bid;
    }

    public function getGid()
    {
        return $this->gid;
    }

    public function getToken()
    {
        return $this->id;
    }
}

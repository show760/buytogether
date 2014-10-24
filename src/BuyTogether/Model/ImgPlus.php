<?php
namespace BuyTogether\Model;

use PDO;
use Fruit\Seed;

class ImgPlus extends Seed
{
    private $id;
    private $bid;
    private $uid;
    private $jid;
    private $gid;


    protected function __construct($i, $b, $u, $j, $g)
    {
        $this->id = $i;
        $this->bid = $b;
        $this->uid = $u;
        $this->jid = $j;
        $this->gid = $g;
    }

    private static function bind($s, $p, $v = null, $t = PDO::PARAM_STR)
    {
        if ($v == null) {
            $s->bindValue($p, $v, PDO::PARAM_NULL);
        } else {
            $s->bindValue($p, $v->getToken(), $t);
        }
    }

    /**
    *  新增一筆ImgPlus資料
    *  @param Buy $b Buy object
    *  @param User $u User object
    *  @param Join $j join object
    *  @param Img $g Img object
    *  @return ImgPlus objest, or null
    */
    public static function create($g, $b = null, $u = null, $j = null)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `imgplus` (bid, uid, jid, gid) VALUES (?,?,?,?)';
        
        $stmt = $db->prepare($sql);
        self::bind($stmt, 1, $b);
        self::bind($stmt, 2, $u);
        self::bind($stmt, 3, $j);
        $stmt->bindValue(4, $g->getToken());

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
        $sql = 'SELECT `bid`, `uid`, `jid`, `gid` FROM `imgplus` WHERE `id` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($id, $res[0], $res[1], $res[2], $res[3] );
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    public static function loadByUid($uid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `bid`,`jid`, `gid` FROM `imgplus` WHERE `uid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $uid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res[0], $res[1], $uid, $res[2], $res[3]);
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    public static function loadByBid($bid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `uid`,`jid`, `gid` FROM `imgplus` WHERE `bid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $bid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res[0], $bid, $res[1], $res[2], $res[3]);
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    public static function loadByJid($jid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `bid`,`uid`, `gid` FROM `imgplus` WHERE `jid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $jid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res[0], $res[1], $res[2], $jid, $res[3]);
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
        $sql = 'DELETE FROM `imgplus` WHERE id = ?';
        
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

    public function getUid()
    {
        return $this->uid;
    }
    
    public function getJid()
    {
        return $this->jid;
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

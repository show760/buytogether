<?php
namespace BuyTogether\Model;

use PDO;
use Fruit\Seed;

class JoinImg extends Seed
{
    private $id;
    private $jid;
    private $gid;

    protected function __construct($i, $j, $g)
    {
        $this->id = $i;
        $this->jid = $j;
        $this->gid = $g;
    }
    /**
    *  新增一筆JoinImg資料
    *  @param Join $j Join objest
    *  @param Img $g Img object
    *  @return UserImg objest, or null
    */
    public static function create($j, $g)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `joinimg` (jid, gid) VALUES (?,?)';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $j->getToken());
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
        $sql = 'SELECT `jid`, `gid` FROM `joinimg` WHERE `id` = ?';

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

    public static function loadByJid($jid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `gid` FROM `joinimg` WHERE `jid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $jid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($$res[0], $jid, $res[1]);
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
        $sql = 'DELETE FROM `joinimg` WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $this->id = null;
        }
        $stmt->closeCursor();
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

<?php
namespace BuyTogether\Model;

use PDO;
use Fruit\Seed;

class UserImg extends Seed
{
    private $id;
    private $uid;
    private $gid;

    protected function __construct($i, $u, $g)
    {
        $this->id = $i;
        $this->uid = $u;
        $this->gid = $g;
    }
    /**
    *  新增一筆UserImg資料
    *  @param User $u User objest
    *  @param Img $g Img object
    *  @return UserImg objest, or null
    */
    public static function create($u, $g)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `userimg` (uid, gid) VALUES (?,?)';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $u->getToken());
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
        $sql = 'SELECT `uid`, `gid` FROM `userimg` WHERE `id` = ?';

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

    public static function loadByUid($uid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `gid` FROM `userimg` WHERE `uid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $uid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($$res[0], $uid, $res[1]);
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
        $sql = 'DELETE FROM `userimg` WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $this->id = null;
        }
        $stmt->closeCursor();
    }

    public function getUid()
    {
        return $this->uid;
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

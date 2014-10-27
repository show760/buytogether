<?php
namespace BuyTogether\Model;

use PDO;
use Fruit\Seed;

class ThreadPlus extends Seed
{
    private $id;
    private $tid;
    private $bid;
    private $uid;

    private $thread_obj_cache;
    private $buy_obj_cache;

    protected function __construct($i, $t, $b, $u)
    {
        $this->id = $i;
        $this->tid = $t;
        $this->bid = $b;
        $this->uid = $u;
        $this->thread_obj_cache = null;
        $this->buy_obj_cache = null;
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
    *  新增一筆ThreadPlus資料
    *  @param Thread $t Thread object
    *  @param Buy $b Buy object
    *  @param User $u User object
    *  @return ThreadPlus objest, or null
    */
    public static function create($t, $b = null, $u = null)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `threadplus` (tid, bid, uid) VALUES (?,?,?)';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $t->getToken());
        self::bind($stmt, 2, $b);
        self::bind($stmt, 3, $u);

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
        $sql = 'SELECT `tid`, `bid`, `uid` FROM `threadplus` WHERE `id` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($id, $res[0], $res[1], $res[2]);
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    public static function loadByUid($uid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `tid`,`bid` FROM `threadplus` WHERE `uid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $uid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res[0], $res[1], $res[2], $uid);
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    public static function loadByBid($bid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `tid`, `uid` FROM `threadplus` WHERE `bid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $bid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res[0], $res[1], $bid, $res[2]);
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    public static function loadByTid($tid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id`, `bid`, `uid` FROM `threadplus` WHERE `tid` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $tid, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res[0], $tid, $res[1], $res[2]);
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
        $sql = 'DELETE FROM `thrreadplus` WHERE id = ?';
        
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

    /**
     * 取得相關討論串
     *
     * @return Thread object
     */
    public function getThread()
    {
        if ($this->thread_obj_cache == null) {
            $this->thread_obj_cache = Thread::load($this->tid);
        }
        return $this->thread_obj_cache;
    }

    /**
     * 取得相關團購
     *
     * @return Buy object
     */
    public function getBuy()
    {
        if ($this->buy_obj_cache == null) {
            $this->buy_obj_cache = Buy::load($this->bid);
        }
        return $this->buy_obj_cache;
    }
    
    public function getTid()
    {
        return $this->tid;
    }

    public function getToken()
    {
        return $this->id;
    }
}

<?php
namespace BuyTogether\Model;

use PDO;
use Fruit\Seed;

class Join extends Seed
{
    private $id;
    private $bid;
    private $uid;
    private $quantity;

    protected function __construct($i, $b, $u, $q)
    {
        $this->id = $i;
        $this->bid = $b;
        $this->uid = $u;
        $this->quantity = $q;
    }

    /**
    *  @param buy $b object buy
    *  @param user $u object user
    *  @param int $q buy's quantity
    *  @return join object or null
    */
    public static function create($b, $u, $q)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `join` (bid, uid, quantity) VALUES (?,?,?)';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $b->getToken());
        $stmt->bindValue(2, $u->getToken());
        $stmt->bindValue(3, $q);

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
        $sql = 'SELECT `bid`, `uid`, `quantity` FROM `join` WHERE `id` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
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

    public function getToken()
    {
        return $this->id;
    }
    public function getBid()
    {
        return $this->bid;
    }
    public function getUid()
    {
        return $this->uid;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
    *  刪除
    */
    public function delete()
    {
        if ($this->id == null) {
            return null;
        }
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM `join` WHERE id = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id);
        if ($stmt->execute()) {
            $this->id = null;
        }
        $stmt->closeCursor();
    }
}

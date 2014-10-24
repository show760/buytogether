<?php
namespace BuyTogether\Model;

use PDO;
use DateTime;
use Fruit\Seed;

class User extends Seed
{
    private $id;
    private $email;
    private $pass;
    private $name;
    private $birth;
    private $power;
    private $address;
    private $counties;
    private $run;
    private $join;
    private $main;

    protected function __construct($id, $info)
    {
        $this->id = $id;
        $this->email = $info[0];
        $this->pass = $info[1];
        $this->name = $info[2];
        $this->birth = $info[3];
        $this->power = $info[4];
        $this->address = $info[5];
        $this->counties = $info[6];
        $this->run = $info[7];
        $this->join = $info[8];
        $this->main = $info[9];
    }

    public static function create($info)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `user` (email, password, name, birth, address, counties) VALUES(?,?,?,?,?,?)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $info['email']);
        $stmt->bindValue(2, $info['pass']);
        $stmt->bindValue(3, $info['name']);
        $stmt->bindValue(4, $info['birth']);
        $stmt->bindValue(5, $info['address']);
        $stmt->bindValue(6, $info['counties']);
        
        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $stmt->closeCursor();
            return self::load($id);
        }

        $stmt->closeCursor();
        return null;
    }

    public static function load($mid)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `email`, `password`, `name`, `birth`, `power`, `address`, `counties`, `run`, `join`, `main` ';
        $sql .= 'FROM `user` WHERE id = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $mid);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($mid, $res);
            }
        }

        $stmt->closeCursor();
        return $ret;
    }
    public function delete()
    {
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM `user` WHERE id = ?';

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
        $sql = 'UPDATE `user` SET `password` = ?, `name` = ?, `birth` = ?, `power` = ?, `address` = ?, '
        $sql .= '`counties` = ?, `run` = ?, `join` = ?, `main` = ? WHERE `id` = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->pass);
        $stmt->bindValue(2, $this->name);
        $stmt->bindValue(3, $this->birth);
        $stmt->bindValue(4, $this->power);
        $stmt->bindValue(5, $this->address);
        $stmt->bindValue(6, $this->counties);
        $stmt->bindValue(7, $this->run);
        $stmt->bindValue(8, $this->join);
        $stmt->bindValue(9, $this->main);
        $stmt->bindValue(10, $this->id);
        $stmt->execute();
        $stmt->closeCursor();
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
    public function getEmail()
    {
        return $this->email;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getBirth()
    {
        return $this->birth;
    }
    public function getPower()
    {
        return $this->power;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function getCounties()
    {
        return $this->counties;
    }
    public function getRun()
    {
        return $this->run;
    }
    public function getJoin()
    {
         return $this->join;
    }
    public function getMain()
    {
         return $this->main;
    }

    public function setPass($p)
    {
        $this->pass = $p;
    }
    public function setName($n)
    {
        $this->name = $n;
    }
    public function setBirth($b)
    {
        $this->birth = $b;
    }
    public function setPower($p)
    {
        $this->power = $p;
    }
    public function setAddress($a)
    {
        $this->address = $a;
    }
    public function setCounties($c)
    {
        $this->counties = $c;
    }
    public function setRun($r)
    {
        $this->run = $r;
    }
    public function setJoin($j)
    {
        $this->join = $j;
    }
    public function setMain($m)
    {
        $this->main = $m;
    }
    /**
     * 確認密碼
     *
     * @param string $p password to check
     * @return bool true if password is right
     */
    public function validatePassword($p)
    {
        return $p === $this->pass;
    }

    /**
     * 取得所有使用者
     *
     * @return array of tokens
     */
    public static function listAll()
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id` FROM `user`';
        
        $stmt = $db->prepare($sql);
        $ret = null;

        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }
    /**
     * 用名稱搜尋
     *
     * @param string $name user name
     * @return token or null
     */
    public static function getIdByName($name)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id` FROM `user` WHERE `name` = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $name);
        $ret = null;

        if ($stmt->execute()) {
            $ret = $stmt->fetch(PDO::FETCH_NUM);
        }
        $stmt->closeCursor();
        return $ret;
    }
    /**
     * 用信箱搜尋
     *
     * @param string $name user name
     * @return token or null
     */
    public static function getIdByEmail($email)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `id` FROM `user` WHERE `email` = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $email);
        $ret = null;
        $token = null;
        if ($stmt->execute()) {
            $ret = $stmt->fetch(PDO::FETCH_NUM);
            $token = $ret[0];
        }
        $stmt->closeCursor();
        return $token;
    }
}

<?php

namespace BuyTogether\Model;

use PDO;
use Fruit\Seed;

/**
 * 討論串
 */
class Thread extends Seed
{
    private $id;
    private $title;

    private $threadplus_obj_cache;

    protected function __construct ($i, $t)
    {
        $this->id = $i;
        $this->title = $t;
        $this->threadplus_obj_cache = null;
    }

    /**
     * 新增一個討論串
     *
     * @param string $title 標題
     * @return Thread 物件
     */
    public static function create($title)
    {
        $db = self::getConfig()->getDb();
        
        $sql = 'INSERT INTO thread (title) VALUES (?)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $title, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $ret = new self($id, $title);
        }

        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 用代碼取得討論串
     *
     * @param mixed $token 代碼
     * @return Thread 物件。找不到會回傳 null
     */
    public static function load($token)
    {
        $db = self::getConfig()->getDb();
        
        $sql = 'SELECT * FROM thread WHERE id = ?';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $token, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res != null) {
                $ret = new self($res['id'], $res['title']);
            }
        }

        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 刪除討論串
     */
    public function delete()
    {
        if ($this->id == null) {
            return;
        }

        $db = self::getConfig()->getDb();
        
        $sql = 'DELETE FROM thread WHERE id = ?';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        $this->id = null;
    }
    
    /**
     * 把對討論串的修改回存
     */
    public function save()
    {
        if ($this->id == null) {
            return;
        }
        $db = self::getConfig()->getDb();
        $sql = 'UPDATE thread SET title = ? WHERE id = ?';
        $stmt = $db->prepare($sql);
        $stmt->bindvalue(1, $this->title);
        $stmt->bindValue(2, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * 取得這個討論串的代碼
     *
     * @return 代碼
     */
    public function getToken()
    {
        return $this->id;
    }

    /**
     * 取得標題
     *
     * @return string of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 設定標題
     *
     * @param string $t title
     */
    public function setTitle($t)
    {
        $this->title = $t;
    }

    /**
     * 取得所有討論串
     *
     * @return array of tokens
     */
    public static function listAll()
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT id FROM thread';

        $stmt = $db->prepare($sql);
        $ret = null;
        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 取得此串所有文章
     *
     * @return array of token to Post
     */
    public function listPosts()
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT id FROM post WHERE tid = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id);
        $ret = null;
        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 取得討論串資料
     *
     * @return ThreadPlus object
     */
    public function getThreadPlus()
    {
        if ($this->threadplus_obj_cache == null) {
            $this->threadplus_obj_cache = ThreadPlus::loadByTid($this->id);
        }
        return $this->threadplus_obj_cache;
    }
}

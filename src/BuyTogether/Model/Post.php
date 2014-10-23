<?php

namespace Buytogether\Model;

use PDO;
use DateTime;
use Fruit\Seed;

/**
 * 文章
 */
class Post extends Seed
{
    private $id;
    private $content;
    private $create_time;
    private $update_time;
    private $tid;

    private $thread_obj_cache;

    protected function __construct($i, $c, $r, $u, $t)
    {
        $this->id = $i;
        $this->content = $c;
        $this->create_time = $r;
        $this->update_time = $u;
        $this->tid = $t;
        
        $this->thread_obj_cache = null;
    }

    /**
     * 新增一篇文章
     *
     * @param Thread $t thread object
     * @param string $c post content
     * @return Post object, or null
     */
    public static function create($t, $c)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO post (tid, content) VALUES (?,?)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $t->getToken());
        $stmt->bindValue(2, $c);

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $stmt->closeCursor();
            return self::load($id);
        }

        $stmt->closeCursor();
        return null;
    }

    /**
     * 取得一篇文章
     *
     * @param mixed $id token to get post
     * @return Post object, or null
     */
    public static function load($id)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT content, UNIX_TIMESTAMP(create_time), UNIX_TIMESTAMP(update_time), tid FROM post WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($id, $res[0], $res[1], $res[2], $res[3]);
            }
        }

        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 存檔
     */
    public function save()
    {
        $this->update_time = (new DateTime())->getTimestamp();
        $db = self::getConfig()->getDb();
        $sql = 'UPDATE post SET content = ?, update_time = FROM_UNIXTIME(?) WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->content);
        $stmt->bindValue(2, $this->update_time);
        $stmt->bindValue(3, $this->id);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * 刪除
     */
    public function delete()
    {
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM post WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id);
        if ($stmt->execute()) {
            $this->id = null;
        }
        $stmt->closeCursor();
    }

    /**
     * 取得代碼
     * @return mixed token
     */
    public function getToken()
    {
        return $this->id;
    }

    /**
     * 取得發文時間
     *
     * @return DateTime object
     */
    public function getCreateTime()
    {
        return new DateTime('@' . $this->create_time);
    }

    /**
     * 取得編輯時間
     *
     * @return DateTime object
     */
    public function getUpdateTime()
    {
        if ($this->update_time == null) {
            return null;
        }
        return new DateTime('@' . $this->update_time);
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
     * 取得文章內容
     *
     * @return string of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 修改文章
     *
     * @param string $c content
     */
    public function setContent($c)
    {
        $this->content = $c;
    }
}

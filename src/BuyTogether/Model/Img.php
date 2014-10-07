<?php
namespace BuyTogether\Model;

use PDO;
use DateTime;
use Fruit\Seed;

class Img extends Seed
{
    private $path;
    private $mime;
    private $location;
    private $time;

    protected function __construct($p, $m, $l, $c)
    {
        $this->path = $p;
        $this->mime = $m;
        $this->location = $l;
        $this->time = $c;
    }

    private static function generate($f, $location)
    {
        $base_dir = self::getConfig()->get('dir', $location);
        $prefix = self::getConfig()->get('prefix', $location);
        return tempnam($base_dir, $prefix);
    }

    private static function fn($f, $location)
    {
        $base_dir = self::getConfig()->get('dir', $location);
        return $base_dir . DIRECTORY_SEPARATOR . $f;
    }

    public static function create($path, $location)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO `img` ';
        $sql .= '(`path`, `mime`, `location`) VALUES (?,?,?)';

        if (!is_file($path)) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);
        if (substr($mime, 0, 6) != 'image/') {
            return null;
        }
        $fn = self::generate($path, $location);

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, basename($fn));
        $stmt->bindValue(2, $mime);
        $stmt->bindValue(3, $location);

        if ($stmt->execute()) {
            $stmt->closeCursor();
            copy($path, $fn);
            return self::load(basename($fn));
        }

        $stmt->closeCursor();
        return null;
    }

    public static function load($id)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT `path`, `mime`, `location`, UNIX_TIMESTAMP(time) ';
        $sql .= 'FROM `img` WHERE path= ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res[0], $res[1], $res[2], $res[3]);
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
        if ($this->path == null) {
            return;
        }
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM `img` WHERE path = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->path);
        if ($stmt->execute()) {
            unlink(self::fn($this->path, $this->location));
            $this->path = null;
        }
        $stmt->closeCursor();
    }

    /**
     * 取得檔案位置
     *
     * @return string of file path
     */
    public function getPath()
    {
        return self::fn($this->path, $this->location);
    }

    /**
     * 直接輸出檔案 (readfile)
     */
    public function readFile()
    {
        readfile(self::fn($this->path, $this->location));
    }

    /**
     * 取得代碼
     *
     * @return mixed token
     */
    public function getToken()
    {
        return $this->path;
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
}

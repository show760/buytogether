<?php
namespace BuyTogether\Model;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use BuyTogetherTest\DataSet;
use BuyTogetherTest\Tool;
use BuyTogether\Model\Thread;
use BuyTogether\Model\User;
use BuyTogether\Model\Post;

class PostTest extends PHPUnit_Extensions_Database_TestCase
{

    const CONTENT = 'content';
    const CONTENT2 = 'content2';
    const CREATE = 86400;

    public function getConnection()
    {
        return $this->createDefaultDBConnection(Tool::getConfig()->getDb(), "default");
    }

    public function getDataSet()
    {
        $d = new DateTime('@' . self::CREATE);
        return new DataSet(
            [
                'thread' => [
                    ['id' => 1, 'title' => 'test title']
                ],
                'user' => [
                    [
                        'id' => 1,
                        'email' => '123@hotmail.com',
                        'password' => '123',
                        'name' => 'testName',
                        'birth' => '199800101',
                        'address' => '台北大安',
                        'counties' => '台北'
                    ]
                ],
                'post' => [
                    [
                        'id' => 1,
                        'content' => self::CONTENT,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'update_time' => null,
                        'tid' => 1,
                        'uid' => 1
                    ],
                    [
                        'id' => 2,
                        'content' => self::CONTENT,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'update_time' => null,
                        'tid' => 1,
                        'uid' => 1
                    ]
                ]
            ]
        );
    }

    public function getUpdatedDataSet($u)
    {
        $u->setTimezone(new \DateTimeZone('+08:00'));
        $d = new DateTime('@' . self::CREATE);
        return new DataSet(
            [
                'thread' => [
                    ['id' => 1, 'title' => 'test']
                ],
                'user' => [
                    [
                        'id' => 1,
                        'email' => '123@hotmail.com',
                        'password' => '123',
                        'name' => 'testName',
                        'birth' => '199800101',
                        'address' => '台北大安',
                        'counties' => '台北'
                    ]
                ],
                'post' => [
                    [
                        'id' => 1,
                        'content' => self::CONTENT2,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'update_time' => $u->format('Y-m-d H:i:s'),
                        'tid' => 1,
                        'uid' => 1
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(2, $this->getConnection()->getRowCount('post'), 'Pre-Condition');
        $ret = Post::create(Thread::load(1), User::load(1), self::CONTENT);
        $this->assertNull($ret->getUpdateTime(), 'Should return null when no update time');
        $this->assertInstanceOf('BuyTogether\Model\Post', $ret);
        $this->assertEquals(3, $this->getConnection()->getRowCount('post'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(2, $this->getConnection()->getRowCount('post'), 'Pre-Condition');
        $ret = Post::load(1);
        $this->assertInstanceOf('BuyTogether\Model\Post', $ret, 'Post loading failed');
        $ret = Post::load(3);
        $this->assertNull($ret, 'Loaded non-exist post');
    }

    public function testDelete()
    {
        $this->assertEquals(2, $this->getConnection()->getRowCount('post'), 'Pre-Condition');
        $ret = Post::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(1, $this->getConnection()->getRowCount('post'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(1, $this->getConnection()->getRowCount('post'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(2, $this->getConnection()->getRowCount('post'), 'Pre-Condition');
        $ret = Post::load(1);
        $ret->setContent(self::CONTENT2);
        $ret->save();
        $actual = $this->getConnection()->createQueryTable('post', 'SELECT * FROM post WHERE id = 1');
        $expect = $this->getUpdatedDataSet($ret->getUpdateTime())->getTable('post');
        $this->assertTablesEqual($expect, $actual, 'update failed');
        $ret->delete();
        $ret->save();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
    }
}

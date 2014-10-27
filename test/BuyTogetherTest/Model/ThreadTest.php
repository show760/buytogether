<?php

namespace BuyTogetherTest\Model;

use PHPUnit_Extensions_Database_TestCase;
use BuyTogetherTest\DataSet;
use BuyTogetherTest\Tool;
use BuyTogether\Model\Thread;

class ThreadTest extends PHPUnit_Extensions_Database_TestCase
{

    const TITLE = 'test';

    public function getConnection()
    {
        return $this->createDefaultDBConnection(Tool::getConfig()->getDb(), "default");
    }

    public function getDataSet()
    {
        $d = new \DateTime();
        return new DataSet(
            [
                'thread' => [
                    ['id' => 1, 'title' => 'test title']
                ],
                'post' => [
                    [
                        'id' => 1,
                        'content' => 'test',
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'update_time' => null,
                        'tid' => 1,
                        'uid' => 1
                    ]
                ]
            ]
        );
    }

    public function getUpdatedDataSet()
    {
        return new DataSet(
            [
                'thread' => [
                    ['id' => 1, 'title' => self::TITLE]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'), 'Pre-Condition');
        $ret = Thread::create(self::TITLE);
        $this->assertInstanceOf('BuyTogether\Model\Thread', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('thread'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'), 'Pre-Condition');
        $ret = Thread::load(1);
        $this->assertInstanceOf('BuyTogether\Model\Thread', $ret, 'Thread loading failed');
        $ret = Thread::load(2);
        $this->assertNull($ret, 'Loaded non-exist thread');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'), 'Pre-Condition');
        $ret = Thread::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('thread'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('thread'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'), 'Pre-Condition');
        $ret = Thread::load(1);
        $ret->setTitle(self::TITLE);
        $ret->save();
        $actual = $this->getConnection()->createQueryTable('thread', 'SELECT * FROM thread');
        $expect = $this->getUpdatedDataSet()->getTable('thread');
        $this->assertTablesEqual($expect, $actual, 'update failed');
        $ret->delete();
        $ret->save();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
    }

    public function testListAll()
    {
        $list = Thread::listAll();
        $this->assertEquals(['1'], $list, 'Wtf thread are you listing?');
    }

    public function testListPost()
    {
        $thread = Thread::load(1);
        $list = $thread->listPosts();
        $this->assertEquals(['1'], $list, 'Wtf thread are you listing?');
    }
}

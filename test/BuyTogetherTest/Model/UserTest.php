<?php
namespace BuyTogetherTest\Model;

use PHPUnit_Extensions_Database_TestCase;
use BuyTogetherTest\DataSet;
use BuyTogetherTest\Tool;
use BuyTogether\Model\User;

class UserTest extends PHPUnit_Extensions_Database_TestCase
{
    private $info;

    public function __construct()
    {
        $this->info = array(
            'email' => 'test123@gmail.com',
            'pass' => '123',
            'name' => 'testname',
            'birth' => '19920520',
            'address' => '台中市神岡區',
            'counties' => '台中'
        );
    }

    public function getConnection()
    {
        return $this->createDefaultDBConnection(Tool::getConfig()->getDb(), "default");
    }

    public function getDataSet()
    {
        return new DataSet(
            [
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
                ]
            ]
        );
    }

    public function getUpdatedDataSet()
    {
        return new DataSet(
            [
                'user' => [
                    [
                        'id' => 1,
                        'email' => '123@hotmail.com',
                        'password' => '321',
                        'name' => 'testName2',
                        'birth' => '19980228',
                        'power' => '正常',
                        'address' => '台中豐原',
                        'counties' => '台中',
                        'run' => 0,
                        'join' => 0,
                        'main' => 0
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('user'), 'Pre-Condition');
        $ret = User::create($this->info);
        $this->assertInstanceOf('BuyTogether\Model\User', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('user'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('user'), 'Pre-Condition');
        $ret = User::load(1);
        $this->assertInstanceOf('BuyTogether\Model\User', $ret, 'User loading failed');
        $ret = User::load(2);
        $this->assertNull($ret, 'Loaded non-exist User');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('user'), 'Pre-Condition');
        $ret = User::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('user'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('user'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('user'), 'Pre-Condition');
        $ret = User::load(1);
        $ret->setPass('321');
        $ret->setName('testName2');
        $ret->setBirth('19980228');
        $ret->setAddress('台中豐原');
        $ret->setCounties('台中');
        $ret->save();
        $actual = $this->getConnection()->createQueryTable('user', 'SELECT * FROM `user` WHERE `id` = 1');
        $expect = $this->getUpdatedDataSet()->getTable('user');
        $this->assertTablesEqual($expect, $actual, 'update failed');
        $ret->delete();
        $ret->save();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
    }
}

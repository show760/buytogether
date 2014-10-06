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
                'members' => [
                    [
                        'members_Id' => 1,
                        'members_EMAIL' => '123@hotmail.com',
                        'members_PASSWORD' => '123',
                        'members_NAME' => 'testName',
                        'members_BIRTH' => '199800101',
                        'members_ADDRESS' => '台北大安',
                        'members_COUNTIES' => '台北'
                    ]
                ]
            ]
        );
    }

    public function getUpdatedDataSet()
    {
        return new DataSet(
            [
                'members' => [
                    [
                        'members_Id' => 1,
                        'members_EMAIL' => '123@hotmail.com',
                        'members_PASSWORD' => '321',
                        'members_NAME' => 'testName2',
                        'members_BIRTH' => '19980228',
                        'members_POWER' => '正常',
                        'members_ADDRESS' => '台中豐原',
                        'members_COUNTIES' => '台中',
                        'members_RUN' => 0,
                        'members_JOIN' => 0,
                        'members_MAIN' => 0
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('members'), 'Pre-Condition');
        $ret = User::create($this->info);
        $this->assertInstanceOf('BuyTogether\Model\User', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('members'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('members'), 'Pre-Condition');
        $ret = User::load(1);
        $this->assertInstanceOf('BuyTogether\Model\User', $ret, 'User loading failed');
        $ret = User::load(2);
        $this->assertNull($ret, 'Loaded non-exist User');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('members'), 'Pre-Condition');
        $ret = User::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('members'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('members'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('members'), 'Pre-Condition');
        $ret = User::load(1);
        $ret->setPass('321');
        $ret->setName('testName2');
        $ret->setBirth('19980228');
        $ret->setAddress('台中豐原');
        $ret->setCounties('台中');
        $ret->save();
        $actual = $this->getConnection()->createQueryTable('members', 'SELECT * FROM `members` WHERE `members_Id` = 1');
        $expect = $this->getUpdatedDataSet()->getTable('members');
        $this->assertTablesEqual($expect, $actual, 'update failed');
        $ret->delete();
        $ret->save();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
    }
}

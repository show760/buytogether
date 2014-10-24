<?php
namespace BuyTogetherTest\Model;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use BuyTogetherTest\DataSet;
use BuyTogetherTest\Tool;
use BuyTogether\Model\Buy;
use BuyTogether\Model\User;
use BuyTogether\Model\Join;

class JoinTest extends PHPUnit_Extensions_Database_TestCase
{
    public function getConnection()
    {
        return $this->createDefaultDBConnection(Tool::getConfig()->getDb(), "default");
    }

    public function getDataSet()
    {
        $d = new DateTime();
        return new DataSet(
            [
                'buy' => [
                    [
                        'buy_Id' => 1,
                        'buy_Time' => $d->format('Y-m-d H:i:s'),
                        'buy_Name' => 'test name',
                        'buy_Price' => 300,
                        'buy_Oprice' => 400,
                        'buy_Com' => 'test com',
                        'buy_Det' => 'test det',
                        'buy_Owner' => 'test owner',
                        'buy_Class' => 'goodeat',
                        'buy_Area' => '台中',
                        'buy_End' => 'open',
                        'buy_Q' => 100,
                        'buy_Num' => 10,
                        'buy_ConRun' => 0,
                        'buy_ConJoin' => 0,
                        'buy_Methor' => 'facetoface',
                        'buy_Gname' => 'test gname',
                        'buy_Gacc' => 'test gacc'
                    ]
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
                    ],
                    [
                        'id' => 2,
                        'email' => 'show760@hotmail.com',
                        'password' => '123',
                        'name' => 'testName',
                        'birth' => '19920520',
                        'address' => '台中大雅',
                        'counties' => '台中'
                    ]
                ],
                'join' => [
                    [
                        'id' => 1,
                        'bid' => 1,
                        'uid' => 1,
                        'quantity' => 10,
                        'handle' => '揪團中'
                    ]
                ]
            ]
        );
    }
    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('join'), 'Pre-Condition');
        $ret = Join::create(Buy::load(1), User::load(2), 10);
        $this->assertInstanceOf('BuyTogether\Model\Join', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('join'), 'Inserting failed');
    }
    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('join'), 'Pre-Condition');
        $ret = Join::load(1);
        $this->assertInstanceOf('BuyTogether\Model\Join', $ret, 'Join loading failed');
        $ret = Join::load(2);
        $this->assertNull($ret, 'Loaded non-exist buylist');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('join'), 'Pre-Condition');
        $ret = Join::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('join'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('join'), 'Unknown record deleted');
    }
}

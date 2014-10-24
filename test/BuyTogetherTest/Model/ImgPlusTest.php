<?php
namespace BuyTogetherTest\Model;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use BuyTogetherTest\DataSet;
use BuyTogetherTest\Tool;
use BuyTogether\Model\Img;
use BuyTogether\Model\Buy;
use BuyTogether\Model\User;
use BuyTogether\Model\Join;
use BuyTogether\Model\ImgPlus;

class BuyimgTest extends PHPUnit_Extensions_Database_TestCase
{
    const FN = 'test.jpg';
    const FN2 = 'test2.jpg';
    const FN3 = 'test3.jpg';
    const FN4 = 'test4.jpg';

    public function getConnection()
    {
        return $this->createDefaultDBConnection(Tool::getConfig()->getDb(), "default");
    }

    public function getDataSet()
    {
        $d = new DateTime();
        return new DataSet(
            [
                'img' => [
                    [
                        'path' => self::FN,
                        'mime' => 'image/jpeg',
                        'location' => 'upload',
                        'time' => $d->format('Y-m-d H:i:s')
                    ],
                    [
                        'path' => self::FN2,
                        'mime' => 'image/jpeg',
                        'location' => 'upload',
                        'time' => $d->format('Y-m-d H:i:s')
                    ],
                    [
                        'path' => self::FN3,
                        'mime' => 'image/jpeg',
                        'location' => 'upload',
                        'time' => $d->format('Y-m-d H:i:s')
                    ],
                    [
                        'path' => self::FN4,
                        'mime' => 'image/jpeg',
                        'location' => 'upload',
                        'time' => $d->format('Y-m-d H:i:s')
                    ]
                ],
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
                    ],
                    [
                        'buy_Id' => 2,
                        'buy_Time' => $d->format('Y-m-d H:i:s'),
                        'buy_Name' => 'test name',
                        'buy_Price' => 150,
                        'buy_Oprice' => 300,
                        'buy_Com' => 'test com',
                        'buy_Det' => 'test det',
                        'buy_Owner' => 'test owner',
                        'buy_Class' => 'goodeat',
                        'buy_Area' => '台中',
                        'buy_End' => 'open',
                        'buy_Q' => 300,
                        'buy_Num' => 0,
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
                ],
                'imgplus' => [
                    [
                        'id' => 1,
                        'bid' => 1,
                        'gid' => self::FN
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('imgplus'), 'Pre-Condition');
        $ret = ImgPlus::create(Img::load(self::FN2), Buy::load(2));
        $this->assertInstanceOf('BuyTogether\Model\ImgPlus', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('imgplus'), 'Inserting failed');
        $ret = ImgPlus::create(Img::load(self::FN3), null, User::load(1));
        $this->assertInstanceOf('BuyTogether\Model\ImgPlus', $ret);
        $this->assertEquals(3, $this->getConnection()->getRowCount('imgplus'), 'Inserting failed');
        $ret = ImgPlus::create(Img::load(self::FN4), null, null, Join::load(1));
        $this->assertInstanceOf('BuyTogether\Model\ImgPlus', $ret);
        $this->assertEquals(4, $this->getConnection()->getRowCount('imgplus'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('imgplus'), 'Pre-Condition');
        $ret = ImgPlus::load(1);
        $this->assertInstanceOf('BuyTogether\Model\ImgPlus', $ret, 'Buyimg loading failed');
        $ret = ImgPlus::load(2);
        $this->assertNull($ret, 'Loaded non-exist buylist');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('imgplus'), 'Pre-Condition');
        $ret = ImgPlus::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('imgplus'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('imgplus'), 'Unknown record deleted');
    }
}

<?php
namespace BuyTogetherTest\Model;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use BuyTogetherTest\DataSet;
use BuyTogetherTest\Tool;
use BuyTogether\Model\Img;
use BuyTogether\Model\Buy;
use BuyTogether\Model\BuyImg;

class BuyimgTest extends PHPUnit_Extensions_Database_TestCase
{
    const FN = 'test.jpg';
    const FN2 = 'test2.jpg';

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
                        'buy_Num' => 0,
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
                'buyimg' => [
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
        $this->assertEquals(1, $this->getConnection()->getRowCount('buyimg'), 'Pre-Condition');
        $ret = BuyImg::create(Buy::load(2), Img::load(self::FN2));
        $this->assertInstanceOf('BuyTogether\Model\BuyImg', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('buyimg'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('buyimg'), 'Pre-Condition');
        $ret = BuyImg::load(1);
        $this->assertInstanceOf('BuyTogether\Model\BuyImg', $ret, 'Buyimg loading failed');
        $ret = BuyImg::load(2);
        $this->assertNull($ret, 'Loaded non-exist buylist');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('buyimg'), 'Pre-Condition');
        $ret = BuyImg::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('buyimg'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('buyimg'), 'Unknown record deleted');
    }
}

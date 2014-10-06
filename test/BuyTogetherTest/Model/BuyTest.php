<?php
namespace BuyTogetherTest\Model;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use BuyTogetherTest\DataSet;
use BuyTogetherTest\Tool;
use BuyTogether\Model\Buy;

class BuyTest extends PHPUnit_Extensions_Database_TestCase
{
    const FN = 'test.jpg';
    const FN2 = 'test2.jpg';

    private $fn;
    private $info;

    public function __construct()
    {
        $this->fn = TEST_DIR . '/files/';
        $this->info = array(
            'name' => 'googeat',
            'price' => 300,
            'oprice' => 400,
            'com' => 'com',
            'det' => 'det',
            'owner' => 'owner',
            'class' => 'gooduse',
            'area' => '台北',
            'quantity' => 123,
            'conrun' => 0,
            'conjoin' => 0,
            'methor' => 'facetoface',
            'gname' => '',
            'gacc' => ''
        );
    }

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
                        'buy_Path' => self::FN,
                        'buy_Mime' => 'image/jpeg',
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
                    ]
                ]
            ]
        );
    }

    public function getUpdatedDataSet()
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
                        'buy_Path' => self::FN,
                        'buy_Mime' => 'image/jpeg',
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
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('buy'), 'Pre-Condition');
        $ret = Buy::create($this->info, $this->fn . self::FN2);
        $this->assertInstanceOf('BuyTogether\Model\Buy', $ret);
        $this->assertTrue(file_exists($ret->getPath()), 'Can not find photo file');
        $this->assertEquals(2, $this->getConnection()->getRowCount('buy'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('buy'), 'Pre-Condition');
        $ret = Buy::load(1);
        $this->assertInstanceOf('BuyTogether\Model\Buy', $ret, 'Photo loading failed');
        $ret = Buy::load(2);
        $this->assertNull($ret, 'Loaded non-exist buylist');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('buy'), 'Pre-Condition');
        $ret = Buy::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('buy'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('buy'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('buy'), 'Pre-Condition');
        $ret = Buy::load(1);
        $ret->setNum(10);
        $ret->save();
        $actual = $this->getConnection()->createQueryTable('buy', 'SELECT * FROM buy');
        $expect = $this->getUpdatedDataSet()->getTable('buy');
        $this->assertTablesEqual($expect, $actual, 'update failed');
    }
}

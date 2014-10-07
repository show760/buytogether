<?php
namespace BuyTogetherTest\Model;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use BuyTogetherTest\DataSet;
use BuyTogetherTest\Tool;
use BuyTogether\Model\Img;

class ImgTest extends PHPUnit_Extensions_Database_TestCase
{
    const FN = 'test.jpg';
    const FN2 = 'test2.jpg';

    private $fn;
    private $location;

    public function __construct()
    {
        $this->fn = TEST_DIR . '/files/';
        $this->location = 'upload';
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
                'img' => [
                    [
                        'path' => self::FN,
                        'mime' => 'image/jpeg',
                        'location' => 'upload',
                        'time' => $d->format('Y-m-d H:i:s')
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('img'), 'Pre-Condition');
        $ret = Img::create($this->fn . self::FN2, $this->location);
        $this->assertInstanceOf('BuyTogether\Model\Img', $ret);
        $this->assertTrue(file_exists($ret->getPath()), 'Can not find img file');
        $this->assertEquals(2, $this->getConnection()->getRowCount('img'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('img'), 'Pre-Condition');
        $ret = Img::load(self::FN);
        $this->assertInstanceOf('BuyTogether\Model\Img', $ret, 'Img loading failed');
        $ret = Img::load(self::FN2);
        $this->assertNull($ret, 'Loaded non-exist photo');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('img'), 'Pre-Condition');
        $ret = Img::load(self::FN);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('img'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('img'), 'Unknown record deleted');
    }
}

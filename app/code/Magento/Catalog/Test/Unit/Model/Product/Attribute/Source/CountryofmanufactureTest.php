<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Test\Unit\Model\Product\Attribute\Source;

use Magento\Framework\Json\JsonInterface;

class CountryofmanufactureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManagerMock;

    /**
     * @var \Magento\Store\Model\Store
     */
    protected $storeMock;

    /**
     * @var \Magento\Framework\App\Cache\Type\Config
     */
    protected $cacheConfig;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManagerHelper;

    /** @var \Magento\Catalog\Model\Product\Attribute\Source\Countryofmanufacture */
    private $countryOfManufacture;
    protected function setUp()
    {
        $this->storeManagerMock = $this->getMock(\Magento\Store\Model\StoreManagerInterface::class);
        $this->storeMock = $this->getMock(\Magento\Store\Model\Store::class, [], [], '', false);
        $this->cacheConfig = $this->getMock(\Magento\Framework\App\Cache\Type\Config::class, [], [], '', false);
        $this->objectManagerHelper = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->countryOfManufacture = $this->objectManagerHelper->getObject(
            \Magento\Catalog\Model\Product\Attribute\Source\Countryofmanufacture::class,
            [
                'storeManager' => $this->storeManagerMock,
                'configCacheType' => $this->cacheConfig,
            ]
        );

        $jsonMock = $this->getMock(JsonInterface::class, [], [], '', false);
        $jsonMock->method('encode')
            ->willReturnCallback(function ($string) {
                return json_encode($string);
            });
        $jsonMock->method('decode')
            ->willReturnCallback(function ($string) {
                return json_decode($string, true);
            });
        $this->objectManagerHelper->setBackwardCompatibleProperty(
            $this->countryOfManufacture,
            'json',
            $jsonMock
        );
    }

    /**
     * Test for getAllOptions method
     *
     * @param $cachedDataSrl
     * @param $cachedDataUnsrl
     *
     * @dataProvider testGetAllOptionsDataProvider
     */
    public function testGetAllOptions($cachedDataSrl, $cachedDataUnsrl)
    {
        $this->storeMock->expects($this->once())->method('getCode')->will($this->returnValue('store_code'));
        $this->storeManagerMock->expects($this->once())->method('getStore')->will($this->returnValue($this->storeMock));
        $this->cacheConfig->expects($this->once())
            ->method('load')
            ->with($this->equalTo('COUNTRYOFMANUFACTURE_SELECT_STORE_store_code'))
            ->will($this->returnValue($cachedDataSrl));

        $this->assertEquals($cachedDataUnsrl, $this->countryOfManufacture->getAllOptions());
    }

    /**
     * Data provider for testGetAllOptions
     *
     * @return array
     */
    public function testGetAllOptionsDataProvider()
    {
        return
            [
                ['cachedDataSrl' => json_encode(['key' => 'data']), 'cachedDataUnsrl' => ['key' => 'data']]
            ];
    }
}

<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Catalog\Test\Unit\Model\Layer\Category;

class FilterableAttributeListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Catalog\Model\Layer\Search\FilterableAttributeList
     */
    protected $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Catalog\Model\Resource\Product\Attribute\CollectionFactory
     */
    protected $collectionFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManagerMock;

    protected function setUp()
    {
        $this->collectionFactoryMock = $this->getMock(
            '\Magento\Catalog\Model\Resource\Product\Attribute\CollectionFactory', ['create'], [], '', false);

        $this->storeManagerMock = $this->getMock(
            '\Magento\Store\Model\StoreManagerInterface', [], [], '', false
        );

        $this->model = new \Magento\Catalog\Model\Layer\Search\FilterableAttributeList(
            $this->collectionFactoryMock,
            $this->storeManagerMock
        );

    }

    public function testGetList()
    {
        $storeMock = $this->getMock('\Magento\Store\Model\Store', [], [], '', false);
        $this->storeManagerMock->expects($this->once())->method('getStore')->will($this->returnValue($storeMock));

        $storeId = 4321;
        $storeMock->expects($this->once())->method('getId')->will($this->returnValue($storeId));

        $collectionMock = $this->getMock(
            '\Magento\Catalog\Model\Resource\Product\Attribute\Collection', [], [], '', false
        );
        $this->collectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($collectionMock));

        $collectionMock
            ->expects($this->once())
            ->method('setItemObjectClass')
            ->with('Magento\Catalog\Model\Resource\Eav\Attribute')
            ->will($this->returnSelf());
        $collectionMock
            ->expects($this->once())
            ->method('addStoreLabel')
            ->with($storeId)
            ->will($this->returnSelf());
        $collectionMock
            ->expects($this->once())
            ->method('setOrder')
            ->with('position', 'ASC');
        $collectionMock->expects($this->once())->method('addIsFilterableInSearchFilter')->will($this->returnSelf());
        $collectionMock->expects($this->once())->method('load');

        $this->assertEquals($collectionMock, $this->model->getList());
    }
}

<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */

namespace Magento\Sales\Test\Constraint;

use Magento\Sales\Test\Fixture\OrderInjectable;
use Magento\Sales\Test\Page\Adminhtml\OrderIndex;
use Mtf\Constraint\AbstractConstraint;

/**
 * Class AssertOrderInOrdersGrid
 * Assert that order is present in Orders grid
 */
class AssertOrderInOrdersGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that order with fixture data is present in Sales -> Orders Grid
     *
     * @param OrderInjectable $order
     * @param OrderIndex $orderIndex
     * @param string|null $status [optional]
     * @param string $orderId [optional]
     * @return void
     */
    public function processAssert(OrderInjectable $order, OrderIndex $orderIndex, $status = null, $orderId = '')
    {
        $orderIndex->open();
        $this->assert($order, $orderIndex, $status, $orderId);
    }

    /**
     * Process assert
     *
     * @param OrderInjectable $order
     * @param OrderIndex $orderIndex
     * @param string $status
     * @param string $orderId [optional]
     * @return void
     */
    protected function assert(OrderInjectable $order, OrderIndex $orderIndex, $status, $orderId = '')
    {
        $filter = [
            'id' => $order->hasData('id') ? $order->getId() : $orderId,
            'status' => $status,
        ];
        $errorMessage = implode(', ', $filter);
        \PHPUnit_Framework_Assert::assertTrue(
            $orderIndex->getSalesOrderGrid()->isRowVisible($filter),
            'Order with following data \'' . $errorMessage . '\' is absent in Orders grid.'
        );
    }

    /**
     * Returns a string representation of the object
     *
     * @return string
     */
    public function toString()
    {
        return 'Sales order is present in sales orders grid.';
    }
}
<?php


namespace Tests;


use PHPUnit\Framework\MockObject\MockObject;

trait MockHelperTrait
{
    /**
     * @param $className
     * @param $methodReturnValues
     *
     * @return MockObject
     */
    public function getMockObject($className, $methodReturnValues)
    {
        $mockObject = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();

        foreach ($methodReturnValues as $methodName => $returnValue) {
            $mockObject->expects($this->any())
                ->method($methodName)
                ->willReturn($returnValue);
        }

        return $mockObject;
    }
}
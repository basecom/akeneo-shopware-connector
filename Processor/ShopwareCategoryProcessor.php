<?php

namespace Basecom\Bundle\ShopwareConnectorBundle\Processor;

use Akeneo\Component\Batch\Item\AbstractConfigurableStepElement;
use Akeneo\Component\Batch\Item\ItemProcessorInterface;
use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Step\StepExecutionAwareInterface;

class ShopwareCategoryProcessor extends AbstractConfigurableStepElement implements ItemProcessorInterface, StepExecutionAwareInterface
{
    /** @var StepExecution */
    protected $stepExecution;

    public function process($item)
    {
        echo "\nCategory Processor...\n";
        return $item;
    }

    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }

    public function getConfigurationFields()
    {
        return array();
    }
}
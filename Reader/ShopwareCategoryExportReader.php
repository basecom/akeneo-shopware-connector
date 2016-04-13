<?php

namespace Basecom\Bundle\ShopwareConnectorBundle\Reader;

use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Item\AbstractConfigurableStepElement;
use Akeneo\Bundle\BatchBundle\Item\ItemReaderInterface;
use Akeneo\Bundle\BatchBundle\Step\StepExecutionAwareInterface;
use Akeneo\Component\Classification\Repository\CategoryRepositoryInterface;
use Basecom\Bundle\ShopwareConnectorBundle\Entity\Category;

class ShopwareCategoryExportReader extends AbstractConfigurableStepElement implements
    ItemReaderInterface,
    StepExecutionAwareInterface
{
    /** @var CategoryRepositoryInterface */
    protected $categoryRepository;

    /** @var StepExecution */
    protected $stepExecution;

    /** @var bool Checks if all categories are sent to the processor */
    protected $isExecuted = false;

    /** @var \ArrayIterator */
    protected $results;

    protected $rootCategory;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        if (!$this->isExecuted) {
            $this->isExecuted = true;

            $this->results = $this->getResults();
        }

        if (null !== $result = $this->results->current()) {
            $this->results->next();
            $this->stepExecution->incrementSummaryInfo('read');
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFields()
    {
        return [
            'rootCategory' => [
                'options' => [
                    'label' => 'Root category',
                    'help'  => 'The code of the root category you want to export'
                ]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }

    /**
     * @return \ArrayIterator
     */
    protected function getResults()
    {
        /** @var Category $category */
        $category = $this->categoryRepository->findOneByIdentifier($this->rootCategory);
        $categories = $this->categoryRepository->findBy(array('root' => $category->getRoot()));
        //unset($categories[0]);
        return new \ArrayIterator($categories);
    }

    /**
     * @return mixed
     */
    public function getRootCategory()
    {
        return $this->rootCategory;
    }

    /**
     * @param mixed $rootCategory
     */
    public function setRootCategory($rootCategory)
    {
        $this->rootCategory = $rootCategory;
    }
}
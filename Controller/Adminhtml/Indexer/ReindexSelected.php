<?php

/**
 * MageCheck
 * Magento 2 Reindex In Admin
 *
 * @author Chiriac Victor
 * @since 07.2018
 * @category   MageCheck
 * @package    MageCheck_Reindex
 * @copyright  Copyright (c) 2017 Mage Check (http://www.magecheck.com/)
 */

namespace MageCheck\Reindex\Controller\Adminhtml\Indexer;

class ReindexSelected extends \MageCheck\Reindex\Controller\Adminhtml\Indexer
{

    /** @var \Magento\Framework\Indexer\IndexerInterface  */
    protected $_indexerFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param \Magento\Indexer\Model\IndexerFactory $indexerFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Indexer\Model\IndexerFactory $indexerFactory
    ) {
        $this->_indexerFactory = $indexerFactory;
        parent::__construct($context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        if (!is_array($indexerIds)) {
            $this->messageManager->addError(__('Please select indexers.'));
        } else {
            try {
                foreach ($indexerIds as $indexerId) {
                    $indexer = $this->_indexerFactory->create();
                    $indexer->load($indexerId)->reindexAll();
                }

                $this->messageManager->addSuccess(
                    __('Reindex %1 indexer(s).', count($indexerIds))
                );
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __("There has been an error while reindexing")
                );
            }
        }

        $this->_redirect('*/*/list');
    }
}

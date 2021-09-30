<?php

namespace Magecustom\PageListWidget\Block\Widget;

use Magento\Framework\VIew\Element\Template;
use Magento\Widget\Block\BlockInterface;

/**
 * Get title of page list and selected or specified CMS pages
 * @package Magecustom\PageListWidget\Block\Widget
 */

class PageList extends Template implements BlockInterface
{
    public const DISPLAY_MODE_SPECIFIC = "specific_page";
    public const DISPLAY_MODE_ALL = "all_page";

    /**
     * Page Repository Interface
     *
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    protected $_pageRepositoryInterface;

    /**
     * Page Repository Interface
     *
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @var string
     */
    protected $_template = "page-list.phtml";

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Cms\Api\PageRepositoryInterface $pageRepositoryInterface
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepositoryInterface,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_pageRepositoryInterface = $pageRepositoryInterface;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Get title of the page list
     *
     * @return string
     */
    public function getTitle() {
        return (string)$this->getData('title');
    }

    /**
     * Get all or specified CMS pages for page list
     *
     * @return array
     */
    public function getPages() {
        $pageArray = [];
        $selectedPages = [];

        $seachCriteria = $this->_searchCriteriaBuilder->create();
        $pages = $this->_pageRepositoryInterface->getList($seachCriteria)->getItems();

        if($this->getData('display_mode') == self::DISPLAY_MODE_SPECIFIC) {
            $selectedPages = explode(',', $this->getData('selected_page'));
        }

        $arrayNum = 0;
        foreach($pages as $page) {
            if(!empty($selectedPages)) {
                if(!in_array($page->getIdentifier(), $selectedPages)) {
                    continue;
                }
            }
            $pageArray[$arrayNum]["title"] = $page->getTitle();
            $pageArray[$arrayNum]["identifier"] = $page->getIdentifier();
            $arrayNum++;
        }
        return $pageArray;
    }

}

<?php
/**
 * Copyright (c) 2025. Volodymyr Hryvinskyi. All rights reserved.
 * Author: Volodymyr Hryvinskyi <volodymyr@hryvinskyi.com>
 * GitHub: https://github.com/hryvinskyi
 */

namespace Hryvinskyi\Csp\Model\UiComponent\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ReportActions extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private readonly UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['report_id'])) {
                continue;
            }
            $item[$this->getData('name')] = [
                'view' => [
                    'href' => $this->urlBuilder->getUrl(
                        'hryvinskyi_csp/report/view',
                        [
                            'id' => $item['report_id']
                        ]
                    ),
                    'label' => __('View')
                ],
                'convert' => [
                    'href' => $this->urlBuilder->getUrl(
                        'hryvinskyi_csp/report/convertToWhitelist',
                        [
                            'id' => $item['report_id']
                        ]
                    ),
                    'label' => __('Convert to Whitelist')
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl(
                        'hryvinskyi_csp/report/delete',
                        [
                            'id' => $item['report_id']
                        ]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete "${ $.$data.report_id } report"'),
                        'message' => __('Are you sure you wan\'t to delete a "${ $.$data.report_id }" report?')
                    ]
                ]
            ];
        }

        return $dataSource;
    }
}

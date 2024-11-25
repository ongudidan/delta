<?php

namespace app\components;

use yii\bootstrap5\LinkPager;
use yii\helpers\Html;

class CustomLinkPager extends LinkPager
{
    protected function renderPageButtons(): string
    {
        $buttons = [];
        $currentPage = $this->pagination->page;
        $totalPages = $this->pagination->pageCount;

        // Display pager only if there is more than one page
        if ($totalPages <= 1) {
            return '';
        }

        // Display "Start" button
        $buttons[] = $this->renderPageButton(
            'Start',
            0,
            'page-item' . ($currentPage <= 0 ? ' disabled' : ''),
            $currentPage <= 0,
            false
        );

        // Display "Previous" button
        $buttons[] = $this->renderPageButton(
            $this->prevPageLabel,
            $currentPage - 1,
            'page-item' . ($currentPage <= 0 ? ' disabled' : ''),
            $currentPage <= 0,
            false
        );

        // Show the first 10 pages as a sliding window
        $startPage = max(0, min($currentPage - 5, $totalPages - 12));
        $endPage = min($startPage + 5, $totalPages - 2);

        for ($i = $startPage; $i < $endPage; $i++) {
            $buttons[] = $this->renderPageButton(
                $i + 1,
                $i,
                'page-item' . ($i == $currentPage ? ' active' : ''),
                $i == $currentPage,
                false
            );
        }

        // Add dots if there are pages between the window and the last two pages
        if ($endPage < $totalPages - 2) {
            $buttons[] = Html::tag('li', '...', ['class' => 'page-item disabled']);
        }

        // Show the last 2 pages
        for ($i = $totalPages - 2; $i < $totalPages; $i++) {
            $buttons[] = $this->renderPageButton(
                $i + 1,
                $i,
                'page-item' . ($i == $currentPage ? ' active' : ''),
                $i == $currentPage,
                false
            );
        }

        // Display "Next" button
        $buttons[] = $this->renderPageButton(
            $this->nextPageLabel,
            $currentPage + 1,
            'page-item' . ($currentPage >= $totalPages - 1 ? ' disabled' : ''),
            $currentPage >= $totalPages - 1,
            false
        );

        // Display "End" button
        $buttons[] = $this->renderPageButton(
            'End',
            $totalPages - 1,
            'page-item' . ($currentPage >= $totalPages - 1 ? ' disabled' : ''),
            $currentPage >= $totalPages - 1,
            false
        );

        // Return the concatenated string of buttons
        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }
}

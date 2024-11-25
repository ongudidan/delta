<?php

use yii\helpers\Url;

// Get the current URL and parse the path
$currentUrl = Url::current();
$currentUrlPath = rtrim(parse_url($currentUrl, PHP_URL_PATH), '/');

// Define menu items using Yii's Url::to() for generating URLs
$menuItems = [
    ['label' => 'Dashboard', 'icon' => 'command', 'url' => Url::to(['/'])],

    Yii::$app->user->can('auth-item-view') ? [
        'label' => 'Auth Items & Rules',
        'icon' => 'command',
        'url' => Url::to(['/auth-item/index']),
        'submenu' => [
            ['label' => 'Auth Items', 'url' => Url::to(['/auth-item/index'])],
            ['label' => 'Auth Assignment', 'url' => Url::to(['/auth-assignment/index'])],
            ['label' => 'Auth Item Child', 'url' => Url::to(['/auth-item-child/index'])],
            ['label' => 'Auth Rules',  'url' => Url::to(['/auth-rule/index'])],
        ]
    ] : null,
    ['label' => 'Products', 'icon' => 'command', 'url' => Url::to(['/products/index'])],
    ['label' => 'Expenses', 'icon' => 'compass', 'url' => Url::to(['/expenses/index'])],
    Yii::$app->user->can('expense-category-view') ?
        ['label' => 'Expense Categories', 'icon' => 'compass', 'url' => Url::to(['/expense-categories/index'])] : null,
    ['label' => 'Sales', 'icon' => 'edit', 'url' => Url::to(['/sales/index'])],
    Yii::$app->user->can('user-view') ?
        ['label' => 'Users', 'icon' => 'eye', 'url' => Url::to(['/user/index'])] : null,
    Yii::$app->user->can('purchase-view') ?
        ['label' => 'Purchases', 'icon' => 'heart', 'url' => Url::to(['/purchases/index'])] : null,
    Yii::$app->user->can('category-view') ?
        ['label' => 'Categories', 'icon' => 'mail', 'url' => Url::to(['/categories/index'])] : null,
];

// Remove null values from the array
$menuItems = array_filter($menuItems);

?>

<div class="main-sidebar sidebar-style-2 ">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= Url::to('/') ?>">
                <img alt="" src="/web/otika/assets/img/logo.png" class="header-logo" />
                <span class="logo-name">DELTA</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <?php foreach ($menuItems as $item) : ?>
                <?php
                // Normalize the menu item URL for comparison
                $menuUrlPath = rtrim(parse_url(Url::to($item['url']), PHP_URL_PATH), '/');
                $isActive = ($currentUrlPath === $menuUrlPath);
                $hasSubmenu = isset($item['submenu']);
                $submenuActive = false;

                if ($hasSubmenu) {
                    // Check if any submenu item is active
                    foreach ($item['submenu'] as $subitem) {
                        $subitemUrlPath = rtrim(parse_url(Url::to($subitem['url']), PHP_URL_PATH), '/');
                        if ($currentUrlPath === $subitemUrlPath) {
                            $submenuActive = true;
                            break;
                        }
                    }
                    $isActive = $submenuActive; // Parent item is active if any submenu item is active
                }
                ?>
                <li class="dropdown <?= $isActive ? 'active' : '' ?>">
                    <a href="<?= $hasSubmenu ? '#' : Url::to($item['url']) ?>" class="nav-link <?= $hasSubmenu ? 'menu-toggle has-dropdown' : '' ?>">
                        <i data-feather="<?= $item['icon'] ?>"></i>
                        <span><?= htmlspecialchars($item['label']) ?></span>
                    </a>
                    <?php if ($hasSubmenu) : ?>
                        <ul class="dropdown-menu <?= $isActive ? 'show' : '' ?>">
                            <?php foreach ($item['submenu'] as $subitem) : ?>
                                <li class="<?= $currentUrlPath === rtrim(parse_url(Url::to($subitem['url']), PHP_URL_PATH), '/') ? 'active' : '' ?>">
                                    <a class="nav-link" href="<?= Url::to($subitem['url']) ?>"><?= htmlspecialchars($subitem['label']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>
</div>


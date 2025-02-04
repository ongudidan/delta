<?php

use yii\helpers\Url;

// Get the current module, controller, and action
$module = Yii::$app->controller->module->id;
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;

// Define sidebar menu structure with module, controller, and action
$sidebarMenus = [
    [
        'label' => 'Dashboard',
        'url' => Url::to(['/dashboard/default/index']),
        'icon' => 'fas fa-tachometer-alt',  // Dashboard icon
        'module' => 'dashboard',
        'controller' => 'default',
        'action' => 'index',
    ],
  

    [
        'label' => 'Bulk Sales',
        'url' => Url::to(['/dashboard/bulk-sale/index']),
        'icon' => 'fas fa-chart-line text-info',  // Bulk Sale icon
        'module' => 'dashboard',
        'controller' => 'bulk-sale',
        'action' => 'index',
    ],
    [
        'label' => 'Bulk Expenses',
        'url' => Url::to(['/dashboard/bulk-expense/index']),
        'icon' => 'fas fa-money-bill-wave text-danger',  // Updated Bulk Expense icon
        'module' => 'dashboard',
        'controller' => 'bulk-expense',
        'action' => 'index',
    ],
    [
        'label' => 'Bulk Purchases',
        'url' => Url::to(['/dashboard/bulk-purchase/index']),
        'icon' => 'fas fa-shopping-cart text-warning',  // Updated Bulk Purchase icon
        'module' => 'dashboard',
        'controller' => 'bulk-purchase',
        'action' => 'index',
    ],
    [
        'label' => 'Products',
        'url' => Url::to(['/dashboard/products/index']),
        'icon' => 'fas fa-shopping-cart text-success',  // Alternative Products icon
        'module' => 'dashboard',
        'controller' => 'products',
        'action' => 'index',
    ],
    [
        'label' => 'All Categories',
        'icon' => 'fas fa-tags text-info',  // Categories icon
        'submenu' => true,
        'active' => $module === 'dashboard' && $controller === 'categories' || $controller === 'expense-categories',
        'items' => [
            [
                'label' => 'Product Categories',
                'url' => Url::to(['/dashboard/categories/index']),
                'module' => 'dashboard',
                'controller' => 'categories',
                'action' => 'index',
            ],
            [
                'label' => 'Expense Categories',
                'url' => Url::to(['/dashboard/expense-categories/index']),
                'module' => 'dashboard',
                'controller' => 'expense-categories',
                'action' => 'index',
            ],
        ]
    ],
];




?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main Dashboard</span>
                </li>

                <?php foreach ($sidebarMenus as $menu): ?>
                    <?php
                    $submenuActive = false; // Track if any subitem is active
                    if (isset($menu['submenu']) && $menu['submenu']) {
                        foreach ($menu['items'] as $subItem) {
                            if ($module == $subItem['module'] && $controller == $subItem['controller'] && $action == $subItem['action']) {
                                $submenuActive = true;
                                break;
                            }
                        }
                    }
                    ?>

                    <?php if (isset($menu['submenu']) && $menu['submenu']): ?>
                        <!-- Submenu -->
                        <li class="submenu <?= ($menu['active'] || $submenuActive) ? 'active' : '' ?>">
                            <a href="#"><i class="<?= $menu['icon'] ?>"></i> <span> <?= $menu['label'] ?> </span> <span class="menu-arrow"></span></a>
                            <ul style="display: <?= $submenuActive ? 'block' : 'none' ?>;">
                                <?php foreach ($menu['items'] as $subItem): ?>
                                    <li>
                                        <a href="<?= $subItem['url'] ?>"
                                            class="pjax-link <?= ($module == $subItem['module'] && $controller == $subItem['controller'] && $action == $subItem['action']) ? 'active' : '' ?>">
                                            <?= $subItem['label'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Regular Menu -->
                        <li class="<?= ($module == $menu['module'] && $controller == $menu['controller'] && $action == $menu['action']) ? 'active' : '' ?>">
                            <a href="<?= $menu['url'] ?>" class="pjax-link"><i class="<?= $menu['icon'] ?>"></i> <span> <?= $menu['label'] ?> </span></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
</div>
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
        'label' => 'Products',
        'icon' => 'fas fa-box-open text-success',  // Products icon
        'submenu' => true,
        'active' => $module === 'dashboard' && $controller === 'products',
        'items' => [
            [
                'label' => 'Products List',
                'url' => Url::to(['/dashboard/products/index']),
                'module' => 'dashboard',
                'controller' => 'products',
                'action' => 'index',
            ],
            [
                'label' => 'Product Categories',
                'url' => Url::to(['/dashboard/categories/index']),
                'module' => 'dashboard',
                'controller' => 'categories',
                'action' => 'index',
            ],
        ]
    ],
    [
        'label' => 'Bulk Sales',
        'icon' => 'fas fa-chart-line text-info',  // Bulk Sale icon
        'submenu' => true,
        'active' => $module === 'dashboard' && $controller === 'bulk-sale',
        'items' => [
            [
                'label' => 'Sales List',
                'url' => Url::to(['/dashboard/bulk-sale/index']),
                'module' => 'dashboard',
                'controller' => 'bulk-sale',
                'action' => 'index',
            ],
            [
                'label' => 'Create Sale',
                'url' => Url::to(['/dashboard/bulk-sale/create']),
                'module' => 'dashboard',
                'controller' => 'bulk-sale',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Bulk Purchases',
        'icon' => 'fas fa-shopping-cart text-warning',  // Updated Bulk Purchase icon
        'submenu' => true,
        'active' => $module === 'dashboard' && $controller === 'bulk-purchase',
        'items' => [
            [
                'label' => 'Purchases List',
                'url' => Url::to(['/dashboard/bulk-purchase/index']),
                'module' => 'dashboard',
                'controller' => 'bulk-purchase',
                'action' => 'index',
            ],
            [
                'label' => 'Create Purchase',
                'url' => Url::to(['/dashboard/bulk-purchase/create']),
                'module' => 'dashboard',
                'controller' => 'bulk-purchase',
                'action' => 'create',
            ],
        ]
    ],
    [
        'label' => 'Bulk Expenses',
        'icon' => 'fas fa-money-bill-wave',  // Updated Bulk Expense icon
        'submenu' => true,
        'active' => $module === 'dashboard' && $controller === 'bulk-expense',
        'items' => [
            [
                'label' => 'Expenses List',
                'url' => Url::to(['/dashboard/bulk-expense/index']),
                'module' => 'dashboard',
                'controller' => 'bulk-expense',
                'action' => 'index',
            ],
            [
                'label' => 'Create Expense',
                'url' => Url::to(['/dashboard/bulk-expense/create']),
                'module' => 'dashboard',
                'controller' => 'bulk-expense',
                'action' => 'create',
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
    // [
    //     'label' => 'Expenses',
    //     'icon' => 'fas fa-receipt text-danger',  // Expenses icon
    //     'submenu' => true,
    //     'active' => $module === 'dashboard' && $controller === 'expenses',
    //     'items' => [
    //         [
    //             'label' => 'Expenses List',
    //             'url' => Url::to(['/dashboard/expenses/index']),
    //             'module' => 'dashboard',
    //             'controller' => 'expenses',
    //             'action' => 'index',
    //         ],
    //         [
    //             'label' => 'Create Expense',
    //             'url' => Url::to(['/dashboard/expenses/create']),
    //             'module' => 'dashboard',
    //             'controller' => 'expenses',
    //             'action' => 'create',
    //         ],
    //         [
    //             'label' => 'Expense Categories List',
    //             'url' => Url::to(['/dashboard/expense-categories/index']),
    //             'module' => 'dashboard',
    //             'controller' => 'expense-categories',
    //             'action' => 'index',
    //         ],
    //         [
    //             'label' => 'Create Expense Category',
    //             'url' => Url::to(['/dashboard/expense-categories/create']),
    //             'module' => 'dashboard',
    //             'controller' => 'expense-categories',
    //             'action' => 'create',
    //         ],
    //     ]
    // ],
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
                    <?php if (isset($menu['submenu']) && $menu['submenu']): ?>
                        <!-- Submenu -->
                        <li class="submenu <?= $menu['active'] ? 'active' : '' ?>">
                            <a href="#"><i class="<?= $menu['icon'] ?>"></i> <span> <?= $menu['label'] ?> </span> <span class="menu-arrow"></span></a>
                            <ul>
                                <?php foreach ($menu['items'] as $subItem): ?>
                                    <li>
                                        <a href="<?= $subItem['url'] ?>"
                                            class="<?= ($module == $subItem['module'] && $controller == $subItem['controller'] && $action == $subItem['action']) ? 'active' : '' ?>">
                                            <?= $subItem['label'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Regular Menu -->
                        <li class="<?= ($module == $menu['module'] && $controller == $menu['controller'] && $action == $menu['action']) ? 'active' : '' ?>">
                            <a href="<?= $menu['url'] ?>"><i class="<?= $menu['icon'] ?>"></i> <span> <?= $menu['label'] ?> </span></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
</div>
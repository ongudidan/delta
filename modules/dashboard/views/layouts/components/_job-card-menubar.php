 <?php

    use yii\helpers\Url;

    // Get the current module, controller, and action
    $module = Yii::$app->controller->module->id;
    $controller = Yii::$app->controller->id;

    $menus = [
        // [
        //     'label' => 'Home',
        //     'url' => Url::to(['/dashboard/card-setting/index']),
        //     'module' => 'dashboard',
        //     'controller' => 'card-setting',
        //     'active' => $controller === 'car-setting' && $module === 'dashboard',
        // ],
        [
            'label' => 'Car Makes',
            'url' => Url::to(['/dashboard/car-make/index']),
            'module' => 'dashboard',
            'controller' => 'car-make',
            'active' => $controller === 'car-make' && $module === 'dashboard',

        ],
        [
            'label' => 'Car Models',
            'url' => Url::to(['/dashboard/car-model/index']),
            'module' => 'dashboard',
            'controller' => 'car-model',
            'active' => $controller === 'car-model' && $module === 'dashboard',

        ],
        [
            'label' => 'Car Types',
            'url' => Url::to(['/dashboard/car-type/index']),
            'module' => 'dashboard',
            'controller' => 'car-type',
            'active' => $controller === 'car-type',

        ],
        [
            'label' => 'Car Defects',
            'url' => Url::to(['/dashboard/defect/index']),
            'module' => 'dashboard',
            'controller' => 'defect',
            'active' => $controller === 'defect',

        ],
    ]

    ?>

 <div class="card invoices-tabs-card border-0">
     <div class="card-body card-body pt-0 pb-0">
         <div class="invoices-main-tabs">
             <div class="row align-items-center">
                 <div class="col-lg-8 col-md-8">
                     <div class="invoices-tabs">
                         <ul>
                             <?php foreach ($menus as $menu) { ?>
                                 <li><a href="<?= $menu['url'] ?>" class="<?= $menu['active'] ? 'active' : '' ?>"><?= $menu['label'] ?></a></li>
                             <?php } ?>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
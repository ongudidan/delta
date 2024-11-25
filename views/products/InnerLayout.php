<?php

use app\models\Inventory;
use app\models\Products;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\ProductsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;


$meniItems= [
    ['id'=> 'home-tab4', ]
];

?>

<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
$this->registerJsFile('@web/js/script.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<div class="products-index">

    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body">
                <div class="row ">

                    <!-- ////////////////// -->
                    <?= $this->render('components/_sales-header') ?>
                    <!-- ///////////////////////// -->

                    <div class="col-12 col-sm-12 col-md-2 bg-light p-3 rounded-lg">
                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab4" data-toggle="tab" href="#home4" role="tab" aria-controls="home" aria-selected="true">All Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab4" href="<?= Url::to('/categories/index') ?>" role="tab" aria-controls="profile" aria-selected="false">All Categories</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab4" href="<?= Url::to('/sales/index') ?>" role="tab" aria-controls="profile" aria-selected="false">All Sales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-tab4" data-toggle="tab" href="#contact4" role="tab" aria-controls="contact" aria-selected="false">New Product</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-12 col-md-10">
                        <div class="tab-content no-padding" id="myTab2Content">
                            <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                <!-- <div class="card"> -->
                                <div class="card-header">
                                    <h4>
                                    </h4>
                                    <div class="card-header-action">
                                        <form method="get" action="<?= Url::to(['index']) ?>">
                                            <div class="input-group">
                                                <input type="text" name="ProductsSearch[product_name]" class="form-control" placeholder="Search by product name" value="<?= Html::encode($searchModel->product_name) ?>">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="sortable-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        #
                                                    </th>
                                                    <th>Product Name</th>
                                                    <th>Product Number</th>
                                                    <th> Quantity Availabe</th>
                                                    <th>Category</th>
                                                    <th>Selling price</th>
                                                    <th>Created At</th>
                                                    <th>Sale</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody class="ui-sortable">
                                                <?php foreach ($dataProvider->getModels() as $index => $product) {
                                                    $inventory = Inventory::findOne(['product_id' => $product->product_id]);
                                                ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                        </td>
                                                        <td><?= $product->product_name ?></td>
                                                        <td><?= $product->product_number ?></td>
                                                        <td><?= $inventory->quantity ?></td>
                                                        <td><?= $product->category->category_name ?></td>
                                                        <td>
                                                            <?= $product->selling_price !== null
                                                                ? Yii::$app->formatter->asCurrency($product->selling_price, 'KES')
                                                                : '<span class="not-set">(not set)</span>'
                                                            ?>
                                                        </td>
                                                        <td><?= date('d/m/Y', $product->created_at) ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($inventory->quantity > 0): ?>
                                                                <?= Html::a('SELL', Url::to(['sales/create', 'product_id' => $product->product_id]), [
                                                                    'class' => 'btn btn-danger sell-btn',
                                                                    'data-quantity' => $inventory->quantity
                                                                ]) ?>
                                                            <?php else: ?>
                                                                <span class="btn btn-secondary">SOLD</span>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -133px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                    <a class="dropdown-item has-icon" href="<?= Url::to(['products/view', 'product_id' => $product->product_id]) ?>"><i class="far fa-heart"></i> View</a>
                                                                    <a class="dropdown-item has-icon" href="<?= Url::to(['products/index', 'product_id' => $product->product_id, '#' => 'contact4']) ?>"><i class="far fa-file"></i> Update</a>
                                                                    <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['products/delete', 'product_id' => $product->product_id]) ?>"><i class="far fa-clock"></i> Delete</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <nav class="d-inline-block">
                                        <?= LinkPager::widget([
                                            'pagination' => $dataProvider->pagination,
                                            'options' => ['class' => 'pagination mb-0'],
                                            'linkOptions' => ['class' => 'page-link'],
                                            'pageCssClass' => 'page-item',
                                            'prevPageCssClass' => 'page-item',
                                            'nextPageCssClass' => 'page-item',
                                            'activePageCssClass' => 'active',
                                        ]) ?>
                                    </nav>
                                </div>
                                <!-- </div> -->

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
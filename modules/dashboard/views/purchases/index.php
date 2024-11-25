<?php

use app\models\Purchases;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\PurchasesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Purchases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchases-index">

    <div class="purchase-group-form">
        <div class="row">
            <form method="get" action="<?= Url::to(['/dashboard/purchases/index']) ?>">
                <div class="row">

                    <div class="col-lg-5 col-md-6">
                        <div class="form-group">
                            <input type="text" name="PurchasesSearch[productName]" class="form-control" placeholder="Search by product name ..." value="<?= Html::encode($searchModel->productName) ?>">
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-6">
                        <div class="form-group">
                            <?= DatePicker::widget([
                                'name' => 'PurchasesSearch[purchase_date]',
                                'value' => $searchModel->purchase_date,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy',  // Date format
                                ],
                                'options' => [
                                    'class' => 'form-control form-control-sm',  // Ensuring same height as other inputs
                                    'placeholder' => 'Search by sale date'
                                ]
                            ]); ?>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="search-student-btn">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="<?= Url::to('/dashboard/purchases/create') ?>" class="btn btn-primary"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>Product name</th>
                                    <th>Quantity</th>
                                    <th>Buying Price</th>
                                    <th>Total Cost</th>
                                    <th>purchase Date</th>
                                    <th>Payment Method </th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($dataProvider->getCount() > 0): // Check if there are any models 
                                ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $purchase): ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>
                                            <td><?= $purchase->product->product_name ?></td>
                                            <td><?= $purchase->quantity ?></td>
                                            <td><?= $purchase->buying_price ?></td>
                                            <td><?= $purchase->total_cost ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($purchase->purchase_date) ?></td>
                                            <td><?= $purchase->paymentMethod->name ?? '{not set}' ?></td>

                                            <!-- <td class="text-end">
                                                <div class="actions ">
                                                    <a href="<?= Url::to(['/dashboard/purchases/view', 'id' => $purchase->id]) ?>" class="btn btn-sm bg-success-light me-2 ">
                                                        <i class="feather-eye"></i>
                                                    </a>
                                                    <a href="<?= Url::to(['/dashboard/purchases/update', 'id' => $purchase->id]) ?>" class="btn btn-sm bg-danger-light">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm bg-danger-light delete-btn" data-url="<?= Url::to(['/dashboard/purchases/delete', 'id' => $purchase->id]) ?>">
                                                        <i class="feather-trash"></i>
                                                    </a>
                                                </div>
                                            </td> -->

                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/purchases/view', 'id' => $purchase->id]) ?>">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/purchases/update', 'id' => $purchase->id]) ?>">
                                                            <i class="feather-edit"></i> Update
                                                        </a>
                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['/dashboard/purchases/delete', 'id' => $purchase->id]) ?>">
                                                            <i class="feather-trash"></i> Delete
                                                        </a>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: // If no models found 
                                ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No data found</td> <!-- Adjust colspan based on your table -->
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                        <!-- Pagination inside the table container -->
                        <div class="pagination-wrapper mt-3">
                            <?= \app\components\CustomLinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'pagination justify-content-center mb-4'],
                                'linkOptions' => ['class' => 'page-link'],
                                'activePageCssClass' => 'active',
                                'disabledPageCssClass' => 'disabled',
                                'prevPageLabel' => '<span aria-hidden="true">«</span><span class="sr-only">Previous</span>',
                                'nextPageLabel' => '<span aria-hidden="true">»</span><span class="sr-only">Next</span>',
                            ]); ?>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>



</div>
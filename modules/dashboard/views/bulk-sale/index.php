<?php

use app\models\BulkSale;
use app\models\PaymentMethods;
use app\models\User;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\BulkSaleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bulk Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bulk-sale-index">

    <div class="sale-group-form">
        <div class="row">
            <form method="get" action="<?= Url::to(['/dashboard/bulk-sale/index']) ?>">
                <div class="row">

                    <div class="col-lg-5 col-md-6">
                        <div class="form-group">
                            <input type="text" name="SalesSearch[reference_no]" class="form-control" placeholder="Product Name ..." value="<?= Html::encode($searchModel->reference_no) ?>">
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-6">
                        <div class="form-group">
                            <?= DatePicker::widget([
                                'name' => 'BulkSaleSearch[sale_date]',
                                'value' => $searchModel->sale_date,
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy',  // Date format
                                ],
                                'options' => [
                                    'class' => 'form-control form-control-sm',  // Ensuring same height as other inputs
                                    'placeholder' => 'Sale Date'
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
                                <a href="<?= Url::to('/dashboard/bulk-sale/create') ?>" class="btn btn-primary"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm mb-0">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>Reference No</th>
                                    <th>Sale Date</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php if ($dataProvider->getCount() > 0): // Check if there are any models 
                                ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $sale):
                                        $createdBy = User::findOne($sale->created_by);
                                        $updatedBy = User::findOne($sale->updated_by);

                                    ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>

                                            <td><?= Html::encode($sale->reference_no) ?></td>

                                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?= Html::encode(Yii::$app->formatter->asDatetime($sale->sale_date, 'php:d/m/Y h:i A')) ?>
                                            </td>
                                            <td><?= $createdBy ? Html::encode($createdBy->username) : 'Admin' ?></td>
                                            <td><?= $updatedBy ? Html::encode($updatedBy->username) : 'Admin' ?></td>


                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/bulk-sale/view', 'id' => $sale->id]) ?>">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/bulk-sale/update', 'id' => $sale->id]) ?>">
                                                            <i class="feather-edit"></i> Update
                                                        </a>
                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['/dashboard/bulk-sale/delete', 'id' => $sale->id]) ?>">
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
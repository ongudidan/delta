<?php

use app\models\BulkSale;
use app\models\PaymentMethods;
use app\models\Sales;
use app\models\User;
use kartik\date\DatePicker;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\BulkSaleSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bulk Sales';
$this->params['breadcrumbs'][] = $this->title;
$this->params['modalSize'] = \yii\bootstrap5\Modal::SIZE_EXTRA_LARGE;



?>

<?php Pjax::begin(['id' => 'pjax-container']); ?>

<div class="bulk-sale-index">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="row align-items-center g-3 pb-3">
                        <!-- Search Form wrapped with PJAX -->
                        <div class="col d-flex align-items-center">
                            <?php $form = ActiveForm::begin([
                                'method' => 'get',
                                'action' => Url::to(['/dashboard/bulk-sale/index']),
                                'options' => ['class' => 'd-flex w-100 flex-wrap gap-2', 'data-pjax' => true], // Enable PJAX on form submission
                            ]); ?>

                            <?= $form->field($searchModel, 'reference_no', [
                                'options' => ['class' => 'flex-grow-1'],
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Reference Number ...',
                            ])->label(false); ?>

                            <?= $form->field($searchModel, 'sale_date', [
                                'options' => ['class' => 'flex-grow-1'],
                            ])->widget(DatePicker::class, [
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy',
                                ],
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Sale Date',
                                ]
                            ])->label(false); ?>

                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary align-self-stretch']); ?>

                            <?php ActiveForm::end(); ?>
                        </div>

                        <!-- Add Button -->
                        <div class="col-auto">
                            <?= Html::button('<i class="fas fa-plus"></i>', [
                                'class' => 'btn btn-primary align-self-stretch add-btn',
                                'data-url' => Url::to(['/dashboard/bulk-sale/create']), // Use Yii2 URL helper
                                'data-title' => 'Add New Fee Collection',
                            ]) ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm mb-0">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>Reference No</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Sale Date</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>

                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php if ($dataProvider->getCount() > 0): // Check if there are any models 
                                ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $row):
                                        $createdBy = User::findOne($row->created_by);
                                        $updatedBy = User::findOne($row->updated_by);
                                        $totalAmount = Sales::find()->where(['bulk_sale_id' => $row->id])->sum('total_amount');
                                        $totalQuantity = Sales::find()->where(['bulk_sale_id' => $row->id])->sum('quantity');
                                    ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>

                                            <td><?= Html::encode($row->reference_no) ?></td>
                                            <td><?= Html::encode(number_format(floatval($totalQuantity))) ?></td>

                                            <td><?= Yii::$app->formatter->asCurrency($totalAmount) ?></td>

                                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?= Html::encode(Yii::$app->formatter->asDatetime($row->sale_date, 'php:d/m/Y h:i A')) ?>
                                            </td>
                                            <td><?= $createdBy ? Html::encode($createdBy->username) : 'Admin' ?></td>
                                            <td><?= $updatedBy ? Html::encode($updatedBy->username) : 'Admin' ?></td>


                                            <!-- <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/bulk-sale/view', 'id' => $row->id]) ?>">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/bulk-sale/update', 'id' => $row->id]) ?>">
                                                            <i class="feather-edit"></i> Update
                                                        </a>
                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['/dashboard/bulk-sale/delete', 'id' => $row->id]) ?>">
                                                            <i class="feather-trash"></i> Delete
                                                        </a>

                                                    </div>
                                                </div>
                                            </td> -->
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <?= Html::button('<i class="fas fa-edit"></i> Edit', [
                                                        'class' => 'btn btn-sm edit-btn btn-outline-info me-2',
                                                        'data-url' => Url::to(['/dashboard/bulk-sale/update', 'id' => $row->id]),
                                                        'data-title' => 'Edit bulk-sale',
                                                    ]) ?>

                                                    <?= Html::button('<i class="fas fa-eye"></i> View', [
                                                        'class' => 'btn btn-sm view-btn btn-outline-primary me-2',
                                                        'data-url' => Url::to(['/dashboard/bulk-sale/view', 'id' => $row->id]),
                                                        'data-title' => 'View bulk-sale',
                                                    ]) ?>

                                                    <?= Html::button('<i class="fas fa-trash-alt"></i> Delete', [
                                                        'class' => 'btn btn-sm delete-btn btn-outline-danger',
                                                        'data-url' => Url::to(['/dashboard/bulk-sale/delete', 'id' => $row->id]),
                                                    ]) ?>
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

                        <!-- AJAX Pagination -->
                        <div class="pagination-wrapper mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-container">
                                    <?= LinkPager::widget([
                                        'pagination' => $dataProvider->pagination,
                                        'options' => [
                                            'class' => 'pagination mb-0',
                                        ],
                                        'linkOptions' => [
                                            'class' => 'page-link',
                                            'data-pjax' => '1',
                                        ],
                                        'activePageCssClass' => 'active',
                                        'disabledPageCssClass' => 'disabled',
                                        'firstPageLabel' => 'Start',
                                        'lastPageLabel' => 'End',
                                        'prevPageLabel' => '<span aria-hidden="true">«</span><span class="sr-only">Previous</span>',
                                        'nextPageLabel' => '<span aria-hidden="true">»</span><span class="sr-only">Next</span>',
                                        'maxButtonCount' => 5,
                                    ]); ?>
                                </div>
                                <div class="text-end mt-2 mt-sm-0">
                                    <span class="small text-muted">Page <?= $dataProvider->pagination->page + 1 ?> of <?= $dataProvider->pagination->pageCount ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End of Table -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php Pjax::end(); ?>
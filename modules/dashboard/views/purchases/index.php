<?php

use app\models\Purchases;
use kartik\date\DatePicker;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\PurchasesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Purchases';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(['id' => 'pjax-container']); ?>

<div class="purchases-index">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="row align-items-center g-3 pb-3">
                        <!-- Search Form wrapped with PJAX -->
                        <div class="col d-flex align-items-center">
                            <?php $form = ActiveForm::begin([
                                'method' => 'get',
                                'action' => Url::to(['/dashboard/purchases/index']),
                                'options' => ['class' => 'd-flex w-100 flex-wrap gap-2', 'data-pjax' => true], // Enable PJAX on form submission
                            ]); ?>

                            <?= $form->field($searchModel, 'productName', [
                                'options' => ['class' => 'flex-grow-1'],
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Product Name ...',
                            ])->label(false); ?>

                            <?= $form->field($searchModel, 'purchase_date', [
                                'options' => ['class' => 'flex-grow-1'],
                            ])->widget(DatePicker::class, [
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy',
                                ],
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Purchase Date',
                                ]
                            ])->label(false); ?>

                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary align-self-stretch']); ?>

                            <?php ActiveForm::end(); ?>
                        </div>

                        <!-- Add Button -->
                        <div class="col-auto">
                            <?= Html::button('<i class="fas fa-plus"></i>', [
                                'class' => 'btn btn-primary align-self-stretch add-btn',
                                'data-url' => Url::to(['/dashboard/purchases/create']), // Use Yii2 URL helper
                                'data-title' => 'Add New Fee Collection',
                            ]) ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-sm mb-0">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>Product name</th>
                                    <th>Quantity</th>
                                    <th>Buying Price</th>
                                    <th>Total Cost</th>
                                    <th>purchase Date</th>
                                    <th>Payment Method </th>

                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php if ($dataProvider->getCount() > 0): // Check if there are any models 
                                ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $row):
                                         ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>
                                            <td><?= $row->product->product_name ?></td>
                                            <td><?= $row->quantity ?></td>
                                            <td><?= $row->buying_price ?></td>
                                            <td><?= $row->total_cost ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($row->purchase_date) ?></td>
                                            <td><?= $row->paymentMethod->name ?? '{not set}' ?></td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <?= Html::button('<i class="fas fa-edit"></i> Edit', [
                                                        'class' => 'btn btn-sm edit-btn btn-outline-info me-2',
                                                        'data-url' => Url::to(['/dashboard/purchases/update', 'id' => $row->id]),
                                                        'data-title' => 'Edit purchases',
                                                    ]) ?>

                                                    <?= Html::button('<i class="fas fa-eye"></i> View', [
                                                        'class' => 'btn btn-sm view-btn btn-outline-primary me-2',
                                                        'data-url' => Url::to(['/dashboard/purchases/view', 'id' => $row->id]),
                                                        'data-title' => 'View purchases',
                                                    ]) ?>

                                                    <?= Html::button('<i class="fas fa-trash-alt"></i> Delete', [
                                                        'class' => 'btn btn-sm delete-btn btn-outline-danger',
                                                        'data-url' => Url::to(['/dashboard/purchases/delete', 'id' => $row->id]),
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
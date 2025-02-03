<?php

use app\models\Purchases;
use app\models\Sales;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\ProductsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
$this->params['modalSize'] = \yii\bootstrap5\Modal::SIZE_DEFAULT;

?>

<!-- ///////////////////////////////////////// -->


<?php Pjax::begin(['id' => 'pjax-container']); ?>

<div class="products-index">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="row align-items-center g-3 pb-3">
                        <!-- Search Form wrapped with PJAX -->
                        <div class="col d-flex align-items-center">
                            <?php $form = ActiveForm::begin([
                                'method' => 'get',
                                'action' => Url::to(['/dashboard/products/index']),
                                'options' => ['class' => 'd-flex w-100 flex-wrap gap-2', 'data-pjax' => true], // Enable PJAX on form submission
                            ]); ?>

                            <?= $form->field($searchModel, 'product_number', [
                                'options' => ['class' => 'flex-grow-1'],
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'products Number ...',
                            ])->label(false); ?>

                            <?= $form->field($searchModel, 'product_name', [
                                'options' => ['class' => 'flex-grow-1'],
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'products Name ...',
                            ])->label(false); ?>

                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary align-self-stretch']); ?>

                            <?php ActiveForm::end(); ?>
                        </div>
                        <div class="col-auto">
                            <?= Html::button('<i class="fas fa-plus"></i>', [
                                'class' => 'btn btn-primary align-self-stretch add-btn',
                                'data-url' => Url::to(['/dashboard/products/create']),
                                'data-title' => 'Add New products',
                            ]) ?>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm mb-0"> <!-- Added 'table-sm' for smaller padding -->
                            <thead class="products-thread "> <!-- Apply 'small' class for smaller font size in the header -->
                                <tr>
                                    <th>#</th>
                                    <th>Product Number</th>
                                    <th>Product Name</th>
                                    <th>Available</th>
                                    <th>Category</th>
                                    <!-- <th>Product Description</th> -->
                                    <th>Created At</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class=""> <!-- Apply 'small' class for smaller font size in the body -->
                                <?php if ($dataProvider->getCount() > 0): ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $row):
                                        $totalSalesQuantity = Sales::find()->where(['product_id' => $row->product_id])->sum('quantity');
                                        $totalPurchasesQuantity = Purchases::find()->where(['product_id' => $row->product_id])->sum('quantity');
                                        $totalQuantity = $totalPurchasesQuantity - $totalSalesQuantity;
                                        $canManage = Yii::$app->user->can('Sales Manage');
                                    ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>
                                            <td><?= $row->product_number ?></td>
                                            <td><?= $row->product_name ?></td>
                                            <td><?= $totalQuantity !== null ? $totalQuantity : 0 ?></td>
                                            <td><?= $row->category->category_name ?></td>
                                            <td><?= Yii::$app->formatter->asDatetime($row->created_at) ?></td>
                                            <!-- <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/products/view', 'product_id' => $row->product_id]) ?>">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/products/update', 'product_id' => $row->product_id]) ?>">
                                                            <i class="feather-edit"></i> Update
                                                        </a>
                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['/dashboard/products/delete', 'product_id' => $row->product_id]) ?>">
                                                            <i class="feather-trash"></i> Delete
                                                        </a>

                                                    </div>
                                                </div>
                                            </td> -->
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <?= Html::button('<i class="fas fa-edit"></i> Edit', [
                                                        'class' => 'btn btn-sm edit-btn btn-outline-info me-2',
                                                        'data-url' => Url::to(['/dashboard/products/update', 'product_id' => $row->product_id]),
                                                        'data-title' => 'Edit products',
                                                    ]) ?>

                                                    <?= Html::button('<i class="fas fa-eye"></i> View', [
                                                        'class' => 'btn btn-sm view-btn btn-outline-primary me-2',
                                                        'data-url' => Url::to(['/dashboard/products/view', 'product_id' => $row->product_id]),
                                                        'data-title' => 'View products',
                                                    ]) ?>

                                                    <?= Html::button('<i class="fas fa-trash-alt"></i> Delete', [
                                                        'class' => 'btn btn-sm delete-btn btn-outline-danger',
                                                        'data-url' => Url::to(['/dashboard/products/delete', 'product_id' => $row->product_id]),
                                                    ]) ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No data found</td>
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
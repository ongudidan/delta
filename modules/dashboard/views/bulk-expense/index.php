<?php

use app\models\BulkExpense;
use app\models\Expenses;
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
/** @var app\modules\dashboard\models\BulkExpenseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bulk Expenses';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(['id' => 'pjax-container']); ?>

<div class="bulk-expense-index">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table comman-shadow">
                <div class="card-body">
                    <div class="row align-items-center g-3 pb-3">
                        <!-- Search Form wrapped with PJAX -->
                        <div class="col d-flex align-items-center">
                            <?php $form = ActiveForm::begin([
                                'method' => 'get',
                                'action' => Url::to(['/dashboard/bulk-expense/index']),
                                'options' => ['class' => 'd-flex w-100 flex-wrap gap-2', 'data-pjax' => true], // Enable PJAX on form submission
                            ]); ?>

                            <?= $form->field($searchModel, 'reference_no', [
                                'options' => ['class' => 'flex-grow-1'],
                            ])->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Reference Number ...',
                            ])->label(false); ?>

                            <?= $form->field($searchModel, 'expense_date', [
                                'options' => ['class' => 'flex-grow-1'],
                            ])->widget(DatePicker::class, [
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy',
                                ],
                                'options' => [
                                    'class' => 'form-control',
                                    'placeholder' => 'Expense Date',
                                ]
                            ])->label(false); ?>

                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary align-self-stretch']); ?>

                            <?php ActiveForm::end(); ?>
                        </div>

                        <div class="col-auto text-end float-end ms-auto download-grp">
                            <a href="<?= Url::to('/dashboard/bulk-expense/create') ?>" class="btn btn-primary"><i
                                    class="fas fa-plus"></i></a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-sm mb-0">
                            <thead class="student-thread">
                                <tr>
                                    <th>#</th>
                                    <th>Reference No</th>
                                    <th>Amount</th>
                                    <th>Expense Date</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <?php if ($dataProvider->getCount() > 0): // Check if there are any models 
                                ?>
                                    <?php foreach ($dataProvider->getModels() as $index => $row):
                                        $createdBy = User::findOne($row->created_by);
                                        $updatedBy = User::findOne($row->updated_by);
                                        $totalAmount = Expenses::find()->where(['bulk_expense_id' => $row->id])->sum('amount');
                                    ?>
                                        <tr>
                                            <td><?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?></td>

                                            <td><?= Html::encode($row->reference_no) ?></td>

                                            <td><?= Yii::$app->formatter->asCurrency($totalAmount) ?></td>

                                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <?= Html::encode(Yii::$app->formatter->asDatetime($row->expense_date, 'php:d/m/Y h:i A')) ?>
                                            </td>
                                            <td><?= $createdBy ? Html::encode($createdBy->username) : 'Admin' ?></td>
                                            <td><?= $updatedBy ? Html::encode($updatedBy->username) : 'Admin' ?></td>


                                            <td>
                                                <div class="dropdown d-inline">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/bulk-expense/view', 'id' => $row->id]) ?>">
                                                            <i class="feather-eye"></i> View
                                                        </a>
                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['/dashboard/bulk-expense/update', 'id' => $row->id]) ?>">
                                                            <i class="feather-edit"></i> Update
                                                        </a>
                                                        <a class="dropdown-item has-icon delete-btn" href="#" data-url="<?= Url::to(['/dashboard/bulk-expense/delete', 'id' => $row->id]) ?>">
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
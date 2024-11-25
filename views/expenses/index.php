<?php

use app\models\User;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\ExpensesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Expenses';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
$this->registerJsFile('@web/js/script.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<div class="expenses-index">

    <div class="col-12 col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4><?= $this->title ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- //////// -->
                    <?= $this->render('components/_expenses-header') ?>
                    <!-- ///////////// -->


                    <div class="col-12 col-sm-12 col-md-12">
                        <div class="tab-content no-padding" id="myTab2Content">

                            <!-- /////////// ALL EXPENSES TABLE////////////////////////-->
                            <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                                <!-- <div class="card"> -->
                                <div class="card-header">
                                    <h4>

                                        <p>
                                            <?= Html::button('Create New Expense', ['value' => Url::to('/expenses/create/'), 'class' => 'btn btn-success', 'id' => 'modalButton']) ?>
                                        </p>
                                        <?php
                                        Modal::begin([
                                            // 'header' => '<h4>Companies</h4>',
                                            'id' => 'mod',
                                            'size' => 'modal-lg'
                                        ]);

                                        echo "<div id='modalContent'></div>";

                                        Modal::end();
                                        ?>
                                    </h4>
                                    <div class="card-header-action">
                                        <form method="get" action="<?= Url::to(['index']) ?>">
                                            <div class="input-group">
                                                <input type="text" name="ExpensesSearch[globalSearch]" class="form-control" placeholder="Search by category name" value="<?= Html::encode($searchModel->globalSearch) ?>">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm" id="sortable-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>Category Name</th>
                                                    <th>Amount</th>
                                                    <th>Created By</th>
                                                    <th>Created at</th>
                                                    <th>Updated at</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="ui-sortable">
                                                <?php foreach ($dataProvider->getModels() as $index => $expense) {
                                                    $user = User::findOne($expense->created_by);

                                                    // Check if the current user is the creator or has the admin role
                                                    $canManage = Yii::$app->user->id == $expense->created_by || Yii::$app->user->can('Expenses Manage');
                                                ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                                                        </td>
                                                        <td><?= Html::encode($expense->expenseCategory->category_name) ?></td>
                                                        <td><?= Html::encode(Yii::$app->formatter->asCurrency($expense->amount, 'KES')) ?></td>
                                                        <td><?= $user ? Html::encode($user->username) : 'Unknown' ?></td>
                                                        <td><?= Html::encode(Yii::$app->formatter->asDatetime($expense->created_at)) ?></td>
                                                        <td><?= Html::encode(Yii::$app->formatter->asDatetime($expense->updated_at)) ?></td>
                                                        <td>
                                                            <div class="dropdown d-inline">
                                                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -133px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                    <a class="dropdown-item has-icon" href="<?= Url::to(['expenses/view', 'expense_id' => $expense->expense_id]) ?>"><i class="far fa-heart"></i> View</a>

                                                                    <?php if ($canManage): ?>
                                                                        <a class="dropdown-item has-icon" href="<?= Url::to(['expenses/update', 'expense_id' => $expense->expense_id]) ?>"><i class="far fa-file"></i> Update</a>
                                                                        <a class="dropdown-item has-icon delete-btn" href="<?= Url::to(['expenses/delete', 'expense_id' => $expense->expense_id]) ?>" data-url="<?= Url::to(['expenses/delete', 'expense_id' => $expense->expense_id]) ?>">
                                                                            <i class="far fa-clock"></i> Delete
                                                                        </a>
                                                                    <?php endif; ?>
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
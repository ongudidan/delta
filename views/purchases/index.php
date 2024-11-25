<?php

use app\models\PaymentMethods;
use app\models\Purchases;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var app\models\PurchasesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Purchases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchases-index">



  <div class="col-12 col-sm-12 col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4><?= $this->title ?></h4>
      </div>
      <div class="card-body">
        <!-- //////////// -->
        <?= $this->render('components/_purchases-header') ?>
        <!-- /////////// -->

        <div class="row">

          <div class="col-12 col-sm-12 col-md-12">
            <div class="tab-content no-padding" id="myTab2Content">
              <div class="tab-pane fade active show" id="home4" role="tabpanel" aria-labelledby="home-tab4">
                <!-- <div class="card"> -->

                <div class="card-header">
                  <h4>

                  </h4>
                  <div class="card-header-action">
                    <form method="get" action="<?= Url::to(['index']) ?>">
                      <div class="input-group">
                        <input type="text" name="PurchasesSearch[globalSearch]" class="form-control" placeholder="Search by product name" value="<?= Html::encode($searchModel->globalSearch) ?>">
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
                          <th class="text-center">
                            #
                          </th>
                          <th>Product Name</th>
                          <th>Quantity</th>
                          <th>Buying Price</th>
                          <th>Total amount</th>
                          <th>Payment Method</th>
                          <th>Date bought at</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody class="ui-sortable">
                        <?php foreach ($dataProvider->getModels() as $index => $purchase) {
                          $data = PaymentMethods::findOne(['id' => $purchase->payment_method_id]);
                        ?>
                          <tr>
                            <td class="text-center">
                              <?= $dataProvider->pagination->page * $dataProvider->pagination->pageSize + $index + 1 ?>
                            </td>
                            <td><?= Html::encode($purchase->product->product_name) ?></td>
                            <td><?= Html::encode($purchase->quantity) ?></td>
                            <td><?= Html::encode(Yii::$app->formatter->asCurrency($purchase->buying_price, 'KES')) ?></td>
                            <td><?= Html::encode(Yii::$app->formatter->asCurrency($purchase->total_cost, 'KES')) ?></td>
                            <td><?= Html::encode($data['name'] ?? null) ?></td>
                            <td><?= Html::encode(Yii::$app->formatter->asDatetime($purchase->purchase_date)) ?></td>
                            <td>
                              <div class="dropdown d-inline">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                </button>
                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -133px, 0px); top: 0px; left: 0px; will-change: transform;">
                                  <a class="dropdown-item has-icon" href="<?= Url::to(['purchases/view', 'id' => $purchase->id]) ?>"><i class="far fa-heart"></i> View</a>
                                  <a class="dropdown-item has-icon delete-btn" href="<?= Url::to(['purchases/delete', 'id' => $purchase->id]) ?>" data-url="<?= Url::to(['purchases/delete', 'id' => $purchase->id]) ?>">
                                    <i class="far fa-clock"></i> Delete
                                  </a>
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
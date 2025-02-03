<?php

use app\models\ExpenseCategories;
use app\models\PaymentMethods;
use app\models\Products;
use kartik\select2\Select2;
use Yii2\Extensions\DynamicForm\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\modules\dashboard\models\JobCard $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
// Use the already fetched payment methods
$paymentMethodList = $paymentMethodList ?? [];

// Find the ID for the 'Cash' payment method, if it exists
$cashPaymentMethodId = null;
foreach ($paymentMethodList as $id => $name) {
    if (strtolower($name) === 'cash') {
        $cashPaymentMethodId = $id;
        break;
    }
}
?>

<div class="row">
    <div class="card-header text-white">
        <h5 class="mb-0">
            <i class="fas fa-list-alt"></i> Items
        </h5>
    </div>
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 50000,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $modelsExpenses[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'expense_category_id',
            'amount',
            'payment_method_id',
        ],
    ]); ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Expense category</th>
                    <th>Amount</th>
                    <th> Payment Method</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="container-items">
                <?php foreach ($modelsExpenses as $i => $modelExpenses): ?>
                    <tr class="item">
                        <?php if (!$modelExpenses->isNewRecord): ?>
                            <?= Html::activeHiddenInput($modelExpenses, "[{$i}]bulk_expense_id"); ?>
                        <?php endif; ?>

                        <td>
                            <?= $form->field($modelExpenses, "[{$i}]expense_category_id", [
                                'template' => "{input}\n{error}"
                            ])->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(ExpenseCategories::find()->all(), 'expense_category_id', 'category_name'),
                                'language' => 'en',
                                'options' => ['placeholder' => 'Select expense category ...', 'class' => 'form-control product-field'],
                                'pluginOptions' => ['allowClear' => true],
                            ]); ?>
                        </td>

                        <td>
                            <?= $form->field($modelExpenses, "[{$i}]amount", [
                                'template' => "{input}\n{error}"
                            ])->textInput([
                                'maxlength' => true,
                                // 'readonly' => true,
                                'class' => 'form-control sell-price-field'
                            ]) ?>
                        </td>

                        <td>
                            <?= $form->field($modelExpenses, "[{$i}]payment_method_id", ['template' => "{input}\n{error}"])
                                ->dropDownList(
                                    $paymentMethodList, // Use the pre-fetched payment methods list
                                    [
                                        'prompt' => 'Select payment method...',
                                        'class' => 'form-select',
                                        'options' => [
                                            // Set 'Cash' as the default selected option
                                            $cashPaymentMethodId => ['Selected' => !$modelExpenses->isNewRecord ? false : true]
                                        ]
                                    ]
                                ); ?>
                        </td>
                        <td>
                            <button type="button" class="remove-item btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <button type="button" class="add-item btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add
        </button>
    </div>

    <?php DynamicFormWidget::end(); ?>
</div>
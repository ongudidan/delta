<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\BulkExpense $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php Pjax::begin(['id' => 'dynamic-form-pjax']); ?>

<div class="row">

    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'options' => ['data-pjax' => true], // Enable PJAX on the form submission


    ]); ?>
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="form-group local-forms">
                <?= $form->field($model, 'date')->widget(DateTimePicker::classname(), [
                    'options' => [
                        'placeholder' => 'Enter expense date...',
                        'class' => 'form-control',  // Add the form-control class to the input
                        'value' => isset($model->expense_date) && $model->expense_date > 0
                            ? date('d-M-Y H:i', $model->expense_date)
                            : date('d-M-Y H:i'),
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd-M-yyyy hh:ii', // Set the date format to 'dd-M-yyyy' and include time
                        'todayHighlight' => true, // Highlight today's date
                        'todayBtn' => true, // Add a button to quickly select today's date and time
                        'minuteStep' => 1, // Optional: set minute interval for time picker
                    ]
                ]);
                ?>
            </div>
        </div>

        <div class="col-12 col-sm-6">
            <div class="form-group local-forms">
                <?= $form->field($model, 'reference_no')->textInput([
                    // Check if the model is new or existing
                    'value' => $model->isNewRecord ? $model->generateReferenceNo() : $model->reference_no,
                ]) ?>
            </div>
        </div>
    </div>



    <?= $this->render(
        'components/_expense-dynamic-form.php',
        [
            'modelsExpenses' => $modelsExpenses,
            'form' => $form,
            'paymentMethodList' => $paymentMethodList,

        ]
    ) ?>
    <div class="col-12">
        <div class="form-group d-flex justify-content-center">
            <?= Html::submitButton('Save Changes', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>


<?php Pjax::end(); ?>
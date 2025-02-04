<?php

use kartik\date\DateTimePicker;
use kartik\datetime\DateTimePicker as DatetimeDateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

?>

<?php Pjax::begin(['id' => 'dynamic-form-pjax']); ?>

<div class="row">

    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
        'method' => 'post',
        'validationUrl' => Url::to(['bulk-sale/validate']), // Optional validation URL, but no AJAX validation now
        'options' => ['data-pjax' => true], // Enable PJAX on the form submission
    ]); ?>

    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="form-group local-forms">
                <?= $form->field($model, 'date')->widget(DatetimeDateTimePicker::classname(), [
                    'options' => [
                        'placeholder' => 'Enter sale date...',
                        'class' => 'form-control',  // Add the form-control class to the input
                        'value' => isset($model->sale_date) && $model->sale_date > 0
                            ? date('d-M-Y H:i', $model->sale_date)
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
        'components/_sale-dynamic-form.php',
        [
            'modelsSales' => $modelsSales,
            'form' => $form,
            'model' => $model,
            'paymentMethodList' => $paymentMethodList,
        ]
    ) ?>

    <div class="col-12">
        <div class="student-submit d-flex justify-content-center">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'form' => 'dynamic-form']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php Pjax::end(); ?>
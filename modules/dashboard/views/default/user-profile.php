<?php

use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

?>
<div class="container-fluid d-flex justify-content-center align-items-start">
    <div class="col-lg-8">
        <section class="section">
            <div class="card shadow-lg border-light">
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="myTab2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab2" href="<?= Url::to('/dashboard/default/user-profile') ?>" role="tab" aria-selected="true">
                                <i class="bi bi-person"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab2" href="<?= Url::to('/dashboard/default/change-password') ?>" role="tab" aria-selected="false">
                                <i class="bi bi-lock"></i> Change Password
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content tab-bordered" id="myTab3Content">
                        <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                            <?php $form = ActiveForm::begin(); ?>
                            <div class="card-header bg-light">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'first_name')->textInput(['class' => 'form-control', 'placeholder' => 'Enter first name'])->label('First Name') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'last_name')->textInput(['class' => 'form-control', 'placeholder' => 'Enter last name'])->label('Last Name') ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'placeholder' => 'Enter email address'])->label('Email Address') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'phone')->textInput(['class' => 'form-control', 'placeholder' => 'Enter phone number'])->label('Phone Number') ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'gender')->dropDownList([
                                            'male' => 'Male',
                                            'female' => 'Female'
                                        ], ['class' => 'form-select'])->label('Gender') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'username')->textInput(['class' => 'form-control', 'placeholder' => 'Enter Username'])->label('Username') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-save"></i> Save Changes
                                </button>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
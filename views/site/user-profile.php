<?php

use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

?>
<div class="col-12 col-sm-12 col-lg-12">
    <section class="section">
        <div class="section-body">
            <div class="row mt-sm-4">

                <?= $this->render('components/_user-sidebar') ?>

                <div class="col-12 col-md-12 col-lg-9">
                    <div class="card">
                        <div class="padding-20">
                            <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab2" href="<?= Url::to('/site/user-profile') ?>" role="tab" aria-selected="false">Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " id="profile-tab2" href="<?= Url::to('/site/change-password') ?>" role="tab" aria-selected="false">Change Password</a>
                                </li>
                            </ul>
                            <div class="tab-content tab-bordered" id="myTab3Content">
                                <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                                    <?php $form = ActiveForm::begin(); ?>
                                    <div class="card-header">
                                        <h4>Edit Profile</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6 col-12">
                                                <?= $form->field($model, 'first_name')->textInput() ?>
                                            </div>
                                            <div class="form-group col-md-6 col-12">
                                                <?= $form->field($model, 'last_name')->textInput() ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-7 col-12">
                                                <?= $form->field($model, 'email')->textInput() ?>
                                            </div>
                                            <div class="form-group col-md-5 col-12">
                                                <?= $form->field($model, 'phone')->textInput() ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-12">
                                                <?= $form->field($model, 'gender')->dropDownList([
                                                    'male' => 'Male',
                                                    'female' => 'Female'
                                                ]) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="btn btn-primary">Save Changes</button>
                                    </div>
                                    <?php ActiveForm::end(); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
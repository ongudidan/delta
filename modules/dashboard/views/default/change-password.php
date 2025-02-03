<?php

use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

$this->title = 'Clange your password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card shadow-lg border-light">
    <div class="card-body">
        <ul class="nav nav-pills mb-3" id="myTab2" role="tablist">
            <li class="nav-item">
                <a class="nav-link" id="profile-tab2" href="<?= Url::to('/dashboard/default/user-profile') ?>" role="tab" aria-selected="false">
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" id="profile-tab2" href="<?= Url::to('/dashboard/default/change-password') ?>" role="tab" aria-selected="true">
                    Change Password
                </a>
            </li>
        </ul>
        <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="settings" role="tabpanel" aria-labelledby="profile-tab2">
                <?php $form = ActiveForm::begin(); ?>
                <div class="card-header bg-light">
                    <h4>Change Password</h4>
                </div>
                <div class="card-body">
                    <?= $form->field($model, 'oldPassword')->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter old password']) ?>
                    <?= $form->field($model, 'newPassword')->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter new password']) ?>
                    <?= $form->field($model, 'newPasswordConfirm')->passwordInput(['class' => 'form-control', 'placeholder' => 'Confirm new password']) ?>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
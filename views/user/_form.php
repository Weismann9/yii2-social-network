<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $profile app\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <div class="row">
        <hr>
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-4">
            <?php

            if (isset($profile->avatar)) {
                echo Html::img($profile->avatar, ['class' => 'center-block', 'id' => 'user-avatar', 'style' => 'width:50%; height:20%']);
            }
            ?>

            <?= $form->field($profile, 'imageFile')->widget(\kartik\file\FileInput::className(), [
                'options' => [
                    'accept' => 'image/*',
                ],
            ]) ?>

            <?= Html::a('Set default image', ['user/reset-image', 'id' => $profile->id], ['class' => 'btn btn-danger']) ?>

        </div>
        <div class="col-md-8">

            <? if (Yii::$app->authManager->checkAccess(Yii::$app->user->identity->getId(), 'admin')): ?>
                <?= $form->field($user, 'role')->dropDownList($user->getRolesArray()) ?>
                <hr>
            <?php endif; ?>

            <? if (Yii::$app->authManager->checkAccess(Yii::$app->user->identity->getId(), 'moderator')): ?>
                <?= $form->field($user, 'status')->dropDownList($user->getStatuses()) ?>
                <hr>
            <?php endif; ?>



            <?= $form->field($user, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>

            <?= $form->field($profile, 'first_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($profile, 'last_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($profile, 'birth_date')->widget(\kartik\date\DatePicker::className(), [
                'options' => ['placeholder' => 'Enter birth date ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]) ?>

            <?= $form->field($profile, 'details')->textarea(['rows' => 6]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
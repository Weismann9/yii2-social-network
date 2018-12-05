<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use \app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Conversation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="conversation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_ids')->widget(Select2::className(), [
        'value' => '',
        'data' => ArrayHelper::map(User::find()->where(['NOT', ['id' => Yii::$app->user->identity->getId()]])->all(), 'id', 'username'),
        'options' => [
            'placeholder' => 'Select user',
            'multiple' => true,
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

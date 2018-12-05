<?php

/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 11.11.2018
 * Time: 13:34
 */

use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model \app\models\ConversationMessage */
/* @var $id integer */

?>

<div class="reply">

    <?php Pjax::begin(['id' => 'new_message']) ?>
    <?php $form = ActiveForm::begin([
        'options' => ['data-pjax' => true],
    ]); ?>

    <div class="col-md-10 side">
        <?= $form->field($model, 'content')->textarea(['maxlength' => true, 'placeholder' => 'Write a message...'])->label(false) ?>
    </div>
    <div class="col-md-2 side">

        <div class="form-group">
            <?= Html::submitButton('<i class="glyphicon glyphicon-send"></i>', ['class' => 'btn btn-success', 'id' => 'send_btn']) ?>
        </div>
    </div>
    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>

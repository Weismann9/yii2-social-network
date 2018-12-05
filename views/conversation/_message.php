<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 11.11.2018
 * Time: 12:19
 */

use yii\helpers\Html;

/**
 * @var \app\models\ConversationMessage $model
 */
?>

<div class="message pull-left well well-sm col-md-8 ">

    <p class="message-author">
        <?= Html::a(Html::encode($model->user->username), ['user/view', 'id' => $model->user->id]) ?>
    </p>
    <?= nl2br(Html::encode($model->content)) ?>

    <p class="create-at text-muted pull-right"><?= Yii::$app->formatter->asDatetime($model->created_at, 'short') ?></p>
</div>
<div class="clearfix"></div>
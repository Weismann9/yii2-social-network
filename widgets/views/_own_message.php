<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 11.11.2018
 * Time: 12:40
 */

use app\models\ConversationMessage;
use Yii\helpers\Html;

/**
 * @var ConversationMessage $model
 */

?>

<div class="message pull-right well well-sm col-md-8">
    <?= nl2br(Html::encode($model->content)) ?>
    <p class="text-muted create-at pull-right"><?= Yii::$app->formatter->asDatetime($model->created_at, 'short') ?></p>
</div>
<div class="clearfix"></div>

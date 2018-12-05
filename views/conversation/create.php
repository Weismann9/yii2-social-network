<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\models\Conversation */

$this->title = 'Create Conversation';
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

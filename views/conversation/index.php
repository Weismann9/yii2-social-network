<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConversationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Conversations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conversation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Conversation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'label' => 'Participants',
                'attribute' => 'user',
                'value' => function (\app\models\Conversation $model) {
                    return implode(', ', array_map(function (\app\models\User $item) {
                        return $item->username;
                    }, $model->participants));
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

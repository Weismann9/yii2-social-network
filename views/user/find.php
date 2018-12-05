<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 28.11.2018
 * Time: 10:30
 */

use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel app\models\UserSearch */

$this->title = 'Find user';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-find">

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => [
            'tab' => 'div',
            'class' => 'col-md-3'
        ],
        'itemView' => function (\app\models\User $model, $key, $index, \yii\widgets\ListView $widget) {
            return $widget->view->render('_user_follower', [
                'model' => $model,
            ]);
        },
        'layout' => '<div class="row">{items}</div> <div class="container">{pager}{summary}</div>',
    ]);
    ?>
</div>

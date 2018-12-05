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
/* @var $user \app\models\User */
/* @var $searchModel app\models\UserSearch */

$this->title = 'Followers';
$this->params['breadcrumbs'][] = ['label' => $user->profile->getName(), 'url' => ['display', 'id' => $user->getId()]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-followers">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

    <?= $form->field($searchModel, 'full_name')->textInput(['placeholder' => 'Start typing any name of word'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <p>
        <?= Html::a('Add user', ['user/find'], ['class' => 'btn btn-success']) ?>
    </p>

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

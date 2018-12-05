<?php

/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 19.11.2018
 * Time: 14:30
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $user \app\models\User */
/* @var $dataProvider \yii\data\ActiveDataProvider */

?>
<div class="profile-view">
    <?php
    $user = Yii::$app->user->identity;

    ?>

    <div class="row">
        <!--left col-->
        <div class="col-sm-3">
            <div class="row text-center">
                <?= Html::img($model->avatar, ['class' => 'img-thumbnail img-responsive center-block', 'id' => 'user-avatar']) ?>

                <h1><?= $model->getName() ?></h1>

                <?php if (!($model->user->isLogged())): ?>
                    <div class="btn-group">
                        <?php if ($user->hasFollower($model->user_id)): ?>
                            <?= Html::a('Unfollow', ['user/unfollow', 'id' => $model->user_id], ['class' => 'btn btn-danger']) ?>
                        <?php else: ?>
                            <?= Html::a('Follow', ['user/follow', 'id' => $model->user_id], ['class' => 'btn btn-success']) ?>
                        <?php endif; ?>

                        <?= Html::a('Send message', ['conversation/get-conversation', 'id' => $model->user_id], ['class' => 'btn btn-info']) ?>
                    </div>
                <?php else: ?>
                    <?= Html::a('Settings', ['user/update', 'id' => $model->user_id], ['class' => 'btn btn-danger']) ?>
                <?php endif; ?>
            </div>

            <br>

            <div class="row">
                <div class="panel panel-default target">
                    <div class="panel-heading">Profile info</div>
                    <div class="panel-body">
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'list-group', 'tag' => 'ul'],
                            'template' => '<li class="list-group-item text-right"><span class="pull-left"><strong>{label}</strong></span>{value}</li>',
                            'attributes' => [
                                [
                                    'attribute' => 'birth_date',
                                    'visible' => isset($model->birth_date)
                                ],
                                [
                                    'label' => 'Followers',
                                    'value' => count($model->user->friendships)
                                ],
                                [
                                    'label' => 'Role',
                                    'value' => function (\app\models\Profile $model) {
                                        $roles = \yii\helpers\ArrayHelper::getColumn($model->user->getRoles(), 'name');
                                        return implode(',', $roles);
                                    },
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-9">
            <?php if (isset($model->details)): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><?= isset($model->first_name) ? $model->getFullName() : $model->getUsername() ?>
                        's Bio
                    </div>
                    <div class="panel-body"> <?= $model->details ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="panel panel-default target">
                <?= Html::a('Followers', ['user/followers', 'id' => $model->user_id], ['class' => 'panel-heading center-block']) ?>
                <div class="panel-body">
                    <?= \yii\widgets\ListView::widget([
                        'dataProvider' => $dataProvider,
                        'itemOptions' => [
                            'tab' => 'div',
                            'class' => 'col-md-4'
                        ],
                        'itemView' => function (\app\models\User $model, $key, $index, \yii\widgets\ListView $widget) {
                            return $widget->view->render('_user_follower', [
                                'model' => $model,
                            ]);
                        },
                        'layout' => '<div class="row">{items}</div> <div class="container">{pager}{summary}</div>',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

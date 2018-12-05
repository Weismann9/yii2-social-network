<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 20.11.2018
 * Time: 14:07
 */

use yii\helpers\Html;

/**
 * @var $model \app\models\User
 * @var $user \app\models\User
 */

?>
<div class="thumbnail">
    <?= Html::a(
        Html::img($model->profile->avatar, ['class' => 'follower-avatar']),
        ['user/display', 'id' => $model->id]
    ); ?>

    <div class="caption">
        <p class="pull-left">
            <?= $model->profile->getName() ?>
        </p>

        <?php
        $user = Yii::$app->user->identity;
        ?>

        <div class="btn-group pull-right">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-option-vertical"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
                <?php if (!($model->isLogged())): ?>
                    <li>
                        <?php if ($user->hasFollower($model->id)): ?>
                            <?= Html::a('Unfollow', ['user/unfollow', 'id' => $model->id]) ?>
                        <?php else: ?>
                            <?= Html::a('Follow', ['user/follow', 'id' => $model->id]) ?>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?= Html::a('Send message', ['conversation/get-conversation', 'id' => $model->id]) ?>
                    </li>
                <?php endif; ?>

                <li>
                    <?= Html::a('Show followers', ['user/followers', 'id' => $model->id]) ?>
                </li>
            </ul>
        </div>

        <div class="clearfix"></div>
    </div>
</div>


<?php

/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 11.11.2018
 * Time: 11:56
 */

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\widgets\UserConversations;
use app\widgets\ConversationMessages;
use app\widgets\ConversationUsers;

/* @var $this \yii\web\View */
/* @var $model \app\models\ConversationMessage */
/* @var $dataProvider yii\data\ActiveDataProvider */

$actionUrl = Url::to(['conversation/check', 'id' => $model->conversation_id]);
$this->registerJs(<<<JS
    window.actionUrl = '$actionUrl';

    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $('#page-content-wrapper').toggleClass('col-md-7');
        $('#sidebar-wrapper').toggleClass('col-md-2');
        $('#sidebar-wrapper').toggleClass('toggled');
        $('#page-content-wrapper').toggleClass('col-md-9');
    });
JS
);
$this->registerJsFile('js/conversation/main.js', ['depends' => JqueryAsset::className()]);
?>

<div class="conversation-display">
    <div class="row">

        <!--        left column-->
        <div class="col-md-3 conversations side">
            <div class="header">
                <p>Conversations</p>
            </div>
            <p id="new-conversation">
                <?= Html::a('Create new', ['conversation/create'], ['class' => 'btn btn-success center-block']) ?>
            </p>

            <?php Pjax::begin(['id' => 'conversations']) ?>
            <div class="user-conversations">
                <?= UserConversations::widget([
                    'currentId' => $model->conversation_id,
                ]) ?>
            </div>
            <?php Pjax::end(); ?>
        </div>

        <?php if ($model->conversation): ?>
            <div id="page-content-wrapper" class="col-md-9 conversation side">
                <!--                conversation header-->
                <div class="header">
                    <div class="pull-left">
                        <p><?= $model->conversation->title ?></p>
                        <span class="text-muted"><?= count($model->conversation->participants) ?> members</span>
                    </div>

                    <div class="buttons pull-right">
                        <div class="btn-group">
                            <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Members</a>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-option-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <?= Html::a('Update', ['update', 'id' => $model->conversation_id]) ?>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <?= Html::a('Delete', ['delete', 'id' => $model->conversation_id], [
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>

                <!--                messages content-->
                <div class="dialog-messages">
                    <?php Pjax::begin(['id' => 'messages']) ?>
                    <?php echo ConversationMessages::widget([
                        'conversation' => $model->conversation
                    ]) ?>
                    <?php Pjax::end(); ?>
                </div>

                <div>
                    <?= $this->render('_form_message', [
                        'model' => $model,
                    ]) ?>
                </div>

            </div>

            <!--        right column-->
            <div id="sidebar-wrapper" class="hide">
                <div class="conversation-users">
                    <div class="header">
                        <p>Members</p>
                    </div>
                    <?= ConversationUsers::widget([
                        'users' => $model->conversation->participants
                    ]) ?>
                </div>
            </div>

        <?php else: ?>
            <div class="col-md-9">
                <h4>Start the first conversation</h4>
            </div>
        <?php endif; ?>
    </div>
</div>
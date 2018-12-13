<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 12.11.2018
 * Time: 23:10
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Conversation;

/**
 * @var mixed $currentId
 * @var \app\models\Conversation[] $conversations
 * @var \app\models\ConversationMessage $lastMessage
 */
?>

<div class="list-group">
    <?php foreach ($conversations as $conversation):
        $cssClass = $conversation->id == $currentId ? 'active' : '';
        ?>
        <a href="<?= Url::to(['conversation/display', 'id' => $conversation->id,]) ?>"
           class="list-group-item <?= $cssClass ?>" data-pjax="0" data-target="<?= $conversation->id ?>">
            <h4 class="list-group-item-heading"> <?= Html::encode($conversation->title) ?></h4>
            <?php
            $lastMessage = Conversation::findOne($conversation->id)->getConversationMessages()->orderBy(['id' => SORT_DESC])->one();
            ?>
            <p class="list-group-item-text"> <?= $lastMessage->content ?></p>
        </a>
    <?php endforeach; ?>
</div>


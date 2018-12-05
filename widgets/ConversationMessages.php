<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 13.11.2018
 * Time: 9:36
 */

namespace app\widgets;


use app\models\Conversation;
use yii\base\Widget;
use yii\data\ActiveDataProvider;


class ConversationMessages extends Widget
{
    /** @var Conversation */
    public $conversation;

    public function run()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $this->conversation->getConversationMessages(),
            'pagination' => false,
        ]);

        return $this->render('messages', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
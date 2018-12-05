<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 12.11.2018
 * Time: 22:57
 */

namespace app\widgets;

use app\models\User;
use Yii;
use yii\base\Widget;

class UserConversations extends Widget
{
    public $currentId = false;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        /** @var User $user */
        $user = Yii::$app->user->getIdentity();
        return $this->render('user_conversations',[
            'conversations' => $user->conversations,
            'currentId' => $this->currentId,
        ]);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 12.11.2018
 * Time: 23:47
 */

namespace app\widgets;

use yii\base\Widget;

class ConversationUsers extends Widget
{
    public $users = [];

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        if (isset($this->users)){
            return $this->render('conversation_users', [
                    'users' => $this->users
                ]
            );
        }
        return false;
    }
}
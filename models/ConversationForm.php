<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 12.11.2018
 * Time: 22:08
 */

namespace app\models;


use Yii;
use yii\base\Model;

class ConversationForm extends Model
{
    public $title;
    public $participants;

    public function rules()
    {
        return [
            [['title', 'users'], 'required'],
            [['title'], 'string', 'max' => 64],
            [['users'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @return bool
     */
    public function addUsers()
    {
        if ($this->validate()) {
            $model = $this->getConversation();
            if ($model->save()) {
                $this->participants[] = Yii::$app->user->identity->getId();
                foreach ($this->participants as $user_id) {
                    $model->link('participants', User::findOne($user_id), ['conversation_id' => $model->id]);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * @return Conversation
     */
    public function getConversation()
    {
        $model = new Conversation();
        $model->title = $this->title;
        return $model;
    }
}
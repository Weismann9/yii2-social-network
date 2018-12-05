<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_has_conversation".
 *
 * @property string $id
 * @property string $user_id
 * @property string $conversation_id
 *
 * @property Conversation $conversation
 * @property User $user
 */
class UserHasConversation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_has_conversation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'conversation_id'], 'integer'],
            [['conversation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conversation::className(), 'targetAttribute' => ['conversation_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'conversation_id' => 'Conversation ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(Conversation::className(), ['id' => 'conversation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

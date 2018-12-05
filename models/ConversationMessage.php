<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "conversation_message".
 *
 * @property string $id
 * @property string $conversation_id
 * @property string $user_id
 * @property string $content
 * @property string $created_at
 *
 * @property Conversation $conversation
 * @property User $user
 */
class ConversationMessage extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversation_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['conversation_id', 'user_id'], 'integer'],
            [['content'], 'string'],
            [['created_at'], 'string', 'max' => 32],
            [['conversation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conversation::className(), 'targetAttribute' => ['conversation_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'conversation_id' => 'Conversation ID',
            'user_id' => 'User ID',
            'content' => 'Content',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(Conversation::className(), ['id' => 'conversation_id']);
    }

    public function getAuthor()
    {
        return $this->user->getUsername();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isOwner($user)
    {
        if ($user === $this->getUser()) {
            return true;
        }
        if ($user === null) {
            return false;
        }
        if ($this->getUser() === null) {
            return false;
        }
        return $user->getId() === $this->user->getId();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

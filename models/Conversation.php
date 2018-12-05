<?php

namespace app\models;

use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\base\Theme;
use yii\db\Expression;

/**
 * This is the model class for table "conversation".
 *
 * @property string $id
 * @property string $title
 *
 * @property User[] $participants
 * @property integer[] $user_ids
 * @property ConversationMessage[] $conversationMessages
 * @property UserHasConversation[] $userHasConversations
 */
class Conversation extends \yii\db\ActiveRecord
{
    /**
     * Find dialog conversation between two users
     *
     * @param $userId
     * @param $currentUserId
     * @return Conversation|array|null|\yii\db\ActiveRecord
     */
    public static function findDialog($userId, $currentUserId)
    {
        $subQuery = self::find()
            ->select(['c.*', 'mc' => new Expression('COUNT(m.user_id)')])
            ->from(['c' => self::tableName()])
            ->leftJoin(['u1' => UserHasConversation::tableName()], 'u1.conversation_id = c.id')
            ->leftJoin(['u2' => UserHasConversation::tableName()], 'u2.conversation_id = c.id')
            ->leftJoin(['m' => UserHasConversation::tableName()], 'm.conversation_id = c.id')
            ->groupBy('c.id')
            ->andWhere(['u1.user_id' => (int)$userId])
            ->andWhere(['u2.user_id' => (int)$currentUserId]);

        $query = self::find()
            ->from(['c' => $subQuery])
            ->where(['mc' => 2]);

        return $query->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conversation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'user_ids'], 'required'],
            [['title'], 'string', 'max' => 64],
            [['user_ids'], 'each', 'rule' => ['integer']],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => \voskobovich\behaviors\ManyToManyBehavior::className(),
                'relations' => [
                    'user_ids' => 'participants',
                ],
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
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversationMessages()
    {
        return $this->hasMany(ConversationMessage::className(), ['conversation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasConversations()
    {
        return $this->hasMany(UserHasConversation::className(), ['conversation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParticipants()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->via('userHasConversations');
    }

    public function beforeSave($insert)
    {
        $this->user_ids = array_merge($this->user_ids, [Yii::$app->user->getId()]);
        return parent::beforeSave($insert);
    }
}

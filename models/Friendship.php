<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "friendship".
 *
 * @property int $id
 * @property string $first_user_id
 * @property string $second_user_id
 *
 * @property User $firstUser
 * @property User $secondUser
 */
class Friendship extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'friendship';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_user_id', 'second_user_id'], 'integer'],
            [['first_user_id', 'second_user_id'], 'unique', 'targetAttribute' => ['first_user_id', 'second_user_id']],
            [['first_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['first_user_id' => 'id']],
            [['second_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['second_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_user_id' => 'First User ID',
            'second_user_id' => 'Second User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstUser()
    {
        return $this->hasOne(User::className(), ['id' => 'first_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSecondUser()
    {
        return $this->hasOne(User::className(), ['id' => 'second_user_id']);
    }
}

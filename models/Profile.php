<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "profile".
 *
 * @property string $id
 * @property string $avatar
 * @property string $first_name
 * @property string $last_name
 * @property string $birth_date
 * @property string $details
 * @property string $user_id
 * @property string $gallery_id
 *
 * @property Gallery $gallery
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['avatar'], 'string'],
            [['imageFile'], 'image', 'extensions' => ['png', 'jpg']],
            [['user_id', 'gallery_id'], 'integer'],
            [['details'], 'string'],
            [['first_name', 'last_name', 'birth_date'], 'string', 'max' => 32],
            [['first_name', 'last_name', 'birth_date', 'details'], 'default', 'value' => null],
            [['gallery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['gallery_id' => 'id']],
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'birth_date' => 'Birthday',
            'details' => 'Details',
            'user_id' => 'User ID',
            'gallery_id' => 'Gallery ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getName()
    {
        return isset($this->first_name) ? $this->getFullName() : $this->getUsername();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->user->username;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($imageFile = UploadedFile::getInstance($this, 'imageFile')) {
                $this->avatar = '/uploads/' . $imageFile->baseName . '.' . $imageFile->extension;
                $imageFile->saveAs(substr($this->avatar, 1));
            }
            return true;
        }
        return false;
    }

    public function setDefaultImage()
    {
        $this->avatar = '/uploads/standart.png';
    }
}

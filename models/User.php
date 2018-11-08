<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $email
 * @property string $username
 * @property string $password_hash
 * @property string $salt
 * @property string $auth_key
 * @property string $access_token
 * @property string $verification_token
 * @property int $status
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'string', 'max' => 64],
            [['username', 'salt', 'auth_key', 'verification_token'], 'string', 'max' => 32],
            [['password_hash'], 'string', 'max' => 60],
            [['status'], 'in', 'range' => array_keys(self::getStatuses())]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'salt' => 'Salt',
            'auth_key' => 'Auth Key',
            'verification_token' => 'Verification Token',
            'status' => 'Status',
        ];
    }

    /**
     * Get status name
     *
     * @return mixed
     */
    public function getStatusName()
    {
        return self::getStatuses()[$this->status];
    }

    /**
     * Get statuses array
     *
     * @return array
     */
    public function getStatuses()
    {
        return [
            self::STATUS_BLOCKED => 'Blocked',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_WAIT => 'Waiting for confirm',
        ];
    }

    /**
     * @param int|string $id
     * @return User|null|IdentityInterface
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    /**
     * @param $verification_token
     * @return User|null
     */
    public static function findIdentityByVerificationToken($verification_token)
    {
        return static::findOne(['verification_token' => $verification_token]);
    }

    /**
     * @param $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password_hash
     * @return string
     * @throws \yii\base\Exception
     */
    public function setPassword($password_hash)
    {
        return $this->password_hash = Yii::$app->security->generatePasswordHash($password_hash);
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateVerificationToken()
    {
        return $this->verification_token = Yii::$app->security->generateRandomString();
    }

    /**
     * @param $password_hash
     * @return bool
     */
    public function validatePassword($password_hash)
    {
        return Yii::$app->security->validatePassword($password_hash, $this->password_hash);
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        return $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
                $this->generateVerificationToken();
            }
            return true;
        }
        return false;
    }

    public function sendConfirmEmail()
    {
        return Yii::$app->mailer->compose(['html' => 'user-verify-html'], ['user' => $this])
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Email confirmation for ' . Yii::$app->name)
            ->send();
    }

    public function verify()
    {
        $this->verification_token = null;
        $this->status = self::STATUS_ACTIVE;
        return $this->save();
    }
}

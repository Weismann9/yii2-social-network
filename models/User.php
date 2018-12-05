<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

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
 *
 * @property Profile $profile
 * @property User[] $followers
 * @property Friendship[] $friendships
 * @property Conversation[] $conversations
 * @property ConversationMessage[] $conversationMessages
 * @property UserHasConversation[] $userHasConversations
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_WAIT = 2;

    public $role;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
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

    public function afterFind()
    {
        $roles = $this->getRoles();
        $role = array_shift($roles);
        $this->role = $role->name;
        parent::afterFind();
    }

    /**
     * @return \yii\rbac\Role[]
     */
    public function getRoles()
    {
        return Yii::$app->authManager->getRolesByUser($this->id);
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
            [['status'], 'in', 'range' => array_keys(self::getStatuses())],
            [['role'], 'string'],
        ];
    }

    /**
     * Get statuses array
     *
     * @return array
     */
    public function getStatuses()
    {
        return [
            self::STATUS_BLOCKED => 'blocked',
            self::STATUS_ACTIVE => 'active',
            self::STATUS_WAIT => 'waiting for confirm',
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
     * @return array
     */
    public function getRolesArray()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
    }

    /**
     * Check if user is logged
     *
     * @return bool
     */
    public function isLogged()
    {
        return Yii::$app->user->identity->getId() === $this->id;
    }

    /**
     * @param $id
     * @return bool
     */
    public function hasFollower($id)
    {
        return $this->getFriendships()->where(['second_user_id' => $id])->exists();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendships()
    {
        return $this->hasMany(Friendship::className(), ['first_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    /**
     * @param $id
     * @return bool
     */
    public function hasConversation($id)
    {
        return $this->getConversations()->where(['id' => $id])->exists();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversation::className(), ['id' => 'conversation_id'])
            ->via('userHasConversations');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowers()
    {
        return $this->hasMany(User::className(), ['id' => 'second_user_id'])->via('friendships');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversationMessages()
    {
        return $this->hasMany(ConversationMessage::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasConversations()
    {
        return $this->hasMany(UserHasConversation::className(), ['user_id' => 'id']);
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
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
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
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
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
     * @param $password_hash
     * @return bool
     */
    public function validatePassword($password_hash)
    {
        return Yii::$app->security->validatePassword($password_hash, $this->password_hash);
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

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        return $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateVerificationToken()
    {
        return $this->verification_token = Yii::$app->security->generateRandomString();
    }

    public
    function sendConfirmEmail()
    {
        return Yii::$app->mailer->compose(['html' => 'user-verify-html'], ['user' => $this])
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Email confirmation for ' . Yii::$app->name)
            ->send();
    }

    public
    function verify()
    {
        $this->verification_token = null;
        $this->status = self::STATUS_ACTIVE;
        return $this->save();
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \Exception
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (parent::save($runValidation, $attributeNames)) {
            if (!isset($this->profile)) {
                $profile = new Profile();
                $profile->setDefaultImage();
                $profile->link('user', $this);
            }

            $authManager = Yii::$app->authManager;
            $role = !isset($this->role) ? 'user' : $this->role;
            if (!$authManager->getAssignment($role, $this->id)) {
                if (count($authManager->getAssignments($this->id))) {
                    $authManager->revokeAll($this->id);
                }

                $userRole = $authManager->getRole($role);
                $authManager->assign($userRole, $this->id);
            }
            return true;
        }
        return false;
    }
}

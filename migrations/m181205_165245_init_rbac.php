<?php

use app\models\User;
use app\rbac\rules\UpdateOwnConversation;
use app\rbac\rules\UpdateOwnProfile;
use yii\db\Migration;

/**
 * Class m181205_165245_init_rbac
 */
class m181205_165245_init_rbac extends Migration
{
    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws Exception
     */
    public function safeUp()
    {
        $authManager = \Yii::$app->authManager;

        $user = $authManager->createRole('user');
        $authManager->add($user);

        $moderator = $authManager->createRole('moderator');
        $authManager->add($moderator);
        $authManager->addChild($moderator, $user);

        $admin = $authManager->createRole('admin');
        $authManager->add($admin);
        $authManager->addChild($admin, $moderator);
        $authManager->addChild($admin, $user);

        //update own profile rule
        $updateProfileRule = new UpdateOwnProfile();
        $authManager->add($updateProfileRule);

        $updateOwnProfile = $authManager->createPermission('updateOwnProfile');
        $updateOwnProfile->ruleName = $updateProfileRule->name;
        $authManager->add($updateOwnProfile);

        $authManager->addChild($user, $updateOwnProfile);

        //update own conversation rule
        $updateConversationRule = new UpdateOwnConversation();
        $authManager->add($updateConversationRule);

        $updateOwnConversation = $authManager->createPermission('updateOwnConversation');
        $updateOwnConversation->ruleName = $updateConversationRule->name;
        $authManager->add($updateOwnConversation);

        $authManager->addChild($user, $updateOwnConversation);

        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@admin.com';
        $user->role = 'admin';
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('admin');

        if ($user->save()) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('user', ['id' => 1]);

        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}

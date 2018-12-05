<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 03.12.2018
 * Time: 19:28
 */

namespace app\rbac\rules;


use yii\rbac\Item;
use yii\rbac\Rule;

class UpdateOwnProfile extends Rule
{
    public $name = 'updateProfile';

    /**
     * Executes the rule.
     *
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     * @return bool a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['user_id']) ? $params['user_id'] == $user : false;
    }
}
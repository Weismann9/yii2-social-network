<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 12.11.2018
 * Time: 23:48
 */

use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var User[] $users
 */
?>

<div class="list-group">
    <?php foreach ($users as $user): ?>
        <?= Html::a(
            '<h4 class="list-group-item-heading">' . Html::encode($user->profile->getName()) . '</h4>',
            ['user/display', 'id' => $user->id,],
            ['class' => 'list-group-item']
        ) ?>
    <?php endforeach; ?>
</div>

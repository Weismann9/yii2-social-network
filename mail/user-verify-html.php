<?php

use yii\helpers\Html;

/** @var $user \app\models\User */

$link = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-user', 'token' => $user->verification_token]);
?>
<div class="password-reset" style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;">
    <h4 style="font-size: 18px">Hello <?= Html::encode($user->username) ?>!</h4>

    <p>Please, follow the link below to verify your email:</p>

    <p><?= Html::a($link, $link) ?></p>
</div>
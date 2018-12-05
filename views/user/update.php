<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $profile app\models\Profile */

$this->title = 'Profile settings';
?>
<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'user' => $user,
        'profile' => $profile,
    ]) ?>

</div>

<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->user->isGuest ? '' : ['label' => 'Followers', 'url' => ['/user/followers', 'id' => Yii::$app->user->identity->getId()]],
            ['label' => 'Conversations', 'url' => ['/conversation/display']],
            Yii::$app->user->can('admin') || Yii::$app->user->can('moderator') ?
                [
                    'label' => 'Administration',
                    'items' => [
                        ['label' => 'Conversations', 'url' => ['/conversation/index']],
                        ['label' => 'Users', 'url' => ['/user/index']],
                    ]
                ] : '',
            Yii::$app->user->isGuest ? ['label' => 'Sign Up', 'url' => ['/site/signup']] : '',
            Yii::$app->user->isGuest ? '' : ['label' => Yii::$app->user->identity->profile->getName(), 'url' => ['/user/display', 'id' => Yii::$app->user->identity->getId()]],
            Yii::$app->user->isGuest ? (['label' => 'Login', 'url' => ['/site/login']]) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->profile->getName() . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

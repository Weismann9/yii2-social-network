<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 11.11.2018
 * Time: 19:19
 */

use app\models\User;
use yii\helpers\Html;

/**
 * @var $model User
 */
?>

<article class="item" data-key="<?= $model->id; ?>">
    <p>
        <?= Html::a($model->username, ['user/view', 'id' => $model->id]) ?>
    </p>
</article>

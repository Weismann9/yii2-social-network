<?php

/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 13.11.2018
 * Time: 10:34
 */

use yii\widgets\ListView;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $conversation \app\models\Conversation */

?>



<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'emptyText' => 'No messages',
    'layout' => '{items}',
    'itemView' => function (\app\models\ConversationMessage $model, $key, $index, ListView $widget) {
        if ($model->isOwner(Yii::$app->user)) {
            return $widget->view->render('_own_message', [
                'model' => $model,
            ]);
        }
        return $widget->view->render('_message', [
            'model' => $model,
        ]);
    }
]);
?>

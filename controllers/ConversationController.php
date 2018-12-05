<?php

namespace app\controllers;

use app\models\ConversationForm;
use app\models\ConversationMessage;
use app\models\User;
use app\models\UserHasConversation;
use Yii;
use app\models\Conversation;
use app\models\ConversationSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ConversationController implements the CRUD actions for Conversation model.
 */
class ConversationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'display', 'get-conversation', 'check'],
                        'roles' => ['moderator'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'permissions' => ['updateOwnConversation'],
                        'roleParams' => [
                            'conversation_id' => Yii::$app->request->get('id'),
                        ]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'delete', 'display', 'get-conversation', 'check'],
                        'roles' => ['user'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Conversation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConversationSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => Conversation::find()
                ->with('participants')
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Conversation model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Conversation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Conversation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Conversation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return bool|Response
     */
    public function actionGetConversation($id)
    {
        $user = User::findOne($id);

        $currentUserId = Yii::$app->user->identity->getId();

        $conversation = Conversation::findDialog($user->id, $currentUserId);

        if ($conversation == null) {
            $conversation = new Conversation([
                'title' => $user->profile->getName(),
                'user_ids' => [$currentUserId, $id],
            ]);
            $conversation->save();
        }

        return $this->redirect(['conversation/display', 'id' => $conversation->id]);
    }

    /**
     * @param bool|integer $id
     * @return string
     */
    public function actionDisplay($id = false)
    {
        if ($id === false) {
            /** @var User $user */
            $user = Yii::$app->user->identity;
            $conversation = $user->getConversations()->one();
            if ($conversation) {
                $id = $conversation->id;
            }
        }

        $model = new ConversationMessage();
        $model->conversation_id = $id;

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->identity->getId();
            if ($model->save()) {
                $model = new ConversationMessage();
            }
        }

        return $this->render('display', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Conversation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Conversation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['conversation/display', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Conversation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['conversation/display']);
    }

    public function actionCheck($id, $lastId)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            /** @var array $message */
            $message = ConversationMessage::find()
                ->select(['id'])
                ->where(['conversation_id' => $id,])
                ->orderBy(['id' => SORT_DESC])
                ->asArray()
                ->one();
            if ($message['id'] != $lastId) {
                return true;
            }

            return false;
        }

        return $this->redirect(['conversation/display', 'id' => $id]);
    }
}

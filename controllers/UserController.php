<?php

namespace app\controllers;

use app\models\Friendship;
use app\models\Profile;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'index', 'display', 'followers', 'find', 'follow', 'unfollow', 'reset-image', 'view'],
                        'roles' => ['moderator'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'permissions' => ['updateOwnProfile'],
                        'roleParams' => [
                            'user_id' => Yii::$app->request->get('id'),
                        ]
                    ],
                    [
                        'allow' => true,
                        'actions' => ['display', 'followers', 'find', 'follow', 'unfollow', 'reset-image'],
                        'roles' => ['user'],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        $user = new User();
        $profile = new Profile();

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->save() && $profile->save()) {
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }

        return $this->render('create', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        /** @var User $user */
        $user = $this->findModel($id);
        /** @var Profile $profile */
        $profile = Profile::find()->where(['user_id' => $id])->one();

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->save() && $profile->save()) {
                return $this->redirect(['display', 'id' => $user->id]);
            }
        }

        return $this->render('update', [
            'user' => $user,
            'profile' => $profile
        ]);
    }

    /**
     * Set profile image to default image
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionResetImage($id)
    {
        /** @var User $user */
        $user = $this->findModel($id);
        $user->profile->setDefaultImage();
        $user->profile->save();
        return $this->redirect(['user/update', 'id' => $id]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * User side profile view
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDisplay($id)
    {
        $model = $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getFollowers(),
            'pagination' => [
                'pageSize' => 3
            ]
        ]);

        return $this->render('user', [
            'model' => $model->profile,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Follow user
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionFollow($id)
    {
        $model = $this->findModel($id);
        /** @var User $user */
        $user = Yii::$app->user->identity;

        $friendship = new Friendship();
        $friendship->link('firstUser', $user);
        $friendship->link('secondUser', $model);

        return $this->redirect(['user/display', 'id' => $id]);
    }

    /**
     * Unfollow user
     *
     * @param $id
     * @return bool|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUnfollow($id)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        if ($user->getFriendships()->where(['second_user_id' => $id])->one()->delete()) {
            return $this->redirect(['user/display', 'id' => $id]);
        }
        return false;
    }

    /**
     * Shows user`s followers
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFollowers($id)
    {
        $user = $this->findModel($id);

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('followers', [
            'user' => $user,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionFind()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('find', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}

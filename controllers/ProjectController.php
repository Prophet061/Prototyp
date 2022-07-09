<?php namespace app\controllers;

      use Yii;
      use yii\web\Response;
      use yii\web\Controller;
      use app\models\User;
      use app\models\Tools;
      use yii\filters\VerbFilter;
      use yii\filters\AccessControl;

class ProjectController extends Controller{
  public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
  public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
  public function beforeAction($action){
      $this->enableCsrfValidation = false;

      // if(!User::getCurrentIdentity() && $action->id != "login") return $this->redirect(['site/login']);
      return true;
    }


  public function actionIndex(){

    return $this->render('index', []);
  }

  public function actionDetails(){

    return $this->render('details', []);
  }

  public function actionAssigned(){

    return $this->render('assigned', []);
  }

  public function actionAssignedTo(){

    return $this->render('assigned-to', []);
  }
}

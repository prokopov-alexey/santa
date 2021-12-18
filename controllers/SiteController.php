<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\db\User;
use app\service\Santa;
use yii\db\Transaction;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'index', 'peer'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
            $service = new Santa();
            /* @var $user User */
            $user = \Yii::$app->user->identity;
            
            return $this->render('index', [
                'user' => $user,
                'availableTargets' => $service->getAllAvailableTargets($user),
            ]);
//        }
    }


    public function actionPeer($key)
    {
        $transaction = \Yii::$app->db->beginTransaction(Transaction::SERIALIZABLE);
        
        try {
            $service = new Santa();
            /* @var $user User */
            $user = \Yii::$app->user->identity;
            
            $target = User::findOne(['public_id' => $key]);

            sleep(10);
            
            $service->peer($user, $target);
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }
            
        $this->goHome();
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin($key = null)
    {
        $form = new LoginForm();
        
        if (\Yii::$app->request->isPost) {
            $form->load(\Yii::$app->request->post());
        } else if ($key) {
            $form->key = $key;
        }
        
        if ($form->validate() && $form->login()) {
            $this->goHome();
        } else {
            return $this->render('login', ['model' => $form]);
        }
//            $user = User::findIdentityByAccessToken($key);
//            if ($user) {
//                Yii::$app->user->login($user, 365*24*3600);
//                return $this->goHome();
//            } else {
//                $this->render('login');
//            }
//        return $this->render('login', ['model' => $form]);
    }
    

    public function actionTest()
    {
//        $santa = User::findOne(5);
//        $target = User::findOne(5);
        
        $service = new \app\service\Santa();
        
//        $service->makeSanta($santa, $target);
        $avail = $service->getAllAvailableTargets(\Yii::$app->user->identity);
        foreach ($avail as $u) {
            echo $u->name . "; ";
        }
    }
    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}

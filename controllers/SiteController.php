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
                'only' => ['logout', 'index', 'peer', 'wishlist'],
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
    
    public function actionWishlist()
    {
        /* @var $user User */
        $user = \Yii::$app->user->identity;
        
        $user->setScenario(User::SCENARIO_WISHLIST);
        
        if (\Yii::$app->request->isPost) {
            if ($user->load(\Yii::$app->request->post()) && $user->save()) {
                $this->goHome();
            } else {
                return $this->render('wishlist', ['model' => $user]);
            }
        } else {
            return $this->render('wishlist', ['model' => $user]);
        }
    }


    public function actionPair($key)
    {
        $transaction = \Yii::$app->db->beginTransaction(Transaction::SERIALIZABLE);
        
        try {
            $service = new Santa();
            /* @var $user User */
            $user = \Yii::$app->user->identity;
            
            $target = User::findOne(['public_id' => $key]);

            $service->pair($user, $target);
            
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

}

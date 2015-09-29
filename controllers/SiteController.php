<?php

namespace app\controllers;

use app\models\IledebeauteProduct;
use app\models\LetualProduct;
use app\models\PodruzkaProduct;
use app\models\ProductLink;
use app\models\RivegaucheProduct;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Goutte\Client;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout', 'index', 'upload'],
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

    public function actionUpload()
    {
        $fileName = 'file';
        $uploadPath = \Yii::$app->basePath . '/web/files';

        if (isset($_FILES[$fileName])) {

            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($uploadPath . '/' . "upload." . $file->extension)) {
                chmod($uploadPath . '/' . "upload." . $file->extension, 0777);
                echo \yii\helpers\Json::encode($file);
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $let = LetualProduct::find()->count();
        $rive = RivegaucheProduct::find()->count();
        $ile = IledebeauteProduct::find()->count();

        return $this->render(
            'index',
            [
                'let_count' => $let,
                'riv_count' => $rive,
                'ile_count' => $ile,
                'let_update' => LetualProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'riv_update' => RivegaucheProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'ile_update' => IledebeauteProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'pod_update' => PodruzkaProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'let_avg' => LetualProduct::find()->select('avg(old_price) as old_price')->join('INNER JOIN','podruzka_product','podruzka_product.l_id=letual_product.id')->one(),
                'riv_avg' => RivegaucheProduct::find()->select('avg(rivegauche_product.price) as price')->join('INNER JOIN','podruzka_product','podruzka_product.r_id=rivegauche_product.id')->one(),
                'ile_avg' => IledebeauteProduct::find()->select('avg(old_price) as old_price')->join('INNER JOIN','podruzka_product','podruzka_product.i_id=iledebeaute_product.id')->one(),
                'pod_avg' => PodruzkaProduct::find()->select('avg(price) as price')->where('l_id is not null or r_id is not null or i_id is not null')->one(),
                'let_avg_brand' => LetualProduct::find()->select('avg(old_price) as old_price')->where('brand in (select brand from podruzka_product)')->one(),
                'riv_avg_brand' => RivegaucheProduct::find()->select('avg(price) as price')->where('brand in (select brand from podruzka_product)')->one(),
                'ile_avg_brand' => IledebeauteProduct::find()->select('avg(old_price) as old_price')->where('brand in (select brand from podruzka_product)')->one(),
                'pod_avg_brand' => PodruzkaProduct::find()->select('avg(price) as price')->where('brand in (select brand from rivegauche_product) or brand in (select brand from letual_product) or brand in (select brand from iledebeaute_product)')->one(),
                'let_compare' => PodruzkaProduct::find()->where('l_id is not null')->count(),
                'riv_compare' => PodruzkaProduct::find()->where('r_id is not null')->count(),
                'ile_compare' => PodruzkaProduct::find()->where('i_id is not null')->count(),
            ]
        );
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionCheck()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.letu.ru/parfyumeriya/zhenskaya-parfyumeriya');

        $crawler->filter('div.productItemDescription h3.title a')->each(function ($node) {
            $links = new ProductLink();
            $links->link = $node->attr('href');
            $links->validate();
            $links->save();
        });
        $crawler->filter('td.price p.new_price')->each(function ($node) {
           // print "Новая цена: ".$node->text()."\n";
        });
        $crawler->filter('td.item p.article')->each(function ($node) {
           // print "Артикул: ".$node->text()."\n";
        });
    }
}

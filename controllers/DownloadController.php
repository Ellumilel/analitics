<?php

namespace app\controllers;

use app\helpers\ExcelComponent;
use app\models\IledebeauteProduct;
use app\models\LetualProduct;
use app\models\LetualProductSearch;
use app\helpers\ExcelXML;
use app\models\RivegaucheProduct;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DownloadController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['inform'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['download', 'export'],
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

    public function actionDownload()
    {
        $request = Yii::$app->getRequest()->get();

        if($request['company'] == 'letual') {
            $command = Yii::$app->getDb()->createCommand('SELECT * FROM letual_product');
            $attr = new LetualProduct();
            $reader = $command->query();
        } elseif($request['company'] == 'rive') {
            $command = Yii::$app->getDb()->createCommand('SELECT * FROM rivegauche_product');
            $attr = new RivegaucheProduct();
            $reader = $command->query();
        } elseif($request['company'] == 'ile') {
            $command = Yii::$app->getDb()->createCommand('SELECT * FROM iledebeaute_product');
            $attr = new IledebeauteProduct();
            $reader = $command->query();
        } else {
            $let = [];
        }
        //sprintf('%s/web/files/%s', \Yii::$app->basePath, sprintf('example_%s.xls',time()))
        $xls = new ExcelXML();

        $header_style = array(
            'bold'       => 1,
            'size'       => '12',
            'color'      => '#FFFFFF',
            'bgcolor'    => '#4F81BD'
        );

        $xls->add_style('header', $header_style);
        //$xls->debug();
        $xls->add_row($attr->attributes(), 'header');

        while ($row = $reader->read()) {
            $xls->add_row($row);
        }

        $xls->create_worksheet('Users');
        $xml = $xls->generate();
        $xls->download(sprintf('%s_%s.xls', $request['company'], time()));
    }
}

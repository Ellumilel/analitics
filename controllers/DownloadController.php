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
                        'actions' => ['download', 'export', 'matching'],
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

    public function actionMatching()
    {
        $request = Yii::$app->getRequest()->get();
        $sql = 'Select
            pp.article,
            pp.title,
            pp.`group`,
            pp.category,
            pp.sub_category,
            pp.detail,
            pp.brand,
            pp.sub_brand,
            pp.line,
            pp.price,
            pp.ma_price,
            lp.`article` as `let.article`,
            lp.`title` as`let.title`,
            lp.`description` as `let.desc`,
            lp.`link` as `let.link`,
            lp.`new_price` as `let.new_price`,
            lp.`old_price` as `let.old_price`,
            lp.`updated_at` as `let.date`,
            rp.`article` as `rive.article`,
            rp.`title` as `rive.title`,
            rp.`description` as `rive.description`,
            rp.`link` as `rive.link`,
            rp.`showcases_new` as `rive.showcases_new`,
            rp.`showcases_compliment` as `rive.showcases_compliment`,
            rp.`showcases_exclusive` as `rive.showcases_exclusive`,
            rp.`showcases_offer` as `rive.showcases_offer`,
            rp.`showcases_bestsellers` as `rive.showcases_bestsellers`,
            rp.`gold_price` as `rive.gold_price`,
            rp.`blue_price` as `rive.blue_price`,
            rp.`price` as `rive.price`,
            rp.`updated_at` as `rive.date`,
            ip.`article` as `ile.article`,
            ip.`title` as `ile.title`,
            ip.`description` as `ile.description`,
            ip.`link` as `ile.link`,
            ip.`showcases_new` as `ile.showcases_new`,
            ip.`showcases_exclusive` as `ile.showcases_exclusive`,
            ip.`showcases_limit` as `ile.showcases_limit`,
            ip.`showcases_sale` as `ile.showcases_sale`,
            ip.`showcases_best` as `ile.showcases_best`,
            ip.`new_price` as `ile.new_price`,
            ip.`old_price` as `ile.old_price`,
            ip.`updated_at` as `ile.date`
            FROM podruzka_product pp
            LEFT JOIN letual_product lp on pp.l_id = lp.id
            LEFT JOIN rivegauche_product rp on pp.r_id = rp.id
            LEFT JOIN iledebeaute_product ip on pp.i_id = ip.id
            WHERE l_id is not null or r_id is not null or i_id is not null';
        $command = Yii::$app->getDb()->createCommand($sql);
        $reader = $command->query();
        $attr = [
            'article',
            'title',
            'group',
            'category',
            'sub_category',
            'detail',
            'brand',
            'sub_brand',
            'line',
            'price',
            'ma_price',
            'let.article',
            'let.title',
            'let.desc',
            'let.link',
            'let.new_price',
            'let.old_price',
            'let.date',
            'rive.article',
            'rive.title',
            'rive.description',
            'rive.link',
            'rive.promo',
            'rive.gold_price',
            'rive.blue_price',
            'rive.price',
            'rive.date',
            'ile.article',
            'ile.title',
            'ile.description',
            'ile.link',
            'ile.promo',
            'ile.new_price',
            'ile.old_price',
            'ile.date',
        ];
        $xls = new ExcelXML();

        $header_style = array(
            'bold'       => 1,
            'size'       => '12',
            'color'      => '#FFFFFF',
            'bgcolor'    => '#4F81BD'
        );

        $xls->add_style('header', $header_style);
        $xls->add_row($attr, 'header');

        while ($row = $reader->read()) {
            $rive_promotion = [];
            if ($row['rive.showcases_new']) {
                $rive_promotion[] = 'Новинка';
                unset($row['rive.showcases_new']);
            }
            if ($row['rive.showcases_compliment']) {
                $rive_promotion[] = 'Комплимент';
                unset($row['rive.showcases_compliment']);
            }
            if ($row['rive.showcases_exclusive']) {
                $rive_promotion[] = 'Эксклюзив';
                unset($row['rive.showcases_exclusive']);
            }
            if ($row['rive.showcases_bestsellers']) {
                $rive_promotion[] = 'Бестселлер';
                unset($row['rive.showcases_bestsellers']);
            }
            if ($row['rive.showcases_offer']) {
                $rive_promotion[] = 'Скидка (Выбор РивГош)';
                unset($row['rive.showcases_offer']);
            }

            $ile_promotion = [];
            if ($row['ile.showcases_new']) {
                $ile_promotion[] = 'Новинка';
                unset($row['ile.showcases_new']);
            }
            if ($row['ile.showcases_limit']) {
                $ile_promotion[] = 'Лимитированный выпуск';
                unset($row['ile.showcases_limit']);
            }
            if ($row['ile.showcases_exclusive']) {
                $ile_promotion[] = 'Эксклюзив';
                unset($row['ile.showcases_exclusive']);
            }
            if ($row['ile.showcases_sale']) {
                $ile_promotion[] = 'Распродажа';
                unset($row['ile.showcases_sale']);
            }
            if ($row['ile.showcases_best']) {
                $ile_promotion[] = 'Лучшая цена';
                unset($row['ile.showcases_best']);
            }

            $rpromo = implode(',', $rive_promotion);
            $ipromo = implode(',', $ile_promotion);

            $data = [
                'article' =>  $row['article'],
                'title' =>  $row['title'],
                'group' =>  $row['group'],
                'category' =>  $row['category'],
                'sub_category' =>  $row['sub_category'],
                'detail' =>  $row['detail'],
                'brand' => $row['brand'],
                'sub_brand' => $row['sub_brand'],
                'line' => $row['line'],
                'price' => str_replace('.',',', $row['price']),
                'ma_price' => str_replace('.',',', $row['ma_price']),
                'let.article' => $row['let.article'],
                'let.title' => $row['let.title'],
                'let.desc' => $row['let.desc'],
                'let.link' => $row['let.link'],
                'let.new_price' => str_replace('.',',', $row['let.new_price']),
                'let.old_price' => str_replace('.',',', $row['let.old_price']),
                'let.date' => $row['let.date'],
                'rive.article' => $row['rive.article'],
                'rive.title' => $row['rive.title'],
                'rive.description' => $row['rive.description'],
                'rive.link' => $row['rive.link'],
                'rive.promo' => $rpromo,
                'rive.gold_price' => str_replace('.',',', $row['rive.gold_price']),
                'rive.blue_price' => str_replace('.',',', $row['rive.blue_price']),
                'rive.price' => str_replace('.',',', $row['rive.price']),
                'rive.date' => $row['rive.date'],
                'ile.article' => $row['ile.article'],
                'ile.title' => $row['ile.title'],
                'ile.description' => $row['ile.description'],
                'ile.link' => $row['ile.link'],
                'ile.promo' => $ipromo,
                'ile.new_price' => str_replace('.',',', $row['ile.new_price']),
                'ile.old_price' => str_replace('.',',', $row['ile.old_price']),
                'ile.date' => $row['ile.date'],
            ];
            $xls->add_row($data);
        }

        $xls->create_worksheet('matching');
        $xml = $xls->generate();
        $xls->download(sprintf('matching_%s.xls', time()));
    }
}

<?php

namespace app\controllers;

use app\models\LetualProduct;
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
                        'actions' => ['download'],
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
        $request = Yii::$app->getRequest()->post();
        if (array_key_exists('letual',$request)) {
            $command = Yii::$app->getDb()->createCommand('SELECT * FROM letual_product');
            $reader = $command->query();

            $phpexcel = new \PHPExcel(); // Создаём объект PHPExcel
            /* Каждый раз делаем активной 1-ю страницу и получаем её, потом записываем в неё данные */
            $page = $phpexcel->setActiveSheetIndex(0); // Делаем активной первую страницу и получаем её
            $page->setCellValue("A1", "id");
            $page->setCellValue("B1", "article");
            $page->setCellValue("C1", "link");
            $page->setCellValue("D1", "group");
            $page->setCellValue("E1", "category");
            $page->setCellValue("F1", "sub_category");
            $page->setCellValue("G1", "brand");
            $page->setCellValue("H1", "title");
            $page->setCellValue("I1", "description");
            $page->setCellValue("J1", "old_price");
            $page->setCellValue("K1", "new_price");
            $page->setCellValue("L1", "image_link");
            $page->setCellValue("M1", "updated_at");
            $page->setTitle("Выгрузка Летуаль"); // Заголовок делаем "Example"
            $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
            $cacheSettings = array( 'memoryCacheSize ' => '312MB');
            \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            $i = 2;

            while ($row = $reader->read()) {
                $page->setCellValue("A".$i, $row["id"]);
                $page->setCellValue("B".$i, $row["article"]);
                $page->setCellValue("C".$i, $row["link"]);
                $page->setCellValue("D".$i, $row["group"]);
                $page->setCellValue("E".$i, $row["category"]);
                $page->setCellValue("F".$i, $row["sub_category"]);
                $page->setCellValue("G".$i, $row["brand"]);
                $page->setCellValue("H".$i, $row["title"]);
                $page->setCellValue("I".$i, $row["description"]);
                $page->setCellValue("J".$i, $row["old_price"]);
                $page->setCellValue("K".$i, $row["new_price"]);
                $page->setCellValue("L".$i, $row["image_link"]);
                $page->setCellValue("M".$i, $row["updated_at"]);
                $i++;
            }

            /* Начинаем готовиться к записи информации в xlsx-файл */
            $objWriter = \PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
            /* Записываем в файл */
            $file = sprintf('example_%s.xlsx',time());
            $objWriter->save(sprintf('%s/web/files/%s', \Yii::$app->basePath, $file));
            echo \yii\helpers\Html::a('<i class="fa fa-download"></i>&nbsp;Скачать','@web/files/'.$file, ['class' => 'btn btn-primary']);
        }
    }
}

<?php

namespace app\controllers;

use app\helpers\ExcelXML;
use app\models\PodruzkaProduct;
use app\models\RivegaucheCategory;
use app\models\Upload;
use Ellumilel\ExcelWriter;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class UploadController extends Controller
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
                        'actions' => ['inform', 'matching', 'brand', 'example', 'comment', 'category'],
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

    public function actionInform()
    {
        $upload = Upload::find()->orderBy(['id' => SORT_DESC])->all();

        return $this->render(
            'inform',
            [
                'upload' => $upload,
            ]
        );
    }

    /**
     * @return string
     */
    public function actionMatching()
    {
        return $this->render('matching');
    }

    /**
     * @return string
     */
    public function actionBrand()
    {
        return $this->render('brand');
    }

    /**
     * @return string
     */
    public function actionComment()
    {
        return $this->render('comment');
    }

    /**
     * @return string
     */
    public function actionCategory()
    {
        $category = RivegaucheCategory::find()->select('category')->distinct()->all();
        $group = RivegaucheCategory::find()->select('group')->distinct()->all();
        $sub = RivegaucheCategory::find()->select('sub_category')->distinct()->all();

        return $this->render('category', ['category' => $category, 'group' => $group, 'sub' => $sub]);
    }

    /**
     * @return string
     */
    public function actionExample()
    {
        $data = Yii::$app->request->get();

        switch ($data['file']) {
            case 'inform':
                $file = 'information_products_example.xlsx';
                break;
            case 'matching':
                $file = 'upload_matching_example.xlsx';
                break;
            case 'brand':
                return $this->getBlankBrand();
                break;
            case 'comment':
                return $this->getComment();
                break;
            case 'category':
                return $this->getCategory();
                break;
        }

        if (!empty($file)) {
            return Yii::$app->response->sendFile(Yii::getAlias('@webroot/files/'.$file));
        }

        return false;
    }

    /**
     * Получаем excel с комментариями
     */
    private function getComment()
    {
        $sql = 'SELECT
            `article`,
            `title`,
            `let_comment`,
            `riv_comment`,
            `ile_comment`,
            `eli_comment`
        FROM podruzka_product';
        $command = Yii::$app->getDb()->createCommand($sql);
        $reader = $command->query();
        $attr = [
            'article' => 'text',
            'title' => 'text',
            'let_comment' => 'text',
            'riv_comment' => 'text',
            'ile_comment' => 'text',
            'eli_comment' => 'text',
        ];

        $xls = new ExcelWriter();
        $xls->setTmpDir(__DIR__.'/../web/files');
        $xls->writeSheetHeader('Sheet1', $attr);
        $xls->setAuthor('Downloader');
        $xls->setFileName(sprintf('comment_%s.xlsx', date_format(new \DateTime(), 'Y_m_d_H_i_s')));
        while ($row = $reader->read()) {
            $xls->writeSheetRow('Sheet1', $row);
        }
        $xls->writeToStdOut();
    }

    /**
     * Получаем excel с пустыми брендами
     */
    private function getBlankBrand()
    {
        $sql = 'SELECT
            `article`,
            `link`,
            `title`
        FROM rivegauche_product
        WHERE brand IS NULL;';
        $command = Yii::$app->getDb()->createCommand($sql);
        $reader = $command->query();
        $attr = [
            'article',
            'link',
            'title',
            'brand',
        ];
        $xls = new ExcelXML();

        $header_style = [
            'bold' => 1,
            'size' => '12',
            'color' => '#FFFFFF',
            'bgcolor' => '#4F81BD',
        ];

        $xls->addStyle('header', $header_style);
        $xls->addRow($attr, 'header');

        while ($row = $reader->read()) {
            $data = [
                'article' => (string)str_pad($row['article'], 5, '0', STR_PAD_LEFT),
                'link' => (string)$row['link'],
                'title' => (string)$row['title'],
            ];
            $xls->addRow($data);
        }

        $xls->createWorksheet('brand');
        $xls->download(sprintf('brand_%s.xls', date_format(new \DateTime(), 'Y_m_d_H_i_s')));
    }

    /**
     * Получаем excel с пустыми брендами
     */
    private function getCategory()
    {
        $sql = 'SELECT distinct `category`, `group`, `sub_category` FROM rivegauche_product';
        $commandCat = Yii::$app->getDb()->createCommand($sql);
        $readerCat = $commandCat->query();

        $sql = 'SELECT
            `article`,
            `link`,
            `title`,
            `description`,
            `group`,
            `category`,
            `sub_category`
        FROM rivegauche_product
        WHERE `category`= \'Без категории\' or `group`=\'Без категории\' or `sub_category`=\'Без категории\';';
        $command = Yii::$app->getDb()->createCommand($sql);
        $reader = $command->query();
        $attr = [
            'article' => 'text',
            'link' => 'string',
            'title' => 'text',
            'description' => 'text',
            'group' => 'string',
            'category' => 'string',
            'sub_category' => 'string',
        ];

        $attrCat = [
            'group' => 'string',
            'category' => 'string',
            'sub_category' => 'string',
        ];

        $xls = new ExcelWriter();
        $xls->setTmpDir(__DIR__.'/../web/files');
        $xls->writeSheetHeader('empty_category', $attr);
        $xls->writeSheetHeader('category', $attrCat);
        $xls->setAuthor('Downloader');
        $xls->setFileName(sprintf('empty_category_%s.xlsx', date_format(new \DateTime(), 'Y_m_d_H_i_s')));
        while ($row = $reader->read()) {
            if (!empty($row['link'])) {
                $row['link'] = str_replace('"', '', $row['link']);
                $row['link'] = '=HYPERLINK("'.urldecode($row['link']).'","'.urldecode($row['link']).'")';
            }
            $xls->writeSheetRow('empty_category', $row);
        }

        while ($row = $readerCat->read()) {
            $xls->writeSheetRow('category', $row);
        }

        $xls->writeToStdOut();
    }
}

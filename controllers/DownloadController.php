<?php

namespace app\controllers;

use app\helpers\ExcelComponent;
use app\helpers\ExportExcel;
use app\models\ElizeProduct;
use app\models\IledebeauteProduct;
use app\models\LetualProduct;
use app\models\LetualProductSearch;
use app\helpers\ExcelXML;
use app\models\PodruzkaProduct;
use app\models\RivegaucheProduct;
use Ellumilel\ExcelWriter;
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
                        'actions' => ['download', 'export', 'matching', 'inform-product'],
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
        $header = [];
        if ($request['company'] == 'letual') {
            $command = Yii::$app->getDb()->createCommand('SELECT * FROM letual_product');
            $attr = new LetualProduct();
            $reader = $command->query();
        } elseif ($request['company'] == 'rive') {
            $command = Yii::$app->getDb()->createCommand('SELECT * FROM rivegauche_product');
            $attr = new RivegaucheProduct();
            $reader = $command->query();
        } elseif ($request['company'] == 'ile') {
            $command = Yii::$app->getDb()->createCommand('SELECT * FROM iledebeaute_product');
            $attr = new IledebeauteProduct();
            $reader = $command->query();
        } elseif ($request['company'] == 'eli') {
            $command = Yii::$app->getDb()->createCommand('SELECT * FROM elize_product');
            $attr = new ElizeProduct();
            $reader = $command->query();
        }
        if (empty($attr) && empty($reader)) {
            return false;
        }
        $wExcel = new ExcelWriter();
        foreach ($attr->getAttributes() as $key => $attribute) {
            if ($key == 'old_price' ||
                $key == 'new_price' ||
                $key == 'gold_price' ||
                $key == 'blue_price' ||
                $key == 'special_price' ||
                $key == 'price'
            ) {
                $header[$key] = '#,##0';
            } elseif ($key == 'showcases_new' ||
                $key == 'showcases_exclusive' ||
                $key == 'showcases_bestsellers' ||
                $key == 'showcases_limit' ||
                $key == 'showcases_sale' ||
                $key == 'showcases_compliment' ||
                $key == 'showcases_offer' ||
                $key == 'showcases_expertiza' ||
                $key == 'showcases_best'
            ) {
                $header[$key] = 'integer';
            } elseif ($key == 'created_at' || $key == 'updated_at' || $key == 'deleted_at') {
                $header[$key] = 'DD.MM.YYYY';
            } else {
                $header[$key] = 'string';
            }
        }

        $wExcel->setTmpDir(__DIR__.'/../web/files');
        $wExcel->writeSheetHeader('Sheet1', $header);
        $wExcel->setAuthor('Downloader');
        $wExcel->setFileName(sprintf('%s_%s.xlsx', $request['company'], date_format(new \DateTime(), 'Y-m-d')));
        while ($row = $reader->read()) {
            if ($row['created_at'] == '0000-00-00 00:00:00') {
                $row['created_at'] = '';
            }
            if ($row['updated_at'] == '0000-00-00 00:00:00') {
                $row['updated_at'] = '';
            }
            if ($row['deleted_at'] == '0000-00-00 00:00:00') {
                $row['deleted_at'] = '';
            }

            if (!empty($row['link'])) {
                if (strlen(urldecode($row['link'])) < 255) {
                    $row['link'] = '=HYPERLINK("'.urldecode(str_replace('"', '', $row['link'])).'")';
                }
            }

            if (!empty($row['image_link'])) {
                $row['image_link'] = '=HYPERLINK("'.urldecode(str_replace('"', '', $row['image_link'])).'")';
            }

            $wExcel->writeSheetRow('Sheet1', $row);
        }
        $wExcel->writeToStdOut();
        return true;
    }

    /**
     * Выгрузка полной таблицы информ продукта
     *
     * @TODO переделать порционно лимит по памяти (
     */
    public function actionInformProduct()
    {
        $downloadAttr = $header = [];
        $command = Yii::$app->getDb()->createCommand(
            '
        SELECT
            `id`,
            `article`,
            `title`,
            `group`,
            `category`,
            `sub_category`,
            `detail`,
            `brand`,
            `sub_brand`,
            `line`,
            `price`,
            `ma_price`,
            `arrival`,
            `let_comment`,
            `riv_comment`,
            `ile_comment`,
            `eli_comment`
        FROM podruzka_product
        '
        );
        $attr = new PodruzkaProduct();
        foreach ($attr->attributes() as $att) {
            if ($att != 'l_id' && $att != 'r_id' && $att != 'i_id' && $att != 'ile_id' && $att != 'rive_id'
                && $att != 'letu_id' && $att != 'updated_at' && $att != 'created_at' && $att != 'deleted_at'
            && $att != 'l_d_price'
            && $att != 'l_d_new_price'
            && $att != 'r_d_price'
            && $att != 'r_d_gold_price'
            && $att != 'e_d_price'
            && $att != 'e_d_new_pric'
            && $att != 'i_d_price'
            && $att != 'i_d_new_price'
            && $att != 'e_d_new_price'
            && $att != 'e_id'
            ) {
                $downloadAttr[] = $att;
            }
        }

        $wExcel = new ExcelWriter();
        foreach ($downloadAttr as $key => $attribute) {
            if ($attribute == 'old_price' ||
                $attribute == 'new_price' ||
                $attribute == 'gold_price' ||
                $attribute == 'blue_price' ||
                $attribute == 'ma_price' ||
                $attribute == 'price'
            ) {
                $header[$attribute] = '#,##0';
            } elseif ($attribute == 'showcases_new' ||
                $attribute == 'showcases_exclusive' ||
                $attribute == 'showcases_bestsellers' ||
                $attribute == 'showcases_limit' ||
                $attribute == 'showcases_sale' ||
                $attribute == 'showcases_compliment' ||
                $attribute == 'showcases_offer' ||
                $attribute == 'showcases_expertiza' ||
                $attribute == 'showcases_best'
            ) {
                $header[$attribute] = 'integer';
            } elseif ($attribute == 'created_at' || $attribute == 'updated_at' || $attribute == 'deleted_at') {
                $header[$attribute] = 'DD.MM.YYYY';
            } elseif ($attribute == 'article') {
                $header[$attribute] = 'text';
            } else {
                $header[$attribute] = 'string';
            }
        }

        //print_r($header);die;
        $wExcel->setTmpDir(__DIR__.'/../web/files');
        $wExcel->writeSheetHeader('Sheet1', $header);
        $wExcel->setAuthor('Downloader');
        $wExcel->setFileName(sprintf('%s.xlsx', date_format(new \DateTime(), 'Y-m-d')));
        $reader = $command->query();
        while ($row = $reader->read()) {
            if (!empty($row['link']) && strlen($row['link']) < 255) {
                $row['link'] = str_replace('"', '', $row['link']);
                $row['link'] = '=HYPERLINK("'.$row['link'].'")';
            }
            if (!empty($row['image_link']) && strlen($row['image_link']) < 255) {
                $row['image_link'] = str_replace('"', '', $row['image_link']);
                $row['image_link'] = '=HYPERLINK("'.$row['image_link'].'")';
            }
            $wExcel->writeSheetRow('Sheet1', $row);
        }
        $wExcel->writeToStdOut();
    }

    public function actionMatching()
    {
        $sql = 'Select
            pp.article,
            pp.title,
            pp.arrival,
            pp.`group`,
            pp.category,
            pp.sub_category,
            pp.detail,
            pp.brand,
            pp.sub_brand,
            pp.line,
            pp.price,
            pp.ma_price,
            pp.let_comment,
            pp.riv_comment,
            pp.eli_comment,
            pp.ile_comment,
            lp.`article` as `let.article`,
            lp.`title` as`let.title`,
            lp.`description` as `let.desc`,
            lp.`link` as `let.link`,
            lp.`showcases_new` as `let.showcases_new`,
            lp.`showcases_exclusive` as `let.showcases_exclusive`,
            lp.`showcases_bestsellers` as `let.showcases_bestsellers`,
            lp.`showcases_limit` as `let.showcases_limit`,
            lp.`showcases_promotext` as `let.showcases_promotext`,
            lp.`new_price` as `let.new_price`,
            lp.`old_price` as `let.old_price`,
            lp.`updated_at` as `let.date`,
            lp.`deleted_at` as `let.deleted_at`,
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
            rp.`special_price` as `rive.special_price`,
            rp.`updated_at` as `rive.date`,
            rp.`deleted_at` as `rive.deleted_at`,
            ep.`article` as `eli.article`,
            ep.`title` as `eli.title`,
            ep.`description` as `eli.description`,
            ep.`link` as `eli.link`,
            ep.`new_price` as `eli.new_price`,
            ep.`old_price` as `eli.old_price`,
            ep.`updated_at` as `eli.date`,
            ep.`deleted_at` as `eli.deleted_at`,
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
            ip.`updated_at` as `ile.date`,
            ip.`deleted_at` as `ile.deleted_at`
            FROM podruzka_product pp
            LEFT JOIN letual_product lp on pp.l_id = lp.id
            LEFT JOIN rivegauche_product rp on pp.r_id = rp.id
            LEFT JOIN iledebeaute_product ip on pp.i_id = ip.id
            LEFT JOIN elize_product ep on pp.e_id = ep.id
            WHERE l_id is not null or r_id is not null or i_id is not null or e_id is not null';
        $command = Yii::$app->getDb()->createCommand($sql);
        $reader = $command->query();
        $attr = [
            'article' => 'text',
            'title' => 'string',
            'arrival' => 'string',
            'group' => 'string',
            'category' => 'string',
            'sub_category' => 'string',
            'detail' => 'string',
            'brand' => 'string',
            'sub_brand' => 'string',
            'line' => 'string',
            'price' => '#,##0',
            'ma_price' => '#,##0',
            'let_comment' => 'string',
            'riv_comment' => 'string',
            'eli_comment' => 'string',
            'ile_comment' => 'string',
            'let.article' => 'string',
            'let.title' => 'string',
            'let.desc' => 'string',
            'let.link' => 'string',
            'let.promo' => 'string',
            'let.old_price' => '#,##0',
            'let.new_price' => '#,##0',
            'let.date' => 'DD.MM.YYYY',
            'let.deleted_at' => 'DD.MM.YYYY',
            'rive.article' => 'string',
            'rive.title' => 'string',
            'rive.description' => 'string',
            'rive.link' => 'string',
            'rive.promo' => 'string',
            'rive.price' => '#,##0',
            'rive.special_price' => '#,##0',
            'rive.blue_price' => '#,##0',
            'rive.gold_price' => '#,##0',
            'rive.date' => 'DD.MM.YYYY',
            'rive.deleted_at' => 'DD.MM.YYYY',
            'eli.article' => 'string',
            'eli.title' => 'string',
            'eli.description' => 'string',
            'eli.link' => 'string',
            'eli.old_price' => '#,##0',
            'eli.new_price' => '#,##0',
            'eli.date' => 'DD.MM.YYYY',
            'eli.deleted_at' => 'DD.MM.YYYY',
            'ile.article' => 'string',
            'ile.title' => 'string',
            'ile.description' => 'string',
            'ile.link' => 'string',
            'ile.promo' => 'string',
            'ile.old_price' => '#,##0',
            'ile.new_price' => '#,##0',
            'ile.date' => 'DD.MM.YYYY',
            'ile.deleted_at' => 'DD.MM.YYYY',
        ];
        $xls = new ExcelWriter();

        $xls->writeSheetHeader('Sheet1', $attr);
        $xls->setTmpDir(__DIR__.'/../web/files/');
        $xls->setFileName(sprintf('matching_%s.xlsx', date_format(new \DateTime(), 'Y-m-d H:i:s')));

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

            $let_promotion = [];
            if ($row['let.showcases_new']) {
                $let_promotion[] = 'Новинка';
                unset($row['let.showcases_new']);
            }
            if ($row['let.showcases_exclusive']) {
                $let_promotion[] = 'Только в Л Этуаль';
                unset($row['let.showcases_exclusive']);
            }
            if ($row['let.showcases_bestsellers']) {
                $let_promotion[] = 'Бестселлер';
                unset($row['let.showcases_bestsellers']);
            }
            if ($row['let.showcases_limit']) {
                $let_promotion[] = 'Лимитированный выпуск';
                unset($row['let.showcases_limit']);
            }
            if ($row['let.showcases_promotext']) {
                $let_promotion[] = $row['let.showcases_promotext'];
                unset($row['let.showcases_promotext']);
            }

            $rpromo = implode(',', $rive_promotion);
            $ipromo = implode(',', $ile_promotion);
            $lpromo = implode(',', $let_promotion);

            $data = [
                'article' => (string)str_pad($row['article'], 5, '0', STR_PAD_LEFT),
                'title' => (string)$row['title'],
                'arrival' => (string)$row['arrival'],
                'group' => (string)$row['group'],
                'category' => (string)$row['category'],
                'sub_category' => (string)$row['sub_category'],
                'detail' => (string)$row['detail'],
                'brand' => (string)$row['brand'],
                'sub_brand' => (string)$row['sub_brand'],
                'line' => (string)$row['line'],
                'price' => (float)sprintf("%8.2f", trim($row['price'])),
                'ma_price' => (float)sprintf("%8.2f", trim($row['ma_price'])),
                'let_comment' => (string)$row['let_comment'],
                'riv_comment' => (string)$row['riv_comment'],
                'eli_comment' => (string)$row['eli_comment'],
                'ile_comment' => (string)$row['ile_comment'],
                'let.article' => (string)$row['let.article'],
                'let.title' => (string)$row['let.title'],
                'let.desc' => (string)$row['let.desc'],
                'let.link' => (string)$this->getLink($row['let.link']),
                'let.promo' => (string)$lpromo,
                'let.old_price' => (float)sprintf("%8.2f", trim($row['let.old_price'])),
                'let.new_price' => (float)sprintf("%8.2f", trim($row['let.new_price'])),
                'let.date' => (string)$row['let.date'],
                'let.deleted_at' => $this->getDeleted($row['let.deleted_at']),
                'rive.article' => (string)$row['rive.article'],
                'rive.title' => (string)$row['rive.title'],
                'rive.description' => (string)$row['rive.description'],
                'rive.link' => (string)$this->getLink($row['rive.link']),
                'rive.promo' => (string)$rpromo,
                'rive.price' => (float)sprintf("%8.2f", trim($row['rive.price'])),
                'rive.special_price' => (float)sprintf("%8.2f", trim($row['rive.special_price'])),
                'rive.blue_price' => (float)sprintf("%8.2f", trim($row['rive.blue_price'])),
                'rive.gold_price' => (float)sprintf("%8.2f", trim($row['rive.gold_price'])),
                'rive.date' => (string)$row['rive.date'],
                'rive.deleted_at' => $this->getDeleted($row['rive.deleted_at']),
                'eli.article' => (string)$row['eli.article'],
                'eli.title' => (string)$row['eli.title'],
                'eli.description' => (string)$row['eli.description'],
                'eli.link' => (string)$this->getLink($row['eli.link']),
                'eli.old_price' => (float)sprintf("%8.2f", trim($row['eli.old_price'])),
                'eli.new_price' => (float)sprintf("%8.2f", trim($row['eli.new_price'])),
                'eli.date' => (string)$row['eli.date'],
                'eli.deleted_at' => $this->getDeleted($row['eli.deleted_at']),
                'ile.article' => (string)$row['ile.article'],
                'ile.title' => (string)$row['ile.title'],
                'ile.description' => (string)$row['ile.description'],
                'ile.link' => (string)$this->getLink($row['ile.link']),
                'ile.promo' => (string)$ipromo,
                'ile.old_price' => (float)sprintf("%8.2f", trim($row['ile.old_price'])),
                'ile.new_price' => (float)sprintf("%8.2f", trim($row['ile.new_price'])),
                'ile.date' => (string)$row['ile.date'],
                'ile.deleted_at' => $this->getDeleted($row['ile.deleted_at']),
            ];

            $xls->writeSheetRow('Sheet1', $data);
        }
        $xls->writeToStdOut();
    }

    /**
     * @param $string
     *
     * @return string
     */
    private function getDeleted($string)
    {
        if ($string == '0000-00-00 00:00:00') {
            return '';
        } else {
            return (string)$string;
        }
    }

    /**
     * @param $string
     *
     * @return string
     */
    private function getLink($string)
    {
        if (!empty($string) && strlen($string) < 255) {
            $string = str_replace('"', '', $string);
            $string = '=HYPERLINK("'.$string.'")';
        }

        return $string;
    }
}

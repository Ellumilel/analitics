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
        } else {
            $let = [];
        }

        $filename = sprintf('%s_%s.xls', $request['company'], date_format(new \DateTime(), 'Y-m-d'));

        $export = new ExportExcel($filename, count($attr->getAttributes()), $reader->count() + 1);

        $export->openWriter();
        $export->openWorkbook();

        $export->writeDocumentProperties();
        $export->writeStyles();
        $export->openWorksheet();

        //title row
        $export->resetRow();
        $export->openRow(true);

        foreach ($attr->getAttributes() as $code => $format) {
            $export->appendCellString($attr->getAttributeLabel($code));
        }
        $export->closeRow();
        $export->flushRow();

        while ($row = $reader->read()) {
            $export->resetRow();
            $export->openRow();
            foreach ($attr->getAttributes() as $code => $format) {
                $export->appendCellString($row[$code]);
            }
            $export->closeRow();
            $export->flushRow();
        }
        //close all
        $export->closeWorksheet();
        $export->closeWorkbook();
        $export->closeWriter();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($export->getBaseFullFileName()));

        readfile($export->getBaseFullFileName());
    }

    /**
     * Выгрузка полной таблицы информ продукта
     *
     * @TODO переделать порционно лимит по памяти (
     */
    public function actionInformProduct()
    {
        $downloadAttr = [];
        $command = Yii::$app->getDb()->createCommand('
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
            `arrival`
        FROM podruzka_product
        ');
        $attr = new PodruzkaProduct();
        foreach ($attr->attributes() as $att) {
            if ($att != 'l_id' && $att != 'r_id' && $att != 'i_id' && $att != 'ile_id' && $att != 'rive_id'
                && $att != 'letu_id' && $att != 'updated_at' && $att != 'created_at' && $att != 'deleted_at') {
                $downloadAttr[] = $att;
            }
        }
        $reader = $command->query();

        if (!empty($reader) && !empty($attr)) {
            $filename = sprintf('%s.csv', date_format(new \DateTime(), 'Y-m-d'));
            $now = gmdate("D, d M Y H:i:s");
            header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
            header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
            header("Last-Modified: {$now} GMT");

            // force download
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header('Content-Type: text/csv; charset=windows-1251');
            // disposition / encoding on response body
            header("Content-Disposition: attachment;filename={$filename}");
            header("Content-Transfer-Encoding: binary");

            ob_start();
            $df = fopen("php://output", 'w');
            fputcsv($df, $downloadAttr, ';');
            while ($row = $reader->read()) {
                foreach ($row as $r) {
                    $data[] =  iconv('utf-8','windows-1251',$r);
                }
                fputcsv($df, $data, ';');
                unset($row);
                unset($data);
            }
            fclose($df);
            echo ob_get_clean();
        }
    }

    public function actionMatching()
    {
        $request = Yii::$app->getRequest()->get();
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
            lp.`article` as `let.article`,
            lp.`title` as`let.title`,
            lp.`description` as `let.desc`,
            lp.`link` as `let.link`,
            lp.`showcases_new` as `let.showcases_new`,
            lp.`showcases_exclusive` as `let.showcases_exclusive`,
            lp.`showcases_bestsellers` as `let.showcases_bestsellers`,
            lp.`showcases_limit` as `let.showcases_limit`,
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
            ip.`updated_at` as `ile.date`,
            ep.`article` as `eli.article`,
            ep.`title` as `eli.title`,
            ep.`link` as `eli.link`,
            ep.`new_price` as `eli.new_price`,
            ep.`old_price` as `eli.old_price`,
            ep.`updated_at` as `eli.date`
            FROM podruzka_product pp
            LEFT JOIN letual_product lp on pp.l_id = lp.id
            LEFT JOIN rivegauche_product rp on pp.r_id = rp.id
            LEFT JOIN iledebeaute_product ip on pp.i_id = ip.id
            LEFT JOIN elize_product ep on pp.e_id = ep.id
            WHERE l_id is not null or r_id is not null or i_id is not null or e_id is not null';
        $command = Yii::$app->getDb()->createCommand($sql);
        $reader = $command->query();
        $attr = [
            'article',
            'title',
            'arrival',
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
            'let.promo',
            'let.old_price',
            'let.new_price',
            'let.date',
            'rive.article',
            'rive.title',
            'rive.description',
            'rive.link',
            'rive.promo',
            'rive.price',
            'rive.blue_price',
            'rive.gold_price',
            'rive.date',
            'ile.article',
            'ile.title',
            'ile.description',
            'ile.link',
            'ile.promo',
            'ile.old_price',
            'ile.new_price',
            'ile.date',
            'eli.article',
            'eli.title',
            'eli.link',
            'eli.old_price',
            'eli.new_price',
            'eli.date',
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

            $rpromo = implode(',', $rive_promotion);
            $ipromo = implode(',', $ile_promotion);
            $lpromo = implode(',', $let_promotion);

            $data = [
                'article' => (string)str_pad($row['article'], 5, '0', STR_PAD_LEFT),
                'title' =>  (string)$row['title'],
                'arrival' => (string)$row['arrival'],
                'group' => (string) $row['group'],
                'category' =>  (string)$row['category'],
                'sub_category' => (string) $row['sub_category'],
                'detail' => (string) $row['detail'],
                'brand' => (string)$row['brand'],
                'sub_brand' =>(string) $row['sub_brand'],
                'line' => (string)$row['line'],
                'price' => (float) sprintf("%8.2f", trim($row['price'])),
                'ma_price' =>(float) sprintf("%8.2f", trim($row['ma_price'])),
                'let.article' =>(string) $row['let.article'],
                'let.title' =>(string) $row['let.title'],
                'let.desc' => (string)$row['let.desc'],
                'let.link' => (string)$row['let.link'],
                'let.promo' => (string)$lpromo,
                'let.old_price' => (float)sprintf("%8.2f", trim($row['let.old_price'])),
                'let.new_price' => (float)sprintf("%8.2f", trim($row['let.new_price'])),
                'let.date' =>(string) $row['let.date'],
                'rive.article' =>(string) $row['rive.article'],
                'rive.title' =>(string) $row['rive.title'],
                'rive.description' =>(string) $row['rive.description'],
                'rive.link' => (string)$row['rive.link'],
                'rive.promo' => (string)$rpromo,
                'rive.price' =>(float)sprintf("%8.2f", trim($row['rive.price'])),
                'rive.blue_price' => (float)sprintf("%8.2f", trim($row['rive.blue_price'])),
                'rive.gold_price' => (float)sprintf("%8.2f", trim($row['rive.gold_price'])),
                'rive.date' =>(string) $row['rive.date'],
                'ile.article' =>(string) $row['ile.article'],
                'ile.title' =>(string) $row['ile.title'],
                'ile.description' =>(string) $row['ile.description'],
                'ile.link' =>(string) $row['ile.link'],
                'ile.promo' =>(string) $ipromo,
                'ile.old_price' => (float)sprintf("%8.2f", trim($row['ile.old_price'])),
                'ile.new_price' =>(float)sprintf("%8.2f", trim($row['ile.new_price'])),
                'ile.date' =>(string) $row['ile.date'],
                'eli.article' =>(string) $row['eli.article'],
                'eli.title' =>(string) $row['eli.title'],
                'eli.link' =>(string) $row['eli.link'],
                'eli.old_price' => (float)sprintf("%8.2f", trim($row['eli.old_price'])),
                'eli.new_price' =>(float)sprintf("%8.2f", trim($row['eli.new_price'])),
                'eli.date' =>(string) $row['eli.date'],
            ];
            //var_dump($data);die;
            $xls->add_row($data);
        }

        $xls->create_worksheet('matching');
        $xml = $xls->generate();
        $xls->download(sprintf('matching_%s.xls', date_format(new \DateTime(), 'Y-m-d H:i:s')));
    }
}

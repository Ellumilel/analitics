<?php

namespace app\controllers;

use app\models\ElizeProduct;
use app\models\IledebeauteProduct;
use app\models\LetualProduct;
use app\models\ParsingStatus;
use app\models\PodruzkaProduct;
use app\models\RivegaucheProduct;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use vova07\console\ConsoleRunner;

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
                        'actions' => [
                            'logout',
                            'index',
                            'upload',
                            'upload-matching',
                            'upload-brand',
                            'upload-comment',
                            'upload-category',
                            'ping-status',
                            'start-parse',
                        ],
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
        $uploadPath = \Yii::$app->basePath.'/web/files';

        if (isset($_FILES[$fileName])) {

            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($uploadPath.'/'."upload.".$file->extension)) {
                chmod($uploadPath.'/'."upload.".$file->extension, 0777);
                echo \yii\helpers\Json::encode($file);
            }
        }

        return false;
    }

    public function actionStartParse()
    {
        $partner = Yii::$app->request->post('partner');
        $status = ParsingStatus::findAll(['company' => $partner, 'status' => 'start']);

        if ($partner == 'rivegauche' && empty($status)) {
            $cr = new ConsoleRunner(['file' => "@app/yii"]);
            $cr->run('riv/parse-api');

            return true;
        }

        if (empty($status) && $partner !== 'rivegauche') {
            $cr = new ConsoleRunner(['file' => "@app/yii"]);
            $cr->run('product-parser/'.$partner.' 0 20000');
        }

        return true;
    }

    public function actionPingStatus()
    {
        $status = ParsingStatus::findAll(['status' => "start"]);

        $ileTextCount = $eliTextCount = $letuTextCount = $riveTextCount = 'сбор закончен';
        $ilePersent = $eliPersent = $letuPersent = $rivePersent = '100%';

        foreach ($status as $item) {
            switch ($item->company) {
                case 'rivegauche':
                    $rive_done = RivegaucheProduct::find()->where(
                        'updated_at >= "'.$item->start_date.'" or deleted_at >= "'.$item->start_date.'"'
                    )->count();
                    $rive_count = RivegaucheProduct::find()->count();

                    $riveTextCount = sprintf('%d из %d', $rive_done, $rive_count);
                    $rivePersent = round(($rive_done / $rive_count) * 100).'%';
                    break;
                case 'letual':
                    $letu_done = LetualProduct::find()->where(
                        'updated_at >= "'.$item->start_date.'" or deleted_at >= "'.$item->start_date.'"'
                    )->count();
                    $letu_count = LetualProduct::find()->count();

                    $letuTextCount = sprintf('%d из %d', $letu_done, $letu_count);
                    $letuPersent = round(($letu_done / $letu_count) * 100).'%';
                    break;
                case 'elize':
                    $eli_done = ElizeProduct::find()->where(
                        'updated_at >= "'.$item->start_date.'" or deleted_at >= "'.$item->start_date.'"'
                    )->count();
                    $eli_count = ElizeProduct::find()->count();

                    $eliTextCount = sprintf('%d из %d', $eli_done, $eli_count);
                    $eliPersent = round(($eli_done / $eli_count) * 100).'%';
                    break;
                case 'iledebeaute':
                    $ile_done = IledebeauteProduct::find()->where(
                        'updated_at >= "'.$item->start_date.'" or deleted_at >= "'.$item->start_date.'"'
                    )->count();
                    $ile_count = IledebeauteProduct::find()->count();

                    $ileTextCount = sprintf('%d из %d', $ile_done, $ile_count);
                    $ilePersent = round(($ile_done / $ile_count) * 100).'%';
                    break;
            }
        }

        return json_encode(
            [
                'let' => [
                    'count' => $letuTextCount,
                    'persent' => $letuPersent,
                ],
                'eli' => [
                    'count' => $eliTextCount,
                    'persent' => $eliPersent,
                ],
                'rive' => [
                    'count' => $riveTextCount,
                    'persent' => $rivePersent,
                ],
                'ile' => [
                    'count' => $ileTextCount,
                    'persent' => $ilePersent,
                ],
            ]
        );
    }

    public function actionUploadBrand()
    {
        $fileName = 'file';
        $uploadPath = \Yii::$app->basePath.'/web/files';

        if (isset($_FILES[$fileName])) {
            //print_R($_FILES[$fileName]);die;
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($uploadPath.'/'."upload_brand.".$file->extension)) {
                chmod($uploadPath.'/'."upload_brand.".$file->extension, 0777);
                if (isset($file)) {
                    $fName = $uploadPath.'/'."upload_brand.".$file->extension;
                    //начинаем читать со строки 2, в PHPExcel первая строка имеет индекс 1, и как правило это строка заголовков
                    $startRow = 2;
                    $objReader = \PHPExcel_IOFactory::createReaderForFile($fName);
                    $objReader->setReadDataOnly(true);

                    $objPHPExcel = $objReader->load($fName); //открываем файл
                    $objPHPExcel->setActiveSheetIndex(0); //устанавливаем индекс активной страницы
                    $objWorksheet = $objPHPExcel->getActiveSheet(); //делаем активной нужную страницу

                    //внутренний цикл по строкам
                    for ($i = $startRow; $i < $startRow + 20000; $i++) {
                        //получаем первое знаение в строке
                        $value = trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue());

                        if (!empty($value)) {
                            if (!empty($value) && !empty($objWorksheet->getCellByColumnAndRow(3, $i)->getValue())) {
                                $rivegauche = RivegaucheProduct::findOne(['article' => (string)$value]);
                                $rivegauche->brand = (string)$objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
                                $rivegauche->save();
                            }
                        } else {
                            break;
                        }
                    }
                    $result = true;
                    $objPHPExcel->disconnectWorksheets(); //чистим
                    unset($objPHPExcel); //память

                    echo \yii\helpers\Json::encode($result);
                }
            }
        }

        return false;
    }

    public function actionUploadCategory()
    {
        $fileName = 'file';
        $uploadPath = \Yii::$app->basePath.'/web/files';

        if (isset($_FILES[$fileName])) {
            //print_R($_FILES[$fileName]);die;
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($uploadPath.'/'."upload_category.".$file->extension)) {
                chmod($uploadPath.'/'."upload_category.".$file->extension, 0777);
                if (isset($file)) {
                    $fName = $uploadPath.'/'."upload_category.".$file->extension;
                    //начинаем читать со строки 2, в PHPExcel первая строка имеет индекс 1, и как правило это строка заголовков
                    $startRow = 2;
                    $objReader = \PHPExcel_IOFactory::createReaderForFile($fName);
                    $objReader->setReadDataOnly(true);

                    $objPHPExcel = $objReader->load($fName); //открываем файл
                    $objPHPExcel->setActiveSheetIndex(0); //устанавливаем индекс активной страницы
                    $objWorksheet = $objPHPExcel->getActiveSheet(); //делаем активной нужную страницу

                    //внутренний цикл по строкам
                    for ($i = $startRow; $i < $startRow + 20000; $i++) {
                        //получаем первое знаение в строке
                        $value = trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue());

                        if (!empty($value)) {
                            if (!empty($value) && !empty($objWorksheet->getCellByColumnAndRow(4, $i)->getValue())) {
                                $riv = RivegaucheProduct::findOne(['article' => (string)$value]);
                                $riv->group = (string)$objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
                                $riv->category = (string)$objWorksheet->getCellByColumnAndRow(5, $i)->getValue();
                                $riv->sub_category = (string)$objWorksheet->getCellByColumnAndRow(6, $i)->getValue();
                                //print_r($riv->getAttributes());die;
                                $riv->save();
                            }
                        } else {
                            break;
                        }
                    }
                    $result = true;
                    $objPHPExcel->disconnectWorksheets(); //чистим
                    unset($objPHPExcel); //память

                    echo \yii\helpers\Json::encode($result);
                }
            }
        }

        return false;
    }

    public function actionUploadComment()
    {
        $fileName = 'file';
        $uploadPath = \Yii::$app->basePath.'/web/files';

        if (isset($_FILES[$fileName])) {
            //print_R($_FILES[$fileName]);die;
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($uploadPath.'/'."upload_comment.".$file->extension)) {
                chmod($uploadPath.'/'."upload_brand.".$file->extension, 0777);
                if (isset($file)) {
                    $fName = $uploadPath.'/'."upload_comment.".$file->extension;
                    //начинаем читать со строки 2, в PHPExcel первая строка имеет индекс 1, и как правило это строка заголовков
                    $startRow = 2;
                    $objReader = \PHPExcel_IOFactory::createReaderForFile($fName);
                    $objReader->setReadDataOnly(true);

                    $objPHPExcel = $objReader->load($fName); //открываем файл
                    $objPHPExcel->setActiveSheetIndex(0); //устанавливаем индекс активной страницы
                    $objWorksheet = $objPHPExcel->getActiveSheet(); //делаем активной нужную страницу

                    //внутренний цикл по строкам
                    for ($i = $startRow; $i < $startRow + 40000; $i++) {
                        //получаем первое знаение в строке
                        $value = trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue());

                        if (!empty($value)) {
                            $rivegauche = PodruzkaProduct::findOne(['article' => (string)$value]);
                            $rivegauche->let_comment = empty((string)$objWorksheet->getCellByColumnAndRow(2, $i)->getValue()) ? null : (string)$objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
                            $rivegauche->riv_comment = empty((string)$objWorksheet->getCellByColumnAndRow(3, $i)->getValue()) ? null : (string)$objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
                            $rivegauche->ile_comment = empty((string)$objWorksheet->getCellByColumnAndRow(4, $i)->getValue()) ? null : (string)$objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
                            $rivegauche->eli_comment = empty((string)$objWorksheet->getCellByColumnAndRow(5, $i)->getValue()) ? null : (string)$objWorksheet->getCellByColumnAndRow(5, $i)->getValue();
                            $rivegauche->save();
                        } else {
                            break;
                        }
                    }
                    $result = true;
                    $objPHPExcel->disconnectWorksheets(); //чистим
                    unset($objPHPExcel); //память

                    echo \yii\helpers\Json::encode($result);
                }
            }
        }

        return false;
    }

    public function actionUploadMatching()
    {
        $fileName = 'file';
        $uploadPath = \Yii::$app->basePath.'/web/files';

        if (isset($_FILES[$fileName])) {
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($uploadPath.'/'."upload_matching.".$file->extension)) {
                chmod($uploadPath.'/'."upload_matching.".$file->extension, 0777);
                if (isset($file)) {
                    $fName = $uploadPath.'/'."upload_matching.".$file->extension;
                    //начинаем читать со строки 2, в PHPExcel первая строка имеет индекс 1, и как правило это строка заголовков
                    $startRow = 2;
                    $objReader = \PHPExcel_IOFactory::createReaderForFile($fName);
                    $objReader->setReadDataOnly(true);

                    $objPHPExcel = $objReader->load($fName); //открываем файл
                    $objPHPExcel->setActiveSheetIndex(0); //устанавливаем индекс активной страницы
                    $objWorksheet = $objPHPExcel->getActiveSheet(); //делаем активной нужную страницу
                    $emptyPodrArt = [];
                    $emptyLArt = [];
                    $emptyRArt = [];
                    $emptyIArt = [];
                    $emptyEArt = [];
                    //внутренний цикл по строкам
                    for ($i = $startRow; $i < $startRow + 20000; $i++) {
                        //получаем первое знаение в строке
                        $value = trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue());

                        if (!empty($value)) {
                            $product = PodruzkaProduct::findOne(['article' => $value]);
                            if (empty($product)) {
                                $emptyPodrArt[] = $value;
                                continue;
                            }

                            if ($letual = LetualProduct::findOne(
                                ['article' => (string)$objWorksheet->getCellByColumnAndRow(1, $i)->getValue()]
                            )
                            ) {
                                $product->l_id = $letual->id;
                            } else {
                                $emptyLArt[] = (string)$objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
                            }

                            if ($rivegauche = RivegaucheProduct::findOne(
                                ['article' => (string)$objWorksheet->getCellByColumnAndRow(2, $i)->getValue()]
                            )
                            ) {
                                $product->r_id = $rivegauche->id;
                            } else {
                                $emptyRArt[] = (string)$objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
                            }

                            if ($iledebeaute = IledebeauteProduct::findOne(
                                ['article' => (string)$objWorksheet->getCellByColumnAndRow(3, $i)->getValue()]
                            )
                            ) {
                                $product->i_id = $iledebeaute->id;
                            } else {
                                $emptyIArt[] = (string)$objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
                            }

                            if ($elize = ElizeProduct::findOne(
                                ['article' => (string)$objWorksheet->getCellByColumnAndRow(4, $i)->getValue()]
                            )
                            ) {
                                $product->e_id = $elize->id;
                            } else {
                                $emptyEArt[] = (string)$objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
                            }

                            if ($product instanceof PodruzkaProduct) {
                                $product->save();
                            }
                        } else {
                            break;
                        }
                    }
                    $result = [
                        'emptyPodrArt' => $emptyPodrArt,
                        'emptyLArt' => $emptyLArt,
                        'emptyRArt' => $emptyRArt,
                        'emptyIArt' => $emptyIArt,
                        'emptyEArt' => $emptyEArt,
                    ];
                    $objPHPExcel->disconnectWorksheets(); //чистим
                    unset($objPHPExcel); //память
                    //unlink($fName);

                    echo \yii\helpers\Json::encode($result);
                    //echo 123;
                }
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
        $eli = ElizeProduct::find()->count();

        return $this->render(
            'index',
            [
                'let_count' => $let,
                'riv_count' => $rive,
                'ile_count' => $ile,
                'eli_count' => $eli,
                'let_update' => LetualProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'riv_update' => RivegaucheProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'ile_update' => IledebeauteProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'eli_update' => ElizeProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'pod_update' => PodruzkaProduct::find()->select('max(updated_at) as updated_at')->one()->updated_at,
                'let_avg' => LetualProduct::find()->select('avg(old_price) as old_price')->join(
                    'INNER JOIN',
                    'podruzka_product',
                    'podruzka_product.l_id=letual_product.id'
                )->one(),
                'riv_avg' => RivegaucheProduct::find()->select('avg(rivegauche_product.price) as price')->join(
                    'INNER JOIN',
                    'podruzka_product',
                    'podruzka_product.r_id=rivegauche_product.id'
                )->one(),
                'ile_avg' => IledebeauteProduct::find()->select('avg(old_price) as old_price')->join(
                    'INNER JOIN',
                    'podruzka_product',
                    'podruzka_product.i_id=iledebeaute_product.id'
                )->one(),
                'eli_avg' => ElizeProduct::find()->select('avg(old_price) as old_price')->join(
                    'INNER JOIN',
                    'podruzka_product',
                    'podruzka_product.i_id=elize_product.id'
                )->one(),
                'pod_avg' => PodruzkaProduct::find()->select('avg(price) as price')->where(
                    'l_id is not null or r_id is not null or i_id is not null or e_id is not null'
                )->one(),
                'let_avg_brand' => LetualProduct::find()->select('avg(old_price) as old_price')->where(
                    'brand in (select brand from podruzka_product)'
                )->one(),
                'riv_avg_brand' => RivegaucheProduct::find()->select('avg(price) as price')->where(
                    'brand in (select brand from podruzka_product)'
                )->one(),
                'ile_avg_brand' => IledebeauteProduct::find()->select('avg(old_price) as old_price')->where(
                    'brand in (select brand from podruzka_product)'
                )->one(),
                'eli_avg_brand' => ElizeProduct::find()->select('avg(old_price) as old_price')->where(
                    'brand in (select brand from podruzka_product)'
                )->one(),
                'pod_avg_brand' => PodruzkaProduct::find()->select('avg(price) as price')->where(
                    'brand in (select brand from rivegauche_product) or brand in (select brand from letual_product) or brand in (select brand from iledebeaute_product) or brand in (select brand from elize_product)'
                )->one(),
                'let_compare' => PodruzkaProduct::find()->where('l_id is not null')->count(),
                'riv_compare' => PodruzkaProduct::find()->where('r_id is not null')->count(),
                'ile_compare' => PodruzkaProduct::find()->where('i_id is not null')->count(),
                'eli_compare' => PodruzkaProduct::find()->where('e_id is not null')->count(),
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
            return $this->render(
                'login',
                [
                    'model' => $model,
                ]
            );
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
            return $this->render(
                'contact',
                [
                    'model' => $model,
                ]
            );
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}

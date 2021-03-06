<?php

namespace app\commands;

use app\helpers\ExcelComponent;
use app\models\Upload;
use yii\console\Controller;


/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class UploadController
 *
 * @package app\commands
 */
class UploadController extends Controller
{
    /**
     * @return int
     */
    public function actionIndex()
    {
        // проверяем существует есть ли файл
        $filename = \Yii::$app->basePath . '/web/files/upload.xlsx';

       // \Yii::info(\yii\helpers\Json::encode(['fdsfds'=>'test']), 'cron');

        if (file_exists($filename)) {
            // проверяем наличие запущенного процесса
            if (Upload::findOne(['status' => 0])) {
                return 0;
            } else {
                $model = new Upload();
                $model->status = 0;
                $model->task = 'Инф.продукт';
                $model->save();

                //Вызываем компонент парсинга excel
                $component = new ExcelComponent();
                $component->uploadInformProduct();

                $model->status = 1;
                $model->save();
            }
        }

        return 0;
    }
}

<?php

namespace app\helpers;

use app\models\PodruzkaPrice;
use app\models\PodruzkaProduct;


/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class ExcelComponent
 *
 * @package app\helpers
 */
class ExcelComponent
{
    public function uploadInformProduct()
    {
        // проверяем существует есть ли файл
        $filename = './web/files/upload.xlsx';

        $chunkSize = 500;  //размер считываемых строк за раз
        $startRow = 2;  //начинаем читать со строки 2, в PHPExcel первая строка имеет индекс 1, и как правило это строка заголовков
        $exit = false;  //флаг выхода
        $empty_value = 0;   //счетчик пустых знаений

        $objReader = \PHPExcel_IOFactory::createReaderForFile($filename);
        $objReader->setReadDataOnly(true);

        $chunkFilter = new chunkReadFilter();
        $objReader->setReadFilter($chunkFilter);

        /**  Tell the Reader that we want to use the Read Filter that we've Instantiated  **/
        $objReader->setReadFilter($chunkFilter);

        //внешний цикл, пока файл не кончится
        while (!$exit) {
            $chunkFilter->setRows($startRow, $chunkSize);    //устанавливаем знаечние фильтра
            $objPHPExcel = $objReader->load($filename);        //открываем файл
            $objPHPExcel->setActiveSheetIndex(0);        //устанавливаем индекс активной страницы
            $objWorksheet = $objPHPExcel->getActiveSheet();    //делаем активной нужную страницу
            for ($i = $startRow; $i < $startRow + $chunkSize; $i++)    //внутренний цикл по строкам
            {
                //получаем первое знаение в строке
                $value = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();

                if (!empty($value)) {
                    if (!PodruzkaProduct::findOne(['article' => $value])) {
                        $product = new PodruzkaProduct();
                        $product->article = trim(
                            htmlspecialchars(
                                $objWorksheet->getCellByColumnAndRow(0, $i)->getValue()
                            )
                        );
                        $product->title = (string) $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
                        $product->arrival = (string) $objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
                        $product->group = (string) $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
                        $product->category = (string) $objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
                        $product->sub_category = (string) $objWorksheet->getCellByColumnAndRow(5, $i)->getValue();
                        $product->detail = (string) $objWorksheet->getCellByColumnAndRow(6, $i)->getValue();
                        $product->brand = (string) $objWorksheet->getCellByColumnAndRow(7, $i)->getValue();
                        $product->sub_brand = (string) $objWorksheet->getCellByColumnAndRow(8, $i)->getValue();
                        $product->line = (string) $objWorksheet->getCellByColumnAndRow(9, $i)->getValue();
                        $product->price = (string) $objWorksheet->getCellByColumnAndRow(10, $i)->getValue();
                        $product->ma_price = (string) $objWorksheet->getCellByColumnAndRow(11, $i)->getValue();

                        $product->save();
                    }
                }

                $productPrice = new PodruzkaPrice();
                $productPrice->article = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
                $productPrice->price = (string) $objWorksheet->getCellByColumnAndRow(10, $i)->getValue();
                $productPrice->ma_price = (string) $objWorksheet->getCellByColumnAndRow(11, $i)->getValue();

                if (!$productPrice->save()) {
                    //TODO ?
                }

                if (empty($value))  //проверяем значение на пустоту
                {
                    $empty_value++;
                }

                if ($empty_value == 1) //после 1 пустого значения, завершаем обработку файла, думая, что это конец
                {
                    $exit = true;
                    continue;
                }
            }

            $objPHPExcel->disconnectWorksheets();   //чистим
            unset($objPHPExcel);    //память
            $startRow += $chunkSize;    //переходим на следующий шаг цикла, увеличивая строку, с которой будем читать файл
        }

        unlink($filename);
    }
}

class chunkReadFilter implements \PHPExcel_Reader_IReadFilter
{
    private $_startRow = 0;
    private $_endRow = 0;

    /**
     * Set the list of rows that we want to read
     *
     * @param $startRow
     * @param $chunkSize
     */
    public function setRows($startRow, $chunkSize)
    {
        $this->_startRow = $startRow;
        $this->_endRow = $startRow + $chunkSize;
    }

    /**
     * @param $column
     * @param $row
     * @param string $worksheetName
     *
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = '')
    {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow
        if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
            return true;
        }

        return false;
    }
}

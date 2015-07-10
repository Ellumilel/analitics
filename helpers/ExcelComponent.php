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
        $filename = \Yii::$app->basePath . '/web/files/upload.xlsx';

        $objReader = \PHPExcel_IOFactory::createReaderForFile($filename);

        /**  Define how many rows we want to read for each "chunk"  **/
        $chunkSize = 20;
        /**  Create a new Instance of our Read Filter  **/
        $chunkFilter = new chunkReadFilter();

        /**  Tell the Reader that we want to use the Read Filter that we've Instantiated  **/
        $objReader->setReadFilter($chunkFilter);

        for ($startRow = 1; $startRow <= 15000; $startRow += $chunkSize) {

            /**  Tell the Read Filter, the limits on which rows we want to read this iteration  **/
            $chunkFilter->setRows($startRow, $chunkSize);
            /**  Load only the rows that match our filter from $inputFileName to a PHPExcel Object  **/
            $objPHPExcel = $objReader->load($filename);

            //    Do some processing here

            $sheetDataFull = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

            foreach ($sheetDataFull as $sheetData) {
                echo $sheetData['A']. ": added";
                $product = new PodruzkaProduct();
                $product->article = $sheetData['A'];
                $product->group = $sheetData['C'];
                $product->category = $sheetData['D'];
                $product->sub_category = $sheetData['E'];
                $product->detail = $sheetData['F'];
                $product->brand = $sheetData['G'];
                $product->sub_brand = $sheetData['H'];
                $product->line = $sheetData['I'];
                $product->save();

                $productPrice = new PodruzkaPrice();
                $productPrice->article = $sheetData['A'];
                $productPrice->price = $sheetData['J'];
                $productPrice->ma_price = $sheetData['K'];
                $productPrice->save();

                if(empty($sheetData['A'])) {
                    break;
                }
            }

            if(empty($sheetDataFull)) {
                break;
            }
        }
    }
}

/**  Define a Read Filter class implementing PHPExcel_Reader_IReadFilter  */
class chunkReadFilter implements \PHPExcel_Reader_IReadFilter
{
    private $_startRow = 0;

    private $_endRow = 0;

    /**
     * @param $startRow
     * @param $chunkSize
     */
    public function setRows($startRow, $chunkSize) {
        $this->_startRow = $startRow;
        $this->_endRow = $startRow + $chunkSize;
    }

    /**
     * @param String $column
     * @param \Row $row
     * @param string $worksheetName
     *
     * @return bool
     */
    public function readCell($column, $row, $worksheetName = '') {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow
        if (($row == 1) || ($row >= $this->_startRow && $row < $this->_endRow)) {
            return true;
        }
        return false;
    }
}

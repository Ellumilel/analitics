<?php
namespace app\helpers;

use yii\base\Exception;
use yii\helpers\Url;

/**
 * @author Denis Tikhonov <ozy@mailserver.ru>
 *
 * Class ExcelXML
 *
 * @package app\helpers
 */
class ExcelXML
{
    var $xml_data;
    private $nl;
    private $tab;
    private $cols;
    private $rows;
    private $worksheets;
    private $counters;
    private $xml;

    /** @var array */
    private $styles = [];
    /** @var bool */
    public $debug = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->column_width = 150;
        $this->debug        = false;
        $this->cols         = array();
        $this->row_array    = array();
        $this->rows         = array();
        $this->worksheets   = array();
        $this->counters     = array();
        $this->nl           = "\n";
        $this->tab          = "\t";
    }

    /**
     * Set debug
     */
    public function debug()
    {
        $this->debug = true;
    }
    /**
     * Generate xml
     * @returns string
     */
    public function generate()
    {
        // Create header
        $xml = $this->createHeader().$this->nl;
        // Put all worksheets
        $xml .= join('', $this->worksheets).$this->nl;
        // Finish with a footer
        $xml .= $this->createFooter();
        $this->xml = $xml;
        return $this->xml;
    }
    /**
     * Create worksheet
     * Uppon creating a worksheet, delete counters
     * @param string $worksheet_name: name of the worksheet
     */
    public function createWorksheet($worksheet_name)
    {
        $worksheet = '<Worksheet ss:Name="'.$worksheet_name.'">';
        $worksheet .= $this->createTable();
        $worksheet .= '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
        <ProtectObjects>False</ProtectObjects>
        <ProtectScenarios>False</ProtectScenarios>
        </WorksheetOptions>
        </Worksheet>';
        // Unset the counters and rows so you can generate another worksheet table
        $this->counters     = array();
        $this->row_array    = array();
        $this->rows         = '';
        // Add generated worksheet to the worksheets array
        $this->worksheets[] = $worksheet;
    }
    /**
     * Create table
     * @returns string
     */
    public function createTable()
    {
        // Create rows with the method that automaticaly sets counters for number of columns and rows
        $rows = $this->createRows();
        // Table header
        $table = sprintf(
            '<Table ss:ExpandedColumnCount="%s" ss:ExpandedRowCount="%s" x:FullColumns="1" x:FullRows="1">%s',
            $this->counters['cols'],
            $this->counters['rows'],
            $this->nl
        );
        //$table = '<Table ss:ExpandedColumnCount="'.$this->counters['cols'].'" ss:ExpandedRowCount="'.
        //$this->counters['rows'].'" x:FullColumns="1" x:FullRows="1">'.$this->nl;
        // Columns data (width mainly)
        for ($i = 1; $i <= $this->counters['cols']; $i++) {
            $table .= '<Column ss:Index="'.$i.'" ss:Width="'.$this->column_width.'" />'.$this->nl;
        }
        // Insert all rows
        $table .= join('', $rows);
        // End table
        $table .= '</Table>'.$this->nl;
        return $table;
    }
    /**
     * Add another row into the array
     * @param mixed $array: array with row cells
     * @param mixed $style: default null, if set, adds style to the array
     */
    public function addRow($array, $style = null)
    {
        if (!is_array($array)) {
            // Asume the delimiter is , or ;
            $array = str_replace(',', ';', $array);
            $array = explode(';', $array);
        }
        if (!is_null($style)) {
            $style_array = ['attach_style' => $style];
            $array = array_merge($array, $style_array);
        }
        $this->row_array[] = $array;
    }

    /**
     * Create rows
     *
     * @return array|void
     */
    public function createRows()
    {
        $row_array = $this->row_array;
        if (!is_array($row_array)) {
            return;
        }
        $cnt = 0;
        $row_cell = [];
        foreach ($row_array as $row_data) {
            $cnt++;
            // See if there are styles attached
            $style = null;
            if (isset($row_data['attach_style']) && ! empty($row_data['attach_style'])) {
                $style = $row_data['attach_style'];
                unset($row_data['attach_style']);
            }
            // Store the counter of rows
            $this->counters['rows'] = $cnt;
            $cells = '';
            $cell_cnt = 0;
            foreach ($row_data as $key => $cell_data) {
                $cell_cnt++;
                $cells .= $this->nl.$this->prepareCell($cell_data, $style);
            }
            // Store the number of cells in row
            $row_cell[$cnt][] = $cell_cnt;
            $this->rows[] = '<Row>'.$cells.$this->nl.'</Row>'.$this->nl;
        }
        // Find out max cells in all rows
        $max_cells = max($row_cell);
        $this->counters['cols'] = $max_cells[0];
        return $this->rows;
    }

    /**
     * Prepare cell
     *
     * @param string $cell_data: string for a row cell
     * @param null $style
     *
     * @return string
     */
    public function prepareCell($cell_data, $style = null)
    {
        $type = null;
        if (gettype($cell_data) == 'double') {
            $type = 'Number';
        }
        $merge = '';
        $str = str_replace("\t", " ", $cell_data);          // replace tabs with spaces
        $str = str_replace("\r\n", "\n", $str);             // replace windows-like new-lines with unix-like
        $str = str_replace('"', '""', $str);               // escape quotes so we support multiline cells now
        // If there are double doublequotes, encapsulate str in doublequotes
        preg_match('#\"\"#', $str) ? $str = '"'.$str.'"' : $str;
        // Formating: bold
        if (!is_null($style)) {
            $style = ' ss:StyleID="'.$style.'"';
        } elseif (preg_match('/^\*([^\*]+)\*$/', $str, $out)) {
            $style  = ' ss:StyleID="bold"';
            $str    = $out[1];
        }
        if (preg_match('/\|([\d]+)$/', $str, $out)) {
            $merge  = ' ss:MergeAcross="'.$out[1].'"';
            $str    = str_replace($out[0], '', $str);
        }
        //preg_match('/^([\d]+)$/', $str) ? 'Number' : 'String';
        // Get type

        $type = ($type) ? 'Number' : 'String';
        if ($type == 'Number') {
            return '<Cell ss:StyleID="s66"'.$style.$merge.'><Data ss:Type="'.$type.'">'.$str.'</Data></Cell>';
        } else {
            if (!empty($str) && (substr($str, 0, 7) == 'http://' || substr($str, 0, 8) == 'https://')) {
                $str = urldecode(iconv("utf-8", "windows-1251", $str));
                return '<Cell ss:HRef="'.$str.'"'.$style.$merge.'><Data ss:Type="'.$type.'">'.$str.'</Data></Cell>';
            } else {
                return '<Cell'.$style.$merge.'><Data ss:Type="'.$type.'">'.$str.'</Data></Cell>';
            }
        }
    }

    /**
     * Create header
     *
     * @returns string
     */
    public function createHeader()
    {
        if (is_array($this->styles)) {
            $styles = join('', $this->styles);
        }
        $header = <<<EOF
<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
xmlns:html="http://www.w3.org/TR/REC-html40">
    <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
        <DownloadComponents/>
        <LocationOfComponents HRef="file:///\\"/>
    </OfficeDocumentSettings>
    <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
        <WindowHeight>12525</WindowHeight>
        <WindowWidth>15195</WindowWidth>
        <WindowTopX>480</WindowTopX>
        <WindowTopY>120</WindowTopY>
        <ActiveSheet>0</ActiveSheet>
        <ProtectStructure>False</ProtectStructure>
        <ProtectWindows>False</ProtectWindows>
    </ExcelWorkbook>
    <Styles>
        <Style ss:ID="Default" ss:Name="Normal">
            <Alignment ss:Vertical="Bottom"/>
            <Borders/>
            <Font/>
            <Interior/>
            <NumberFormat/>
            <Protection/>
        </Style>
        <Style ss:ID="bold">
            <Font ss:Bold="1" />
        </Style>
        <Style ss:ID="s66">
            <NumberFormat ss:Format="Standard"/>
        </Style>
        $styles
    </Styles>
EOF;
        return $header;
    }

    /**
     * Add style to the header
     * @param string $style_id: id of the style the cells will reference to
     * @param array $parameters: array with parameters
     */
    public function addStyle($style_id, $parameters)
    {
        $interior = '';
        $font = '';

        foreach ($parameters as $param => $data) {
            switch ($param) {
                case 'size':
                    $font['ss:Size'] = $data;
                    break;
                case 'font':
                    $font['ss:FontName'] = $data;
                    break;
                case 'color':
                case 'colour':
                    $font['ss:Color'] = $data;
                    break;
                case 'bgcolor':
                    $interior['ss:Color'] = $data;
                    break;
                case 'bold':
                    $font['ss:Bold'] = $data;
                    break;
                case 'italic':
                    $font['ss:Italic'] = $data;
                    break;
                case 'strike':
                    $font['ss:StrikeThrough'] = $data;
                    break;
            }
        }

        if (!empty($interior) && is_array($interior)) {
            $interiors = '';
            foreach ($interior as $param => $value) {
                $interiors .= ' '.$param.'="'.$value.'"';
            }
            $interior = '<Interior ss:Pattern="Solid"'.$interiors.' />'.$this->nl;
        }

        if (!empty($font) && is_array($font)) {
            $fonts = '';
            foreach ($font as $param => $value) {
                $fonts .= ' '.$param.'="'.$value.'"';
            }
            $font = '<Font'.$fonts.' />'.$this->nl;
        }

        $this->styles[] = sprintf('<Style ss:ID="%s">%s%S</Style>', $style_id, $interior, $font);
    }
    /**
     * Create footer
     * @returns string
     */
    public function createFooter()
    {
        return '</Workbook>';
    }

    /**
     * Output as download
     *
     * @param $filename
     */
    public function download($filename)
    {
        if (!strlen($this->xml)) {
            $this->generate();
        }
        header("Cache-Control: public, must-revalidate");
        header("Pragma: no-cache");
        header("Content-Length: ".strlen($this->xml));
        header("Content-Type: application/vnd.ms-excel");
        if (!$this->debug) {
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            header("Content-Transfer-Encoding: binary");
        } else {
            header("Content-Type: text/plain");
        }
        print $this->xml;
        exit;
    }

    /**
     * @param $filename
     *
     * @throws Exception
     */
    public function sendWorkbook($filename)
    {
        if (!preg_match('/\.(xml|xls)$/', $filename)):
            throw new Exception('Filename mimetype must be .xml or .xls');
        endif;
        $filename = $this->getWorkbookTitle($filename);
        $this->generateWorkbook();
        if (preg_match('/\.xls$/', $filename)):
            header("Content-Type: application/vnd.ms-excel; charset=".$this->sEncoding);
            header("Content-Disposition: inline; filename=\"".$filename."\"");
        else:
            header("Content-Type: application/xml; charset=".$this->sEncoding);
            header("Content-Disposition: attachment; filename=\"".$filename."\"");
        endif;
        echo $this->sOutput;
    }
}

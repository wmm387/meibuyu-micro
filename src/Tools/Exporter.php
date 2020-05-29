<?php
/**
 * Created by PhpStorm.
 * User: 梁俊杰
 * Date: 2020/5/12
 * Time: 11:37 AM
 * Description:
 */

namespace Meibuyu\Micro\Tools;

use Hyperf\Contract\ConfigInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Exporter
{
    /**
     * 以字节流的形式下载
     */
    CONST DOWNLOAD_TYPE_STREAM = 1;

    /**
     * 保存文件，并返回文件的路径
     */
    CONST DOWNLOAD_TYPE_RETURN_FILE_PATH = 2;

    /**
     * 保存文件，直接输出下载
     */
    CONST DOWNLOAD_TYPE_SAVE_AND_DOWNLOAD = 3;

    CONST EXPORTER_TYPE_CSV = 1;
    CONST EXPORTER_TYPE_XLS = 2;
    CONST EXPORTER_TYPE_XLSX = 3;
    /**
     * @var ConfigInterface
     */
    protected $config;
    private $rootPath;
    private $beginRowIndex = 1;
    private $fileType = 'Xlsx';
    private $isFromTemplate = false;
    private $sheet = null;
    private $reader = null;
    private $sheetIndex = 0;
    private $name = "";
    private $beginColumnChar = "A";

    /**
     * Exporter constructor.
     * @param int $export_type 类型
     * @param string $tempFilePath 模板文件地址
     * @param string $name 名称
     * @param int $sheetIndex
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \Exception
     */
    public function __construct(int $export_type, $tempFilePath = "", $name = "export_data", $sheetIndex = 0)
    {
        $this->config = container(ConfigInterface::class);
        $this->rootPath = $this->config->get('server.settings.document_root', BASE_PATH . '/public');
        if ($tempFilePath) {
            $tempFilePath = $this->rootPath . "/" . $tempFilePath;
            //print_r($tempFilePath);
            if (file_exists($tempFilePath)) {
                $this->fileType = ucfirst(strtolower(pathinfo($tempFilePath, PATHINFO_EXTENSION)));
                $reader = IOFactory::createReader($this->fileType)->load($tempFilePath);
                $this->isFromTemplate = true;
            } else {
                throw new \Exception("模板文件不存在");
            }
        } else {
            switch ($export_type) {
                case self::EXPORTER_TYPE_CSV:
                    $this->fileType = "Csv";
                    break;
                case self::EXPORTER_TYPE_XLS:
                    $this->fileType = "Xls";
                    break;
                case self::EXPORTER_TYPE_XLSX:
                    $this->fileType = "Xlsx";
                    break;
                default:
                    throw new \Exception("类型不支持。");
                    break;
            }
            $reader = new Spreadsheet();
        }
        $this->name = $name;
        $this->sheet = $reader->getSheet($sheetIndex);
        $this->reader = $reader;
        $this->sheetIndex = $sheetIndex;
    }

    /**
     * 设置非保护区间，调用此方法全表将自动保护
     * @param string $unprotectRange 例如"A1:B1";从A列B列第一行到数据结束行不需要保护,
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setUnprotectRange($unprotectRange = "")
    {
        $this->sheet->getProtection()->setSheet(true);
        if ($unprotectRange) {
            $this->sheet->getStyle($unprotectRange)
                ->getProtection()
                ->setLocked(Protection::PROTECTION_UNPROTECTED);
        }
        return $this;
    }

    /**
     * 往Excel里面新增一张sheet表
     * @param string $name sheet的名字
     * @param bool $activeSheet 是否激货并切换到当前sheet 默认否
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function addSheet($name, $activeSheet = false)
    {
        $workSheet = new Worksheet(null, $name);
        $this->reader->addSheet($workSheet);
        if ($activeSheet) {
            $this->setSheetByName($name);
        }
        return $this;
    }

    /** 根据名字sheet表名激化切换到 该表
     * @param $name
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setSheetByName($name)
    {
        $this->sheet = $this->reader->setActiveSheetIndexByName($name);
        $this->sheetIndex = $this->reader->getActiveSheetIndex();
        $this->resetRowIndex();
        return $this;
    }

    /**
     * 重置排序
     */
    private function resetRowIndex()
    {
        $this->beginRowIndex = 1;
        return $this;
    }

    /**
     * 获取当前数据填充到哪一行了 或者最高行
     * @return int
     */
    public function getCurrentRowIndex()
    {
        return $this->sheet->getHighestRow();
    }

    /**设置从哪一列开始填充数据
     * @param string $char 默认 A列
     */
    public function setBeginColumnChar($char = "A")
    {
        $this->beginColumnChar = $char;
        return $this;
    }

    /**设置从哪一行开始填充数据
     * @param int $index 默认第一行
     */
    public function setBeginRowIndex($index = 1)
    {
        $this->beginRowIndex = $index;
        return $this;
    }

    /**
     * 根据sheet索引切换到对应sheet
     * @param int $sheetIndex
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setSheetByIndex(int $sheetIndex)
    {
        $this->sheet = $this->reader->setActiveSheetIndex($sheetIndex);
        $this->sheetIndex = $sheetIndex;
        $this->resetRowIndex();
        return $this;
    }

    /**
     * 往表格里填充数据
     * @param array $data 支持一维数组和二位数组，数据顺序与表头顺序相同
     * @param array $keys 如果$data是原生数组支持直接根据$keys
     * 提取数据，可多层提取例如 ['product_name','color.name','creator.user_status.name']
     * 支持默认值，为空时显示默认值 例如['product_name|默认值','color.name|无颜色']
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function append(array $data, $keys = [])
    {
        // 一维数组转二维
        foreach ($data as $v) {
            if (!is_array($v)) {
                $data = [$data];
            }
            break;
        }

        if (count($keys)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->getKeyValue($v, $keys);
            }
        }

        $this->sheet->fromArray($data, null, $this->beginColumnChar . $this->beginRowIndex);
        //美化样式
        $this->applyStyle($this->beginColumnChar . $this->beginRowIndex . ":" . $this->sheet->getHighestColumn() . ($this->beginRowIndex + count($data) - 1));
        $this->beginRowIndex += count($data);
        return $this;
    }

    /**
     * 根据key提取数据
     * @param array $data
     * @param array $keys
     * @return array
     */
    private function getKeyValue(array $data, array $keys)
    {
        $result = [];
        foreach ($keys as $k => $v) {
            if (strpos($v, ".") === false) {
                if (isset($data[$v])) {
                    $result[] = $data[$v];
                } else {
                    new \Exception("key为{$v}的数据不存在");
                }
            } else {
                $separate = explode("|", $v);
                $nullValue = isset($separate[1]) ? $separate[1] : "";
                $list = explode(".", $separate[0]);
                $t = count($list) - 1;
                $b = $data;
                foreach ($list as $lk => $lv) {
                    if (isset($b[$lv])) {
                        $b = $b[$lv];
                    } else {
                        $b = "";
                    }
                    //提取到最后一个
                    if ($t === $lk) {
                        $result[] = (($b === "") ? $nullValue : $b);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 给表格加样式
     * @param $pRange
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function applyStyle($pRange)
    {
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '666'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'quotePrefix' => true,
        ];

        $this->sheet->getStyle($pRange)->applyFromArray($styleArray);
    }

    /**
     * 下载导出的文件
     * @param int $downloadType 下载形式 支持 Exporter::DOWNLOAD_TYPE_STREAM Exporter::DOWNLOAD_TYPE_RETURN_FILE_PATH Exporter::DOWNLOAD_TYPE_SAVE_AND_DOWNLOAD
     * @param string $filename 下载的文件名
     * @return mixed | void
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Exception
     */
    public function download(int $downloadType, $filename = "")
    {
        set_time_limit(0);
        if (!$filename) {
            $filename = $this->name ? $this->name : rand(1, 9999999) . time() . rand(1, 9999999);
        }
        $filename .= "." . strtolower($this->fileType);
        $this->reader->setActiveSheetIndex(0);
        $objWriter = IOFactory::createWriter($this->reader, $this->fileType);
        switch ($downloadType) {
            case self::DOWNLOAD_TYPE_STREAM:
            case self::DOWNLOAD_TYPE_SAVE_AND_DOWNLOAD:
                if ($this->fileType == 'Xlsx') {
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                } elseif ($this->fileType == 'Xls') {
                    header('Content-Type: application/vnd.ms-excel');
                } else {
                    header("Content-type:text/csv;");
                }
                header("Content-Disposition: attachment;filename="
                    . $filename);
                header('Cache-Control: max-age=0');
                ob_clean();

                if ($downloadType == self::DOWNLOAD_TYPE_STREAM) {
                    $objWriter->save('php://output');
                } else {
                    $f = $this->rootPath . "/export";
                    if (!file_exists($f)) {
                        mkdir($f, 0777, true);
                    }
                    $f .= "/" . $filename;
                    $objWriter->save($f);
                    header('Content-length:' . filesize($f));
                    $fp = fopen($f, 'r');
                    fseek($fp, 0);
                    ob_start();
                    while (!feof($fp) && connection_status() == 0) {
                        $chunk_size = 1024 * 1024 * 2; // 2MB
                        echo fread($fp, $chunk_size);
                        ob_flush();
                        flush();
                    }
                    ob_end_clean();
                    fclose($fp);
                    @unlink($f);
                }
                break;
            case self::DOWNLOAD_TYPE_RETURN_FILE_PATH:
                $filePath = $this->rootPath . "/export/";
                !is_dir($filePath) && mkdir($filePath, 0777, true);
                $fileName = $filePath . date('YmdHis') . '-' . $filename;
                $objWriter->save($fileName);
                return $this->config->get('app_domain') . str_replace($this->rootPath, '', $fileName);
                break;
            default:
                throw new \Exception('不支持此种下载类型');
                break;
        }
    }

    /**
     * 返回当前激化的sheet表
     * @return null|Worksheet
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * 返回原生的PhpOffice Reader 手动处理数据
     * @return null|Spreadsheet
     */
    public function getReader()
    {
        return $this->reader;
    }

}

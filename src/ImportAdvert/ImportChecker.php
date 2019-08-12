<?php

namespace App\ImportAdvert;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ImportChecker
{
    const XLS_EXTENSION = "xls";
    const CSV_EXTENSION = "csv";
    const XLSX_EXTENSION = "xlsx";

    const IMPORT_FILE_EXTENSIONS = [self::XLS_EXTENSION, self::CSV_EXTENSION, self::XLSX_EXTENSION];

    const BRAND_HEADERS = ["МАРКА", "Марка"];
    const MODEL_HEADERS = ["МОДЕЛЬ", "Модель"];
    const YEAR_HEADERS = ["ГОД", "Год"];
    const SPARE_PART_HEADERS = ["НАИМЕНОВАНИЕ ЗАПЧАСТИ", "Запчасть", "ЗАПЧАСТЬ", "Наименование детали"];

    const ENGINE_TYPE_HEADERS = ["ТОПЛИВО", "Топливо", "Двигатель"];
    const ENGINE_CAPACITY_HEADERS = ["ОБЪЕМ ДВИГАТЕЛЯ", "Объем", "ОБЪЕМ"];
    const GEAR_BOX_TYPE_HEADERS = ["КОРОБКА", "Коробка", "КПП"];
    const VEHICLE_TYPE_HEADERS = ["ТИП КУЗОВА", "Тип кузова"];
    const NUMBER_SPARE_PART_HEADERS = ["ОРИГИНАЛЬНЫЙ НОМЕР", "Номер", "Оригинальный номер"];
    const DESCRIPTION_HEADERS = ["ОПИСАНИЕ", "Описание", "Описание - примечание к состоянию или комплектности детали, прочие данные"];
    const IMAGE_HEADERS = ["ФОТО", "Фото", "URL фото детали"];
    const COST_HEADERS = ["ЦЕНА", "Цена", "Цена BYN"];
    const CURRENCY_HEADERS = ["ВАЛЮТА", "Валюта"];

    const BRAND_KEY = "brand";
    const MODEL_KEY = "model";
    const YEAR_KEY = "year";
    const SPARE_PART_KEY = "spare_part";
    const ENGINE_TYPE_KEY = "engine_type";
    const ENGINE_CAPACITY_KEY = "engine_capacity";
    const GEAR_BOX_TYPE_KEY = "gear_box_type";
    const VEHICLE_TYPE_KEY = "vehicle_type";
    const NUMBER_SPARE_PART_KEY = "number_spare_part";
    const DESCRIPTION_KEY = "description";
    const IMAGE_KEY = "image";
    const COST_KEY = "cost";
    const CURRENCY_KEY = "currency";

    static $requiredFields = [self::BRAND_KEY, self::MODEL_KEY, self::YEAR_KEY, self::SPARE_PART_KEY];

    /** @var EntityManagerInterface */
    private $em;

    private $headerIndexes = [];

    /**
     * ImportChecker constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isCorrectFileToImportSpecificAdvert($filePath)
    {
        $result = $this->checkExtension($filePath);

        if($result !== true){
            return $result["errors"];
        }

        return $this->checkFileData($filePath);
    }

    private function checkExtension($filePath)
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);

        if(!in_array($ext, self::IMPORT_FILE_EXTENSIONS)){
            return ["errors" => ["Некорректное расширение"]];
        }

        return true;
    }

    /**
     * @param $file
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     *
     * @return mixed
     */
    private function checkFileData($file)
    {
        $response = ["errors" => []];

        $reader = self::getReader($file);
        $reader->setReadDataOnly(true);
        $chunkFilter = new ChunkReaderFilter();
        $reader->setReadFilter($chunkFilter);

        $chunkFilter->setRows(0, 1);

        $spreadsheet = $reader->load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $response["errors"] = array_merge($this->checkHeaders($sheetData)["errors"], $response["errors"]);

        if(!count($response["errors"])) {
            for ($startRow = 1; $startRow < ImportUploader::MAX_ROWS; $startRow += ImportUploader::ROWS_CHUNK) {
                /**  Tell the Read Filter which rows we want this iteration  **/
                $chunkFilter->setRows($startRow, ImportUploader::ROWS_CHUNK);
                /**  Load only the rows that match our filter  **/
                $spreadsheet = $reader->load($file);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                //    Do some processing here
                $response["errors"] = array_merge($this->checkAdvertData($sheetData)["errors"], $response["errors"]);

                if(!count($response["errors"]) || count($sheetData) < ImportUploader::ROWS_CHUNK){
                    break;
                }
            }
        }

        $response["success"] = !count($response["errors"]);

        return $response;
    }

    private function checkHeaders(array $headersData)
    {
        if(!count($headersData)){
            return ["errors" => ["Нет обязательных столбцов (Марка, Модель, Год, Запчасть)"],];
        }

        $errors = ["errors" => []];
        $headers = array_values($headersData)[0];

        $this->headerIndexes = self::getHeaderIndexes($headers);

        foreach (self::$requiredFields as $field){
            if($this->headerIndexes[$field] === false){
                $errors["errors"][] = "Отсутствует обязательное поле: " . $field;
            }
        }

        return $errors;
    }

    private function checkAdvertData(array $sheetData)
    {
        if(!count($sheetData)){
            return ["errors" => ["Нет данных для импорта"]];
        }

        $data = array_values($sheetData);

        foreach ($data as $line){
            if($this->isCorrectLineToImport($line)){
                return ["errors" => []];
            }
        }

        return ["errors" => ["Нет корретных данных для импорта"]];
    }

    private function isCorrectLineToImport(array $line)
    {
        $brand = $this->em->getRepository(Brand::class)->findBrandForImport(trim($line[$this->headerIndexes[self::BRAND_KEY]]));

        if(count($brand) != 1){
            return false;
        }

        $sparePart = $this->em->getRepository(SparePart::class)->findSparePartForImport(trim($line[$this->headerIndexes[self::SPARE_PART_KEY]]));

        if(!count($sparePart)){
            //echo $line[$sparePartIndex] . " | ";
            return false;
        }

        $models = $this->em->getRepository(Model::class)->findModelForImport(trim($line[$this->headerIndexes[self::MODEL_KEY]]), $brand[0]);

        if(!count($models)){
            return false;
        }

        $year = (int)trim($line[$this->headerIndexes[self::YEAR_KEY]]);

        foreach ($models as $model){
            if($model->getTechnicalData()->getYearFrom() <= $year && $year <= $model->getTechnicalData()->getYearTo()){
                return true;
            }
        }

        return false;
    }

    static function getHeaderIndexes($headers)
    {
        return [
            self::BRAND_KEY => self::getSpecificHeaderIndex($headers, self::BRAND_HEADERS),
            self::MODEL_KEY => self::getSpecificHeaderIndex($headers, self::MODEL_HEADERS),
            self::YEAR_KEY => self::getSpecificHeaderIndex($headers, self::YEAR_HEADERS),
            self::SPARE_PART_KEY => self::getSpecificHeaderIndex($headers, self::SPARE_PART_HEADERS),
            self::ENGINE_TYPE_KEY => self::getSpecificHeaderIndex($headers, self::ENGINE_TYPE_HEADERS),
            self::ENGINE_CAPACITY_KEY => self::getSpecificHeaderIndex($headers, self::ENGINE_CAPACITY_HEADERS),
            self::GEAR_BOX_TYPE_KEY => self::getSpecificHeaderIndex($headers, self::GEAR_BOX_TYPE_HEADERS),
            self::VEHICLE_TYPE_KEY => self::getSpecificHeaderIndex($headers, self::VEHICLE_TYPE_HEADERS),
            self::NUMBER_SPARE_PART_KEY => self::getSpecificHeaderIndex($headers, self::NUMBER_SPARE_PART_HEADERS),
            self::DESCRIPTION_KEY => self::getSpecificHeaderIndex($headers, self::DESCRIPTION_HEADERS),
            self::IMAGE_KEY => self::getSpecificHeaderIndex($headers, self::IMAGE_HEADERS),
            self::COST_KEY => self::getSpecificHeaderIndex($headers, self::COST_HEADERS),
            self::CURRENCY_KEY => self::getSpecificHeaderIndex($headers, self::CURRENCY_HEADERS),
        ];
    }

    static function getSpecificHeaderIndex($headers, $suggestions)
    {
        foreach ($suggestions as $suggestion){
            if(in_array($suggestion, $headers)){
                return array_search($suggestion, $headers);
            }
        }

        return false;
    }

    static function getReader($file)
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        switch ($ext){
            case self::CSV_EXTENSION:
                return new Csv();
            case self::XLSX_EXTENSION:
                return new Xlsx();
            default:
                return new Xls();
        }
    }
}
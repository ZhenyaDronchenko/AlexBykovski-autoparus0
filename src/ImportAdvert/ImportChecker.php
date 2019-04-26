<?php

namespace App\ImportAdvert;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class ImportChecker
{
    const IMPORT_FILE_EXTENSION = "csv";

    const BRAND_HEADER = "Марка";
    const MODEL_HEADER = "Модель";
    const YEAR_HEADER = "Год";
    const SPARE_PART_HEADER = "Запчасть";

    const ENGINE_TYPE_HEADER = "Топливо";
    const ENGINE_CAPACITY_HEADER = "Объём";
    const GEAR_BOX_TYPE_HEADER = "Коробка";
    const VEHICLE_TYPE_HEADER = "Тип кузова";
    const NUMBER_SPARE_PART_HEADER = "Оригинальный номер";
    const DESCRIPTION_HEADER = "Описание";
    const IMAGE_HEADER = "Фото";
    const COST_HEADER = "Цена";

    static $requiredFields = [self::BRAND_HEADER, self::MODEL_HEADER, self::YEAR_HEADER, self::SPARE_PART_HEADER];

    /** @var EntityManagerInterface */
    private $em;

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

        return [
            "success" => true,
            "errors" => [],
        ];
        if($result !== true){
            return $result["errors"];
        }

        return $this->checkFileData($filePath);
    }

    private function checkExtension($filePath)
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);

        if($ext !== self::IMPORT_FILE_EXTENSION){
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

        $reader = new Csv();

        $spreadsheet = $reader->load($file);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $response["errors"] = array_merge($this->checkHeaders($sheetData)["errors"], $response["errors"]);

        if(!count($response["errors"])) {
            $response["errors"] = array_merge($this->checkAdvertData($sheetData)["errors"], $response["errors"]);
        }

        $response["success"] = !count($response["errors"]);

        return $response;
    }

    private function checkHeaders(array $sheetData)
    {
        if(!count($sheetData)){
            return ["errors" => ["Нет обязательных столбцов (Марка, Модель, Год, Запчасть)"],];
        }

        $errors = ["errors" => []];
        $headers = array_values($sheetData)[0];

        foreach (self::$requiredFields as $field){
            if(!in_array($field, $headers)){
                $errors["errors"][] = "Отсутствует обязательное поле: " . $field;
            }
        }

        return $errors;
    }

    private function checkAdvertData(array $sheetData)
    {
        if(count($sheetData) < 2){
            return ["errors" => ["Нет данных для импорта"]];
        }

        $data = array_values($sheetData);
        $headers = array_shift($data);

        $count = 0;

        foreach ($data as $line){
            if($this->isCorrectLineToImport($headers, $line)){
                ++$count;
                return ["errors" => []];
            }
        }

        return ["errors" => ["Нет корретных данных для импорта"]];
    }

    private function isCorrectLineToImport(array $headers, array $line)
    {
        $brandIndex = array_search(self::BRAND_HEADER, $headers);
        $modelIndex = array_search(self::MODEL_HEADER, $headers);
        $sparePartIndex = array_search(self::SPARE_PART_HEADER, $headers);
        $yearIndex = array_search(self::YEAR_HEADER, $headers);
        $brand = $this->em->getRepository(Brand::class)->findOneBy(["brandEn" => trim($line[$brandIndex])]);

        if(!$brand){
            return false;
        }

        $sparePart = $this->em->getRepository(SparePart::class)->findSparePartForImport(trim($line[$sparePartIndex]));

        if(count($sparePart) == 1){
            //echo $line[$sparePartIndex] . " | ";
            return false;
        }

        $models = $this->em->getRepository(Model::class)->findModelForImport(trim($line[$modelIndex]), $brand);

        if(!count($models)){
            return false;
        }

        $year = (int)trim($line[$yearIndex]);

//        if(count($models) > 1){
//            echo $line[$modelIndex] . " | ";
//        }

        foreach ($models as $model){
            if($model->getTechnicalData()->getYearFrom() <= $year && $year <= $model->getTechnicalData()->getYearTo()){
                return true;
            }
        }

        return false;
    }
}
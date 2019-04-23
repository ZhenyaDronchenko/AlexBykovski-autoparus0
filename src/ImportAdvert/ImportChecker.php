<?php

namespace App\ImportAdvert;

use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class ImportChecker
{
    const IMPORT_FILE_EXTENSION = "csv";
    const BRAND_HEADER = "Марка";
    const MODEL_HEADER = "Модель";
    const YEAR_HEADER = "Год";
    const SPARE_PART_HEADER = "Запчасть";

    static $requiredFields = [self::BRAND_HEADER, self::MODEL_HEADER, self::YEAR_HEADER, self::SPARE_PART_HEADER];

    static $allFields = ["Марка", "Модель", "Год", "Запчасть", "Топливо", "Объём", "Коробка", "Тип кузова",
        "Оригинальный номер", "Описание", "Фото", "Цена"];

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
            return [
                "errors" => ["Нет обязательных столбцов (Марка, Модель, Год, Запчасть)"],
            ];
        }

        $errors = [];
        $headers = array_values($sheetData)[0];

        foreach (self::$requiredFields as $field){
            if(!in_array($field, $headers)){
                $errors[] = "Отсутствует обязательное поле: " . $field;
            }
        }

        return $errors;
    }

    private function checkAdvertData(array $sheetData)
    {
        if(!count($sheetData) < 1){
            return [
                "errors" => ["Нет данных для импорта"],
            ];
        }

        $data = array_values($sheetData);
        $headers = array_shift($data);

        foreach ($data as $line){
            if($this->isCorrectLineToImport($headers, $line)){
                return ["errors" => []];
            }
        }

        return ["errors" => ["Нет корретных данных для импорта"]];
    }

    private function isCorrectLineToImport(array $headers, array $line)
    {

    }
}
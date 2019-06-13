<?php

namespace App\ImportAdvert;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\Client\SellerAdvertDetail;
use App\Entity\Engine;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\SparePart;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class ImportUploaderCSV
{
    const ERROR_EMPTY_DATA = "Строка %d: нет данных или не найдено соответствие по полю: %s";
    const ERROR_MULTIPLE_DATA = "Строка %d: найдено несколько соответствий по полю: %s";

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

    public function importFile($filePath, SellerAdvertDetail $advertDetail)
    {
        list($headers, $lines) = $this->getFileData($filePath);

        list($errors, $countImported) = $this->importFileLines($headers, $lines, $advertDetail);

        return [
            "errors" => $errors,
            "success" => !!count($errors),
            "countImported" => $countImported,
        ];
    }

    /**
     * @param $file
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     *
     * @return mixed
     */
    private function getFileData($file)
    {
        $reader = new Csv();

        $spreadsheet = $reader->load($file);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $data = array_values($sheetData);

        $headers = array_shift($data);

        return [$headers, $data];
    }

    private function importFileLines(array $headers, array $lines, SellerAdvertDetail $advertDetail)
    {
        $count = 0;
        $errors = [];

        foreach ($lines as $index => $line){
            $advert = $this->importLine($headers, $line, ++$index);

            if($advert instanceof AutoSparePartSpecificAdvert && !$advertDetail->hasSameImportSpecificAdvert($advert)){
                ++$count;

                $advert->setSellerAdvertDetail($advertDetail);
                $advert->setCondition(AutoSparePartSpecificAdvert::USED_TYPE);
                $advert->setStockType(AutoSparePartSpecificAdvert::IN_STOCK_TYPE);

                $advertDetail->addAutoSparePartSpecificAdvert($advert);

                $this->em->persist($advert);

                if($count > 0 && $count % 50 === 0){
                    $this->em->flush();
                }
            }
            elseif(is_array($advert)){
                $errors = array_merge($errors, $advert);
            }
        }

        $this->em->flush();

        if(!$count){
            return [["Нет корретных данных для импорта"], $count];
        }

        return [$errors, $count];
    }

    private function importLine(array $headers, array $line, $index)
    {
        $advert = new AutoSparePartSpecificAdvert(new SellerAdvertDetail());

        $brand = $this->getBrand($headers, $line, $index);

        if(!($brand instanceof Brand)){
            return $brand;
        }

        $advert->setBrand($brand);

        $sparePart = $this->getSparePart($headers, $line, $index);

        if(is_array($sparePart)){
            return $sparePart;
        }

        $advert->setSparePart($sparePart);

        $model = $this->getModel($headers, $line, $index, $brand);

        if(!($model instanceof Model)){
            return $model;
        }

        $advert->setModel($model);

        $year = $this->getYear($headers, $line, $index, $model);

        if(!is_int($year)){
            return $year;
        }

        $advert->setYear($year);

        $engineType = $this->getEngineType($headers, $line, $model);

        $advert->setEngineType($engineType);
        $advert->setEngineCapacity($this->getEngineCapacity($headers, $line, $model, $engineType));
        $advert->setGearBoxType($this->getGearBoxType($headers, $line, $model));
        $advert->setVehicleType($this->getVehicleType($headers, $line, $model));
        $advert->setSparePartNumber($this->getSparePartNumber($headers, $line));
        $advert->setImage($this->getImage($headers, $line));
        $advert->setCost($this->getCost($headers, $line));
        $advert->setComment($this->getDescription($headers, $line));

        return $advert;
    }

    private function getBrand($headers, $line, $index)
    {
        $brandIndex = array_search(ImportCheckerCSV::BRAND_HEADER, $headers);

        if(!trim($line[$brandIndex])){
            return [sprintf(self::ERROR_EMPTY_DATA, $index, ImportCheckerCSV::BRAND_HEADER)];
        }

        $brand = $this->em->getRepository(Brand::class)->findOneBy(["brandEn" => trim($line[$brandIndex])]);

        if(!$brand){
            return [sprintf(self::ERROR_EMPTY_DATA, $index, ImportCheckerCSV::BRAND_HEADER)];
        }

        return $brand;
    }

    private function getSparePart($headers, $line, $index)
    {
        $sparePartIndex = array_search(ImportCheckerCSV::SPARE_PART_HEADER, $headers);

        if(!trim($line[$sparePartIndex])){
            return [sprintf(self::ERROR_EMPTY_DATA, $index, ImportCheckerCSV::SPARE_PART_HEADER)];
        }

        $sparePart = $this->em->getRepository(SparePart::class)->findSparePartForImport(trim($line[$sparePartIndex]));

        if(!is_array($sparePart ) || !count($sparePart)){
            return [sprintf(self::ERROR_EMPTY_DATA, $index, ImportCheckerCSV::SPARE_PART_HEADER)];
        }

        if(count($sparePart) > 1){
            return [sprintf(self::ERROR_MULTIPLE_DATA, $index, ImportCheckerCSV::SPARE_PART_HEADER)];
        }

        return array_values($sparePart)[0]->getName();
    }

    private function getModel($headers, $line, $index, Brand $brand)
    {
        $modelIndex = array_search(ImportCheckerCSV::MODEL_HEADER, $headers);

        if(!trim($line[$modelIndex])){
            return [sprintf(self::ERROR_EMPTY_DATA, $index, ImportCheckerCSV::MODEL_HEADER)];
        }

        $model = $this->em->getRepository(Model::class)->findModelForImport(trim($line[$modelIndex]), $brand);

        if(!is_array($model) || !count($model)){
            return [sprintf(self::ERROR_EMPTY_DATA, $index, ImportCheckerCSV::MODEL_HEADER)];
        }

        if(count($model) > 1){
            return [sprintf(self::ERROR_MULTIPLE_DATA, $index, ImportCheckerCSV::MODEL_HEADER)];
        }

        return array_values($model)[0];
    }

    private function getYear($headers, $line, $index, Model $model)
    {
        $yearIndex = array_search(ImportCheckerCSV::YEAR_HEADER, $headers);
        $year = (int)trim($line[$yearIndex]);

        if(!$year){
            return [sprintf(self::ERROR_EMPTY_DATA, $index, ImportCheckerCSV::YEAR_HEADER)];
        }

        if(!($model->getTechnicalData()->getYearFrom() <= $year && $year <= $model->getTechnicalData()->getYearTo())){
            return [sprintf(self::ERROR_EMPTY_DATA, $index, ImportCheckerCSV::YEAR_HEADER)];
        }

        return $year;
    }

    private function getEngineType($headers, $line, Model $model)
    {
        $engineTypeIndex = array_search(ImportCheckerCSV::ENGINE_TYPE_HEADER, $headers);
        $engineType = strtolower(trim($line[$engineTypeIndex]));

        if(!$engineType){
            return null;
        }

        return count($model->getTechnicalData()->getEnginesByType($engineType)) ?
            $engineType : null;

    }

    private function getEngineCapacity($headers, $line, Model $model, $engineType)
    {
        $engineCapacityIndex = array_search(ImportCheckerCSV::ENGINE_CAPACITY_HEADER, $headers);
        $engineCapacity = strtolower(trim($line[$engineCapacityIndex]));

        if(!$engineCapacity || $engineType){
            return null;
        }

        $engineCapacity = number_format((float)$engineCapacity,1,'.','');
        $engines = $model->getTechnicalData()->getEnginesByType($engineType);

        /** @var Engine $engine */
        foreach ($engines as $engine){
            if($engine->getCapacity() === $engineCapacity){
                return $engineCapacity;
            }
        }

        return null;
    }

    private function getGearBoxType($headers, $line, Model $model)
    {
        $gearBoxTypeIndex = array_search(ImportCheckerCSV::GEAR_BOX_TYPE_HEADER, $headers);
        $gearBoxType = strtolower(trim($line[$gearBoxTypeIndex]));

        if(!$gearBoxType){
            return null;
        }

        $gearBoxType = strtolower($this->parseGearBox($gearBoxType));

        $gearBox = $model->getTechnicalData()->getGearBoxByType($gearBoxType);

        return count($gearBox) ? $gearBox[0] : null;
    }

    private function getVehicleType($headers, $line, Model $model)
    {
        $vehicleTypeIndex = array_search(ImportCheckerCSV::VEHICLE_TYPE_HEADER, $headers);
        $vehicleType = trim($line[$vehicleTypeIndex]);

        if(!$vehicleType){
            return null;
        }

        $vehicle = $model->getTechnicalData()->getVehicleType($vehicleType);

        return count($vehicle) ? $vehicle[0] : null;
    }

    private function getSparePartNumber($headers, $line)
    {
        $numberIndex = array_search(ImportCheckerCSV::NUMBER_SPARE_PART_HEADER, $headers);
        $number = trim($line[$numberIndex]);

        return $number ?: null;
    }

    private function getDescription($headers, $line)
    {
        $descriptionIndex = array_search(ImportCheckerCSV::DESCRIPTION_HEADER, $headers);
        $description = trim($line[$descriptionIndex]);

        return $description ?: null;
    }

    private function getImage($headers, $line)
    {
        $imageIndex = array_search(ImportCheckerCSV::IMAGE_HEADER, $headers);
        $image = trim($line[$imageIndex]);

        return $image ?: null;
    }

    private function getCost($headers, $line)
    {
        $costIndex = array_search(ImportCheckerCSV::COST_HEADER, $headers);
        $cost = trim($line[$costIndex]);

        return $cost ?: null;
    }

    private function parseGearBox($gearBoxType)
    {
        switch (strtoupper($gearBoxType)){
            case "АКПП":
                return GearBoxType::AUTOMATIC_TYPE;
            case "MКПП":
                return GearBoxType::MECHANICS_TYPE;
            default:
                return $gearBoxType;
        }
    }
}
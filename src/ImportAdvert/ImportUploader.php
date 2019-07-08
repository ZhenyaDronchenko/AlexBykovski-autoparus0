<?php

namespace App\ImportAdvert;

use App\Entity\Advert\AutoSparePart\AutoSparePartSpecificAdvert;
use App\Entity\Brand;
use App\Entity\Client\SellerAdvertDetail;
use App\Entity\Engine;
use App\Entity\GearBoxType;
use App\Entity\Model;
use App\Entity\SparePart;
use App\Entity\UserData\ImportAdvertError;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ImportUploader
{
    const ERROR_EMPTY_DATA = "Строка %d: нет данных или не найдено соответствие по полю: %s";
    const ERROR_MULTIPLE_DATA = "Строка %d: найдено несколько соответствий по полю: %s";

    /** @var EntityManagerInterface */
    private $em;

    /** @var array */
    private $importErrors;

    /**
     * ImportChecker constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->importErrors = [];
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
        $reader = new Xls();

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

        $advertDetail->setAutoSparePartSpecificAdverts(new ArrayCollection());

        foreach ($lines as $index => $line){
            $advert = $this->importLine($headers, $line, ++$index);

            //if($advert instanceof AutoSparePartSpecificAdvert && !$advertDetail->hasSameImportSpecificAdvert($advert)){
            if($advert instanceof AutoSparePartSpecificAdvert){
                ++$count;

                $advert->setCondition(AutoSparePartSpecificAdvert::USED_TYPE);
                $advert->setStockType(AutoSparePartSpecificAdvert::IN_STOCK_TYPE);
                $advert->setSellerAdvertDetail($advertDetail);

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

        if(!$count && !count($errors)){
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
        $advert->setCurrency($this->getCurrency($headers, $line));

        return $advert;
    }

    private function getBrand($headers, $line, $index)
    {
        $brandIndex = array_search(ImportChecker::BRAND_HEADER, $headers);
        $value = trim($line[$brandIndex]);

        if(!$value){
            return $this->handleError($headers, $line, $index, $value, ImportChecker::BRAND_HEADER, self::ERROR_EMPTY_DATA);
        }

        $brand = $this->em->getRepository(Brand::class)->findBrandForImport($value);

        if(!is_array($brand) || !count($brand)){
            return $this->handleError($headers, $line, $index, $value, ImportChecker::BRAND_HEADER, self::ERROR_EMPTY_DATA);
        }

        if(count($brand) > 1){
            return $this->handleError($headers, $line, $index, $value, ImportChecker::BRAND_HEADER, self::ERROR_MULTIPLE_DATA);
        }

        return array_values($brand)[0];
    }

    private function getSparePart($headers, $line, $index)
    {
        $sparePartIndex = array_search(ImportChecker::SPARE_PART_HEADER, $headers);
        $value = trim($line[$sparePartIndex]);

        if(!$value){
            return $this->handleError($headers, $line, $index, $value, ImportChecker::SPARE_PART_HEADER, self::ERROR_EMPTY_DATA);
        }

        $sparePart = $this->em->getRepository(SparePart::class)->findSparePartForImport($value);

        if(!is_array($sparePart ) || !count($sparePart)){
            return $this->handleError($headers, $line, $index, $value, ImportChecker::SPARE_PART_HEADER, self::ERROR_EMPTY_DATA);
        }

        if(count($sparePart) > 1){
            $fullSame = null;
            $beforeBracketSame = null;

            /** @var SparePart $item */
            foreach ($sparePart as $item){
                if(trim($item->getName()) === $value){
                    $fullSame = $item->getName();
                }

                if(trim(strtok($item->getName(), '(')) === $value){
                    $beforeBracketSame = $item->getName();
                }
            }

            if($fullSame){
                return $fullSame;
            }
            elseif ($beforeBracketSame){
                return $beforeBracketSame;
            }

            return $this->handleError($headers, $line, $index, $value, ImportChecker::SPARE_PART_HEADER, self::ERROR_MULTIPLE_DATA);
        }

        return array_values($sparePart)[0]->getName();
    }

    private function getModel($headers, $line, $index, Brand $brand)
    {
        $modelIndex = array_search(ImportChecker::MODEL_HEADER, $headers);
        $value = trim($line[$modelIndex]);

        if(!$value){
            return $this->handleError($headers, $line, $index, $value, ImportChecker::MODEL_HEADER, self::ERROR_EMPTY_DATA);
        }

        $model = $this->em->getRepository(Model::class)->findModelForImport($value, $brand);

        if(!is_array($model) || !count($model)){
            return $this->handleError($headers, $line, $index, $value, ImportChecker::MODEL_HEADER, self::ERROR_EMPTY_DATA);
        }

        if(count($model) > 1){
            $year = (int)trim($line[array_search(ImportChecker::YEAR_HEADER, $headers)]);
            $difference = PHP_INT_MIN;
            $modelForYear = null;

            /** @var Model $item */
            foreach ($model as $item){
                $modelYearDiff = $item->getTechnicalData()->getYearTo() - $year;

                if($item->getTechnicalData()->getYearFrom() <= $year && $year <= $item->getTechnicalData()->getYearTo() && $difference < $modelYearDiff){
                    $difference = $modelYearDiff;
                    $modelForYear = $item;
                }
            }

            if($modelForYear){
                return $modelForYear;
            }

            //return [sprintf(self::ERROR_MULTIPLE_DATA, $index + 1, ImportChecker::MODEL_HEADER . ' | ' . count($model) . ' | ' . $brand->getId() . ' | ' . $year . ' | ' . trim($line[$modelIndex]))];
            return $this->handleError($headers, $line, $index, $value, ImportChecker::MODEL_HEADER, self::ERROR_MULTIPLE_DATA);
        }

        return array_values($model)[0];
    }

    private function getYear($headers, $line, $index, Model $model)
    {
        $yearIndex = array_search(ImportChecker::YEAR_HEADER, $headers);
        $year = (int)trim($line[$yearIndex]);

        if(!$year ||
            !($model->getTechnicalData()->getYearFrom() <= $year && $year <= $model->getTechnicalData()->getYearTo())){
            return $this->handleError($headers, $line, $index, $year, ImportChecker::YEAR_HEADER, self::ERROR_EMPTY_DATA);
        }

        return $year;
    }

    private function getEngineType($headers, $line, Model $model)
    {
        $engineTypeIndex = array_search(ImportChecker::ENGINE_TYPE_HEADER, $headers);
        $engineType = strtolower(trim($line[$engineTypeIndex]));

        if(!$engineType){
            return null;
        }

        return count($model->getTechnicalData()->getEnginesByType($engineType)) ?
            $engineType : null;
    }

    private function getEngineCapacity($headers, $line, Model $model, $engineType)
    {
        $engineCapacityIndex = array_search(ImportChecker::ENGINE_CAPACITY_HEADER, $headers);
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
        $gearBoxTypeIndex = array_search(ImportChecker::GEAR_BOX_TYPE_HEADER, $headers);
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
        $vehicleTypeIndex = array_search(ImportChecker::VEHICLE_TYPE_HEADER, $headers);
        $vehicleType = trim($line[$vehicleTypeIndex]);

        if(!$vehicleType){
            return null;
        }

        $vehicle = $model->getTechnicalData()->getVehicleType($vehicleType);

        return count($vehicle) ? $vehicle[0] : null;
    }

    private function getSparePartNumber($headers, $line)
    {
        $numberIndex = array_search(ImportChecker::NUMBER_SPARE_PART_HEADER, $headers);
        $number = trim($line[$numberIndex]);

        return $number ?: null;
    }

    private function getDescription($headers, $line)
    {
        $descriptionIndex = array_search(ImportChecker::DESCRIPTION_HEADER, $headers);
        $description = trim($line[$descriptionIndex]);

        return $description ?: null;
    }

    private function getImage($headers, $line)
    {
        $imageIndex = array_search(ImportChecker::IMAGE_HEADER, $headers);
        $image = trim($line[$imageIndex]);

        return $image ?: null;
    }

    private function getCost($headers, $line)
    {
        $costIndex = array_search(ImportChecker::COST_HEADER, $headers);
        $cost = trim($line[$costIndex]);

        return $cost ?: null;
    }

    private function getCurrency($headers, $line)
    {
        $currencyIndex = array_search(ImportChecker::CURRENCY_HEADER, $headers);

        $currency = trim($line[$currencyIndex]);

        return $currency ?: null;
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

    private function handleError($headers, $line, $index, $value, $header, $errorTemplate)
    {
        $lineData = json_encode($this->getLineDataValues($headers, $line));
        $errorDB = sprintf($errorTemplate, "file", $header);
        $requiredValues = json_encode($this->getRequiredValues($headers, $line));

        $errorImport = new ImportAdvertError($lineData, $value, $header, $errorDB);
        $errorImport->setRequiredValues($requiredValues);

        if($errorTemplate === self::ERROR_EMPTY_DATA){
            $errorImport->setCanAddKeyword(true);
        }

        $this->addErrorImport($errorImport);

        return [sprintf($errorTemplate, $index + 1, $header . ' | ' . $value)];
    }

    private function addErrorImport(ImportAdvertError $errorImport)
    {
        $requiredValues = $errorImport->getRequiredValues();

        /** @var ImportAdvertError[] $existErrorImport */
        $existErrorImport = $this->em->getRepository(ImportAdvertError::class)->findBy([
            "issue" => $errorImport->getIssue(),
            "requiredValues" => $requiredValues,
        ]);

        if(count($existErrorImport)){
            $existErrorImport[0]->increaseCount();
        }
        else {
            if(array_key_exists($requiredValues, $this->importErrors)){
                $this->importErrors[$requiredValues]->increaseCount();
            }
            else{
                $this->importErrors[$requiredValues] = $errorImport;
                $this->em->persist($errorImport);
            }
        }
    }

    private function getRequiredValues($headers, $line)
    {
        $brandIndex = array_search(ImportChecker::BRAND_HEADER, $headers);
        $modelIndex = array_search(ImportChecker::MODEL_HEADER, $headers);
        $yearIndex = array_search(ImportChecker::YEAR_HEADER, $headers);
        $sparePartIndex = array_search(ImportChecker::SPARE_PART_HEADER, $headers);

        return [
            ImportChecker::BRAND_HEADER => trim($line[$brandIndex]),
            ImportChecker::MODEL_HEADER => trim($line[$modelIndex]),
            ImportChecker::YEAR_HEADER => trim($line[$yearIndex]),
            ImportChecker::SPARE_PART_HEADER => trim($line[$sparePartIndex]),
        ];
    }

    private function getLineDataValues($headers, $line)
    {
        $data = [];

        foreach ($headers as $index => $header){
            $data[$header] = $line[$index];
        }

        return $data;
    }
}
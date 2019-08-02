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

    /** @var AutoSparePartSpecificAdvert */
    private $currentAdvert;

    /** @var bool */
    private $saveMode = true;

    /** @var array */
    private $headerIndexes = [];

    private $brands = [];
    private $models = [];
    private $spareParts = [];

    /**
     * ImportChecker constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->importErrors = [];
    }

    public function setSaveMode($saveMode)
    {
        $this->saveMode = $saveMode;
    }

    public function importFile($filePath, SellerAdvertDetail $advertDetail)
    {
        list($headers, $lines) = $this->getFileData($filePath);

        list($errors, $countImported, $countLines) = $this->importFileLines($headers, $lines, $advertDetail);

        return [
            "errors" => $errors,
            "success" => !!count($errors),
            "countImported" => $countImported,
            "countLines" => $countLines,
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
        $reader = ImportChecker::getReader($file);

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

        $this->headerIndexes = ImportChecker::getHeaderIndexes($headers);
        $advertDetail->setAutoSparePartSpecificAdverts(new ArrayCollection());

        foreach ($lines as $index => $line){
            $advert = $this->importLine($headers, $line, ++$index);

            //if($advert instanceof AutoSparePartSpecificAdvert && !$advertDetail->hasSameImportSpecificAdvert($advert)){
            if($advert instanceof AutoSparePartSpecificAdvert){
                ++$count;

                $advert->setCondition(AutoSparePartSpecificAdvert::USED_TYPE);
                $advert->setStockType(AutoSparePartSpecificAdvert::IN_STOCK_TYPE);
                $advert->setSellerAdvertDetail($advertDetail);

                if($this->saveMode) {
                    $this->em->persist($advert);

                    if ($count > 0 && $count % 50 === 0) {
                        $this->em->flush();
                    }
                }
            }
            elseif(is_array($advert)){
                $errors = array_merge($errors, $advert);
            }
        }

        if($this->saveMode) {
            $this->em->flush();
        }

        if(!$count && !count($errors)){
            return [["Нет корретных данных для импорта"], $count, count($lines)];
        }

        return [$errors, $count, count($lines)];
    }

    private function importLine(array $headers, array $line, $index)
    {
        $advert = new AutoSparePartSpecificAdvert(new SellerAdvertDetail());
        $this->currentAdvert = $advert;

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

        $engineType = $this->getEngineType($line, $model);

        $advert->setEngineType($engineType);
        $advert->setEngineCapacity($this->getEngineCapacity($line, $model, $engineType));
        $advert->setGearBoxType($this->getGearBoxType($line, $model));
        $advert->setVehicleType($this->getVehicleType($line, $model));
        $advert->setSparePartNumber($this->getSparePartNumber($line));
        $advert->setImage($this->getImage($line));
        $advert->setCost($this->getCost($line));
        $advert->setComment($this->getDescription($line));
        $advert->setCurrency($this->getCurrency($line));

        return $advert;
    }

    private function getBrand($headers, $line, $index)
    {
        $brandIndex = $this->headerIndexes[ImportChecker::BRAND_KEY];
        $brandKey = $headers[$brandIndex];

        $value = trim($line[$brandIndex]);

        if(!$value){
            return $this->handleError($headers, $line, $index, $value, $brandKey, self::ERROR_EMPTY_DATA);
        }

        if(array_key_exists($value, $this->brands)){
            return $this->brands[$value];
        }

        $brand = $this->em->getRepository(Brand::class)->findBrandForImport($value);

        if(!is_array($brand) || !count($brand)){
            return $this->handleError($headers, $line, $index, $value, $brandKey, self::ERROR_EMPTY_DATA);
        }

        if(count($brand) > 1){
            return $this->handleError($headers, $line, $index, $value, $brandKey, self::ERROR_MULTIPLE_DATA);
        }

        /** @var Brand $brandReturn */
        $brandReturn = array_values($brand)[0];

        $this->brands[$value] = $brandReturn;

        return $brandReturn;
    }

    private function getSparePart($headers, $line, $index)
    {
        $sparePartIndex = $this->headerIndexes[ImportChecker::SPARE_PART_KEY];
        $sparePartKey = $headers[$sparePartIndex];

        $value = trim($line[$sparePartIndex]);

        if(!$value){
            return $this->handleError($headers, $line, $index, $value, $sparePartKey, self::ERROR_EMPTY_DATA);
        }

        if(array_key_exists($value, $this->spareParts)){
            if(!$this->spareParts[$value]){
                return $this->handleError($headers, $line, $index, $value, $sparePartKey, self::ERROR_MULTIPLE_DATA);
            }

            return $this->spareParts[$value];
        }
        else{
            $sparePart = $this->em->getRepository(SparePart::class)->findSparePartForImport($value);
        }

        if(!is_array($sparePart ) || !count($sparePart)){
            return $this->handleError($headers, $line, $index, $value, $sparePartKey, self::ERROR_EMPTY_DATA);
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
                $this->spareParts[$value] = $fullSame;

                return $fullSame;
            }
            elseif ($beforeBracketSame){
                $this->spareParts[$value] = $beforeBracketSame;

                return $beforeBracketSame;
            }

            return $this->handleError($headers, $line, $index, $value, $sparePartKey, self::ERROR_MULTIPLE_DATA);
        }

        $sparePartReturn = array_values($sparePart)[0]->getName();

        $this->spareParts[$value] = $sparePartReturn;

        return $sparePartReturn;
    }

    private function getModel($headers, $line, $index, Brand $brand)
    {
        $modelIndex = $this->headerIndexes[ImportChecker::MODEL_KEY];
        $modelKey = $headers[$modelIndex];

        $value = trim($line[$modelIndex]);
        $valueSaveArray = $value . '_' . $brand->getId();

        if(!$value){
            return $this->handleError($headers, $line, $index, $value, $modelKey, self::ERROR_EMPTY_DATA);
        }

        if(array_key_exists($valueSaveArray, $this->models)){
            $model = $this->models[$valueSaveArray];
        }
        else {
            $this->models[$valueSaveArray] = $model = $this->em->getRepository(Model::class)->findModelForImport($value, $brand);
        }

        if(!is_array($model) || !count($model)){
            return $this->handleError($headers, $line, $index, $value, $modelKey, self::ERROR_EMPTY_DATA);
        }

        if(count($model) > 1){
            $yearIndex = $this->headerIndexes[ImportChecker::YEAR_KEY];
            $year = (int)trim($line[$yearIndex]);
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
            return $this->handleError($headers, $line, $index, $value, $modelKey, self::ERROR_MULTIPLE_DATA);
        }

        return array_values($model)[0];
    }

    private function getYear($headers, $line, $index, Model $model)
    {
        $yearIndex = $this->headerIndexes[ImportChecker::YEAR_KEY];
        $year = (int)trim($line[$yearIndex]);

        if(!$year ||
            !($model->getTechnicalData()->getYearFrom() <= $year && $year <= $model->getTechnicalData()->getYearTo())){
            return $this->handleError($headers, $line, $index, $year, $headers[$yearIndex], self::ERROR_EMPTY_DATA);
        }

        return $year;
    }

    private function getEngineType($line, Model $model)
    {
        $engineTypeIndex = $this->headerIndexes[ImportChecker::ENGINE_TYPE_KEY];

        if(!$engineTypeIndex){
            return null;
        }

        $engineType = strtolower(trim($line[$engineTypeIndex]));

        if(!$engineType){
            return null;
        }

        return count($model->getTechnicalData()->getEnginesByType($engineType)) ?
            $engineType : null;
    }

    private function getEngineCapacity($line, Model $model, $engineType)
    {
        $engineCapacityIndex = $this->headerIndexes[ImportChecker::ENGINE_CAPACITY_KEY];

        if(!$engineCapacityIndex || $engineType){
            return null;
        }

        $engineCapacity = strtolower(trim($line[$engineCapacityIndex]));

        if(!$engineCapacity){
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

    private function getGearBoxType($line, Model $model)
    {
        $gearBoxTypeIndex = $this->headerIndexes[ImportChecker::GEAR_BOX_TYPE_KEY];

        if(!$gearBoxTypeIndex){
            return null;
        }

        $gearBoxType = strtolower(trim($line[$gearBoxTypeIndex]));

        if(!$gearBoxType){
            return null;
        }

        $gearBoxType = strtolower($this->parseGearBox($gearBoxType));

        $gearBox = $model->getTechnicalData()->getGearBoxByType($gearBoxType);

        return count($gearBox) ? $gearBox[0] : null;
    }

    private function getVehicleType($line, Model $model)
    {
        $vehicleTypeIndex = $this->headerIndexes[ImportChecker::VEHICLE_TYPE_KEY];

        if(!$vehicleTypeIndex){
            return null;
        }

        $vehicleType = trim($line[$vehicleTypeIndex]);

        if(!$vehicleType){
            return null;
        }

        $vehicle = $model->getTechnicalData()->getVehicleType($vehicleType);

        return count($vehicle) ? $vehicle[0] : null;
    }

    private function getSparePartNumber($line)
    {
        $numberIndex = $this->headerIndexes[ImportChecker::NUMBER_SPARE_PART_KEY];

        if(!$numberIndex){
            return null;
        }

        $number = trim($line[$numberIndex]);

        if(($posNumber = strpos($number, ',')) && $posNumber <= 20){
            $number = substr($number, 0, $posNumber);
        }
        elseif (($posNumber = strpos($number, ';')) && $posNumber <= 20){
            $number = substr($number, 0, $posNumber);
        }
        else{
            $number = substr($number, 0, 20);
        }

        return $number ?: null;
    }

    private function getDescription($line)
    {
        $descriptionIndex = $this->headerIndexes[ImportChecker::DESCRIPTION_KEY];

        if(!$descriptionIndex){
            return null;
        }

        $description = trim($line[$descriptionIndex]);

        return $description ?: null;
    }

    private function getImage($line)
    {
        $imageIndex = $this->headerIndexes[ImportChecker::IMAGE_KEY];

        if(!$imageIndex){
            return null;
        }

        $images = explode(',', trim($line[$imageIndex]));

        return count($images) ? $images[0] : null;
    }

    private function getCost($line)
    {
        $costIndex = $this->headerIndexes[ImportChecker::COST_KEY];

        if(!$costIndex){
            return null;
        }

        $cost = trim($line[$costIndex]);

        return $cost ?: null;
    }

    private function getCurrency($line)
    {
        $currencyIndex = $this->headerIndexes[ImportChecker::CURRENCY_KEY];

        if(!$currencyIndex){
            return null;
        }


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
                $this->addParsedData($errorImport);

                $this->importErrors[$requiredValues] = $errorImport;

                if($this->saveMode) {
                    $this->em->persist($errorImport);
                }
            }
        }
    }

    private function addParsedData(ImportAdvertError $error)
    {
        $data = [
            ImportChecker::BRAND_KEY => $this->currentAdvert->getBrand() ? $this->currentAdvert->getBrand()->getId() : "",
            ImportChecker::MODEL_KEY => $this->currentAdvert->getModel() ? $this->currentAdvert->getModel()->getId() : "",
            ImportChecker::SPARE_PART_KEY => $this->currentAdvert->getSparePart() ?: "",
            ImportChecker::YEAR_KEY => $this->currentAdvert->getYear() ?: "",
        ];

        $error->setParsedValues(json_encode($data));
    }

    private function getRequiredValues($headers, $line)
    {
        $brandIndex = $this->headerIndexes[ImportChecker::BRAND_KEY];
        $modelIndex = $this->headerIndexes[ImportChecker::MODEL_KEY];
        $yearIndex = $this->headerIndexes[ImportChecker::YEAR_KEY];
        $sparePartIndex = $this->headerIndexes[ImportChecker::SPARE_PART_KEY];

        return [
            $headers[$brandIndex] => trim($line[$brandIndex]),
            $headers[$modelIndex] => trim($line[$modelIndex]),
            $headers[$yearIndex] => trim($line[$yearIndex]),
            $headers[$sparePartIndex] => trim($line[$sparePartIndex]),
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
<?php

namespace App\Provider\CheckFine;


use App\Entity\UserData\PotentialUserCheckFine;

class CheckFineTrafficPoliceProvider
{
    const URL = "http://mvd.gov.by/Ajax.asmx/GetExt";
    const GUID_CONTROL = 2091;

    public function provideFineData(PotentialUserCheckFine $checkFineUser)
    {
        $fullName = $checkFineUser->getLastName() . ' ' . $checkFineUser->getFirstName() . ' ' .
            $checkFineUser->getPatronymic();

        $params = [
            "GuidControl" => self::GUID_CONTROL,
            "Param1" => $fullName,
            "Param2" => $checkFineUser->getSeries(),
            "Param3" => $checkFineUser->getNumber(),
        ];

        $response = $this->request($params);

        return $this->isValidJSON($response) ? $response : json_encode($response);
    }

    protected function request($params)
    {
        $data_string = json_encode($params);

        $ch = curl_init(self::URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    protected function isValidJSON($possibleJson) {
        json_decode($possibleJson);

        return (json_last_error()===JSON_ERROR_NONE);
    }
}
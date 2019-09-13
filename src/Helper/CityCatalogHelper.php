<?php

namespace App\Helper;

use App\Entity\Client\Client;
use App\Entity\Request\CityCatalogRequest;
use App\Entity\Request\SparePartRequest;
use App\Entity\SparePart;
use App\Generator\PasswordGenerator;
use App\Sender\RegistrationSender;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CityCatalogHelper
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var RegistrationSender $sender */
    private $sender;

    /** @var PasswordGenerator $passwordGenerator */
    private $passwordGenerator;

    /**
     * CityCatalogHelper constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param RegistrationSender $sender
     * @param PasswordGenerator $passwordGenerator
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, RegistrationSender $sender, PasswordGenerator $passwordGenerator)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->sender = $sender;
        $this->passwordGenerator = $passwordGenerator;
    }


    public function addSparePartRequests(CityCatalogRequest $cityCatalogRequest, $sparePartRequestForms, $sparePartDefault)
    {
        $cityCatalogRequest->setSparePartRequests(new ArrayCollection());

        /** @var FormInterface $sparePartRequestForm */
        foreach ($sparePartRequestForms as $sparePartRequestForm){
            $sparePart = $this->em->getRepository(SparePart::class)->findOneBy(["name" => $sparePartRequestForm->get("sparePartText")->getData()]);

            if(!($sparePart instanceof SparePart)){
                continue;
            }

            $sparePartRequest = new SparePartRequest();
            $sparePartRequest->setSparePart($sparePart);
            $sparePartRequest->setComment($sparePartRequestForm->get("comment")->getData());
            $sparePartRequest->setSparePartNumber($sparePartRequestForm->get("sparePartNumber")->getData());
            $sparePartRequest->setCatalogRequest($cityCatalogRequest);

            $cityCatalogRequest->addSparePartRequest($sparePartRequest);
        }

        $sparePartRequest = new SparePartRequest();
        $sparePartRequest->setSparePart($sparePartDefault);
        $sparePartRequest->setCatalogRequest($cityCatalogRequest);

        $cityCatalogRequest->addSparePartRequest($sparePartRequest);
    }

    public function getExistUser(CityCatalogRequest $catalogRequest)
    {

        $userRep = $this->em->getRepository(Client::class);

        $emailUser = $userRep->findOneBy(["email" => $catalogRequest->getEmail()]);

        if($emailUser){
            return [
                "from" => $catalogRequest->getEmail(),
                "user" => $emailUser,
            ];
        }

        $phoneBYUser = $userRep->findOneBy(["phone" => $catalogRequest->getPhoneBY()]);

        if($phoneBYUser){
            return [
                "from" => $catalogRequest->getPhoneBY(),
                "user" => $phoneBYUser,
            ];
        }

        $phoneRUUser = $userRep->findOneBy(["phone" => $catalogRequest->getPhoneRU()]);

        if($phoneRUUser){
            return [
                "from" => $catalogRequest->getPhoneRU(),
                "user" => $phoneRUUser,
            ];
        }

        return null;
    }

    public function getNewUser(CityCatalogRequest $catalogRequest)
    {
        $phoneBY = $catalogRequest->getPhoneBY();
        $phoneRU = $catalogRequest->getPhoneRU();
        $email = $catalogRequest->getEmail();

        $client = new Client();
        $client->setEmail($email);
        $client->setEmailCanonical($email);
        $client->setUsername($email);
        $client->setUsernameCanonical($email);
        $client->setPhone($phoneBY ?: $phoneRU);
        $client->setName($email);

        $plainPassword = $this->passwordGenerator->generatePassword();
        $password = $this->encoder->encodePassword($client, $plainPassword);
        $client->setPassword($password);
        $client->setEnabled(false);

        $client->setActivateCode($this->passwordGenerator->generateNumberWordCode(8));

        $this->em->persist($client);

        $this->sender->sendActivationDataForCityCatalog($client, $plainPassword, !!$phoneBY);

        return [
            "from" => $email,
            "user" => $client,
        ];
    }
}
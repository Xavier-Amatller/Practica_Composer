<?php

namespace App\Model;

class Reparation
{
    private $idWorkshop;

    private $nameWorkshop;

    private $registerDate;

    private $license;

    private $uuid;

    public function __construct($idWorkshop, $nameWorkshop, $registerDate, $license, $uuid)
    {
        $this->idWorkshop = $idWorkshop;
        $this->nameWorkshop = $nameWorkshop;
        $this->registerDate = $registerDate;
        $this->license = $license;
        $this->uuid = $uuid;
    }

    // Getter and Setter for idWorkshop
    public function getIdWorkshop()
    {
        return $this->idWorkshop;
    }

    public function setIdWorkshop($idWorkshop)
    {
        $this->idWorkshop = $idWorkshop;
    }

    // Getter and Setter for nameWorkshop
    public function getNameWorkshop()
    {
        return $this->nameWorkshop;
    }

    public function setNameWorkshop($nameWorkshop)
    {
        $this->nameWorkshop = $nameWorkshop;
    }

    // Getter and Setter for registerDate
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;
    }

    // Getter and Setter for license
    public function getLicense()
    {
        return $this->license;
    }

    public function setLicense($license)
    {
        $this->license = $license;
    }

    // Getter and Setter for uuid
    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }
}

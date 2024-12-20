<?php

namespace Src\Model;
require_once  '../../vendor/autoload.php';

class Reparation
{
    private $idWorkshop;

    private $nameWorkshop;

    private $registerDate;

    private $license;

    private $uuid;

    private $image;

    public function __construct($idWorkshop, $nameWorkshop, $registerDate, $license, $uuid, $image)
    {
        $this->idWorkshop = $idWorkshop;
        $this->nameWorkshop = $nameWorkshop;
        $this->registerDate = $registerDate;
        $this->license = $license;
        $this->uuid = $uuid;
        $this->image = $image;

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

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image): void
    {
        $this->image = $image;
    }
}

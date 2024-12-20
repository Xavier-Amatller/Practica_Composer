<?php
 namespace Src\Controller;
 require_once  '../../vendor/autoload.php';

 use Src\Service\ServiceReparation;

final class ControllerReparation
{
    function getReparation($uuid)
    {
        $reparationService = new ServiceReparation();
        $reparationService->conexion();
        return $reparationService->getReparation($uuid);
    }

    function setReparation($idWorkshop, $workshopName, $license, $image) {
        $reparationService = new ServiceReparation();
        $reparationService->conexion();
        return $reparationService->insertReparation($idWorkshop, $workshopName, $license, $image);
    }
}


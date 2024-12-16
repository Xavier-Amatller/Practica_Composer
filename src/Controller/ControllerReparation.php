<?php
// namespace App\Controller;
// require_once __DIR__ . '/../../vendor/autoload.php';

require_once "../Service/ServiceReparation.php";
final class ControllerReparation
{
    function getReparation($uuid)
    {
        $reparationService = new ServiceReparation();
        $reparationService->conexion();
        return $reparationService->getReparation($uuid);
    }

    function setReparation($idWorkshop, $workshopName, $license) {
        $reparationService = new ServiceReparation();
        $reparationService->conexion();
        return $reparationService->setReparation($idWorkshop, $workshopName, $license);
    }
}


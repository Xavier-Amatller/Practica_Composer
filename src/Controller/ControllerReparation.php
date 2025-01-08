<?php

namespace Src\Controller;

require_once  '../../vendor/autoload.php';

use Src\Service\ServiceReparation;

final class ControllerReparation
{
    public function getReparation($uuid)
    {
        $reparationService = new ServiceReparation();
        return $reparationService->getReparation($uuid);
    }

    public function setReparation($idWorkshop, $workshopName, $license, $image)
    {
        $reparationService = new ServiceReparation();
        return $reparationService->insertReparation($idWorkshop, $workshopName, $license, $image);
    }
}

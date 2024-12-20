<?php

namespace Src\Service;

require '../../vendor/autoload.php';
// require_once "../Model/Reparation.php";
use Src\Model\Reparation;
use Ramsey\Uuid\Uuid;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PDO;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

final class ServiceReparation
{
    private $log;

    public function __construct()
    {
        $this->log = $this->monolog();
    }
    function conexion()
    {
        $d = parse_ini_file('../../cfg/db_config.ini');

        $dsn = 'mysql:host=' . $d["host"] . ';dbname=' . $d["db_name"] . ';charset=utf8mb4';
        $username = $d["user"];
        $password = $d["pwd"];

        try {
            $conn = new PDO($dsn, $username, $password);
            // Configuración opcional: manejo de errores
            $conn->setAttribute(PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
            $this->log->info("Conexion successfull");
            return $conn;
        } catch (\PDOException $e) {
            $this->log->error("Conexion dennied. Erorr: " . $e->getMessage());
            die("Error de conexión: " . $e->getMessage());
        }
    }

    function getReparation($uuid): Reparation | null
    {
        $conn = $this->conexion();

        $query = "select * from Reparation where uuid = :uuid";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->execute();

        $reparation =  $stmt->fetch(PDO::FETCH_ASSOC);
        if ($reparation) {
            $this->log->info("Get reparation successfull");
            // return new Reparation(
            //     $reparation["id_workshop"],
            //     $reparation["name_workshop"],
            //     $reparation["register_date"],
            //     $reparation["license"],
            //     $reparation["uuid"],
            //     $reparation["image"]
            // );
            return processImageFromReparation(new Reparation(
                $reparation["id_workshop"],
                $reparation["name_workshop"],
                $reparation["register_date"],
                $reparation["license"],
                $reparation["uuid"],
                $reparation["image"]
            ));
        } else {
            $this->log->info("Reparation not found.");
            return null;
        }
    }
    function insertReparation($idWorkshop, $workshopName, $license, $image): bool
    {
        $date = date("Y-m-d");
        $uuid = $this->generarUUID();

        $conn = $this->conexion();

        $query = "insert into reparation(id_workshop,name_workshop,register_date,license,uuid,image)values
                    (:idWorkshop, :workshopName, :registerDate, :license , :uuid, :image)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':idWorkshop', $idWorkshop, PDO::PARAM_STR);
        $stmt->bindParam(':workshopName', $workshopName, PDO::PARAM_STR);
        $stmt->bindParam(':registerDate', $date, PDO::PARAM_STR);
        $stmt->bindParam(':license', $license, PDO::PARAM_STR);
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_LOB);


        $this->log->info("Reparation inserted correctly.");

        return $stmt->execute();
    }

    function generarUUID()
    {
        $uuid = Uuid::uuid4();

        return $uuid->toString();
    }

    function monolog()
    {
        // create log
        $log = new Logger("LogReparations");
        // define logs location
        $log->pushHandler(new StreamHandler("../Logs/Reparations.log", Level::Info));
        return $log;
    }
}
function processImageFromReparation(Reparation $reparation)
{
    $manager = ImageManager::gd();

    $image = $manager->read($reparation->getImage());

    $image->text($reparation->getUuid(), 10, 10, function ($font) {
        $font->size(20);
        $font->color('#ffffff');
        $font->align('left');
        $font->valign('top');
    });

    $zonaPixelar = $image->crop(100, 100, 50, 50)
        ->resize(10, 10)
        ->resize(100, 100);
    $image->place($zonaPixelar, 'top-left', 50, 50);


        // Convierte la imagen a base64
        $encoded = $image->encode(); // Intervention\Image\EncodedImage

        // Establece la imagen codificada en base64 en el objeto Reparation
        $reparation->setImage($encoded);
    return $reparation;
}

<?php

namespace Src\Service;

require '../../vendor/autoload.php';

use Exception;
use Src\Model\Reparation;
use Ramsey\Uuid\Uuid;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PDO;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;

final class ServiceReparation
{
    private $log;

    public function __construct()
    {
        $this->log = $this->monolog();
    }
    private function conexion()
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

    public function getReparation($uuid): Reparation | null
    {
        $conn = $this->conexion();

        $query = "select * from Reparation where uuid = :uuid";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
        $stmt->execute();

        $reparation =  $stmt->fetch(PDO::FETCH_ASSOC);
        if ($reparation) {
            $this->log->info("Get reparation successfull");
            $reparation = new Reparation(
                $reparation["id_workshop"],
                $reparation["name_workshop"],
                $reparation["register_date"],
                $reparation["license"],
                $reparation["uuid"],
                $reparation["image"]
            );
            return ($_SESSION["rol"] == "client")
                ? $this->pixelateImage($reparation)
                : $reparation;
        } else {
            $this->log->info("Reparation not found.");
            return null;
        }
    }
    public function insertReparation($idWorkshop, $workshopName, $license, $image)
    {
        $date = date("Y-m-d");
        $uuid = $this->generarUUID();
        $image = $this->addWatermark($uuid, $image);
        $conn = $this->conexion();
        try {
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

            $stmt->execute();
            return $uuid;
        } catch (Exception $e) {
            $this->log->warning(" Couldn't insert. warning: " . $e->getMessage());
            throw new Exception("No se ha podido insertar. Exception:" . $e->getMessage());
        }
    }

    private function generarUUID()
    {
        $uuid = Uuid::uuid4();

        return $uuid->toString();
    }

    private function monolog()
    {
        // create log
        $log = new Logger("LogReparations");
        // define logs location
        $log->pushHandler(new StreamHandler("../Logs/Reparations.log", Level::Info));
        return $log;
    }
    private function pixelateImage(Reparation $reparation): Reparation
    {
        $manager = ImageManager::gd();

        $image = $manager->read($reparation->getImage());

        $image = $image->pixelate(100);

        // Convierte la imagen a base64
        $encoded = $image->encode();

        // Establece la imagen codificada en base64 en el objeto Reparation
        $reparation->setImage($encoded);

        return $reparation;
    }

    private function addWatermark($text, $image)
    {
        $manager = ImageManager::gd();

        $image = $manager->read($image);

        $image->resize(300, 300);

        $image->text($text, 120, 100, function (FontFactory $font) {
            $font->size(20);
            $font->color('black');
            $font->align('middle');
            $font->valign('top');
            $font->lineHeight(1.6);
            $font->angle(10);
        });

        return  $image->encode();
    }
}

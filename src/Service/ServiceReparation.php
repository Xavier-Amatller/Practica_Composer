<?php

require __DIR__ . '/../../vendor/autoload.php';
// require_once "../Model/Reparation.php";
use App\Model\Reparation;
use Ramsey\Uuid\Uuid;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

final class ServiceReparation
{
    function conexion(): PDO
    {

        $d = parse_ini_file('../../cfg/db_config.ini');

        $dsn = 'mysql:host=' . $d["host"] . ';dbname=' . $d["db_name"] . ';charset=utf8mb4';
        $username = $d["user"];
        $password = $d["pwd"];

        try {
            $conn = new PDO($dsn, $username, $password);
            // Configuración opcional: manejo de errores
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->monolog()->info("Conexion successfull");
            return $conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    function getReparation($uuid): Reparation | string
    {
        $conn = $this->conexion();

        $query = "select * from Reparation where uuid = :uuid";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_INT);
        $stmt->execute();

        $reparation =  $stmt->fetch(PDO::FETCH_ASSOC);
        if ($reparation) {
            return new Reparation(
                $reparation["id_workshop"],
                $reparation["name_workshop"],
                $reparation["register_date"],
                $reparation["license"],
                $reparation["uuid"],
            );
        } else {
            return new Reparation(0, 0, 0, 0, 0);
        }
    }
    function setReparation($idWorkshop, $workshopName, $license): bool
    {
        $date = date("Y-m-d");
        $uuid = $this->generarUUID();

        $conn = $this->conexion();

        $query = "insert into reparation(id_workshop,name_workshop,register_date,license,uuid)values
                    (:idWorkshop, :workshopName, :registerDate, :license , :uuid)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':idWorkshop', $idWorkshop, PDO::PARAM_STR);
        $stmt->bindParam(':workshopName', $workshopName, PDO::PARAM_STR);
        $stmt->bindParam(':registerDate', $date, PDO::PARAM_STR);
        $stmt->bindParam(':license', $license, PDO::PARAM_STR);
        $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);

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

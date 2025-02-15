<?php

namespace Src\View;

require_once  '../../vendor/autoload.php';

use Src\Controller\ControllerReparation;

session_start();

if (isset($_GET["rol"])) {
    $_SESSION["rol"] = $_GET["rol"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViewReparation</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Search your Reparation</h1>
    <form action="" method="get">
        uuid:<input type="text" name="uuid" required>
        <input type="submit">
    </form>

    <br>

    <div id="search-reparation">
        <table>
            <thead>
                <tr>
                    <th>id_workshop</th>
                    <th>name_workshop</th>
                    <th>register_date</th>
                    <th>license</th>
                    <th>uuid</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    if (isset($_GET["uuid"])) {
                        $rc = new ControllerReparation();
                        $reparation = $rc->getReparation($_GET["uuid"]);

                        if ($reparation == null) {
                            echo "<h1>No se ha encontrado la reparacion</h1>";
                        } else {

                            $idWorkshop = $reparation->getIdWorkshop();
                            $workshopName = $reparation->getNameWorkshop();
                            $registerDate = $reparation->getRegisterDate();
                            $license = $reparation->getLicense();
                            $uuid = $reparation->getUuid();
                            $image = $reparation->getImage();
                            
                            echo "<td>$idWorkshop</td>";
                            echo "<td>$workshopName</td>";
                            echo "<td>$registerDate</td>";
                            echo "<td>$license</td>";
                            echo "<td>$uuid</td>";
                            echo '<td><img src="data:image/png;base64,' . base64_encode($image) . '" alt="Image" /></td>';
                        }
                    }
                    ?>

                </tr>
            </tbody>

        </table>
    </div>
    <div id="add-reparation">
        <?php
        if ($_SESSION["rol"] == "employee") {
            echo '<h1>ADD a new Reparation to the Database </h1>';
            echo '
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="text" name="idWorkshop" placeholder="idWorkshop" pattern="^\d{4}$" required>
                    <input type="text" name="workshopName" placeholder="workshopName" pattern="^[a-zA-Z\s]{1,12}$" required>
                    <input type="text" name="license" placeholder="license" pattern="^\d{4}-[A-Z]{3}$" required>
                    <input type="file" name="image" accept="image/*" required>
                    <input type="submit" name="submit" id="submit" value="Submit" required>
                </form>
            ';
            if (isset($_POST["idWorkshop"])) {
                try {
                    $rc = new ControllerReparation();
                    $imageName = $_FILES['image']['name'];
                    $imageData = file_get_contents($_FILES['image']['tmp_name']);
                    $reparationUuid = $rc->setReparation($_POST["idWorkshop"], $_POST["workshopName"], $_POST["license"], $imageData);

                    echo "<h1>Reparacion insertada correctamente</h1>";
                    echo "<h2 >$reparationUuid</h2>";

                } catch (\Exception $e) {
                    echo "<h1>No se ha podido insertar la reparacion</h1>";
                    echo $e->getMessage();
                }
            }
        }
        ?>
    </div>


</body>

</html>
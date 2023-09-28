<?php
require_once "_includes/db_connect.php";

$results = [];
$insertedRows = 0;

function updateData($link)
{
    $query = "UPDATE kfood SET menu = ? WHERE kfoodID = ?";

    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, 'si', $_REQUEST["menu"], $_REQUEST["kfoodID"]);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) === -1) {
            throw new Exception("Error updating data: " . mysqli_stmt_error($stmt));
        }
        $results[] = [
            "updatedData() affected_rows man" => mysqli_stmt_affected_rows($stmt)
        ];
        return mysqli_stmt_affected_rows($stmt);
    }
}

//Main logic of the app is in this try-catch block.

try {
    if (!isset($_REQUEST["kfoodID"]) || !isset($_REQUEST["menu"])) {
        throw new Exception('Required data is missing i.e. kfoodID, menu');
    } else {
        $results[] = ["updateData() affected_rows" => updateData($link)];
    }
} catch (Exception $error) {
    $results[] = ["error" => $error->getMessage()];
} finally {
    echo json_encode($results);
}

<?php

require_once "_includes/db_connect.php";

$results = [];
$insertedRows = 0;

function selectUser($link)
{
    $query = "SELECT * FROM kfood WHERE name = ?";

    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "s", $_REQUEST["name"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $results[] = ["from selectUser() function" => $result];
        return mysqli_num_rows($result) > 0;
    } else {
        throw new Exception("No user was found.");
    }
}

function updateData($link)
{
    $query = "UPDATE kfood SET mealtime = ?, menu = ? WHERE name = ?";

    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "ss", $_REQUEST["menu"], $_REQUEST["name"]);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) <= 0) {
            throw new Exception("Error updating data: " . mysqli_stmt_error($stmt));
        }
        $results[] = ["updateData() affected_rows man" => mysqli_stmt_affected_rows($stmt)];
        return mysqli_stmt_affected_rows($stmt);
    }
}

function insertData($link)
{
    $query = "INSERT INTO kfood (name, mealtime, menu) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, 'sss', $_REQUEST["full_name"], $_REQUEST["mealtime"], $_REQUEST["menu"]);
        mysqli_stmt_execute($stmt);
        $insertedRows = mysqli_stmt_affected_rows($stmt);

        if ($insertedRows > 0) {
            $results[] = [
                "insertedRows" => $insertedRows,
                "id" => $link->insert_id,
                "full_name" => $_REQUEST["full_name"],
                "menu" => $_REQUEST["menu"]
            ];
        } else {
            throw new Exception("No rows were inserted!");
        }
    }
}

try {
    if (!isset($_REQUEST["full_name"]) || !isset($_REQUEST["mealtime"]) || !isset($_REQUEST["menu"])) {
        throw new Exception('Required data is missing i.e. full_name, mealtime, menu');
    } else {
        if (selectUser($link)) {
            $results[] = ["selectUser()" => "called updateData()"];
            $results[] = ["updateData() affected_rows" => updateData($link)];
        } else {
            $results[] = ["insertData()" => "called insertData()"];
            $results[] = ["insertData() affected_rows" => insertData($link)];
        }
    }
} catch (Exception $error) {
    $results[] = ["error" => $error->getMessage()];
} finally {
    echo json_encode($results);
}

<?php

//connect to db - $link is the connection object
require_once "_includes/db_connect.php";


//prepare the statement passing the db $link and the SQL query
$stmt = mysqli_prepare($link, "SELECT kfoodID, name, mealtime, menu, timestamp FROM kfood ORDER BY timestamp DESC");


//execute the statement / query from the db
mysqli_stmt_execute($stmt);

//get result from the statement / query
$result = mysqli_stmt_get_result($stmt);

//loop through the result
while ($row = mysqli_fetch_assoc($result)) {
    $results[] = $row;
}


//encode the results in json format
echo json_encode($results);

//close the connection to the db
mysqli_close($link);

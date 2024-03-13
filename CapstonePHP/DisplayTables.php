<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $serverName = "csc.henderson.edu";
    $connectionInfo = array("Database"=>"CSC4483_Spring2024", "UID"=>"kegley", "PWD"=>"kegleyHSU20");
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    if ($conn === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch and display data for selected tables
    $selectedTables = $_POST['tables'];
    foreach ($selectedTables as $tableName) {
        $sql = "SELECT * FROM $tableName";
        $stmt = sqlsrv_query($conn, $sql);
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        echo "<h3>$tableName</h3>";
        echo "<table class='table table-bordered' data-table='$tableName'>";
        echo "<thead><tr>";
        for ($i = 0; $i < sqlsrv_num_fields($stmt); $i++) {
            $field = sqlsrv_field_metadata($stmt)[$i]['Name'];
            echo "<th>$field</th>";
        }
        echo "</tr></thead>";
        echo "<tbody>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td contenteditable='true' class='editable' data-	column='$key'>$value</td>";
            }
            // Assuming the first column is the primary key
            echo "<td class='primaryKey'>" . reset($row) . "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";

        // Clean up
        sqlsrv_free_stmt($stmt);
    }

    // Close connection
    sqlsrv_close($conn);
}
?>
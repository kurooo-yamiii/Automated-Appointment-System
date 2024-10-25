<?php
session_start();
include "db_conn.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $limit = validate($_POST['limit']);

    $sqlsearch = "SELECT * FROM user ORDER BY ID DESC LIMIT $limit";
    $resultsearch = mysqli_query($conn, $sqlsearch);
    
    // Display the record in Table
    if (mysqli_num_rows($resultsearch)) {
        echo '<table class="table table-striped" id="startupproftable">';
        echo '  <thead>';
        echo '    <tr>';
        echo '      <th scope="col">Name</th>';
        echo '      <th scope="col">Username</th>';
        echo '      <th scope="col">Password</th>';
        echo '      <th scope="col">Action</th>';
        echo '    </tr>';
        echo '  </thead>';
        echo '  <tbody>';
    
        $i = 0;
        while($rows = mysqli_fetch_assoc($resultsearch)) {
            $i++;
            echo '    <tr>';
            echo '      <td>' . htmlspecialchars($rows['Name']) . '</td>';
            echo '      <td>' . htmlspecialchars($rows['Username']) . '</td>';
            echo '      <td>' . htmlspecialchars($rows['Password']) . '</td>';
            echo '      <td><button type="button" onclick="DeleteUserShow(' . (int)$rows['ID'] . ');" class="red-button">Delete</button>';
            echo '        <button type="button" onclick="UpdateUserShow(' . (int)$rows['ID'] . ', \'' . htmlspecialchars($rows['Name'], ENT_QUOTES) . '\', \'' . htmlspecialchars($rows['Username'], ENT_QUOTES) . '\', \'' . htmlspecialchars($rows['Password'], ENT_QUOTES) . '\');" class="blue-button">Update</button>';
            echo '      </td>';
            echo '    </tr>';
        }
    
        echo '  </tbody>';
        echo '</table>';
    } else {
        echo '    <tr>';
        echo '      <td colspan="4">No records found</td>';
        echo '    </tr>';
    }

}
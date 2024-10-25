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

                    $sqlsearch = "SELECT * FROM overall_appointments ORDER BY ID DESC LIMIT $limit";
                    $resultsearch = mysqli_query($conn, $sqlsearch);

                    // Start outputting the HTML content
                    echo '<table class="table table-striped" id="startupproftable">';
                    echo '  <thead>';
                    echo '    <tr>';
                    echo '      <th scope="col">Name</th>';
                    echo '      <th scope="col">Date</th>';
                    echo '      <th scope="col">Time</th>';
                    echo '      <th scope="col">Office</th>';
                    echo '      <th scope="col">Branch</th>';
                    echo '      <th scope="col">Action</th>';
                    echo '    </tr>';
                    echo '  </thead>';
                    echo '  <tbody>';

                    if (mysqli_num_rows($resultsearch) > 0) {
                        while ($rows = mysqli_fetch_assoc($resultsearch)) {
                            echo '    <tr>';
                            echo '      <td>' . htmlspecialchars($rows['Name']) . '</td>';
                            echo '      <td>' . htmlspecialchars($rows['Date']) . '</td>';
                            echo '      <td>' . htmlspecialchars($rows['Time']) . '</td>';
                            echo '      <td>' . htmlspecialchars($rows['Office']) . '</td>';
                            echo '      <td>' . htmlspecialchars($rows['Branch']) . '</td>';
                            echo '      <td>';
                            echo '    <button type="button" onclick="deleteAppoint(' . (int)$rows['ID'] . ', \'' . addslashes($rows['Email']) . '\');" class="red-button">Delete</button>';
                            echo '<button type="button" onclick="updateAppoint(' . (int)$rows['ID'] . ',\'' . htmlspecialchars($rows['Email'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Name'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Date'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Time'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Office'], ENT_QUOTES) . '\',\'' . htmlspecialchars($rows['Branch'], ENT_QUOTES) . '\');" class="blue-button">Update</button>';

                            echo '      </td>';
                            echo '    </tr>';
                        }
                    } else {
                        echo '    <tr>';
                        echo '      <td colspan="6">No records found</td>';
                        echo '    </tr>';
                    }

                    echo '  </tbody>';
                    echo '</table>';



}
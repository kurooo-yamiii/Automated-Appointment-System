<?php
session_start();
include "db_conn.php";
include 'db_pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $id = validate($_POST['id']);

    $stmt = $conn->prepare("SELECT Date FROM announcement WHERE ID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dateRow = $result->fetch_assoc();
    $date = $dateRow['Date'];

    $formudate = DateTime::createFromFormat('F j, Y', $date);
    $formattedDate = $formudate->format('Y-m-d');

    $delRestrict = $conn->prepare("DELETE FROM restriction WHERE Date = ?");
            $delRestrict->bind_param("s", $formattedDate);
            $delRestrictexe = $delRestrict->execute();

            $delAnnounce = $conn->prepare("DELETE FROM announcement WHERE ID = ?");
                $delAnnounce->bind_param("s", $id);
                $delAnnounceexe = $delAnnounce->execute();

                if ($delAnnounceexe && $delRestrictexe) {
                    echo '<p class="success">Announcement Succesfully Deleted</p>';
    
                    $announcement = $ponn->query("SELECT * FROM announcement ORDER BY ID DESC");
    
                    if ($announcement->rowCount() <= 0) {
                        echo '<div class="todo-item">';
                        echo '    <a href="" id="remove-to-do" class="removee-to-do">N/A</a> <br>';
                        echo '    <div class="row-announce">';
                        echo '        <div class="empty">';
                        echo '            <img src="resources/default.jpeg">';
                        echo '        </div>';
                        echo '        <div class="post-ann">';
                        echo '            <h2>There is no announcement currently</h2>';
                        echo '            <small>Note: If you want to add announcement click the button "create announcement" in the upper right corner</small>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    } else {
                        while ($fetchannounce = $announcement->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="todo-item">';
                            echo '    <button onclick="DeleteAnnouncement(' . $fetchannounce['ID'] . ')" id="remove-to-do" class="removee-to-do">' . $fetchannounce['Date'] . '</button> <br>';
                            echo '    <div class="row-announce">';
                            echo '        <div class="empty">';
                            echo '            <img src="resources/cedlogo.png">';
                            echo '        </div>';
                            echo '        <div class="post-ann">';
                            echo '            <h2 style="font-weight: 700; font-size: 18px;">' . $fetchannounce['Title'] . '</h2>';
                            echo '            <h2>' . $fetchannounce['Description'] . '</h2>';
                            echo '            <small>- ' . $fetchannounce['Author'] . '</small>';
                            echo '        </div>';
                            echo '    </div>';
                            echo '</div>';
                        }
                    }
                }
}
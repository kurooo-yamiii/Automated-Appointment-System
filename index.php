<?php
include 'db_conn.php';
include 'db_pdo.php';

?>
<!DOCTYPE html>
<html>
<head>
	<title>TechFlow - Appointment System</title>
     <meta charset="UTF-8">
	<meta http-eequiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0"> 
	<link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="stylemedia.css">
	<link rel="icon" href="resources/TF.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

</head>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/2.3.3/purify.min.js"></script>
     <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="admin.js"> </script>
</head>
<body>
    <div class="horizontal">
    <div class="right-space">
    <table style="margin-bottom: 1.5%;">
                        <thead>
                            <tr><th class="table-header" scope="col">ANNOUNCEMENT</th></tr>
                        </thead>
                    </table>
                    <?php $announcement = $ponn->query("SELECT * FROM announcement ORDER BY ID DESC LIMIT 3"); ?>
                    <?php while($fetchannounce = $announcement->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
				    <button onclick="DeleteAnnouncement(<?php echo $fetchannounce['ID']; ?>)" 
			      	   id="remove-to-do" class="removee-to-do"><?php echo $fetchannounce['Date']; ?></button> <br>
                        <div class="row-announce">
                            <div class="empty">
                             <img src="resources/logo.png">
                            </div>
                            <div class="post-ann">
                            <h2 style="font-weight: 700; font-size: 18px; margin-bottom: -2%;"><?php echo $fetchannounce['Title']; ?></h2>
                        <h2>
                        <?php echo $fetchannounce['Description']; ?>
                    </h2>
              
            <small>- <?php echo $fetchannounce['Author']; ?></small> 
            </div>
            </div>
            </div>
            <?php } ?>
    </div>
           
    
    <div class="login-form">
    <form action="register.php" method="post">
  
        <div id="login-h2"><img src="resources/TF.png"></div>
        
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } elseif (isset($_GET['success'])) { ?>
            <p class="success"><?php echo $_GET['success']; ?></p>
        <?php } ?>

        <div class="form-groupy">
            <label>Make an appointment</label>
            <button type="button" onclick="disStudentpop()" class="app-button">As a Student</button>
            <button type="button" onclick="disStudentpop2()" class="app-button">As a Employee</button>
            <button type="button" onclick="disGuestpop()" class="app-button">As a Guest</button>
        </div>
        <div class="space"></div>

        <div class="form-groupy">
            <label>Existing appointment</label>
            <div class="existrow">
                <div style="flex: 8;">
                <input type="text" name="email-exist" id="email-exist" placeholder="Enter your Email" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|rtu\.edu\.ph)$" required>
                </div>
                <div style="flex: 2;">
                <button type="button" id="exist-button" onclick="ViewAppointment()" class="app-button2">View</button>
              
                </div>
            </div>
           
        </div>
</div>
        </div>
    </form>
    <!-- STUDENT APPOINTMENT FORM -->
    <div id="popup">
        <form method="post" action="sttudapp.php" id="popupstud">
                    <table>
                        <thead>
                            <tr><th class="table-header" scope="col">STUDENT APPOINTMENT</th></tr>
                        </thead>
                    </table>
            <div id="logdelConstruct" class="rowleftright">
                <div class="form-group">
                    <div class="remove-space"></div>
                    <div class="popform-group" style="margin-bottom: 2%; margin-top: 1%;">
                        <select id="branch-student" name="branch-student" placeholder="RTU Branch" onchange="updateOffices()" required>
                             <option value="">RTU Branch</option>
                            <option value="Pasig Branch" selected>Pasig Branch</option>
                            <option value="Mandaluyong Branch">Mandaluyong Branch</option>
                        </select>
                    </div>
                   
                    <div class="popform-group" style="margin-bottom: -2%; margin-top: 1%;">
                    <select id="office-student" name="office-student" placeholder="RTU Branch" required>
                        
                        </select>
                    </div>
                    <div class="rowform-group" style="margin-bottom: -4%;" >
                        <div style="margin-right: 2%;">
                            <input type="text" id="student-number" name="student-number" placeholder="Student Number" required>
                        </div>
                        <div>
                            <input type="text" id="contact-number-student" name="contact-number-student" placeholder="Contact Number" required>
                        </div>
                    </div>
                    <div class="rowform-group" style="margin-bottom: -2%;">
                        <div style="margin-right: 2%;">
                            <input type="text" id="first-name-student" name="first-name-student" placeholder="First Name" required>
                        </div>
                        <div>
                            <input type="text" id="last-name-student" name="last-name-student" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="popform-group">
                        <input type="email" id="email-student" name="email-student" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|rtu\.edu\.ph)$" required>
                    </div>
                    <div class="popform-group">
                        <textarea id="purpose-student" name="purpose-student" placeholder="Purpose of appointment" required></textarea>
                    </div>
                  
                </div>
                <div class="calendar-space">
                <div class="calendar">
                    <div class="month">
                        <button type="button" class="prev" id="prev-month">❮</button>
                        <div class="month-name" id="month-name">June 2024</div>
                        <button type="button" class="next" id="next-month">❯</button>
                    </div>
                    <div class="weekdays">
                    
                        <div class="day-name">Mon</div>
                        <div  class="day-name">Tue</div>
                        <div  class="day-name">Wed</div>
                        <div  class="day-name">Thu</div>
                        <div  class="day-name">Fri</div>
                     
                    </div>
                    <div id="days"></div>
                
                </div>
                <div class="rowform-group" style="margin-bottom: 2%; margin-top: 2%;">
                <input type="text" id="selected-date" name="date-student" readonly placeholder="Select a date" style="margin-right: 2%;">
                <select id="time-selection" name="time-student" placeholder="RTU Branch">
                 
                </select>
                                
                    </div>
            
                <script src="admin.js"> </script>
                </div>
            </div>
            <div class="choice-container">
                        <button class="choice-button" type="button" onclick="StudentRequest()">Set Appointment</button>
                        <button class="choice-button" type="button" onclick="hideStudentpop()">Back</button>
                    </div>
        </form>
    </div>
     <!-- EMPLOYEE APPOINTMENT FORM -->
    <div id="employee">
        <form id="employeepop" method="post" action="employeeapp.php">
        <table>
                        <thead>
                            <tr><th class="table-header" scope="col">EMPLOYEE APPOINTMENT</th></tr>
                        </thead>
                    </table>
        <div id="logdelConstruct" class="rowleftright">
                <div class="form-group">
                    <div class="remove-space"></div>
                  
                    <div class="popform-group" style="margin-bottom: 2%; margin-top: 1%;">
                        <select id="branch-employee" name="branch-employee" placeholder="RTU Branch" onchange="updateOfficesEmployee()" required>
                            <option value="">RTU Branch</option>
                            <option value="Pasig Branch">Pasig Branch</option>
                            <option value="Mandaluyong Branch">Mandaluyong Branch</option>
                        </select>
                    </div>
                    <div class="popform-group" style="margin-bottom: -2%; margin-top: 1%;">
                    <select id="office-employee" name="office-employee" placeholder="RTU Branch" required>
                        
                        </select>
                    </div>
                    <div class="rowform-group" style="margin-bottom: -5%; margin-top: 2%;">
                        <div style="margin-right: 2%;">
                            <input type="text" id="employee-number" name="employee-number" placeholder="Employee Number" required>
                        </div>
                        <div>
                            <input type="text" id="contact-number-employee" name="contact-number-employee" placeholder="Contact Number" required>
                        </div>
                    </div>
                    <div class="rowform-group"  style="margin-bottom: -2%;">
                        <div style="margin-right: 2%;">
                            <input type="text" id="first-name-employee" name="first-name-employee" placeholder="First Name" required>
                        </div>
                        <div>
                            <input type="text" id="last-name-employee" name="last-name-employee" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="popform-group">
                        <input type="email" id="email-employee" name="email-employee" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|rtu\.edu\.ph)$" required>
                    </div>
                    <div class="popform-group">
                        <textarea id="purpose-employee" name="purpose-employee" placeholder="Purpose of appointment" required></textarea>
                    </div>
                  
                </div>
                <div class="calendar-space">
                <div class="calendar">
                    <div class="month">
                        <button type="button" class="prev" id="prev-month-employee">❮</button>
                        <div class="month-name" id="month-name-employee">June 2024</div>
                        <button type="button" class="next" id="next-month-employee">❯</button>
                    </div>
                    <div class="weekdays">
                    
                        <div class="day-name">Mon</div>
                        <div  class="day-name">Tue</div>
                        <div  class="day-name">Wed</div>
                        <div  class="day-name">Thu</div>
                        <div  class="day-name">Fri</div>
                     
                    </div>
                    <div id="days-employee"></div>
                
                </div>
                <div class="rowform-group" style="margin-bottom: 2%; margin-top: 2%;">
                <input type="text" id="selected-date-employee" name="date-employee" readonly placeholder="Select a date" style="margin-right: 2%;">
                <select id="time-selection-employee" name="time-employee" placeholder="RTU Branch">
                 
                </select>
                                
                    </div>
            
                <script src="admin.js"> </script>
                </div>
               
            </div>
            <div class="choice-container">
                        <button class="choice-button" type="button" onclick="EmployeeRequest()">Set Appointment</button>
                        <button class="choice-button" type="button" onclick="hideStudentpop2()">Back</button>
                    </div>
        </form>
    </div>
    <!-- GUEST APPOINTMENT FORM -->
    <div id="guest">
        <form id="guestpop" method="post" action="guestapp.php">
              <table>
                        <thead>
                            <tr><th class="table-header" scope="col">GUEST APPOINTMENT</th></tr>
                        </thead>
                    </table>
            <div  class="rowleftright">
                <div class="form-group">
                    <div class="remove-space"></div>
                  
                    <div class="popform-group" style="margin-bottom: 2%; margin-top: 2%;">
                        <select id="branch-guest" name="branch-guest" placeholder="RTU Branch" onchange="updateOfficesGuest()" required>
                            <option value="">RTU Branch</option>
                            <option value="Pasig Branch">Pasig Branch</option>
                            <option value="Mandaluyong Branch">Mandaluyong Branch</option>
                        </select>
                    </div>
                    <div class="popform-group" style="margin-bottom: 3%; margin-top: 1%;">
                    <select id="office-guest" name="office-guest" placeholder="RTU Branch" required>
                        
                        </select>
                    </div>
                    <div class="rowform-group" style="margin-top: -3%;">
                        <div style="margin-right: 2%;">
                            <input type="text" id="email-guest" name="email-guest" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|rtu\.edu\.ph)$" required>
                        </div>
                        <div>
                            <input type="text" id="contact-number-guest" name="contact-number-guest" placeholder="Contact Number" required>
                        </div>
                    </div>
                    <div class="rowform-group" style="margin-top: -4.5%;">
                        <div style="margin-right: 2%;">
                            <input type="text" id="first-name-guest" name="first-name-guest" placeholder="First Name" required>
                        </div>
                        <div>
                            <input type="text" id="last-name-guest" name="last-name-guest" placeholder="Last Name" required>
                        </div>
                    </div>
                    <div class="rowform-group" style="margin-top: -4.5%; margin-bottom: -1%;">
                        <div style="margin-right: 2%;">
                            <input type="text" id="type-guest" name="type-guest" placeholder="Type of ID" required>
                        </div>
                        <div>
                            <input type="text" id="identification-guest" name="identification-guest" placeholder="Identification No." required>
                        </div>
                    </div>
                    <div class="popform-group">
                        <textarea id="purpose-guest"  name="purpose-guest" placeholder="Purpose of appointment" required></textarea>
                    </div>
                    
                </div>
                <div class="calendar-space">
                <div class="calendar">
                    <div class="month">
                        <button type="button" class="prev" id="prev-month-guest">❮</button>
                        <div class="month-name" id="month-name-guest">June 2024</div>
                        <button type="button" class="next" id="next-month-guest">❯</button>
                    </div>
                    <div class="weekdays">
                    
                        <div class="day-name">Mon</div>
                        <div  class="day-name">Tue</div>
                        <div  class="day-name">Wed</div>
                        <div  class="day-name">Thu</div>
                        <div  class="day-name">Fri</div>
                     
                    </div>
                    <div id="days-guest"></div>
                
                </div>
                <div class="rowform-group" style="margin-bottom: 2%; margin-top: 2%;">
                <input type="text" id="selected-date-guest" name="date-guest" readonly placeholder="Select a date" style="margin-right: 2%;">
                <select id="time-selection-guest" name="time-guest" placeholder="RTU Branch">
                 
                </select>
                                
                    </div>
            
     
                </div>
            </div>
            <div class="choice-container">
                        <button type="button" id="generatePdfButton" class="choice-button" onclick="GuestRequest()">Set Appointment</button>
                        <script>
					window.jsPDF = window.jspdf.jsPDF;
					</script>
                        <button class="choice-button" type="button" onclick="hideGuestpop()">Back</button>
                    </div>
                    <script src="admin.js"> </script>
        </form>
    </div>
     <!-- VIEW APPOINTMENT FORM -->
     <div id="view">
        <form id="viewpop">
            <div>
                <div class="form-group">
                    <div class="remove-space"></div>
                    <table>
                        <thead>
                            <tr><th class="table-header" scope="col">VIEW APPOINTMENT</th></tr>
                        </thead>
                    </table>
                   
                    <div id="fetch-view" class="popform-group">
                       
                    </div> 
                    
                </div>
            </div>
        </form>
    </div>
</body>
</html>
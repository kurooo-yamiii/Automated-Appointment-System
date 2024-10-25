<?php
include 'db_conn.php';
include 'db_pdo.php';
session_start();
if (isset($_SESSION['ID']) && isset($_SESSION['Name'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="resources/TF.png" type="image/x-icon">
	<!-- icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
	<!-- My CSS -->
	<link rel="stylesheet" href="administrator.css">
    <link rel="stylesheet" href="administratormedia.css">
	<script src="administrator.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<title>TechFlow - Administrator</title>
</head>
<body>
	<!-- SIDEBAR -->
	<header class="header">
	<h2 class="u-name">TECH<b> FLOW</b> 
			</h2>
			<h2 class="u-name">RIZAL TECHNOLOGICAL UNIVERSITY
			</h2>
			<div id="sideicon">
			<img  src="resources/TF.png">
			<img  src="resources/logo.png">
		
			</div>
		</header>
		<style>
		#Announcement{
			display: none;
			}
            #dailyShow{
                display: none;
            }
            #weeklyShow{
                display: none;
            }
            #announce{
                display: none;
            }
        #Appointment{
            display: none;
        }
        #User{
            display: none;
        }
     
			
    </style>
		<div class="body">
			<nav class="side-bar">
			<div class="user-p">
          
		  <img  src="resources/TF.png">
          <div style="display: flex; flex-direction: column;">
          <p class="parawelcome">Welcome Back</p>
          <p class="parawelcome"><?php	
            echo $_SESSION['Name'];
            ?></p>
          </div>
	   </div>
	   
				<ul>
					<li id="btndashboard">
						<b onclick="DisplayDashboard()">
						<a style="text-decoration: none; color: #eee;">
						<div class="direction">
						<span class="material-icons-outlined" id="iconblue">calendar_month</span>
						<p>Dashboard</p>
						</div>
						</a>
						</b>
					</li>

					<li id="btnlaptop">
						<b onclick="DisplayAppointment()">
						<a style="text-decoration: none; color: #eee;">
						<div class="direction">
						<span class="material-icons-outlined" id="iconblue3">pending_actions</span>
						<p id="layoutbtn">Appointment</p>
						</div>
						</a>
						</b>
					</li>

					<li id="btntools">
						<b onclick="DisplayAnnouncement()">
						<a style="text-decoration: none; color: #eee;">
						<div class="direction">
						<span class="material-icons-outlined" id="iconblue3">campaign</span>
						<p id="layoutbtn">Announcement</p>
						</div>
						</a>
						</b>
					</li>

					<li id="btnlog">
						<b onclick="DisplayUser()">
						<a style="text-decoration: none; color: #eee;">
						<div class="direction">
						<span class="material-icons-outlined" id="iconblue3">group</span>
						<p id="layoutbtn">User Manager</p>
						</div>
						</a>
						</b>
					</li>


					<li id="btrout">
							<a href="logout.php" style="text-decoration: none; color: #eee;">
							<div class="direction">
							<span class="material-icons-outlined" id="iconblue3">logout</span>
						<p id="layoutbtn">Logout</p>
						</div>
						</a>
						</b>
					</li>
	
					
				</ul>
			</nav>

	<section id="content">
	<form id="Dashboard">
    <div class="calendar" id="dailyShow">
        <div class="dailyform">
            <div class="next-prev-div">
            <button type="button" class="prev" id="prev-day">❮</button>
            <div class="week-name" id="daily-name"></div>
            <button type="button" class="next" id="next-day">❯</button>
            </div>
            <div class="choices-div">
                        <button type="button"  id="" onclick="monthlyShow()">Monthly</button>
                        <button type="button" id="" onclick="weeklyShow()">Weekly</button>
                        <button type="button"  id="" onclick="dailyShow()">Daily</button>
                        </div>
        </div>
        <div id="daily"></div>
    </div>
    <div class="calendar" id="weeklyShow">
        <div class="week">
            <div class="next-prev-div2">
            <button type="button" class="prev" id="prev-week">❮</button>
            <div class="week-name" id="week-name"></div>
            <button type="button" class="next" id="next-week">❯</button>
            </div>
            <div class="choices-div2">
                        <button type="button"  id="" onclick="monthlyShow()">Monthly</button>
                        <button type="button" id="" onclick="weeklyShow()">Weekly</button>
                        <button type="button"  id="" onclick="dailyShow()">Daily</button>
                        </div>
        </div>
        <div class="weekdays">
            <div class="day-name">Mon</div>
            <div class="day-name">Tue</div>
            <div class="day-name">Wed</div>
            <div class="day-name">Thu</div>
            <div class="day-name">Fri</div>
        </div>
        <div id="weeks" class="days-container"></div>
    </div>

    <div class="calendar" id="monthlyShow">
                    <div class="month">
                        <div class="next-prev-div">
                        <button type="button" class="prev" id="prev-month">❮</button>
                        <div class="month-name" id="month-name">June 2024</div>
                        <button type="button" class="next" id="next-month">❯</button>
                        </div>
                        <div class="choices-div">
                        <button type="button"  id="" onclick="monthlyShow()">Monthly</button>
                        <button type="button" id="" onclick="weeklyShow()">Weekly</button>
                        <button type="button"  id="" onclick="dailyShow()">Daily</button>
                        </div>
                    </div>
                    <div class="weekdays">
                    
                        <div class="day-name">Mon</div>
                        <div  class="day-name">Tue</div>
                        <div  class="day-name">Wed</div>
                        <div  class="day-name">Thu</div>
                        <div  class="day-name">Fri</div>
                     
                    </div>
                    <div id="days">
                        <p id="appoint"></p>
                    </div>
                
                </div>
    </form>
    <form id="Announcement">
    <div class="dashboard">
        <h1>ANNOUNCEMENT</h1>

        <div class="logos">
            <button class="announcement-button"  type="button" onclick="AnnouncePopShow()">Create Announcement</button>
        </div>
     </div>

    <div class="divider"></div>
    <div id="fetchAnnouncement">
    <?php $announcement = $ponn->query("SELECT * FROM announcement ORDER BY ID DESC"); ?>
    <?php if($announcement->rowCount() <= 0){ ?>
        <div class="todo-item">
				    <a href="" 
			      	   id="remove-to-do" class="removee-to-do">N/A</a> <br>
                        <div class="row-announce">
                            <div class="empty">
                             <img src="resources/default.jpeg">
                            </div>
                            <div class="post-ann">
                        <h2>There is no announcement concurrently
                    </h2>
              
            <small>Note: If you want to add announcement click the button "create announcement" in the upper right corner</small> 
            </div>
            </div>
            </div>
    <?php }else{ ?>
    <?php while($fetchannounce = $announcement->fetch(PDO::FETCH_ASSOC)) { ?>
    <div class="todo-item">
				    <button type="button" onclick="DelAnnShow(<?php echo $fetchannounce['ID']; ?>)" 
			      	   id="remove-to-do" class="removee-to-do"><?php echo $fetchannounce['Date']; ?></button> <br>
                        <div class="row-announce">
                            <div class="empty">
                             <img src="resources/logo.png">
                            </div>
                            <div class="post-ann">
                            <h2 style="font-weight: 700; font-size: 18px;"><?php echo $fetchannounce['Title']; ?></h2>
                        <h2>
                        <?php echo $fetchannounce['Description']; ?>
                    </h2>
              
            <small>- <?php echo $fetchannounce['Author']; ?></small> 
            </div>
            </div>
            </div>
            <?php }} ?>
    </div>
    </form>
    <form id="Appointment">
    <div class="dashboard">
        <h1>APPOINTMENT</h1>

       
     </div>

    <div class="divider"></div>
   
           <div class="row-disapp">  
            <div>
                    <label for="searchapp" class="search-label">Search:</label>
                    <input type="text" name="searchapp" id="searchapp" placeholder="Enter your search term" class="search-input">
                    <button type="button" onclick="SearchAppointment()" class="search-button">Search</button>
            </div>
            <div class="row-limit">
            <label for="mySelect" class="search-label2">Limit:</label>
                    <select class="search-input2" id="mySelect" onchange="ChangeLimit()">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
            </div>
        
           </div>
           <div id="fetchAppointment">
           <?php 
           
           $searchTerm = "";
   
           $sqlsearch = "SELECT * FROM overall_appointments ORDER BY ID DESC LIMIT 5";
           $resultsearch = mysqli_query($conn, $sqlsearch);
       
      
            ?>
           <?php 
           // Display the record in Table
           if (mysqli_num_rows($resultsearch)) { ?>
           <table class="table table-striped" id="startupproftable">
             <thead>
               <tr>
                
                 <th scope="col">Name</th>
                 <th scope="col">Date</th>
                 <th scope="col">Time</th>
                 <th scope="col">Office</th>
                 <th scope="col">Branch</th>
                 <th scope="col">Action</th>
              
         
               </tr>
             </thead>
             <tbody>
             <?php 
             // Continue adding rows depening to the Fetch Data
                  $i = 0;
                  while($rows = mysqli_fetch_assoc($resultsearch)){
                  $i++;
                ?>
               <tr>
                
               <td><?php echo $rows['Name']; ?></td>
               <td><?php echo $rows['Date']; ?></td>
               <td><?php echo $rows['Time']; ?></td>
               <td><?php echo $rows['Office']; ?></td>
               <td><?php echo $rows['Branch']; ?></td>
                       <td><button type="button" onclick="deleteAppoint(<?php echo $rows['ID']; ?>,'<?php echo $rows['Email']; ?>');"
                        class="red-button">Delete</button> 
                        <button  type="button" onclick="updateAppoint(<?php echo $rows['ID']; ?>,'<?php echo $rows['Email']; ?>','<?php echo $rows['Name']; ?>','<?php echo $rows['Date']; ?>','<?php echo $rows['Time']; ?>','<?php echo $rows['Office']; ?>','<?php echo $rows['Branch']; ?>');"
                        class="blue-button">Update</button>
                         </td>
               </tr>
               <?php } ?>
             </tbody>
             <?php }else  ?>
           </table>
           </div>
    </form>
    <form id="User">
    <div class="dashboard">
        <h1>USER MANAGER</h1>
        <div class="logos">
            <button class="announcement-button"  type="button" onclick="CreateUserShow()">Create User</button>
        </div>
       
     </div>

    <div class="divider"></div>
   
           <div class="row-disapp">  
            <div>
                    <label for="searchuser" class="search-label">Search:</label>
                    <input type="text" name="searchuser" id="searchuser" placeholder="Enter your search term" class="search-input">
                    <button type="button" onclick="SearchUser()" class="search-button">Search</button>
            </div>
            <div class="row-limit">
            <label for="limit" class="search-label2">Limit:</label>
                    <select class="search-input2" id="limit" onchange="UserLimit()">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
            </div>
        
           </div>
           <div id="fetchUser">
           <?php 
           
           $searchTerm = "";
   
           $sqlsearch = "SELECT * FROM user ORDER BY ID DESC LIMIT 5";
           $resultsearch = mysqli_query($conn, $sqlsearch);
       
      
            ?>
           <?php 
           // Display the record in Table
           if (mysqli_num_rows($resultsearch)) { ?>
           <table class="table table-striped" id="startupproftable">
             <thead>
               <tr>
                
                 <th scope="col">Name</th>
                 <th scope="col">Username</th>
                 <th scope="col">Password</th>
                 <th scope="col">Action</th>
              
         
               </tr>
             </thead>
             <tbody>
             <?php 
             // Continue adding rows depening to the Fetch Data
                  $i = 0;
                  while($rows = mysqli_fetch_assoc($resultsearch)){
                  $i++;
                ?>
               <tr>
                
               <td><?php echo $rows['Name']; ?></td>
               <td><?php echo $rows['Username']; ?></td>
               <td><?php echo $rows['Password']; ?></td>
                       <td><button type="button" onclick="DeleteUserShow(<?php echo $rows['ID']; ?>);"
                        class="red-button">Delete</button> 
                        <button  type="button" onclick="UpdateUserShow(<?php echo $rows['ID']; ?>,'<?php echo $rows['Name']; ?>','<?php echo $rows['Username']; ?>','<?php echo $rows['Password']; ?>',);"
                        class="blue-button">Update</button>
                         </td>
               </tr>
               <?php } ?>
             </tbody>
             <?php }else  ?>
           </table>
           </div>

    </form>
	</section>
     <!-- DELETE USER FORM -->
     <div id="deluser">
        <form id="deluserpop">
            <div>
                <div class="form-group">
                    <div class="remove-space"></div>
                    <table>
                        <thead>
                            <tr><th class="table-header" style="padding: 10px 20px; border: none; background-color: #003f66;" scope="col">DELETE ACCOUNT</th></tr>
                        </thead>
                    </table>
                   
                    <div id="fetch-view">

                    <input style="margin-top: 2%;" type="hidden" id="userdelid" name="userdelid" >
                    <p class="dealannpara">Are you sure you want to delete the selected user account?</p>

                    <div class="choice-container">
                        <button class="choice-button" type="button" onclick="DeleteUser()">Yes</button>
                        <button class="choice-button" type="button" onclick="DelUserHide()">No</button>
                    </div>
                    </div> 
                    
                </div>
            </div>
        </form>
    </div>
    <!-- UPDATE USER FORM -->
    <div id="upuser">
        <form id="upuserpop">
            <div>
                <div class="form-group">
                    <div class="remove-space"></div>
                    <table>
                        <thead>
                            <tr><th class="table-header" style="padding: 10px 20px; border: none; background-color: #003f66;" scope="col">UPDATE ACCOUNT</th></tr>
                        </thead>
                    </table>
                   
                    <div id="fetch-view" style="padding: 10px;">
                  
                    <input style="margin-top: 2%;" type="hidden" id="upid" name="upid" >
                    <label for="upname" >Name</label>
                    <input style="margin-top: 3%; margin-bottom: 3%;" type="text" id="upname" name="upname" placeholder="Name" required>
                    <label for="upusername" >Username</label>
                    <input style="margin-top: 3%; margin-bottom: 3%;" type="text" id="upusername" name="upusername" placeholder="Username" required>
                    <label for="uppassword">Password</label>
                    <input style="margin-top: 3%; margin-bottom: 3%;" type="text" id="uppassword" name="uppassword" placeholder="Password" required>
                    
                    
                </div> 
                <div class="choice-container" style="margin-bottom: 3%;">
                        <button class="choice-button" type="button" onclick="UpdateUser()">Update</button>
                        <button class="choice-button" type="button" onclick="UpdateUserHide()">Back</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
     <!-- CREATE USER FORM -->
     <div id="createuser">
        <form id="createuserpop">
            <div>
                <div class="form-group">
                    <div class="remove-space"></div>
                    <table>
                        <thead>
                            <tr><th class="table-header" style="padding: 10px 20px; border: none; background-color: #003f66;" scope="col">CREATE ACCOUNT</th></tr>
                        </thead>
                    </table>
                   
                    <div id="fetch-view" style="padding: 10px;">
                    <input style="margin-top: 2%;"type="text" id="u-name" name="u-name" placeholder="Name" required>
                    <input style="margin-top: 5%;"type="text" id="username" name="username" placeholder="Username" required>
                    <input style="margin-top: 5%;"type="password" id="password" name="password" placeholder="Password" required>
                    <input style="margin-top: 5%; margin-bottom: 3%; "type="password" id="cpassword" name="cpassword" placeholder="Confirm Password" required>
                    
                </div> 
                <div class="choice-container" style="margin-bottom: 3%;">
                        <button class="choice-button" type="button" onclick="CreateAccount()">Create</button>
                        <button class="choice-button" type="button" onclick="CreateUserHide()">Back</button>
                    </div>
                </div>
            </div>
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
                            <tr><th class="table-header" style="padding: 10px 20px; border: none; background-color: #003f66;" scope="col">APPOINTMENT INFORMATION</th></tr>
                        </thead>
                    </table>
                   
                    <div id="seek-view">
                       
                    </div> 
                    
                </div>
            </div>
        </form>
    </div>
       <!-- DELETE ANNOUNCEMENT FORM -->
       <div id="delann">
        <form id="delannpop">
            <div>
                <div class="form-group">
                    <div class="remove-space"></div>
                    <table>
                        <thead>
                            <tr><th class="table-header" style="padding: 10px 20px; border: none; background-color: #003f66;" scope="col">DELETE ANNOUNCEMENT</th></tr>
                        </thead>
                    </table>
                   
                    <div id="fetch-view">

                    <input style="margin-top: 2%;" type="hidden" id="del-id" name="del-id" >
                    <p class="dealannpara">The restriction will be lifted in the selected anouncement. Are you sure you want to delete the selected announcement?</p>

                    <div class="choice-container">
                        <button class="choice-button" type="button" onclick="DeleteAnnouncement()">Yes</button>
                        <button class="choice-button" type="button" onclick="DelAnnHide()">No</button>
                    </div>
                    </div> 
                    
                </div>
            </div>
        </form>
    </div>
      <!-- DELETE APPOINTMENT FORM -->
      <div id="delapp">
        <form id="delapppop">
            <div>
                <div class="form-group">
                    <div class="remove-space"></div>
                    <table>
                        <thead>
                            <tr><th class="table-header" style="padding: 10px 20px; border: none; background-color: #003f66;" scope="col">DELETE APPOINTMENT</th></tr>
                        </thead>
                    </table>
                   
                    <div id="fetch-view">

                    <input style="margin-top: 2%;" type="hidden" id="app-id" name="app-id" >
                    <input style="margin-top: 2%;" type="hidden" id="email-app" name="email-app" >
                    <p class="dealannpara" >The appointee will be notified for the deleted appointment. Are you sure you want to delete the selected appointment?</p>

                    <div class="choice-container">
                        <button class="choice-button" type="button" onclick="DeleteApp()">Yes</button>
                        <button class="choice-button" type="button" onclick="DelAppHide()">No</button>
                    </div>
                    </div> 
                    
                </div>
            </div>
        </form>
    </div>
       <!-- CREATE ANNOUNCEMENT -->
       <div id="announce">
        <form id="announcepop">
            <div>
                <div class="form-group">
                    <div class="remove-space"></div>
                    <table>
                        <thead>
                            <tr><th class="table-header" style="padding: 10px 20px; border: none; background-color: #003f66;" scope="col">CREATE ANNOUNCEMENT</th></tr>
                        </thead>
                    </table>
                   
                    <div id="fetch-view">
                    <input style="margin-top: 2%;" type="text" id="announce-title" name="announce-title" placeholder="Event" required>

                    <textarea id="announce-desc" name="announce-desc" placeholder="Event Description" required></textarea>

                    <input type="text" id="announce-author" name="announce-author" placeholder="Person In-Charge" required>
                    <div class="cspace">
                        <div class="cal">
                            <div class="mon">
                                <button type="button" class="prev" id="prev-month1">❮</button>
                                <div class="month-name" id="month-name1">June 2024</div>
                                <button type="button" class="next" id="next-month1">❯</button>
                            </div>
                            <div class="wd">
                            
                                <div class="dm">Mon</div>
                                <div  class="dm">Tue</div>
                                <div  class="dm">Wed</div>
                                <div  class="dm">Thu</div>
                                <div  class="dm">Fri</div>
                            
                            </div>
                            <div id="ds"></div>
                        
                        </div>
                        <div class="rowform-group" style="margin-bottom: 2%; margin-top: 2%;">
                        <input type="text" id="selected-date" name="date-student" readonly placeholder="Select a date" style="margin-right: 2%;">
                              
                            </div>
                    
                        <script src="calendar.js"> </script>
                        </div>
                        <label class="custom-checkbox">
                            <input style="display: none;" id="drop_appointment" name="drop_appointment" type="checkbox">
                            <span class="checkbox-custom"></span>
                            Drop existing appointment in the selected Date
                        </label>
                    <div class="choice-container">
                        <button class="choice-button" type="button" onclick="CreateAnnouncement()">Post</button>
                        <button class="choice-button" type="button" onclick="AnnouncePopHide()">Back</button>
                    </div>
                    </div> 
                    
                </div>
            </div>
        </form>
    </div>
	  <!-- UPDATE APPOINTMENT -->
      <div id="upapp">
        <form id="upapppop">
            <div>
                <div class="form-group">
                    <div class="remove-space"></div>
                    <table>
                        <thead>
                            <tr><th class="table-header" style="padding: 10px 20px; border: none; background-color: #003f66;" scope="col">UPDATE APPOINTMENT</th></tr>
                        </thead>
                    </table>
                   
                    <div id="fetch-view">
                    <input style="margin-top: 2%;" type="hidden" id="id-app" name="id-app" >
                    <input style="margin-top: 2%;" type="hidden" id="email-up" name="email-up" >

                    <input style="margin-top: 2%;" type="text" id="name-app" name="name-app"  required>
                    
                    <input style="margin-top: 2%;" type="text" id="office-app" name="office-app" required>
                 
                    <input style="margin-top: 2%;" type="text" id="branch-app" name="branch-app" required>
                    <div class="cspace">
                                <div class="cal">
                                    <div class="mon">
                                        <button type="button" class="prev" id="prev-month-2">❮</button>
                                        <div class="month-name" id="month-name-2">June 2024</div>
                                        <button type="button" class="next" id="next-month-2">❯</button>
                                    </div>
                                    <div class="wd">
                                        <div class="dm">Mon</div>
                                        <div class="dm">Tue</div>
                                        <div class="dm">Wed</div>
                                        <div class="dm">Thu</div>
                                        <div class="dm">Fri</div>
                                    </div>
                                    <div id="ds-2"></div>
                                </div>
                                <div class="rowform-group" style="margin-bottom: 2%; margin-top: 2%;">
                                    <input type="text" id="selected-date-2" name="date-student-2" readonly placeholder="Select a date" style="margin-right: 2%;">
                                    <select id="time-selection" name="time-student" placeholder="RTU Branch">
                                            
                                    </select>    
                                </div>
                                <script src="calendar.js"> </script>
                            </div>
                              
                      
                        </div>
                    <div class="choice-container">
                        <button class="choice-button" type="button" onclick="UpdateAnnounce()">Update</button>
                        <button class="choice-button" type="button" onclick="UpdateAppHide()">Back</button>
                    </div>
                    </div> 
                    
                </div>
            </div>
        </form>
    </div>
	
	<script src="administrator.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
<?php
}else{
     header("Location: index.php");
     exit();
}
?>
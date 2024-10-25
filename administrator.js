


document.addEventListener('DOMContentLoaded', function() {
  checkDateAndDeleteAppointments();
  checkDateAndSendReminder();
});

function checkDateAndDeleteAppointments() {
  const currentDate = new Date();
  const todayStr = currentDate.toISOString().split('T')[0];

  const lastCheckedDate = localStorage.getItem('lastCheckedDate');

  if (lastCheckedDate !== todayStr) {
      const yesterday = new Date();
      yesterday.setDate(currentDate.getDate() - 1);
      const dateStr = yesterday.toISOString().split('T')[0];

      
      fetch('resetappointment.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({ date: dateStr })
      })
      .then(response => response.json())
      .then(data => {
          console.log('Appointments deleted:', data);
          // Update the last checked date to today
          localStorage.setItem('lastCheckedDate', todayStr);
      })
      .catch(error => {
          console.error('Error:', error);
      });
  }
}



function checkDateAndSendReminder() {
  const currentDate = new Date();
  console.log('Current Date:', currentDate.toISOString().split('T')[0]);
  
  const tomorrow = new Date();
  tomorrow.setDate(currentDate.getDate() + 3);
  const tom = tomorrow.toISOString().split('T')[0];
  
  console.log('Tomorrow Date:', tom);

  // Check if the reminder for the current date is already sent
  if (localStorage.getItem('reminder_sent_date') === tom) {
  
      console.log('Reminder already sent for today.');
      return; // Exit the function if already sent
  }
 

  fetch('reminder.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ date: tom })
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          console.log('Reminder email sent.');
          // Set the flag in localStorage to indicate the email is sent for today
          localStorage.setItem('reminder_sent_date', tom);
      } else {
          console.error('Error sending reminder:', data.error);
      }
  })
  .catch(error => {
      console.error('Error:', error);
  });
}


function monthlyShow(){
    document.getElementById("monthlyShow").style.display = "block"
    document.getElementById("weeklyShow").style.display = "none"
    document.getElementById("dailyShow").style.display = "none"
}

function weeklyShow(){
    document.getElementById("monthlyShow").style.display = "none"
    document.getElementById("weeklyShow").style.display = "block"
    document.getElementById("dailyShow").style.display = "none"
}

function dailyShow(){
    document.getElementById("monthlyShow").style.display = "none"
    document.getElementById("weeklyShow").style.display = "none"
    document.getElementById("dailyShow").style.display = "block"
}

document.addEventListener('DOMContentLoaded', function() {
    const monthNameElement = document.getElementById('month-name');
    const daysElement = document.getElementById('days');
    const prevMonthButton = document.getElementById('prev-month');
    const nextMonthButton = document.getElementById('next-month');
   

    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    let currentDate = new Date();

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();

        // Set the month name
        monthNameElement.textContent = `${months[month]} ${year}`;

        // Clear previous days
        daysElement.innerHTML = '';

        // Get first and last day of the month
        const lastDate = new Date(year, month + 1, 0).getDate();

        // Get today's date without time
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Create day elements
        for (let i = 1; i <= lastDate; i++) {
            const dateToCheck = new Date(year, month, i);
            const dayOfWeek = dateToCheck.getDay();

            // Skip Saturday (6) and Sunday (0)
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                continue;
            }

            const dayDiv = document.createElement('div');
            dayDiv.className = 'day';
            
            const dateDiv = document.createElement('div');
            dateDiv.className = 'date';
            dateDiv.textContent = i;

            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'button-container';

            dayDiv.appendChild(dateDiv);
            dayDiv.appendChild(buttonContainer);

            // Check if the day is in the past
            if (dateToCheck < today) {
                dayDiv.classList.add('disabled');
            } else {
                (function(dayDiv, year, month, i) {
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                    fetch(`restriction.php?date=${dateStr}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.isFull || data.restrictedDates.includes(dateStr)) {
                                dayDiv.classList.add('disabled');
                            } else {
                                dayDiv.addEventListener('click', () => {
                                  
                                   
                                });

                                // Fetch and display appointments for the day
                                fetch(`fetchappointment.php?date=${dateStr}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data && data.appointment) {
                                            data.appointment.forEach(appointment => {
                                                const button = document.createElement('button');
                                                button.className = 'appointment-button';
                                                button.type = "button";
                                                button.textContent = `${appointment.time}`;
                                                button.onclick = function() { ViewAppointment(appointment.id); };
                                                buttonContainer.appendChild(button);
                                            });
                                        }
                                    })
                                    .catch(error => console.error('Error fetching date info:', error));
                            }
                        })
                        .catch(error => console.error('Error fetching date info:', error));
                })(dayDiv, year, month, i);
            }

            daysElement.appendChild(dayDiv);
        }
    }

    function changeMonth(delta) {
        currentDate.setMonth(currentDate.getMonth() + delta);
        renderCalendar(currentDate);
    }

    prevMonthButton.addEventListener('click', () => changeMonth(-1));
    nextMonthButton.addEventListener('click', () => changeMonth(1));

    // Initial render
    renderCalendar(currentDate);
});


console.log("Inline script running");
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOMContentLoaded event fired");
    const weekNameElement = document.getElementById('week-name');
    const daysElement = document.getElementById('weeks');
    const prevWeekButton = document.getElementById('prev-week');
    const nextWeekButton = document.getElementById('next-week');

    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    let currentDate = new Date();

    function getMonday(d) {
        d = new Date(d);
        const day = d.getDay(),
              diff = d.getDate() - day + (day === 0 ? -6 : 1); // adjust when day is sunday
        return new Date(d.setDate(diff));
    }

    function renderWeek(date) {
        console.log("Rendering week for date:", date);
        const monday = getMonday(date);
        const daysOfWeek = [];

        for (let i = 0; i < 5; i++) { // Monday to Friday
            daysOfWeek.push(new Date(monday.getFullYear(), monday.getMonth(), monday.getDate() + i));
        }

        console.log("Days of week:", daysOfWeek);

        // Set the week name
        weekNameElement.textContent = `${months[daysOfWeek[0].getMonth()]} ${daysOfWeek[0].getDate()}, ${daysOfWeek[0].getFullYear()} - ${months[daysOfWeek[4].getMonth()]} ${daysOfWeek[4].getDate()}, ${daysOfWeek[4].getFullYear()}`;

        // Clear previous days
        daysElement.innerHTML = '';

        // Get today's date without time
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Create day elements
        daysOfWeek.forEach(date => {
            const year = date.getFullYear();
            const month = date.getMonth();
            const day = date.getDate();
            const dateToCheck = new Date(year, month, day);

            const dayDiv = document.createElement('div');
            dayDiv.className = 'weekday';

            const dateDiv = document.createElement('div');
            dateDiv.className = 'date';
            dateDiv.textContent = date.getDate();

            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'button-container';

            dayDiv.appendChild(dateDiv);
            dayDiv.appendChild(buttonContainer);

            // Check if the day is in the past
            if (dateToCheck < today) {
                dayDiv.classList.add('disabled');
            } else {
                (function(dayDiv, year, month, day) {
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    fetch(`restriction.php?date=${dateStr}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.isFull || data.restrictedDates.includes(dateStr)) {
                                dayDiv.classList.add('disabled');
                            } else {
                                dayDiv.addEventListener('click', () => {
                                    
                                });

                                // Fetch and display appointments for the day
                                fetch(`fetchappointment.php?date=${dateStr}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data && data.appointment) {
                                            data.appointment.forEach(appointment => {
                                                const button = document.createElement('button');
                                                button.className = 'appointment-week';
                                                button.type = "button";
                                                button.textContent = `\u25CF ${appointment.time} - ${appointment.office}`;
                                                button.onclick = function() { ViewAppointment(appointment.id); };
                                                buttonContainer.appendChild(button);
                                            });
                                        }
                                    })
                                    .catch(error => console.error('Error fetching appointments:', error));
                            }
                        })
                        .catch(error => console.error('Error fetching restrictions:', error));
                })(dayDiv, year, month, day);
            }

            daysElement.appendChild(dayDiv);
        });
    }

    function changeWeek(delta) {
        currentDate.setDate(currentDate.getDate() + (delta * 7));
        renderWeek(currentDate);
    }

    prevWeekButton.addEventListener('click', () => changeWeek(-1));
    nextWeekButton.addEventListener('click', () => changeWeek(1));

    // Initial render
    renderWeek(currentDate);
});


document.addEventListener('DOMContentLoaded', function() {
    console.log("DOMContentLoaded event fired");
    const dailyNameElement = document.getElementById('daily-name');
    const dailyElement = document.getElementById('daily');
    const prevDayButton = document.getElementById('prev-day');
    const nextDayButton = document.getElementById('next-day');

    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    let currentDate = new Date();

    function renderDaily(date) {
        console.log("Rendering daily for date:", date);
       
        // Set the daily name
        dailyNameElement.textContent = `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;

        // Clear previous day
        dailyElement.innerHTML = '';

        // Get today's date without time
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Create day elements
        const year = date.getFullYear();
        const month = date.getMonth();
        const day = date.getDate();
        const dateToCheck = new Date(year, month, day);

        const dayDiv = document.createElement('div');
        dayDiv.className = 'dailyday';

        const dateDiv = document.createElement('div');
        dateDiv.className = 'date';
  

        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'button-container';

        dayDiv.appendChild(dateDiv);
        dayDiv.appendChild(buttonContainer);

        // Check if the day is in the past
        if (dateToCheck < today) {
            dayDiv.classList.add('disabled');
        } else {
            (function(dayDiv, year, month, day) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                fetch(`restriction.php?date=${dateStr}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.isFull || data.restrictedDates.includes(dateStr)) {
                            dayDiv.classList.add('disabled');
                        } else {
                            dayDiv.addEventListener('click', () => {
                            
                            });

                            // Fetch and display appointments for the day
                            fetch(`fetchappointment.php?date=${dateStr}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data && data.appointment) {
                                        data.appointment.forEach(appointment => {
                                            const button = document.createElement('button');
                                            button.className = 'appointment-daily';
                                            button.type = "button";
                                            button.textContent = `${appointment.time}  \u25CF  ${appointment.name} at ${appointment.office} (${appointment.branch})`;
                                            button.onclick = function() { ViewAppointment(appointment.id); };
                                            buttonContainer.appendChild(button);
                                        });
                                    }
                                })
                                .catch(error => console.error('Error fetching appointments:', error));
                        }
                    })
                    .catch(error => console.error('Error fetching restrictions:', error));
            })(dayDiv, year, month, day);
        }

        dailyElement.appendChild(dayDiv);
    }
    function changeDay(delta) {
        currentDate.setDate(currentDate.getDate() + delta);
    
        if (delta < 0 && currentDate.getDay() === 0) { // Monday
            currentDate.setDate(currentDate.getDate() - 2); // Move to previous Friday
        }
    
        // If it's Friday and clicking "Next", move to the next Monday
        if (delta > 0 && currentDate.getDay() === 6) { // Friday
            currentDate.setDate(currentDate.getDate() + 2); // Move to next Monday
        }
    
        renderDaily(currentDate); // Update the daily view
    }
    prevDayButton.addEventListener('click', () => changeDay(-1));
    nextDayButton.addEventListener('click', () => changeDay(1));

    if (currentDate.getDay() === 0) { // Sunday
        currentDate.setDate(currentDate.getDate() - 2); // Move to previous Friday
    } else if (currentDate.getDay() === 6) { // Saturday
        currentDate.setDate(currentDate.getDate() - 1); // Move to previous Friday
    }

    renderDaily(currentDate); // Update the daily view
});


function ViewAppointment(id) {
  
    $.ajax({
      type: 'POST', 
      url: 'viewadmin.php', 
      data: { id: id },
      success: function(response) {
       
        $('#seek-view').empty();
            $('#seek-view').html(response);
            document.getElementById("view").style.display = "flex";
           
  
      },
      error: function(error) {
      
        console.error('AJAX error:', error);
      }
    });
  }

  function CreateUserShow(){
    event.preventDefault();
    document.getElementById("createuser").style.display = "flex";
  }

  function DeleteUserShow(id){
    event.preventDefault();
    document.getElementById("deluser").style.display = "flex";
    document.getElementById("userdelid").value = id;

  }

  function DelUserHide(){
    event.preventDefault();
    document.getElementById("deluser").style.display = "none";
  }

  function CreateUserHide(){
    event.preventDefault();
    document.getElementById("createuser").style.display = "none";
    document.getElementById("u-name").value = "";
    document.getElementById("username").value = "";
    document.getElementById("password").value = "";
    document.getElementById("cpassword").value = "";
  }

  function hideViewpop() {
    event.preventDefault();
    document.getElementById("view").style.display = "none";
  }

  function AnnouncePopShow() {
    event.preventDefault();
    document.getElementById("announce").style.display = "flex";
  }

  function AnnouncePopHide() {
    event.preventDefault();
    document.getElementById("announce-title").value = "";
    document.getElementById("announce-desc").value = "";
    document.getElementById("announce-author").value = "";
    document.getElementById("selected-date").value = "";
    $('#drop_appointment').prop('checked', false);
    document.getElementById("announce").style.display = "none";
  }

  function DelAnnShow(id) {
    event.preventDefault();
    document.getElementById("delann").style.display = "flex";
    document.getElementById("del-id").value = id;
  }

  function updateAppoint(id, email, name, date, time, office, branch){
    event.preventDefault();
    document.getElementById("upapp").style.display = "flex";
    document.getElementById("id-app").value = id;
    document.getElementById("email-up").value = email;
    document.getElementById("name-app").value = name;
    document.getElementById("office-app").value = office;
    document.getElementById("branch-app").value = branch;
    document.getElementById("selected-date-2").value = date;
    var select = document.getElementById("time-selection");
    var newOption = document.createElement("option");
    newOption.value = time;
    newOption.text = time;
    select.add(newOption);
  }
  

  function deleteAppoint(id, email){
    event.preventDefault();
    document.getElementById("delapp").style.display = "flex";
    document.getElementById("app-id").value = id;
    document.getElementById("email-app").value = email;
  }

  function UpdateUserShow(id, name, user, pas){
    event.preventDefault();
    document.getElementById("upuser").style.display = "flex";
    document.getElementById("upid").value = id;
    document.getElementById("upname").value = name;
    document.getElementById("upusername").value = user;
    document.getElementById("uppassword").value = pas;
  }

  function UpdateUserHide(){
    event.preventDefault();
    document.getElementById("upuser").style.display = "none";
  }
  
  function DelAppHide(){
    event.preventDefault();
    document.getElementById("delapp").style.display = "none";
  }

  function DelAnnHide() {
    event.preventDefault();
    document.getElementById("delann").style.display = "none";
  }

  function UpdateAppHide(){
    event.preventDefault();
    document.getElementById("upapp").style.display = "none";
  }

  function DisplayDashboard(){
    document.getElementById("Dashboard").style.display = "block";
    document.getElementById("Announcement").style.display = "none";
    document.getElementById("Appointment").style.display = "none";
    document.getElementById("User").style.display = "none";
  }

  function DisplayAnnouncement(){
    document.getElementById("Dashboard").style.display = "none";
    document.getElementById("Announcement").style.display = "block";
    document.getElementById("Appointment").style.display = "none";
    document.getElementById("User").style.display = "none";
  }

  function DisplayAppointment(){
    document.getElementById("Dashboard").style.display = "none";
    document.getElementById("Appointment").style.display = "block";
    document.getElementById("Announcement").style.display = "none";
    document.getElementById("User").style.display = "none";
  }

  function DisplayUser(){
    document.getElementById("Dashboard").style.display = "none";
    document.getElementById("Appointment").style.display = "none";
    document.getElementById("User").style.display = "block";
    document.getElementById("Announcement").style.display = "none";
  }

  function CreateAnnouncement() {
    var title = $('#announce-title').val().trim();
    var desc = $('#announce-desc').val().trim();
    var author = $('#announce-author').val().trim();
    var date = $('#selected-date').val().trim();
    var dropAppointment = $('#drop_appointment').is(':checked') ? 'true' : 'false';
  
    $.ajax({
      type: 'POST',
      url: 'createann.php',
      data: {
        title: title,
        desc: desc,
        author: author,
        date: date,
        drop_appointment: dropAppointment
      },
      success: function(response) {
        $('#fetchAnnouncement').empty();
        $('#fetchAnnouncement').html(response);
        AnnouncePopHide();
      },
      error: function(error) {
        console.error('AJAX error:', error);
      }
    });
  }

  
  function DeleteAnnouncement() {
    var id = $('#del-id').val().trim();
   
    $.ajax({
      type: 'POST',
      url: 'delann.php',
      data: {
        id: id
      },
      success: function(response) {
        $('#fetchAnnouncement').empty();
        $('#fetchAnnouncement').html(response);
        DelAnnHide();
      },
      error: function(error) {
        console.error('AJAX error:', error);
      }
    });
  }

  function ChangeLimit(){
    var limit = $('#mySelect').val().trim();

    $.ajax({
        type: 'POST',
        url: 'applimit.php',
        data: {
          limit: limit
        },
        success: function(response) {
          $('#fetchAppointment').empty();
          $('#fetchAppointment').html(response);
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function SearchAppointment(){
    var limit = $('#mySelect').val().trim();
    var search = $('#searchapp').val().trim();
    $.ajax({
        type: 'POST',
        url: 'appsearch.php',
        data: {
          limit: limit,
          search: search
        },
        success: function(response) {
          $('#fetchAppointment').empty();
          $('#fetchAppointment').html(response);
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function DeleteApp(){
    var limit = $('#mySelect').val().trim();
    var appid = $('#app-id').val().trim();
    var email = $('#email-app').val().trim();
    $.ajax({
        type: 'POST',
        url: 'deleteapp.php',
        data: {
          limit: limit,
          appid: appid,
          email: email
        },
        success: function(response) {
          $('#fetchAppointment').empty();
          $('#fetchAppointment').html(response);
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function UpdateConstruct(){
    var limit = $('#mySelect').val().trim();
    var appid = $('#app-id').val().trim();
    var email = $('#email-app').val().trim();
    $.ajax({
        type: 'POST',
        url: 'deleteapp.php',
        data: {
          limit: limit,
          appid: appid,
          email: email
        },
        success: function(response) {
          $('#fetchAppointment').empty();
          $('#fetchAppointment').html(response);
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function UpdateAnnounce(){
    var limit = $('#mySelect').val().trim();
    var id = $('#id-app').val().trim();
    var email = $('#email-up').val().trim();
    var name = $('#name-app').val().trim();
    var office = $('#office-app').val().trim();
    var branch = $('#branch-app').val().trim();
    var date = $('#selected-date-2').val().trim();
    var time = $('#time-selection').val().trim();
    $.ajax({
        type: 'POST',
        url: 'appupdate.php',
        data: {
          limit: limit,
          id: id,
          email: email,
          name: name,
          office: office,
          branch: branch,
          date: date,
          time: time
        },
        success: function(response) {
          $('#fetchAppointment').empty();
          $('#fetchAppointment').html(response);
          UpdateAppHide();
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function UpdateAnnounce(){
    var limit = $('#mySelect').val().trim();
    var id = $('#id-app').val().trim();
    var email = $('#email-up').val().trim();
    var name = $('#name-app').val().trim();
    var office = $('#office-app').val().trim();
    var branch = $('#branch-app').val().trim();
    var date = $('#selected-date-2').val().trim();
    var time = $('#time-selection').val().trim();
    $.ajax({
        type: 'POST',
        url: 'appupdate.php',
        data: {
          limit: limit,
          id: id,
          email: email,
          name: name,
          office: office,
          branch: branch,
          date: date,
          time: time
        },
        success: function(response) {
          $('#fetchAppointment').empty();
          $('#fetchAppointment').html(response);
          UpdateAppHide();
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function CreateAccount(){
    var limit = $('#limit').val().trim();
    var name = $('#u-name').val().trim();
    var username = $('#username').val().trim();
    var password = $('#password').val().trim();
    var confirm = $('#cpassword').val().trim();
    $.ajax({
        type: 'POST',
        url: 'register.php',
        data: {
          limit: limit,
          name: name,
          username: username,
          password: password,
          confirm: confirm
        },
        success: function(response) {
          $('#fetchUser').empty();
          $('#fetchUser').html(response);
          CreateUserHide();
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function UserLimit(){
    var limit = $('#limit').val().trim();

    $.ajax({
        type: 'POST',
        url: 'userlimit.php',
        data: {
          limit: limit
        },
        success: function(response) {
            $('#fetchUser').empty();
            $('#fetchUser').html(response);
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function SearchUser(){
    var limit = $('#limit').val().trim();
    var search = $('#searchuser').val().trim();
    $.ajax({
        type: 'POST',
        url: 'usersearch.php',
        data: {
          limit: limit,
          search: search
        },
        success: function(response) {
            $('#fetchUser').empty();
            $('#fetchUser').html(response);
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function UpdateUser(){
    var limit = $('#limit').val().trim();
    var id = $('#upid').val().trim();
    var name = $('#upname').val().trim();
    var username = $('#upusername').val().trim();
    var password = $('#uppassword').val().trim();
    $.ajax({
        type: 'POST',
        url: 'userupdate.php',
        data: {
          limit: limit,
          id: id,
          name: name,
          username: username,
          password: password
        },
        success: function(response) {
            $('#fetchUser').empty();
            $('#fetchUser').html(response);
            UpdateUserHide();
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }

  function DeleteUser(){
    var limit = $('#limit').val().trim();
    var id = $('#userdelid').val().trim();

    $.ajax({
        type: 'POST',
        url: 'userdelete.php',
        data: {
          limit: limit,
          id: id,
        },
        success: function(response) {
            $('#fetchUser').empty();
            $('#fetchUser').html(response);
            DelUserHide();
        },
        error: function(error) {
          console.error('AJAX error:', error);
        }
      });
  }
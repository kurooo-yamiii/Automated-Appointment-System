
function ViewAppointment() {
  var email = $('#email-exist').val().trim();
  $.ajax({
    type: 'POST', 
    url: 'viewapp.php', 
    data: { email: email },
    success: function(response) {
     
      $('#fetch-view').empty();
          $('#fetch-view').html(response);
          document.getElementById("view").style.display = "flex";
          document.getElementById("email-exist").value = "";

    },
    error: function(error) {
    
      console.error('AJAX error:', error);
    }
  });
}

function updateOffices() {
  const branch = document.getElementById('branch-student').value;
  const officeSelect = document.getElementById('office-student');

  let options = '';

  if (branch === 'Mandaluyong Branch') {
      options += '<option value="MIS">MIS</option>';
      options += '<option value="Registrar">Registrar</option>';
  } else if (branch === 'Pasig Branch') {
      options += '<option value="EDP">EDP</option>';
      options += '<option value="Registrar">Registrar</option>';
  }

  officeSelect.innerHTML = options;
}

function updateOfficesEmployee() {
  const branchemploy = document.getElementById('branch-employee').value;
  const officeSelectemploy = document.getElementById('office-employee');

  let options = '';

  if (branchemploy === 'Mandaluyong Branch') {
      options += '<option value="MIS">MIS</option>';
      options += '<option value="Registrar">Registrar</option>';
  } else if (branchemploy === 'Pasig Branch') {
      options += '<option value="EDP">EDP</option>';
      options += '<option value="Registrar">Registrar</option>';
  }

  officeSelectemploy.innerHTML = options;
}

function updateOfficesGuest() {
  const branchguest = document.getElementById('branch-guest').value;
  const officeSelectguest = document.getElementById('office-guest');

  let options = '';

  if (branchguest === 'Mandaluyong Branch') {
      options += '<option value="MIS">MIS</option>';
      options += '<option value="Registrar">Registrar</option>';
  } else if (branchguest === 'Pasig Branch') {
      options += '<option value="EDP">EDP</option>';
      options += '<option value="Registrar">Registrar</option>';
  }

  officeSelectguest.innerHTML = options;
}



function hideStudentpop() {
    event.preventDefault();
    document.getElementById("popup").style.display = "none";
  }

  function disStudentpop(){
    event.preventDefault();
    document.getElementById("popup").style.display = "flex";
  }

  
function hideStudentpop2() {
    event.preventDefault();
    document.getElementById("employee").style.display = "none";
  }

  function disStudentpop2(){
    event.preventDefault();
    document.getElementById("employee").style.display = "flex";
  }

  function hideGuestpop() {
    event.preventDefault();
    document.getElementById("guest").style.display = "none";
}

function disGuestpop(){
    event.preventDefault();
    document.getElementById("guest").style.display = "flex";
}

function hideViewpop() {
  event.preventDefault();
  document.getElementById("view").style.display = "none";
}

function disViewpop(){
  event.preventDefault();
  document.getElementById("view").style.display = "flex";
}

// For Calendar
document.addEventListener('DOMContentLoaded', function() {
  const monthNameElement = document.getElementById('month-name');
  const daysElement = document.getElementById('days');
  const prevMonthButton = document.getElementById('prev-month');
  const nextMonthButton = document.getElementById('next-month');
  const selectedDateInput = document.getElementById('selected-date');
  const timeSelection = document.getElementById('time-selection');

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
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    // Get today's date without time
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Calculate the date for seven days from now
    const sevenDaysFromNow = new Date(today);
    sevenDaysFromNow.setDate(sevenDaysFromNow.getDate() + 7);

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
        dayDiv.textContent = i;

        // Check if the day is in the past or within the next seven days
        if (dateToCheck < today || dateToCheck <= sevenDaysFromNow) {
            dayDiv.classList.add('disabled');
        } else {
            // Add click event listener to non-disabled days
            (function(dayDiv, year, month, i) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                fetch(`restriction.php?date=${dateStr}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.isFull || data.restrictedDates.includes(dateStr)) {
                            dayDiv.classList.add('disabled');
                        } else {
                            dayDiv.addEventListener('click', () => {
                                fetchAvailableTimes(dateStr);
                                selectedDateInput.value = dateStr;
                            });
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

  function fetchAvailableTimes(date) {
    fetch(`availtime.php?date=${date}`)
      .then(response => response.json())
      .then(data => {
        // Clear previous options
        timeSelection.innerHTML = '';
        
        // Populate with new options
        data.availableTimes.forEach(time => {
          const option = document.createElement('option');
          option.value = time;
          option.textContent = time;
          timeSelection.appendChild(option);
        });
      })
      .catch(error => console.error('Error fetching available times:', error));
  }
});

function StudentRequest() {
  var branch = $('#branch-student').val().trim();
  var office= $('#office-student').val().trim();
  var email = $('#email-student').val().trim();
  var number = $('#student-number').val().trim();
  var contact = $('#contact-number-student').val().trim();
  var first = $('#first-name-student').val().trim();
  var last = $('#last-name-student').val().trim();
  var purpose = $('#purpose-student').val().trim();
  var date = $('#selected-date').val().trim();
  var time= $('#time-selection').val().trim();
  $.ajax({
    type: 'POST', 
    url: 'sttudapp.php', 
    data: { office: office, branch: branch, email: email, contact: contact, first: first, last: last, number: number, purpose: purpose, date: date, time: time },
    success: function() {
          AppoitnmentGuess(first, last, date, time, office, branch);
    },
    error: function() {
    
      window.location.href = 'index.php?error=You already have an appointment';
    }
  });
}

// Employee Callendar

document.addEventListener('DOMContentLoaded', function() {
  const monthNameElement = document.getElementById('month-name-employee');
  const daysElement = document.getElementById('days-employee');
  const prevMonthButton = document.getElementById('prev-month-employee');
  const nextMonthButton = document.getElementById('next-month-employee');
  const selectedDateInput = document.getElementById('selected-date-employee');
  const timeSelection = document.getElementById('time-selection-employee');

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
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    // Get today's date without time
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Calculate the date for seven days from now
    const sevenDaysFromNow = new Date(today);
    sevenDaysFromNow.setDate(sevenDaysFromNow.getDate() + 7);

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
        dayDiv.textContent = i;

        // Check if the day is in the past or within the next seven days
        if (dateToCheck < today || dateToCheck <= sevenDaysFromNow) {
            dayDiv.classList.add('disabled');
        } else {
            // Add click event listener to non-disabled days
            (function(dayDiv, year, month, i) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                fetch(`restriction.php?date=${dateStr}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.isFull || data.restrictedDates.includes(dateStr)) {
                            dayDiv.classList.add('disabled');
                        } else {
                            dayDiv.addEventListener('click', () => {
                                fetchAvailableTimes(dateStr);
                                selectedDateInput.value = dateStr;
                            });
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

  function fetchAvailableTimes(date) {
    fetch(`availtime.php?date=${date}`)
      .then(response => response.json())
      .then(data => {
        // Clear previous options
        timeSelection.innerHTML = '';
        
        // Populate with new options
        data.availableTimes.forEach(time => {
          const option = document.createElement('option');
          option.value = time;
          option.textContent = time;
          timeSelection.appendChild(option);
        });
      })
      .catch(error => console.error('Error fetching available times:', error));
  }
});

function EmployeeRequest() {
  var branch = $('#branch-employee').val().trim();
  var office= $('#office-employee').val().trim();
  var email = $('#email-employee').val().trim();
  var number = $('#employee-number').val().trim();
  var contact = $('#contact-number-employee').val().trim();
  var first = $('#first-name-employee').val().trim();
  var last = $('#last-name-employee').val().trim();
  var purpose = $('#purpose-employee').val().trim();
  var date = $('#selected-date-employee').val().trim();
  var time= $('#time-selection-employee').val().trim();
  $.ajax({
    type: 'POST', 
    url: 'employeeapp.php', 
    data: { office: office, branch: branch, email: email, contact: contact, first: first, last: last, number: number, purpose: purpose, date: date, time: time },
    success: function() {
          AppoitnmentGuess(first, last, date, time, office, branch);
    },
    error: function() {
    
      window.location.href = 'index.php?error=You already have an appointment';
    }
  });
}

// Guest Calendar

document.addEventListener('DOMContentLoaded', function() {
  const monthNameElement = document.getElementById('month-name-guest');
  const daysElement = document.getElementById('days-guest');
  const prevMonthButton = document.getElementById('prev-month-guest');
  const nextMonthButton = document.getElementById('next-month-guest');
  const selectedDateInput = document.getElementById('selected-date-guest');
  const timeSelection = document.getElementById('time-selection-guest');
  const generatePdfButton = document.getElementById('generate-pdf');

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
    const firstDay = new Date(year, month, 1).getDay();
    const lastDate = new Date(year, month + 1, 0).getDate();

    // Get today's date without time
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Calculate the date for seven days from now
    const sevenDaysFromNow = new Date(today);
    sevenDaysFromNow.setDate(sevenDaysFromNow.getDate() + 7);

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
        dayDiv.textContent = i;

        // Check if the day is in the past or within the next seven days
        if (dateToCheck < today || dateToCheck <= sevenDaysFromNow) {
            dayDiv.classList.add('disabled');
        } else {
            // Add click event listener to non-disabled days
            (function(dayDiv, year, month, i) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

                fetch(`restriction.php?date=${dateStr}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.isFull || data.restrictedDates.includes(dateStr)) {
                            dayDiv.classList.add('disabled');
                        } else {
                            dayDiv.addEventListener('click', () => {
                                fetchAvailableTimes(dateStr);
                                selectedDateInput.value = dateStr;
                            });
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

  
  renderCalendar(currentDate);

  function fetchAvailableTimes(date) {
      fetch(`availtime.php?date=${date}`)
          .then(response => response.json())
          .then(data => {
           
              timeSelection.innerHTML = '';

            
              data.availableTimes.forEach(time => {
                  const option = document.createElement('option');
                  option.value = time;
                  option.textContent = time;
                  timeSelection.appendChild(option);
              });

         
            
          })
          .catch(error => console.error('Error fetching available times:', error));
  }
});



function GuestRequest() {
  var branch = $('#branch-guest').val().trim();
  var office= $('#office-guest').val().trim();
  var email = $('#email-guest').val().trim();
  var contact = $('#contact-number-guest').val().trim();
  var first = $('#first-name-guest').val().trim();
  var last = $('#last-name-guest').val().trim();
  var type = $('#type-guest').val().trim();
  var identity= $('#identification-guest').val().trim();
  var purpose = $('#purpose-guest').val().trim();
  var date = $('#selected-date-guest').val().trim();
  var time= $('#time-selection-guest').val().trim();
  $.ajax({
    type: 'POST', 
    url: 'guestapp.php', 
    data: { office: office, branch: branch, email: email, contact: contact, first: first, last: last, type: type, identity: identity, purpose: purpose, date: date, time: time },
    success: function() {
          AppoitnmentGuess(first, last, date, time, office, branch);
    },
    error: function() {
    
      window.location.href = 'index.php?error=You already have an appointment';
    }
  });
}

function convertDate(fetchdate) {
  
  const date = new Date(fetchdate);
  
  const monthNames = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December'
  ];

  const year = date.getFullYear();
  const month = monthNames[date.getMonth()]; 
  const day = date.getDate();

  return `${month} ${day}, ${year}`;
}

function Today(date) {
  const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
  ];
  const monthIndex = date.getMonth();
  const day = date.getDate();
  const year = date.getFullYear();

  return `${months[monthIndex]} ${day}, ${year}`;
}

function AppoitnmentGuess(first, last, date, time, office, branch) {
  const convertedDate = convertDate(date);
  const UpdatedDate = new Date();
  const TodayDate = Today(UpdatedDate);
  const pdf = new jsPDF('p', 'mm', 'a4');

  const contentMargin = 25;
  let currentY = 40; 

  pdf.setFontSize(12);

  pdf.setFont("helvetica", "bold");
  pdf.text("Request Letter for Appointment", 75, currentY, { maxWidth: 160 });
  currentY += 15; 

  pdf.setFont("helvetica", "normal");
  pdf.text(`${TodayDate}`, contentMargin, currentY);
  currentY += 15; 

  pdf.text(`Mr/Ms. ${first} ${last}`, contentMargin, currentY);
  currentY += 10; 

  pdf.text("We are pleased to confirm your appointment with our office.", contentMargin, currentY, { maxWidth: 160 });
  currentY += 15; 

  pdf.setFont("helvetica", "bold");
  pdf.text("Appointment Details:", contentMargin, currentY, { maxWidth: 160 });
  currentY += 7; 

  pdf.setFont("helvetica", "normal");
  pdf.text(`Date: ${convertedDate}`, contentMargin, currentY, { maxWidth: 160 });
  currentY += 7; 

  pdf.text(`Time: ${time}`, contentMargin, currentY, { maxWidth: 160 });
  currentY += 7; 

  pdf.text(`Office: ${office}`, contentMargin, currentY, { maxWidth: 160 });
  currentY += 7; 

  pdf.text(`Branch: ${branch}`, contentMargin, currentY, { maxWidth: 160 });
  currentY += 15; 

  pdf.setFont("helvetica", "normal");

  const text1 = "Please make sure to bring any necessary documents and identification required for your appointment.";
  pdf.text(text1, contentMargin, currentY, { maxWidth: 160, align: "justify" });
  currentY += 20; 
  
  const text2 = "If you need to reschedule or have any questions, feel free to contact our office or via email at appointmentsys@rtu.edu.ph";
  pdf.text(text2, contentMargin, currentY, { maxWidth: 160, align: "justify" });
  currentY += 20; 

  pdf.text("Thank you for your cooperation. We look forward to assisting you.", contentMargin, currentY, { maxWidth: 160 });
  currentY += 20; 

  pdf.text("Sincerely,", contentMargin, currentY);
  currentY += 5; 
  pdf.text("Appointment System Team", contentMargin, currentY);

  const headerImage = new Image();
  headerImage.src = 'resources/header.PNG';

  const footerImage = new Image();
  footerImage.src = 'resources/footer.PNG';

  pdf.addImage(headerImage, 'PNG', 12, 5, 184, 29);
  pdf.addImage(footerImage, 'PNG', 12, pdf.internal.pageSize.height - 23, 180, 22);

  pdf.save("Appointment Information.pdf");

      window.location.href = 'index.php?success=Appointment scheduled successfully';
  
}

function printAppPDF(){
  var name = $('#name').val().trim();
  var date = $('#date').val().trim();
  var time = $('#time').val().trim();
  var office = $('#office').val().trim();
  var branch = $('#branch').val().trim();

  AppointmentView(name, date, time, office, branch);
}

function AppointmentView(name, date, time, office, branch) {
  const convertedDate = convertDate(date);
  const UpdatedDate = new Date();
  const TodayDate = Today(UpdatedDate);
  const pdf = new jsPDF('p', 'mm', 'a4');

  const contentMargin = 25;
  let currentY = 40; 

  pdf.setFontSize(12);

  pdf.setFont("helvetica", "bold");
  pdf.text("Request Letter for Appointment", 75, currentY, { maxWidth: 160 });
  currentY += 15; 

  pdf.setFont("helvetica", "normal");
  pdf.text(`${TodayDate}`, contentMargin, currentY);
  currentY += 15; 

  pdf.text(`Mr/Ms. ${name}`, contentMargin, currentY);
  currentY += 10; 

  pdf.text("We are pleased to confirm your appointment with our office.", contentMargin, currentY, { maxWidth: 160 });
  currentY += 15; 

  pdf.setFont("helvetica", "bold");
  pdf.text("Appointment Details:", contentMargin, currentY, { maxWidth: 160 });
  currentY += 7; 

  pdf.setFont("helvetica", "normal");
  pdf.text(`Date: ${convertedDate}`, contentMargin, currentY, { maxWidth: 160 });
  currentY += 7; 

  pdf.text(`Time: ${time}`, contentMargin, currentY, { maxWidth: 160 });
  currentY += 7; 

  pdf.text(`Office: ${office}`, contentMargin, currentY, { maxWidth: 160 });
  currentY += 7; 

  pdf.text(`Branch: ${branch}`, contentMargin, currentY, { maxWidth: 160 });
  currentY += 15; 

  pdf.setFont("helvetica", "normal");

  const text1 = "Please make sure to bring any necessary documents and identification required for your appointment.";
  pdf.text(text1, contentMargin, currentY, { maxWidth: 160, align: "justify" });
  currentY += 20; 
  
  const text2 = "If you need to reschedule or have any questions, feel free to contact our office or via email at servicestechflow@gmail.com";
  pdf.text(text2, contentMargin, currentY, { maxWidth: 160, align: "justify" });
  currentY += 20; 

  pdf.text("Thank you for your cooperation. We look forward to assisting you.", contentMargin, currentY, { maxWidth: 160 });
  currentY += 20; 

  pdf.text("Sincerely,", contentMargin, currentY);
  currentY += 5; 
  pdf.text("Techflow Team", contentMargin, currentY);

  const headerImage = new Image();
  headerImage.src = 'resources/header.PNG';

  const footerImage = new Image();
  footerImage.src = 'resources/footer.PNG';

  pdf.addImage(headerImage, 'PNG', 12, 5, 184, 29);
  pdf.addImage(footerImage, 'PNG', 12, pdf.internal.pageSize.height - 23, 180, 22);

  pdf.save("Appointment Information.pdf");

      window.location.href = 'index.php';
  
}
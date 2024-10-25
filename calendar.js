document.addEventListener('DOMContentLoaded', function() {
    const monthNameElement = document.getElementById('month-name1');
    const daysElement = document.getElementById('ds');
    const prevMonthButton = document.getElementById('prev-month1');
    const nextMonthButton = document.getElementById('next-month1');
    const selectedDateInput = document.getElementById('selected-date');
    
  
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
     
  
      // Create day elements
      for (let i = 1; i <= lastDate; i++) {
          const dateToCheck = new Date(year, month, i);
          const dayOfWeek = dateToCheck.getDay();
  
          // Skip Saturday (6) and Sunday (0)
          if (dayOfWeek === 0 || dayOfWeek === 6) {
              continue;
          }
  
          const dayDiv = document.createElement('div');
          dayDiv.className = 'dsy';
          dayDiv.textContent = i;
  
          // Check if the day is in the past or within the next seven days
          if (dateToCheck < today ) {
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
  
  });

  document.addEventListener('DOMContentLoaded', function() {
    const monthNameElement = document.getElementById('month-name-2');
    const daysElement = document.getElementById('ds-2');
    const prevMonthButton = document.getElementById('prev-month-2');
    const nextMonthButton = document.getElementById('next-month-2');
    const selectedDateInput = document.getElementById('selected-date-2');
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
          dayDiv.className = 'dsy';
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
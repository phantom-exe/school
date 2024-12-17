document.addEventListener('DOMContentLoaded', function() {
    // Calendar functionality
    const monthSelect = document.getElementById('monthSelect');
    const yearSelect = document.getElementById('yearSelect');
    const calendarGrid = document.getElementById('calendarGrid');
    const attendanceDetails = document.getElementById('attendanceDetails');

    // Initialize calendar
    function initializeCalendar() {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth();

        // Populate month select
        const months = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
        
        months.forEach((month, index) => {
            const option = document.createElement('option');
            option.value = index;
            option.textContent = month;
            if (index === currentMonth) option.selected = true;
            monthSelect.appendChild(option);
        });

        // Populate year select
        for (let year = currentYear - 2; year <= currentYear + 2; year++) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            if (year === currentYear) option.selected = true;
            yearSelect.appendChild(option);
        }

        // Initial calendar population
        populateCalendar();
    }

    // Add event listeners
    monthSelect?.addEventListener('change', populateCalendar);
    yearSelect?.addEventListener('change', populateCalendar);

    // Initialize if elements exist
    if (monthSelect && yearSelect) {
        initializeCalendar();
    }
});

// Add any additional JavaScript functions you need 
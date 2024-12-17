import './base.js';

document.addEventListener('DOMContentLoaded', () => {
    const timetable = [
        { time: '9:00 AM', mon: 'Math', tue: 'Science', wed: 'English', thu: 'History', fri: 'Art' },
        { time: '10:30 AM', mon: 'Science', tue: 'English', wed: 'Math', thu: 'PE', fri: 'Music' },
        { time: '1:00 PM', mon: 'History', tue: 'Math', wed: 'Science', thu: 'English', fri: 'Computer' },
        { time: '2:30 PM', mon: 'English', tue: 'Art', wed: 'PE', thu: 'Science', fri: 'Math' },
    ];

    const timetableGrid = document.getElementById('timetableGrid');

    // Add header
    const header = ['Time', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    header.forEach(day => {
        const cell = document.createElement('div');
        cell.className = 'timetable-header';
        cell.textContent = day;
        timetableGrid.appendChild(cell);
    });

    // Add timetable rows
    timetable.forEach(row => {
        const timeCell = document.createElement('div');
        timeCell.className = 'timetable-time';
        timeCell.textContent = row.time;
        timetableGrid.appendChild(timeCell);

        ['mon', 'tue', 'wed', 'thu', 'fri'].forEach(day => {
            const cell = document.createElement('div');
            cell.className = 'timetable-cell';
            cell.textContent = row[day];
            timetableGrid.appendChild(cell);
        });
    });
});
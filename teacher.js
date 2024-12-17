import './base.js';

document.addEventListener('DOMContentLoaded', () => {
    const dashboardData = {
        totalStudents: 120,
        averageAttendance: '95%',
        upcomingAssignments: 3,
        pendingGrades: 25
    };

    const classList = [
        { name: 'Math 101', time: 'Mon, Wed, Fri 9:00 AM', students: 30 },
        { name: 'Science 202', time: 'Tue, Thu 10:30 AM', students: 25 },
        { name: 'English Literature', time: 'Mon, Wed 1:00 PM', students: 35 },
        { name: 'History 301', time: 'Tue, Thu 2:30 PM', students: 30 },
    ];

    // Populate dashboard
    Object.keys(dashboardData).forEach(key => {
        const element = document.getElementById(key);
        if (element) {
            element.textContent = dashboardData[key];
        }
    });

    // Populate class list
    const classListContainer = document.getElementById('classList');
    classList.forEach(classItem => {
        const classElement = document.createElement('div');
        classElement.className = 'class-item';
        classElement.innerHTML = `
            <div class="class-info">
                <h3>${classItem.name}</h3>
                <p>${classItem.time} | ${classItem.students} students</p>
            </div>
            <div class="class-action">
                <button class="view-btn">View</button>
                <button class="edit-btn">Edit</button>
            </div>
        `;
        classListContainer.appendChild(classElement);
    });

    // Add event listeners for view and edit buttons
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Viewing class details');
        });
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Editing class details');
        });
    });
});
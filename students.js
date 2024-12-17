import './base.js';

document.addEventListener('DOMContentLoaded', () => {
    const students = [
        { name: 'John Doe', grade: '10th', id: '12345' },
        { name: 'Jane Smith', grade: '11th', id: '23456' },
        { name: 'Bob Johnson', grade: '9th', id: '34567' },
        { name: 'Alice Williams', grade: '12th', id: '45678' },
    ];

    const studentList = document.getElementById('studentList');

    students.forEach(student => {
        const studentCard = document.createElement('div');
        studentCard.className = 'student-card';
        studentCard.innerHTML = `
            <img src="https://i.pravatar.cc/60?u=${student.id}" alt="${student.name}" class="student-avatar">
            <div class="student-info">
                <h3>${student.name}</h3>
                <p>Grade: ${student.grade} | ID: ${student.id}</p>
            </div>
        `;
        studentList.appendChild(studentCard);
    });
});
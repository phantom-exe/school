import './base.js';
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const marks = [
        { subject: 'Math', score: 85 },
        { subject: 'Science', score: 92 },
        { subject: 'English', score: 78 },
        { subject: 'History', score: 88 },
        { subject: 'Art', score: 95 },
    ];

    const marksTable = document.getElementById('marksTable');
    let totalScore = 0;

    marks.forEach(item => {
        const row = marksTable.insertRow();
        const subjectCell = row.insertCell(0);
        const scoreCell = row.insertCell(1);
        
        subjectCell.textContent = item.subject;
        scoreCell.textContent = item.score;
        totalScore += item.score;
    });

    const averageRow = marksTable.insertRow();
    const averageSubjectCell = averageRow.insertCell(0);
    const averageScoreCell = averageRow.insertCell(1);
    
    averageSubjectCell.textContent = 'Average';
    averageSubjectCell.style.fontWeight = 'bold';
    averageScoreCell.textContent = (totalScore / marks.length).toFixed(2);
    averageScoreCell.style.fontWeight = 'bold';

    // Create chart
    const ctx = document.getElementById('marksChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: marks.map(item => item.subject),
            datasets: [{
                label: 'Score',
                data: marks.map(item => item.score),
                backgroundColor: 'rgba(74, 144, 226, 0.6)',
                borderColor: 'rgba(74, 144, 226, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
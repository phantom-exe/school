import './base.js';
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            datasets: [{
                label: 'Attendance Rate (%)',
                data: [95, 92, 88, 96, 90],
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
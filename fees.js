import './base.js';

document.addEventListener('DOMContentLoaded', () => {
    const feeItems = [
        { description: 'Tuition Fee', amount: 5000 },
        { description: 'Library Fee', amount: 500 },
        { description: 'Technology Fee', amount: 1000 },
        { description: 'Sports Fee', amount: 750 },
    ];

    const feeTable = document.getElementById('feeTable');
    let totalFees = 0;

    feeItems.forEach(item => {
        const row = feeTable.insertRow();
        const descCell = row.insertCell(0);
        const amountCell = row.insertCell(1);
        
        descCell.textContent = item.description;
        amountCell.textContent = `$${item.amount.toFixed(2)}`;
        totalFees += item.amount;
    });

    const totalRow = feeTable.insertRow();
    const totalDescCell = totalRow.insertCell(0);
    const totalAmountCell = totalRow.insertCell(1);
    
    totalDescCell.textContent = 'Total';
    totalDescCell.style.fontWeight = 'bold';
    totalAmountCell.textContent = `$${totalFees.toFixed(2)}`;
    totalAmountCell.style.fontWeight = 'bold';
});
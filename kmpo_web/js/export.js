// Экспортирование таблицы в Excel
document.getElementById('exportExcel').addEventListener('click', function () {
    const table = document.querySelector('table');
    const rows = table.querySelectorAll('tbody tr');
    const data = [];

    const headers = [];
    table.querySelectorAll('thead th').forEach(header => {
        headers.push(header.innerText);
    });
    data.push(headers);

    rows.forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach(cell => {
            rowData.push(cell.innerText);
        });
        data.push(rowData);
    });

    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Замены");

    XLSX.writeFile(wb, "Замены.xlsx");
});

// Экспортирование таблицы в PDF
document.getElementById('exportPDF').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const headers = [];
    document.querySelectorAll('thead th').forEach(header => {
        headers.push(header.innerText);
    });

    const data = [];
    document.querySelectorAll('tbody tr').forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach(cell => {
            rowData.push(cell.innerText);
        });
        data.push(rowData);
    });

    doc.autoTable({
        head: [headers],
        body: data,
        startY: 20, 
        theme: 'grid', 
        styles: { fontSize: 10 }, 
        headStyles: { fillColor: [189, 0, 57] }
    });

    doc.save("Замены.pdf");
});

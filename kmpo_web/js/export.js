// Экспортирование таблицы в Excel 
document.getElementById('exportExcel').addEventListener('click', function() {
    const table = document.querySelector('table');
    const data = [];
    
    // Добавляем строку с группировкой колонок
    const groupHeaders = [
        'Общие сведения', '', '', '', '', '', // 6 объединенных для "Общие сведения"
        'Было', '', '', '',    // 4 объединенных для "Было"
        'Стало', '', '', ''    // 4 объединенных для "Стало"
    ];
    data.push(groupHeaders);
    
    // Заголовки колонок
    const headers = [];
    table.querySelectorAll('thead tr:last-child th').forEach(header => {
        if (!header.classList.contains('hidden_tab')) {
            headers.push(header.innerText);
        }
    });
    data.push(headers);

    // Данные таблицы
    table.querySelectorAll('tbody tr').forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach(cell => {
            if (!cell.classList.contains('hidden_tab')) {
                rowData.push(cell.innerText.trim());
            }
        });
        data.push(rowData);
    });

    // Создаем книгу и лист
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(data);
    
    // Настраиваем ширину колонок
    ws['!cols'] = [
        { wch: 5 },  { wch: 12 }, { wch: 10 }, { wch: 25 },
        { wch: 20 }, { wch: 8 },  { wch: 20 }, { wch: 20 },
        { wch: 10 }, { wch: 10 }, { wch: 20 }, { wch: 20 },
        { wch: 10 }, { wch: 10 }
    ];
    
    // Добавляем автофильтр
    ws['!autofilter'] = { ref: XLSX.utils.encode_range({ s: {r:1,c:0}, e: {r:data.length,c:headers.length-1} }) };
    
    // Включаем перенос текста для всех ячеек
    const wrapStyle = { alignment: { wrapText: true } };
    for (let r = 0; r < data.length; r++) {
        for (let c = 0; c < headers.length; c++) {
            const cell = XLSX.utils.encode_cell({r,c});
            if (ws[cell]) {
                ws[cell].s = ws[cell].s || {};
                Object.assign(ws[cell].s, wrapStyle);
            }
        }
    }
    
    XLSX.utils.book_append_sheet(wb, ws, "Замены");
    const dateFilter = document.getElementById('dateFilter').value;
    XLSX.writeFile(wb, `Замены ${dateFilter || 'все'}.xlsx`);
});

document.getElementById('exportPDF').addEventListener('click', function() {

    
    Promise.all([]).then(() => {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('l', 'mm', 'a4');

        // Подключаем шрифт
        pdf.addFont('../fonts/Montserrat-Medium.ttf', 'Montserrat', 'normal');
        pdf.setFont('Montserrat', 'normal');
        
        // Заголовок документа
        const titleLines = [
            // "Отдел организации образовательного процесса,",
            // "учета студентов и отчетности КМПО РАНХиГС",
            // "",
            "Замены в учебном расписании КМПО РАНХиГС"
        ];
        
        pdf.setFontSize(12);
        const pageWidth = pdf.internal.pageSize.getWidth();
        let currentY = 20;
        
        titleLines.forEach(line => {
            if (line.trim() === "") {
                currentY += 5; // Дополнительный отступ для пустой строки
                return;
            }
            const textWidth = pdf.getStringUnitWidth(line) * 12 / pdf.internal.scaleFactor;
            pdf.text(line, ((pageWidth - textWidth) / 2) - 5, currentY);
            currentY += 5; // Межстрочный интервал
        });
        
        // Дата документа
        const dateFilter = document.getElementById('dateFilter').value;
        if (dateFilter) {
            pdf.setFontSize(10);
            pdf.text(`Дата: ${dateFilter}`, 15, currentY + 5);
            currentY += 15;
        }
        
        // Блок подписей в начале документа
        pdf.setFontSize(10);
        const leftColX = 15; // Левый край для "Подготовил"
        const rightColX = pageWidth - 75; // Правый край для "Согласовано"
        
        // // Левая колонка - Подготовил

        // pdf.text("ПОДГОТОВИЛ:", leftColX, currentY);
        // pdf.text("Начальник отдела организации", leftColX, currentY + 5);
        // pdf.text("Иванова А.А.", leftColX, currentY + 10);
        
        // // Правая колонка - Согласовано

        // pdf.text("СОГЛАСОВАНО:", rightColX, currentY);
        // pdf.text("Директор КМПО РАНХиГС", rightColX, currentY + 5);
        // pdf.text("Петров В.В.", rightColX, currentY + 10);
        
        // // Отступ перед таблицей
        // currentY += 20;
        
        // Подготовка данных таблицы с многоуровневыми заголовками
        const headers = [
            {content: 'Общие сведения', colSpan: 6, styles: {fillColor: '#6c757d', textColor: '#fff', halign: 'center'}},
            {content: 'Было', colSpan: 4, styles: {fillColor: '#d9534f', textColor: '#fff', halign: 'center'}},
            {content: 'Стало', colSpan: 4, styles: {fillColor: '#5cb85c', textColor: '#fff', halign: 'center'}}
        ];
        
        const subHeaders = [
            {content: 'ID', styles: {halign: 'center'}},
            {content: 'Дата', styles: {halign: 'center'}},
            {content: 'Группа', styles: {halign: 'center'}},
            {content: 'Тип замены', styles: {halign: 'center'}},
            {content: 'Причина', styles: {halign: 'center'}},
            {content: 'Согл.', styles: {halign: 'center'}},
            {content: 'Преподаватель', styles: {halign: 'center'}},
            {content: 'Дисциплина', styles: {halign: 'center'}},
            {content: 'Пара', styles: {halign: 'center'}},
            {content: 'Кабинет', styles: {halign: 'center'}},
            {content: 'Преподаватель', styles: {halign: 'center'}},
            {content: 'Дисциплина', styles: {halign: 'center'}},
            {content: 'Пара', styles: {halign: 'center'}},
            {content: 'Кабинет', styles: {halign: 'center'}}
        ];
        
        const rows = [];
        document.querySelectorAll('table tbody tr').forEach(row => {
            const rowData = [];
            row.querySelectorAll('td').forEach(cell => {
                rowData.push({content: cell.textContent.trim(), styles: {halign: 'center'}});
            });
            rows.push(rowData);
        });
        
        // Стилизация таблицы
        pdf.autoTable({
            head: [headers, subHeaders],
            body: rows,
            startY: currentY,
            styles: {
                font: 'Montserrat',
                fontSize: 8,
                cellPadding: 2,
                overflow: 'linebreak',
                valign: 'middle',
                fillColor: '#ffffff',
                halign: 'center'
            },
            headStyles: {
                fontStyle: 'bold',
                textColor: '#fff',
                halign: 'center'
            },
            columnStyles: {
                0: { cellWidth: 10, fillColor: '#f8f9fa' },
                1: { cellWidth: 15, fillColor: '#f8f9fa' },
                2: { cellWidth: 20, fillColor: '#f8f9fa' },
                3: { cellWidth: 20, fillColor: '#f8f9fa' },
                4: { cellWidth: 25, fillColor: '#f8f9fa' },
                5: { cellWidth: 10, fillColor: '#f8f9fa' },
                6: { cellWidth: 25, fillColor: '#f8d7da' }, 
                7: { cellWidth: 25, fillColor: '#f8d7da' },
                8: { cellWidth: 15, fillColor: '#f8d7da' },
                9: { cellWidth: 15, fillColor: '#f8d7da' },
                10: { cellWidth: 25, fillColor: '#d1e7dd' },
                11: { cellWidth: 25, fillColor: '#d1e7dd' },
                12: { cellWidth: 15, fillColor: '#d1e7dd' },
                13: { cellWidth: 15, fillColor: '#d1e7dd' }
            },
            didParseCell: function(data) {
                if (data.section === 'head' && data.row.index === 1) {
                    if (data.column.index < 6) {
                        data.cell.styles.fillColor = '#6c757d';
                        data.cell.styles.textColor = '#fff';
                    } 
                    else if (data.column.index < 10) {
                        data.cell.styles.fillColor = '#d9534f';
                        data.cell.styles.textColor = '#fff';
                    }
                    else {
                        data.cell.styles.fillColor = '#5cb85c';
                        data.cell.styles.textColor = '#fff';
                    }
                }
            }
        });
        
        pdf.save(`Замены_${dateFilter || 'все'}.pdf`);
    }).catch(error => {
        console.error('Error:', error);
        alert('Ошибка при создании PDF');
    });
});
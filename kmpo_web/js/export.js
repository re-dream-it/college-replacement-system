document.getElementById('exportExcel').addEventListener('click', function() {
    const table = document.querySelector('table');
    const data = [];
    
    // Строка группировки колонок
    const groupHeaders = [
        'Общие сведения', '', '', '', '', '',
        'Было', '', '', '',
        'Стало', '', '', ''
    ];
    data.push(groupHeaders);
    
    // Заголовки таблицы (вторая строка)
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
    
    // Настройка ширины колонок
    ws['!cols'] = [
        { wch: 5 },   // ID 
        { wch: 13 },  // Дата
        { wch: 15 },  // Группа
        { wch: 20 },  // Тип
        { wch: 10 },  // Причина
        { wch: 8 },   // Согл
        { wch: 20 },   // Препод (было)
        { wch: 25 },  // Дисциплина (было)
        { wch: 12 },   // Номер пары (было)
        { wch: 9 },   // Номер Кабинета (было)
        { wch: 20 },  // Препод (стало)
        { wch: 25 },   // Дисциплина (стало)
        { wch: 12 },   // Номер пары (стало)
        { wch: 9 }   // Номер Кабинета (стало)
    ];
    
    // Объединение ячеек
    ws['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 5 } }, // Общие сведения
        { s: { r: 0, c: 6 }, e: { r: 0, c: 9 } }, // Было
        { s: { r: 0, c: 10 }, e: { r: 0, c: 13 } } // Стало
    ];
    
    // Стили для заголовков
    const centerStyle = { alignment: { horizontal: 'center', vertical: 'center' } };
    const grayStyle = {
        fill: { patternType: "solid", fgColor: { rgb: "D3D3D3" } },
        font: { bold: true },
        alignment: { horizontal: 'center', vertical: 'center' }
    };
    const redHeaderStyle = {
        fill: { patternType: "solid", fgColor: { rgb: "FFC7CE" } },
        font: { bold: true, color: { rgb: "9C0006" } },
        alignment: { horizontal: 'center', vertical: 'center' }
    };
    const greenHeaderStyle = {
        fill: { patternType: "solid", fgColor: { rgb: "C6EFCE" } },
        font: { bold: true, color: { rgb: "006100" } },
        alignment: { horizontal: 'center', vertical: 'center' }
    };
    
    // Стили для данных
    const wrapStyle = { alignment: { wrapText: true } };
    const centerAlign = { alignment: { horizontal: 'center' } };
    const redDataStyle1 = {
        fill: { patternType: "solid", fgColor: { rgb: "FFEBEE" } }
    };
    const redDataStyle2 = {
        fill: { patternType: "solid", fgColor: { rgb: "FFCDD2" } }
    };
    const greenDataStyle1 = {
        fill: { patternType: "solid", fgColor: { rgb: "E8F5E9" } }
    };
    const greenDataStyle2 = {
        fill: { patternType: "solid", fgColor: { rgb: "C8E6C9" } }
    };
    const grayDataStyle1 = {
        fill: { patternType: "solid", fgColor: { rgb: "FAFAFA" } }
    };
    const grayDataStyle2 = {
        fill: { patternType: "solid", fgColor: { rgb: "F5F5F5" } }
    };
    
    // Применяем стили к заголовкам (первые 2 строки)
    for (let r = 0; r < 2; r++) {
        for (let c = 0; c < headers.length; c++) {
            const cellAddress = XLSX.utils.encode_cell({ r, c });
            ws[cellAddress] = ws[cellAddress] || { t: 's', v: data[r][c] || '' };
            
            if (c < 6) { // Общие сведения
                ws[cellAddress].s = { ...grayStyle, ...centerStyle };
            } else if (c < 10) { // Было
                ws[cellAddress].s = { ...redHeaderStyle, ...centerStyle };
            } else { // Стало
                ws[cellAddress].s = { ...greenHeaderStyle, ...centerStyle };
            }
        }
    }
    
    // Применяем стили к данным
    for (let r = 2; r < data.length; r++) {
        for (let c = 0; c < headers.length; c++) {
            const cellAddress = XLSX.utils.encode_cell({ r, c });
            ws[cellAddress] = ws[cellAddress] || { t: 's', v: data[r][c] || '' };
            
            // Базовый стиль для ячейки
            let cellStyle = {};
            
            // Определяем цвет фона
            if (c < 6) { // Общие сведения
                cellStyle = r % 2 === 0 ? grayDataStyle1 : grayDataStyle2;
            } else if (c < 10) { // Было
                cellStyle = r % 2 === 0 ? redDataStyle1 : redDataStyle2;
            } else { // Стало
                cellStyle = r % 2 === 0 ? greenDataStyle1 : greenDataStyle2;
            }
            

            // cellStyle.alignment = { horizontal: 'center' };
            cellStyle.alignment = { wrapText: true, horizontal: 'center', vertical: 'center'};

            
            ws[cellAddress].s = cellStyle;
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
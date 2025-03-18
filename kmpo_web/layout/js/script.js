document.addEventListener('DOMContentLoaded', function () {
    // Функция для фильтрации таблицы
    function filterTable() {
        const dateFilter = document.getElementById('dateFilter').value.toLowerCase();
        const groupFilter = document.getElementById('groupFilter').value.toLowerCase();
        const typeFilters = Array.from(document.querySelectorAll('input[name="typeFilter"]:checked')).map(checkbox => checkbox.value.toLowerCase());
        const teacherFilter = document.getElementById('teacherFilter').value.toLowerCase();
        const disciplineFilter = document.getElementById('disciplineFilter').value.toLowerCase();
        const pairFilter = document.getElementById('pairFilter').value.toLowerCase();

        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const date = row.cells[0].textContent.toLowerCase();
            const group = row.cells[1].textContent.toLowerCase();
            const type = row.cells[2].textContent.toLowerCase();
            const teacher = row.cells[3].textContent.toLowerCase();
            const discipline = row.cells[4].textContent.toLowerCase();
            const pair = row.cells[5].textContent.toLowerCase();

            if (
                date.includes(dateFilter) &&
                group.includes(groupFilter) &&
                (typeFilters.length === 0 || typeFilters.includes(type)) &&
                teacher.includes(teacherFilter) &&
                discipline.includes(disciplineFilter) &&
                pair.includes(pairFilter)
            ) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Добавляем обработчики событий на фильтры
    document.getElementById('dateFilter').addEventListener('input', filterTable);
    document.getElementById('groupFilter').addEventListener('input', filterTable);
    document.querySelectorAll('input[name="typeFilter"]').forEach(checkbox => {
        checkbox.addEventListener('change', filterTable);
    });
    document.getElementById('teacherFilter').addEventListener('input', filterTable);
    document.getElementById('disciplineFilter').addEventListener('input', filterTable);
    document.getElementById('pairFilter').addEventListener('input', filterTable);
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('replacementForm');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Сбор данных из формы
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        // Здесь можно добавить логику для отправки данных на сервер
        console.log('Данные для отправки:', data);

        // Очистка формы после отправки
        form.reset();
        alert('Замена успешно добавлена!');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const editForm = document.getElementById('editReplacementForm');

    editForm.addEventListener('submit', function (event) {
        event.preventDefault();

        // Сбор данных из формы
        const formData = new FormData(editForm);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        // Здесь можно добавить логику для отправки данных на сервер
        console.log('Данные для редактирования:', data);

        // Очистка формы после отправки (опционально)
        editForm.reset();
        alert('Изменения успешно сохранены!');
    });
});


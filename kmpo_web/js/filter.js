document.addEventListener('DOMContentLoaded', function () {
    const dateFilter = document.getElementById('dateFilter');
    const groupFilter = document.getElementById('groupFilter');
    const teacherFilter = document.getElementById('teacherFilter');
    const disciplineFilter = document.getElementById('disciplineFilter');
    const pairFilter = document.getElementById('pairFilter');
    const typeFilters = document.querySelectorAll('input[name="typeFilter"]');

    let replacementsData = [];

    // Функция для загрузки данных по дате
    async function loadReplacementsByDate(date) {
        try {
            const response = await fetch(`functions/get_replaces.php?date=${date}`);
            if (!response.ok) throw new Error('Ошибка при загрузке данных');
            replacementsData = await response.json();
            applyFilters(); 
        } catch (error) {
            console.error('Ошибка:', error);
        }
    }

    // Функция для применения фильтров
    function applyFilters() {
        if (replacementsData.length === 0) {
            const tbody = document.querySelector('table tbody');
            tbody.innerHTML = ''; 
            tbody.innerHTML = `<tr><td colspan="12" style="text-align: center;">Нет данных для отображения</td></tr>`;
            return;
        }

        const group = groupFilter.value.trim().toLowerCase();
        const teacher = teacherFilter.value.trim().toLowerCase();
        const discipline = disciplineFilter.value.trim().toLowerCase();
        const pair = pairFilter.value.trim();
        const selectedTypes = Array.from(typeFilters)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        // Фильтруем данные
        const filteredData = replacementsData.filter(replace => {
            const matchesGroup = group ? replace.group_name.toLowerCase().includes(group) : true;
            const matchesTeacher = teacher ? 
                (replace.was_teacher_fullname.toLowerCase().includes(teacher) || 
                 replace.became_teacher_fullname.toLowerCase().includes(teacher)) : true;
            const matchesDiscipline = discipline ? 
                (replace.was_discipline.toLowerCase().includes(discipline) || 
                 replace.became_discipline.toLowerCase().includes(discipline)) : true;
            const matchesPair = pair ? 
                (replace.was_slot_id === pair || replace.became_slot_id === pair) : true;
            const matchesType = selectedTypes.length > 0 ? 
                selectedTypes.some(type => replace.replacement_types.includes(type)) : true;

            return matchesGroup && matchesTeacher && matchesDiscipline && matchesPair && matchesType;
        });

        renderTable(filteredData);
    }

    // Функция для обновления таблицы
    function renderTable(data) {
        const tbody = document.querySelector('table tbody');
        tbody.innerHTML = ''; 

        if (data.length === 0) {
            // Если данных нет
            tbody.innerHTML = `<tr><td colspan="12" style="text-align: center;">Нет данных для отображения</td></tr>`;
            return;
        }

        // Наполнение таблицы
        data.forEach(replace => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${replace.replacement_id}</td>
                <td>${replace.date}</td>
                <td>${replace.group_name}</td>
                <td>${replace.replacement_types}</td>
                <td class="was">${replace.was_teacher_fullname}</td>
                <td class="was">${replace.was_discipline}</td>
                <td class="was">${replace.was_slot_id}</td>
                <td class="was">${replace.was_cabinet}</td>
                <td class="became">${replace.became_teacher_fullname}</td>
                <td class="became">${replace.became_discipline}</td>
                <td class="became">${replace.became_slot_id}</td>
                <td class="became">${replace.became_cabinet}</td>
            `;
            tbody.appendChild(row);
        });
    }

    // Загружаем данные для сегодняшней даты при загрузке страницы
    const today = new Date().toISOString().split('T')[0]; // Формат YYYY-MM-DD
    dateFilter.value = today; // Устанавливаем сегодняшнюю дату в поле
    loadReplacementsByDate(today);

    // Обработчики событий для фильтров
    dateFilter.addEventListener('change', function () {
        const selectedDate = dateFilter.value;
        if (selectedDate) {
            loadReplacementsByDate(selectedDate); 
        }
    });

    groupFilter.addEventListener('input', applyFilters);
    teacherFilter.addEventListener('input', applyFilters);
    disciplineFilter.addEventListener('input', applyFilters);
    pairFilter.addEventListener('input', applyFilters);
    typeFilters.forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const dateFilter = document.getElementById('dateFilter');
    const groupFilter = document.getElementById('groupFilter');
    const teacherFilter = document.getElementById('teacherFilter');
    const disciplineFilter = document.getElementById('disciplineFilter');
    const pairFilter = document.getElementById('pairFilter');
    const roomFilter = document.getElementById('roomFilter');
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
        const room = roomFilter.value.trim().toLowerCase();
        const selectedTypes = Array.from(typeFilters)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        // Фильтруем данные
        const filteredData = replacementsData.filter(replace => {
            const matchesGroup = group ? replace.group_name.toLowerCase().includes(group) : true;
            const matchesRoom = room ? 
            (replace.was_cabinet.toLowerCase().includes(room) ||
            replace.became_cabinet.toLowerCase().includes(room)) : true;
            console.log("Filter room:", room);
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

            return matchesGroup && matchesTeacher && matchesDiscipline && matchesPair && matchesType && matchesRoom;
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
            if (window.location.pathname == '/admin_replacements') {
                row.innerHTML += `<td class="delete-td"><button type="button" data-replacement-id="${replace.replacement_id}" class="delete-btn"><i class="fa-solid fa-trash"></i></button></td>`;
            }
            tbody.appendChild(row);
        });

        // Добавляем обработчики для кнопок удаления
        addDeleteHandlers();
    }

    // Функция для добавления обработчиков удаления
    function addDeleteHandlers() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', async function () {
                const replacementId = this.getAttribute('data-replacement-id');
                if (confirm('Вы уверены, что хотите удалить эту замену?')) {
                    await deleteReplacement(replacementId);
                    loadReplacementsByDate(dateFilter.value); // Перезагружаем данные
                }
            });
        });
    }

    // Функция для удаления замены
    async function deleteReplacement(replacementId) {
        try {
            const response = await fetch(`functions/delete_replacement.php?replacement_id=${replacementId}`, {
                method: 'DELETE'
            });
            if (!response.ok) throw new Error('Ошибка при удалении замены');
            const result = await response.json();
            alert(result.message);
        } catch (error) {
            console.error('Ошибка:', error);
        }
    }



    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1);
    const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
    dateFilter.value = tomorrowFormatted; 
    loadReplacementsByDate(tomorrowFormatted);

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
    roomFilter.addEventListener('input', applyFilters);
    typeFilters.forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });
});
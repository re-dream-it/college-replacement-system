document.addEventListener('DOMContentLoaded', function () {
    const dateFilter = document.getElementById('dateFilter');
    const groupFilter = document.getElementById('groupFilter');
    const teacherFilter = document.getElementById('teacherFilter');
    const disciplineFilter = document.getElementById('disciplineFilter');
    const pairFilter = document.getElementById('pairFilter');
    const roomFilter = document.getElementById('roomFilter');
    const typeFilters = document.querySelectorAll('input[name="typeFilter"]');
    const confirmedFilter = document.getElementById('confirmedFilter');


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
            if (window.location.pathname == '/admin_replacements') {
                tbody.innerHTML = `<tr><td colspan="13" style="text-align: center;">Нет данных для отображения</td></tr>`;
            }
            else if (window.location.pathname == '/replacements') {
                tbody.innerHTML = `<tr><td colspan="12" style="text-align: center;">Нет данных для отображения</td></tr>`;
            }
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
            // console.log(replace.confirmed);
            const matchesGroup = group ? replace.group_name.toLowerCase().includes(group) : true;
            const matchesRoom = room ? 
            (replace.was_cabinet.toLowerCase().includes(room) ||
            replace.became_cabinet.toLowerCase().includes(room)) : true;
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

            replace.confirmed_text = replace.confirmed == 1 ? "Да" : "Нет";

            if (window.location.pathname == '/admin_replacements') {
                const confirmed = confirmedFilter.value;
                const matchesConfirmed = confirmed !== 'all' ? replace.confirmed_text === confirmed : true;
                return matchesGroup && matchesTeacher && matchesDiscipline && matchesPair && matchesType && matchesRoom && matchesConfirmed;
            }
            else if (window.location.pathname == '/replacements') {
                return matchesGroup && matchesTeacher && matchesDiscipline && matchesPair && matchesType && matchesRoom;
            }

        });

        renderTable(filteredData);
    }

    // Функция для обновления таблицы
    function renderTable(data) {
        const tbody = document.querySelector('table tbody');
        tbody.innerHTML = '';

        if (data.length === 0) {
            // Если данных нет
            if (window.location.pathname == '/admin_replacements') {
                tbody.innerHTML = `<tr><td colspan="13" style="text-align: center;">Нет данных для отображения</td></tr>`;
            }
            else if (window.location.pathname == '/replacements') {
                tbody.innerHTML = `<tr><td colspan="12" style="text-align: center;">Нет данных для отображения</td></tr>`;
            }
            return;
        }

        // Наполнение таблицы
        data.forEach(replace => {
            const row = document.createElement('tr');
            if (window.location.pathname == '/admin_replacements') {
                    // console.log(replace.confirmed)
                    if (replace.confirmed == 1){
                        replace.confirmed_text = "Да"
                        conf_btn = ``;
                    }
                    else{
                        replace.confirmed_text = "Нет"
                        conf_btn = `<button type="button" data-replacement-id="${replace.replacement_id}" class="dropdown-actions-item confirm-btn">
                                        <i class="fa-solid fa-check"></i> Подтвердить
                                    </button>`                    
                    }
                    row.innerHTML = `
                    <td>${replace.replacement_id}</td>
                    <td>${replace.date}</td>
                    <td>${replace.group_name}</td>
                    <td>${replace.replacement_types}</td>
                    <td>${replace.reason}</td>
                    <td>${replace.confirmed_text}</td>
                    <td class="was">${replace.was_teacher_fullname}</td>
                    <td class="was">${replace.was_discipline}</td>
                    <td class="was">${replace.was_slot_id}</td>
                    <td class="was">${replace.was_cabinet}</td>
                    <td class="became">${replace.became_teacher_fullname}</td>
                    <td class="became">${replace.became_discipline}</td>
                    <td class="became">${replace.became_slot_id}</td>
                    <td class="became">${replace.became_cabinet}</td>
                    <td class="actions-td">
                        <div class="dropdown-actions">
                            <button class="dropdown-actions-toggle" type="button"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                            <div class="dropdown-actions-menu">
                                ${conf_btn}
                                <a href="edit_replacement?id=${replace.replacement_id}"><button type="button" data-replacement-id="${replace.replacement_id}" class="dropdown-actions-item edit-btn">
                                    <i class="fa-solid fa-pen-to-square"></i> Изменить
                                </button></a>
                                <button type="button" data-replacement-id="${replace.replacement_id}" class="dropdown-actions-item delete-btn">
                                    <i class="fa-solid fa-trash"></i> Удалить
                                </button>
                                <!-- Дополнительные кнопки можно добавить здесь -->
                            </div>
                        </div>
                    </td>
                    `;
                    
            }
            else if (window.location.pathname == '/replacements') {
                if (replace.confirmed != 0){
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
                }       
            }
            tbody.appendChild(row);
        });

        // Добавляем обработчики для кнопок удаления и подтверждения
        addDeleteHandlers();
        addConfirmHandlers();
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

    // Функция для добавления обработчика утверждения
    function addConfirmHandlers() {
        const confirmButtons = document.querySelectorAll('.confirm-btn');
        confirmButtons.forEach(button => {
            button.addEventListener('click', async function () {
                const replacementId = this.getAttribute('data-replacement-id');
                if (confirm('Вы уверены, что хотите утвердить эту замену?')) {
                    await confirmReplacement(replacementId);
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

    // Функция для удаления замены
    async function confirmReplacement(replacementId) {
        try {
            const response = await fetch(`functions/confirm_replacement.php`, {
                method: 'POST',
                body: JSON.stringify({'replacement_id': replacementId}),
            });
            if (!response.ok) throw new Error('Ошибка при удалении замены');
            const result = await response.json();
            alert(result.message);
        } catch (error) {
            console.error('Ошибка:', error);
        }
    }

    document.addEventListener('click', function(e) {

        const toggleBtn = e.target.closest('.dropdown-action-toggle');
        const clickedDropdown = toggleBtn ? toggleBtn.closest('.dropdown-action') : null;
        
        // Закрываем все меню, кроме текущего
        document.querySelectorAll('.dropdown-actions').forEach(dropdown => {
            const menu = dropdown.querySelector('.dropdown-actions-menu');
            if (dropdown !== clickedDropdown && menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        });

        // Показать/скрыть выпадающее меню
        if (e.target.closest('.dropdown-actions-toggle')) {
            const dropdown = e.target.closest('.dropdown-actions');
            const menu = dropdown.querySelector('.dropdown-actions-menu');
            menu.classList.toggle('show');
        }
        
        // Скрыть все выпадающие меню при клике вне
        if (!e.target.closest('.dropdown-actions')) {
            document.querySelectorAll('.dropdown-actions-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        }
        
    });

    // В начале загрузки страницы (после определения всех элементов)
    setInterval(() => {
        const selectedDate = dateFilter.value;
        if (selectedDate) {
            loadReplacementsByDate(selectedDate);
        }
    }, 3000);



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
    confirmedFilter.addEventListener('change', applyFilters);
});
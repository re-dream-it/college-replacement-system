// Dropdown для вариантов заполнения.
document.addEventListener('DOMContentLoaded', function () {

    // Загружаем данные для сегодняшней даты при загрузке страницы
    const dateField = document.getElementById('date') 

    const fields = ['group', 'oldTeacher', 'oldDiscipline', 'newTeacher', 'newDiscipline', 'oldRoom', 'newRoom'];

    fields.forEach(fieldId => {
        const input = document.getElementById(fieldId);
        const dropdown = document.createElement('div');
        dropdown.className = 'autocomplete-dropdown hidden';
        input.parentNode.appendChild(dropdown);

        input.addEventListener('input', async function () {
            const query = input.value.trim();
            if (query.length < 2) {
                dropdown.classList.add('hidden')
                dropdown.innerHTML = '';
                return;
            }
            else{
                dropdown.classList.remove('hidden')
            }

            try {
                let response;
                if (fieldId === 'oldDiscipline' || fieldId === 'newDiscipline') {
                    const group = document.getElementById('group').value;
                    response = await fetch(`functions/get_suggestions.php?field=${fieldId}&query=${query}&group=${group}`);
                } else{
                    response = await fetch(`functions/get_suggestions.php?field=${fieldId}&query=${query}`);
                }
                
                if (!response.ok) throw new Error('Ошибка при загрузке данных');
                const data = await response.json(); 

                dropdown.innerHTML = '';
                data.forEach(item => {
                    const option = document.createElement('div');
                    option.className = 'autocomplete-option';
                    option.textContent = item;
                    option.addEventListener('click', function () {
                        input.value = item;
                        dropdown.innerHTML = '';
                    });
                    dropdown.appendChild(option);
                });
            } catch (error) {
                console.error('Ошибка:', error);
            }

            // Закрываем dropdown при потере фокуса
            input.addEventListener('blur', function() {
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 500);
            });
        });

        // Функция для перехода к следующему полю
        function moveToNextField(currentField) {
            const allInputs = Array.from(document.querySelectorAll('input:not([type="checkbox"]), select, textarea'));
            const currentIndex = allInputs.indexOf(currentField);
            
            if (currentIndex < allInputs.length - 1) {
                allInputs[currentIndex + 1].focus();
            }
        }

        // Обработка нажатия Enter для переключения между полями
        document.querySelectorAll('input:not([type="checkbox"]), select, textarea').forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    
                    // Если открыто автодополнение, выбираем первый вариант
                    const dropdown = this.parentNode.querySelector('.autocomplete-dropdown');
                    if (dropdown && !dropdown.classList.contains('hidden')) {
                        const firstOption = dropdown.querySelector('.autocomplete-option');
                        if (firstOption) {
                            this.value = firstOption.textContent;
                            dropdown.classList.add('hidden');
                            dropdown.innerHTML = '';
                        }
                    }
                    
                    moveToNextField(this);
                }
            });
        });
        });

});
// Обрабтка данных формы
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('replacementForm');

    // Проверка корректности заполненных данных
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const fieldsToCheck = [
            { id: 'group', table: 'groups', field: 'name', rus: 'Группа' },
            { id: 'oldTeacher', table: 'teachers', field: 'name', rus: '[Было] Преподаватель' },
            { id: 'oldDiscipline', table: 'disciplines', field: 'name', rus: '[Было] Дисциплина'  },
            { id: 'newTeacher', table: 'teachers', field: 'name', rus: '[Стало] Преподаватель'   },
            { id: 'newDiscipline', table: 'disciplines', field: 'name', rus: '[Стало] Дисциплина'   },
            { id: 'oldPair', table: 'slots', field: 'id', rus: '[Было] Пара' },
            { id: 'newPair', table: 'slots', field: 'id', rus: '[Стало] Пара' },
            { id: 'oldRoom', table: 'rooms', field: 'number', rus: '[Было] Кабинет' },
            { id: 'newRoom', table: 'rooms', field: 'number', rus: '[Стало] Кабинет' },
        ];

        let isValid = true;

        const date = document.getElementById('date').value.trim();
        today = new Date().toISOString().split('T')[0];
        if (date < today) {
            alert('Дата не может быть раньше текущей.');
            isValid = false;
        }

        for (const field of fieldsToCheck) {
            const input = document.getElementById(field.id);
            const value = input.value.trim();

            if (value) {
                const response = await fetch(`functions/check_value.php?table=${field.table}&field=${field.field}&value=${value}`);
                const exists = await response.json();

                if (!exists) {
                    alert(`Значение "${value}" не найдено в базе данных для поля "${field.rus}".`);
                    isValid = false;
                    break;
                }
            }
        }

        const newPair = document.getElementById('newPair').value
        const group = document.getElementById('group').value
        const rep_id = new URLSearchParams(window.location.search).get("id");

        // Проверка занятости кабинета.
        if (!isValid) {return;}
        const newRoom = document.getElementById('newRoom').value
        let response = await fetch(`functions/check_replace.php?type=room&value=${newRoom}&date=${date}&newPair=${newPair}`);
        let exists = await response.json();
        console.log(exists)
        if (exists && (exists['replacement_id'] != rep_id)) {
            const isConfirmedRoom = confirm(`Кабинет ${newRoom} уже занят следующей парой:\n\nДата и время: ${date}, ${exists['slot_id']} пара\nГруппа: ${exists['name']}\nДисциплина: ${exists['discipline_name']}\nПреподаватель: ${exists['teacher_fullname']}\n\nВы уверены, что хотите продолжить и поставить эту замену для группы ${group}?`);
            isValid = isConfirmedRoom;
        }

        // Проверка занятости преподавателя.
        if (!isValid) {return;}
        const newTeacher = document.getElementById('newTeacher').value
        response = await fetch(`functions/check_replace.php?type=teacher&value=${newTeacher}&date=${date}&newPair=${newPair}`);
        exists = await response.json();
        console.log(exists)
        if (exists && (exists['replacement_id'] != rep_id)) {
            const isConfirmedTeacher = confirm(`Преподаватель ${exists['teacher_fullname']} уже занят следующей парой:\n\nДата и время: ${date}, ${exists['slot_id']} пара\nГруппа: ${exists['name']}\nДисциплина: ${exists['discipline_name']}\nКабинет: ${newRoom}\n\nВы уверены, что хотите продолжить и поставить эту замену для группы ${group}?`);
            isValid = isConfirmedTeacher;
        }

        // Проверка связи дисциплины и группы поля "Было".
        if (!isValid) {return;}
        const oldDiscipline = document.getElementById('oldDiscipline').value
        if(oldDiscipline != '') {
            response = await fetch(`functions/check_replace.php?type=discipline_relation&discipline=${oldDiscipline}&group=${group}`);
            exists = await response.json();
            console.log(exists)
            if (!exists) {
                alert(`[Было] Дисциплины "${oldDiscipline}" нет в учебной нагрузке группы "${group}".`);
                isValid = false;
            }
        }

        // Проверка связи дисциплины и группы поля "Стало".
        if (!isValid) {return;}
        const newDiscipline = document.getElementById('newDiscipline').value
        if(newDiscipline != '') {
            response = await fetch(`functions/check_replace.php?type=discipline_relation&discipline=${newDiscipline}&group=${group}`);
            exists = await response.json();
            console.log(exists)
            if (!exists) {
                alert(`[Стало] Дисциплины "${newDiscipline}" нет в учебной нагрузке группы "${group}".`);
                isValid = false;
            }
        }

        if (isValid) {
            submitForm();
        }
    });

    // Отправка данных формы на файл-обработчик
    async function submitForm() {
        const formData = new FormData(form);

        const rep_id = new URLSearchParams(window.location.search).get("id");
        formData.append('id', rep_id);

        try {
            const response = await fetch('functions/edit_replacement.php', {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) throw new Error('Ошибка при отправке данных');
            const result = await response.json();

            if (result.success) {
                alert('Замена успешно изменена!');
                document.location.href = '/admin_replacements'
            } else {
                alert('Ошибка: ' + result.message);
            }
        } catch (error) {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при отправке формы.');
        }
    }

});
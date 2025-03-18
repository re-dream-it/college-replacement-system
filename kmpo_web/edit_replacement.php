<? include "head.php"; ?>
<? include "header.php"; ?>
<body>
    <!-- Основной контент -->
    <main>
        <h1>Редактирование замены</h1>

        <!-- Форма для редактирования замены -->
        <form id="editReplacementForm">
            <div class="form-group">
                <label for="editDate"><b>ID замены:</b> 21</label>
            </div>
            <div class="form-group">
                <label for="editDate">Дата:</label>
                <input type="date" id="editDate" name="date" value="2025-03-12" required>
            </div>

            <div class="form-group">
                <label for="editGroup">Группа:</label>
                <input type="text" id="editGroup" name="group" value="101-СА-23" placeholder="Например: 201-СА-23" required>
            </div>

            <div class="form-group-cb">
                <label for="editType">Тип замены:</label>
                <div class="dropdown-checkbox">
                    <button type="button" class="dropdown-toggle">Выберите типы замен</button>
                    <div class="dropdown-content">
                        <label><input class="cb" type="checkbox" name="type" value="teacher"> Замена преподавателя</label>
                        <label><input class="cb" type="checkbox" name="type" value="discipline"> Замена дисциплины</label>
                        <label><input class="cb" type="checkbox" name="type" value="cancel"> Отмена занятия</label>
                        <label><input class="cb" type="checkbox" name="type" value="remote"> Замена на дистанционный формат</label>
                    </div>
                </div>
            </div>

            <div class="form-group form-title"><b>Было:</b></div>

            <div class="form-group">
                <label for="editOldTeacher">Преподаватель:</label>
                <input type="text" id="editOldTeacher" name="oldTeacher" value="Малькова Т.А." placeholder="Например: Иванов И.И." required>
            </div>

            <div class="form-group">
                <label for="editOldDiscipline">Дисциплина:</label>
                <input type="text" id="editOldDiscipline" name="oldDiscipline" value="СО.02.01 Математика" placeholder="Например: СО.02.01 Математика" required>
            </div>

            <div class="form-group">
                <label for="editOldPair">Номер пары:</label>
                <input type="number" id="editOldPair" name="oldPair" value="2" placeholder="Например: 2" required>
            </div>

            <div class="form-group">
                <label for="editOldRoom">Кабинет:</label>
                <input type="text" id="editOldRoom" name="oldRoom" value="412" placeholder="Например: 412" required>
            </div>

            <div class="form-group form-title"><b>Стало:</b></div>

            <div class="form-group">
                <label for="editNewTeacher">Преподаватель:</label>
                <input type="text" id="editNewTeacher" name="newTeacher" value="Пяткина М.А." placeholder="Например: Петров П.П.">
            </div>

            <div class="form-group">
                <label for="editNewDiscipline">Дисциплина:</label>
                <input type="text" id="editNewDiscipline" name="newDiscipline" value="СО.01.04 История России (общ)" placeholder="Например: СО.01.04 История России (общ)">
            </div>

            <div class="form-group">
                <label for="editNewPair">Номер пары:</label>
                <input type="number" id="editNewPair" name="newPair" value="2" placeholder="Например: 2">
            </div>

            <div class="form-group">
                <label for="editNewRoom">Кабинет:</label>
                <input type="text" id="editNewRoom" name="newRoom" value="412" placeholder="Например: 412">
            </div>
            
            <div class="form-group-cb">
                <label>Согласовано: <input class="cb" type="checkbox" name="type" value="confirmed"></label>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Сохранить изменения</button>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Удалить замену</button>
            </div>

        </form>
    </main>
</body>
<? include "footer.php"; ?>
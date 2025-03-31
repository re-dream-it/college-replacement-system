<? include "head.php"; ?>
<? include "header.php"; ?>
<body>
    <!-- Основной контент -->
    <main>
        <h1>Внесение замены</h1>

        <!-- Форма для внесения замены -->
        <form id="replacementForm">
            <!-- Основные сведения -->
            <div class="form-group form-title"><b>Общие сведения:</b></div>
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Дата:</label>
                    <input autocomplete="off" type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="group">Группа:</label>
                    <input autocomplete="off" type="text" id="group" name="group" placeholder="Например: 201-СА-23" required>
                </div>

                <div class="form-group">
                    <label for="groupPart">Подгруппа:</label>
                    <input autocomplete="on" type="text" id="groupPart" name="groupPart" placeholder="Заполнить при необходимости">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="reason">Причина:</label>
                    <input autocomplete="on" type="text" id="reason" name="reason" placeholder="Причина замены" requierd>
                </div>
            </div>

            <div class="form-columns">
                <!-- Левая колонка - Было -->
                <div class="form-column">
                    <div class="form-group form-title"><b>Было:</b></div>

                    <div class="form-group">
                        <label for="oldTeacher">Преподаватель:</label>
                        <input autocomplete="off" type="text" id="oldTeacher" name="oldTeacher" placeholder="Например: Иванов И.И.">
                    </div>

                    <div class="form-group">
                        <label for="oldDiscipline">Дисциплина:</label>
                        <input autocomplete="off" type="text" id="oldDiscipline" name="oldDiscipline" placeholder="Например: СО.02.01 Математика">
                    </div>

                    <div class="form-group">
                        <label for="oldPair">Номер пары:</label>
                        <input autocomplete="off" type="number" id="oldPair" name="oldPair" placeholder="Например: 2">
                    </div>

                    <div class="form-group">
                        <label for="oldRoom">Кабинет:</label>
                        <input autocomplete="off" type="text" id="oldRoom" name="oldRoom" placeholder="Например: 412">
                    </div>
                </div>

                <!-- Правая колонка - Стало -->
                <div class="form-column">
                    <div class="form-group form-title"><b>Стало:</b></div>

                    <div class="form-group">
                        <label for="newTeacher">Преподаватель:</label>
                        <input autocomplete="off" type="text" id="newTeacher" name="newTeacher" placeholder="Например: Петров П.П.">
                    </div>

                    <div class="form-group">
                        <label for="newDiscipline">Дисциплина:</label>
                        <input autocomplete="off" type="text" id="newDiscipline" name="newDiscipline" placeholder="Например: СО.01.04 История России (общ)">
                    </div>

                    <div class="form-group">
                        <label for="newPair">Номер пары:</label>
                        <input autocomplete="off" type="number" id="newPair" name="newPair" placeholder="Например: 2">
                    </div>

                    <div class="form-group">
                        <label for="newRoom">Кабинет:</label>
                        <input autocomplete="off" type="text" id="newRoom" name="newRoom" placeholder="Например: 412">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Добавить замену</button>
            </div>
        </form>
    </main>
</body>
<? include "footer.php"; ?>

<script src="js/add_replace.js"></script>
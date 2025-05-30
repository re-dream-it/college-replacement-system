
<? include "head.php"; ?>
<? include "header.php"; ?>
<body>
    <main>
        <h1>Просмотр замен</h1>

        <div class="filters">
            <input required type="date" id="dateFilter">
            <div class="dropdown-checkbox">
                <button type="button" class="dropdown-toggle">Тип замены</button>
                <div class="dropdown-content">
                    <? foreach ($DB->getTypes() as $type): ?>
                        <label><input type="checkbox" name="typeFilter" value="<?= $type['name'] ?>"> <?= $type['name'] ?></label>
                    <? endforeach; ?>
                </div>
            </div>

            <div class="dropdown-select">
                <select id="introducedFilter">
                    <option value="all">Внесена (все)</option>
                    <option value="Да">Внесенные</option>
                    <option value="Нет">Не внесенные</option>
                </select>
            </div>

            <input type="text" id="groupFilter" placeholder="Группа (например: 201-СА-23)">
        </div>

        <div class="filters">
            <div class="dropdown-select">
                <select id="confirmedFilter">
                    <option value="all">Согласованность (все)</option>
                    <option value="Да">Согласована</option>
                    <option value="Нет">Не согласована</option>
                </select>
            </div>
            <input type="text" id="teacherFilter" placeholder="Фамилия преподавателя (например: Арзуманян)">
            <input type="text" id="disciplineFilter" placeholder="Код дисциплины / Дисциплина">
            <input type="text" id="pairFilter" placeholder="Номер пары">
            <input type="text" id="roomFilter" placeholder="Кабинет (например: К612)">

        </div>

        
        <div class="export-buttons">
            <button type="button" id="exportExcel" class="export-btn">
                <i class="fas fa-file-excel"></i> 
            </button>
            <button type="button" id="exportPDF" class="export-btn">
                <i class="fas fa-file-pdf"></i> 
            </button>
        </div>

        <table>
            <thead>
                <tr>
                    <th colspan="7" class="left">Общ. сведения</th>
                    <th colspan="4" class="was-header">Было</th>
                    <th colspan="4" class="became-header right">Стало</th>
                    <th class="hidden_tab"></th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Дата</th>
                    <th>Внесена</th>
                    <th>Группа</th>
                    <th>Тип замены</th>
                    <th>Причина</th>
                    <th>Согл.</th>
                    <th class="was">Преподаватель</th>
                    <th class="was">Дисциплина</th>
                    <th class="was">Номер пары</th>
                    <th class="was">Кабинет</th>
                    <th class="became">Преподаватель</th>
                    <th class="became">Дисциплина</th>
                    <th class="became">Номер пары</th>
                    <th class="became">Кабинет</th>
                    <th class="hidden_tab"></th>
                </tr>

            </thead>
            <tbody>       
                <!-- Данные будут добавляться сюда динамически (filter.js) -->
            </tbody>
        </table>
    </main>
</body>


<script src="js/filter.js?v2.0"></script>
<script src="js/export.js?v=2.0"></script>
<? include "footer.php"; ?>
                

<? include "head.php"; ?>
<?php include "public_header.php"; ?>

<body>
    <main>
        <h1>Просмотр замен</h1>

        <div class="filters">
            <div class="dropdown-checkbox">
                <button type="button" class="dropdown-toggle">Тип замены</button>
                <div class="dropdown-content">
                    <? foreach ($DB->getTypes() as $type): ?>
                        <label><input type="checkbox" name="typeFilter" value="<?= $type['name'] ?>"> <?= $type['name'] ?></label>
                    <? endforeach; ?>
                </div>
            </div>
            <input required type="date" id="dateFilter">
            <input type="text" id="groupFilter" placeholder="Группа (например: 201-СА-23)">
            <input type="text" id="roomFilter" placeholder="Кабинет (например: К612)">
        </div>

        <div class="filters">
            <input type="text" id="teacherFilter" placeholder="Преподаватель">
            <input type="text" id="disciplineFilter" placeholder="Код дисциплины / Дисциплина">
            <input type="text" id="pairFilter" placeholder="Номер пары">
        </div>
        <table>
            <thead>
                <tr>
                    <th colspan="4" class="left">Общ. сведения</th>
                    <th colspan="4" class="was-header">Было</th>
                    <th colspan="4" class="became-header right">Стало</th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Дата</th>
                    <th>Группа</th>
                    <th>Тип замены</th>
                    <th class="was">Преподаватель</th>
                    <th class="was">Дисциплина</th>
                    <th class="was">Номер пары</th>
                    <th class="was">Кабинет</th>
                    <th class="became">Преподаватель</th>
                    <th class="became">Дисциплина</th>
                    <th class="became">Номер пары</th>
                    <th class="became">Кабинет</th>
                </tr>
            </thead>
            <tbody>
                <!-- Данные будут добавляться сюда динамически -->
            </tbody>
        </table>
    </main>
</body>

<script src="js/filter.js"></script>
<? include "footer.php"; ?>
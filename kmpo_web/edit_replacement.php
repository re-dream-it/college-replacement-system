<? include "head.php"; ?>
<? include "header.php"; ?>


<?
$replacement_id = $_GET['id'];
$replacement = $DB->getReplace($replacement_id);
?>

<body>
    <!-- Основной контент -->
    <main>
        <h1>Изменение замены №<?=$replacement_id?></h1>

        <!-- Форма для изменения замены -->
        <form id="replacementForm">
            <!-- Основные сведения -->
            <div class="form-group form-title"><b>Общие сведения:</b></div>
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Дата:</label>
                    <input autocomplete="off" type="date" id="date" name="date" value="<?=$replacement['date']?>" required tabindex="1">
                </div>

                <div class="form-group">
                    <label for="group">Группа:</label>
                    <input autocomplete="off" type="text" id="group" name="group" placeholder="Например: 201-СА-23" value="<?=$replacement['group_name']?>" required tabindex="2">
                </div>

                <div class="form-group">
                    <label for="groupPart">Подгруппа:</label>
                    <input autocomplete="on" type="text" id="groupPart" name="groupPart" placeholder="Заполнить при необходимости" value="<?=$replacement['group_part']?>" tabindex="3">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="reason">Причина:</label>
                    <input autocomplete="on" type="text" id="reason" name="reason" placeholder="Причина замены" value="<?=$replacement['reason']?>" tabindex="4">
                </div>
            </div>

            <div class="form-columns">
                <!-- Левая колонка - Было -->
                <div class="form-column">
                    <div class="form-group form-title"><b>Было:</b></div>

                    <div class="form-group">
                        <label for="oldTeacher">Преподаватель:</label>
                        <input autocomplete="off" type="text" id="oldTeacher" name="oldTeacher" placeholder="Например: Иванов И.И." value="<?=$replacement['was_teacher_fullname']?>" tabindex="5">
                    </div>

                    <div class="form-group">
                        <label for="oldDiscipline">Дисциплина:</label>
                        <input autocomplete="off" type="text" id="oldDiscipline" name="oldDiscipline" placeholder="Например: СО.02.01 Математика" value="<?=$replacement['was_discipline']?>" tabindex="7">
                    </div>

                    <div class="form-group">
                        <label for="oldPair">Номер пары:</label>
                        <input autocomplete="off" type="number" id="oldPair" name="oldPair" placeholder="Например: 2" value="<?=$replacement['was_slot_id']?>" tabindex="9">
                    </div>

                    <div class="form-group">
                        <label for="oldRoom">Кабинет:</label>
                        <input autocomplete="off" type="text" id="oldRoom" name="oldRoom" placeholder="Например: 412" value="<?=$replacement['was_cabinet']?>" tabindex="11">
                    </div>
                </div>

                <!-- Правая колонка - Стало -->
                <div class="form-column">
                    <div class="form-group form-title"><b>Стало:</b></div>

                    <div class="form-group">
                        <label for="newTeacher">Преподаватель:</label>
                        <input autocomplete="off" type="text" id="newTeacher" name="newTeacher" placeholder="Например: Петров П.П." value="<?=$replacement['became_teacher_fullname']?>" tabindex="6">
                    </div>

                    <div class="form-group">
                        <label for="newDiscipline">Дисциплина:</label>
                        <input autocomplete="off" type="text" id="newDiscipline" name="newDiscipline" placeholder="Например: СО.01.04 История России (общ)"  value="<?=$replacement['became_discipline']?>" tabindex="8">
                    </div>

                    <div class="form-group">
                        <label for="newPair">Номер пары:</label>
                        <input autocomplete="off" type="number" id="newPair" name="newPair" placeholder="Например: 2" value="<?=$replacement['became_slot_id']?>" tabindex="10">
                    </div>

                    <div class="form-group">
                        <label for="newRoom">Кабинет:</label>
                        <input autocomplete="off" type="text" id="newRoom" name="newRoom" placeholder="Например: 412" value="<?=$replacement['became_cabinet']?>" tabindex="12">
                    </div>
                </div>
            </div>



            <div class="form-group">
                <button type="submit" class="submit-btn">Изменить замену</button>
            </div>
        </form>
    </main>
</body>
<? include "footer.php"; ?>

<script src="js/edit_replace.js"></script>

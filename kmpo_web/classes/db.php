<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'db_init.php';

class WebDatabase extends DataBase
{
    // Получение списка замен
    public function getReplaces($date){
        $statement = $this->pdo->prepare("SELECT 
            -- Основные сведения
            r.id AS replacement_id,
            r.date,
            g.name AS group_name,
            
            -- Было
            COALESCE(GROUP_CONCAT(ty.name SEPARATOR ', '), '') AS replacement_types,
            COALESCE(CONCAT(LEFT(t1.name, 1), '. ', LEFT(t1.surname, 1), '. ', t1.lastname), '') AS was_teacher_fullname,
            COALESCE(CONCAT(d1.code, ' ', d1.name), '') AS was_discipline,
            COALESCE(s1.id, '') AS was_slot_id,
            COALESCE(rc1.cabinet, '') AS was_cabinet,

            -- Стало
            COALESCE(CONCAT(LEFT(t2.name, 1), '. ', LEFT(t2.surname, 1), '. ', t2.lastname), '') AS became_teacher_fullname,
            COALESCE(CONCAT(d2.code, ' ', d2.name), '') AS became_discipline,
            COALESCE(s2.id, '') AS became_slot_id,
            COALESCE(rc2.cabinet, '') AS became_cabinet
            FROM 
                replacements r
            LEFT JOIN 
                groups g ON r.group_id = g.id
            LEFT JOIN 
                replacement_components rc1 ON r.was_id = rc1.id
            LEFT JOIN 
                replacement_components rc2 ON r.became_id = rc2.id
            LEFT JOIN 
                teachers t1 ON rc1.teacher_id = t1.id
            LEFT JOIN 
                teachers t2 ON rc2.teacher_id = t2.id
            LEFT JOIN 
                disciplines d1 ON rc1.discipline_id = d1.id
            LEFT JOIN 
                disciplines d2 ON rc2.discipline_id = d2.id
            LEFT JOIN 
                slots s1 ON rc1.slot_id = s1.id
            LEFT JOIN 
                slots s2 ON rc2.slot_id = s2.id
            LEFT JOIN 
                replacement_types rt ON r.id = rt.replace_id
            LEFT JOIN 
                types ty ON rt.type_id = ty.id
            WHERE 
                r.date = ?
            GROUP BY 
                r.id
            ORDER BY 
                r.id DESC;
        ");
        $statement->execute([$date]);
        $data = $statement->fetchAll();
        return $data;
    }

    // Получение доступных типов замен
    public function getTypes(){
        $statement = $this->pdo->prepare("SELECT * FROM `types` ORDER BY `id`");
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }

    // Получение доступных типов замен
    public function deleteReplace(){
        $statement = $this->pdo->prepare("SELECT * FROM `types` ORDER BY `id`");
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }

    // Подсказки дисциплин
    public function getDisciplineFields($table, $fieldName, $query){
        $statement = $this->pdo->prepare("SELECT DISTINCT $fieldName FROM `disciplines`
                        WHERE name LIKE :query
                        OR code LIKE :query
                        LIMIT 10;");
         $statement->execute(['query' => "%$query%"]);
        $data = $statement->fetchAll();
        foreach ($data as $key => $value) {
            $data[$key] = implode(' ', $value);
        }
        return $data;
    }

    // Подсказки ФИО преподавателей
    public function getNameFields($table, $fieldName, $query){
        $statement = $this->pdo->prepare("SELECT DISTINCT $fieldName FROM `teachers`
                        WHERE name LIKE :query
                        OR lastname LIKE :query
                        OR surname LIKE :query
                        LIMIT 10;");
         $statement->execute(['query' => "%$query%"]);
        $data = $statement->fetchAll();
        foreach ($data as $key => $value) {
            $data[$key] = implode(' ', $value);
        }
        return $data;
    }

    // Подсказки группы
    public function getGroupFields($table, $fieldName, $query){
        $statement = $this->pdo->prepare("SELECT DISTINCT $fieldName FROM `groups`
                        WHERE name LIKE :query
                        LIMIT 10;");
        $statement->execute(['query' => "%$query%"]);
        $data = $statement->fetchAll(PDO::FETCH_COLUMN);
        return $data;
    }

    // Подсказки кабинетов
    public function getRoomFields($table, $fieldName, $query){
        $statement = $this->pdo->prepare("SELECT DISTINCT $fieldName FROM `rooms`
                        WHERE number LIKE :query
                        LIMIT 10;");
        $statement->execute(['query' => "%$query%"]);
        $data = $statement->fetchAll(PDO::FETCH_COLUMN);
        return $data;
    }

    // Проверка существования ФИО преподавателя
    public function checkNameValue($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM `teachers`
                        WHERE CONCAT(lastname, ' ', name, ' ', surname) = :query;");
        $statement->execute(['query' => $query]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Проверка существования дисциплины
    public function checkDisciplineValue($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM `disciplines`
                        WHERE CONCAT(code, ' ', name) = :query;");
        $statement->execute(['query' => $query]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Проверка существования группы
    public function checkGroupValue($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM `groups`
                        WHERE name = :query;");
        $statement->execute(['query' => $query]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Проверка существования группы
    public function checkRoomExist($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM `rooms`
                        WHERE number = :query;");
        $statement->execute(['query' => $query]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Проверка существования слота (пары)
    public function checkSlotValue($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM `slots`
                        WHERE id = :query;");
        $statement->execute(['query' => $query]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Получение сотрудника
    public function getEmployee($username){
        $statement = $this->pdo->prepare("SELECT * FROM employes WHERE login = ?");
        $statement->execute([$username]);
        $data = $statement->fetch();
        return $data;
    }

    // Проверка занятости кабинета парой
    public function checkRoom($date, $room, $slot_id){
        $statement = $this->pdo->prepare("SELECT rc.id, rc.slot_id, g.name,
            COALESCE(CONCAT(LEFT(t.name, 1), '. ', LEFT(t.surname, 1), '. ', t.lastname), '') as teacher_fullname,
            COALESCE(CONCAT(d.code, ' ', d.name), '') as discipline_name
            FROM replacements r
            JOIN replacement_components rc ON r.became_id = rc.id
            JOIN slots s ON rc.slot_id = s.id
            JOIN teachers t ON rc.teacher_id = t.id
            JOIN disciplines d ON rc.discipline_id = d.id
            JOIN groups g ON r.group_id = g.id
            WHERE r.date = :date 
            AND rc.cabinet = :room  
            AND s.id = :slot_id");
        $statement->execute(['date' => $date, 'room' => $room, 'slot_id' => $slot_id]);
        $data = $statement->fetch();
        return $data;
    }

    // Проверка занятости преподавателя парой
    public function checkTeacher($date, $fullname, $slot_id){
        $statement = $this->pdo->prepare("SELECT rc.id, rc.slot_id, g.name,
            COALESCE(CONCAT(LEFT(t.name, 1), '. ', LEFT(t.surname, 1), '. ', t.lastname), '') as teacher_fullname,
            COALESCE(CONCAT(d.code, ' ', d.name), '') as discipline_name
            FROM replacements r
            JOIN replacement_components rc ON r.became_id = rc.id
            JOIN slots s ON rc.slot_id = s.id
            JOIN teachers t ON rc.teacher_id = t.id
            JOIN disciplines d ON rc.discipline_id = d.id
            JOIN groups g ON r.group_id = g.id
            WHERE r.date = :date 
            AND CONCAT(t.lastname, ' ', t.name, ' ', t.surname) = :fullname  
            AND s.id = :slot_id");
        $statement->execute(['date' => $date, 'fullname' => $fullname, 'slot_id' => $slot_id]);
        $data = $statement->fetch();
        return $data;
    }

    // Получение ID учителя по ФИО
    public function getTeacherID($fullname){
        $statement = $this->pdo->prepare("SELECT id FROM `teachers`
                        WHERE CONCAT(lastname, ' ', name, ' ', surname) = :query;");
        $statement->execute(['query' => $fullname]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Получение ID дисциплины по Коду и Названию
    public function getDisciplineID($name){
        $statement = $this->pdo->prepare("SELECT id FROM `disciplines`
                        WHERE CONCAT(code, ' ', name) = :query;");
        $statement->execute(['query' => $name]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Получение сотрудника
    public function getGroupID($name){
        $statement = $this->pdo->prepare("SELECT id FROM groups WHERE name = ?");
        $statement->execute([$name]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Добавление компонента замены
    public function addReplaceComponent($cabinet, $teacher_fullname, $slot_id, $discipline_name){
        $teacher_id = $this->getTeacherID($teacher_fullname);
        $discipline_id = $this->getDisciplineID($discipline_name);

        $statement = $this->pdo->prepare("INSERT INTO replacement_components (cabinet, teacher_id, slot_id, discipline_id) VALUES (:cabinet, :teacher_id, :slot_id, :discipline_id);");
        $statement->execute(['cabinet' => $cabinet, 'teacher_id' => $teacher_id, 'slot_id' => $slot_id, 'discipline_id' => $discipline_id]);

        $component_id = $this->pdo->lastInsertId();
        return $component_id;
    }

    // Добавление типов к замене
    public function addReplacementType($replacement_id, $type_id){
        $statement = $this->pdo->prepare("INSERT INTO replacement_types (type_id, replace_id) VALUES (:type_id, :replace_id);");
        $statement->execute(['type_id' => $type_id, 'replace_id' => $replacement_id]);
        return true;
    }

    // Добавление замены
    public function addReplacement($replace, $oldEmpty, $newEmpty, $author_id){
        try {
            // Начинаем транзакцию
            $this->pdo->beginTransaction();

            // Проверка на наличие указания старого компонента.
            if ($oldEmpty) $old_componentId = NULL;
            else $old_component = $this->addReplaceComponent($replace['oldRoom'], $replace['oldTeacher'], $replace['oldPair'], $replace['oldDiscipline']); 

            // Проверка на наличие указания нового компонента.
            if($newEmpty) $new_component = NULL;
            else $new_component = $this->addReplaceComponent($replace['newRoom'], $replace['newTeacher'], $replace['newPair'], $replace['newDiscipline']);

            // Проверка изменений, если старый и новый компоненты указаны
            if (!$oldEmpty && !$newEmpty) {
                if ($replace['oldTeacher'] !== $replace['newTeacher']) {
                    $changes[] = 1; // Замена преподавателя
                }
                if (($replace['oldRoom'] !== $replace['newRoom']) && ($replace['newRoom'] !== 'ДИСТАНТ')) {
                    $changes[] = 2; // Замена кабинета
                }
                if ($replace['oldDiscipline'] !== $replace['newDiscipline']) {
                    $changes[] = 3; // Замена дисциплины
                }
                if ($replace['oldPair'] !== $replace['newPair']) {
                    $changes[] = 7; // Перенос пары
                }
            } elseif ($oldEmpty && !$newEmpty) {
                $changes = [5]; // Добавление пары
            } elseif (!$oldEmpty && $newEmpty) {
                $changes = [4]; // Отмена пары
            } 
            if (isset($replace['newRoom']) && $replace['newRoom'] === 'ДИСТАНТ') {
                $changes[] = 6; // Дистанционный формат
            }

            $group_id = $this->getGroupID($replace['group']);

            $statement = $this->pdo->prepare("INSERT INTO replacements (confirmed, date, author_id, group_id, was_id, became_id) VALUES (:confirmed, :date, :author_id, :group_id, :was_id, :became_id)");
            $statement->execute(['confirmed' => true, 'date' => $replace['date'], 'author_id' => $author_id, 'group_id' => $group_id, 'was_id' => $old_component, 'became_id' => $new_component]);
            $replacement_id = $this->pdo->lastInsertId();

            foreach ($changes as $change_id){
                $this->addReplacementType($replacement_id, $change_id);
            }

            // Фиксируем транзакцию
            $this->pdo->commit();
            return $replacement_id;
        } 
        catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            $this->pdo->rollBack();
            error_log("Ошибка при удалении замены: " . $e->getMessage());
            return false; // Ошибка удаления
        }
    }

    // Удаление замены и её дочерних компонентов
    public function deleteReplacement($replacement_id) {
        try {
            // Начинаем транзакцию
            $this->pdo->beginTransaction();

            // 1. Удаляем связанные записи из таблицы replacement_types
            $statement = $this->pdo->prepare("DELETE FROM replacement_types WHERE replace_id = ?");
            $statement->execute([$replacement_id]);

            // 2. Получаем ID компонентов замены (was_id и became_id)
            $statement = $this->pdo->prepare("SELECT was_id, became_id FROM replacements WHERE id = ?");
            $statement->execute([$replacement_id]);
            $components = $statement->fetch(PDO::FETCH_ASSOC);

            // 3. Удаляем саму замену
            $statement = $this->pdo->prepare("DELETE FROM replacements WHERE id = ?");
            $statement->execute([$replacement_id]);

            // 4. Удаляем компоненты замены (если они существуют)
            if ($components['was_id']) {
                $statement = $this->pdo->prepare("DELETE FROM replacement_components WHERE id = ?");
                $statement->execute([$components['was_id']]);
            }
            if ($components['became_id']) {
                $statement = $this->pdo->prepare("DELETE FROM replacement_components WHERE id = ?");
                $statement->execute([$components['became_id']]);
            }
            

            // Фиксируем транзакцию
            $this->pdo->commit();

            return true; 
        } catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            $this->pdo->rollBack();
            echo("Ошибка при удалении замены: " . $e->getMessage());
            return false; 
        }
    }

}


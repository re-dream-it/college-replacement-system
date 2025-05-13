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
            CONCAT(g.name, ' ', r.group_part) AS group_name,
            
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
                r.date = ? AND r.confirmed = 1
            GROUP BY 
                r.id
            ORDER BY 
                r.id DESC;
        ");
        $statement->execute([$date]);
        $data = $statement->fetchAll();
        return $data;
    }

    // Получение списка замен для админ панели
    public function getReplacesAdmin($date){
        $statement = $this->pdo->prepare("SELECT 
            -- Основные сведения
            r.id AS replacement_id,
            r.date,
            r.is_introduced,
            CONCAT(g.name, ' ', r.group_part) AS group_name,
            r.reason AS reason,
            r.confirmed AS confirmed,
            
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

    // Получение списка замен
    public function getReplace($id){
        $statement = $this->pdo->prepare("SELECT 
            -- Основные сведения
            r.id AS replacement_id,
            r.date,
            g.name AS group_name,
            r.group_part AS group_part,
            r.reason AS reason,
            r.confirmed AS confirmed,
            
            -- Было
            COALESCE(GROUP_CONCAT(ty.name SEPARATOR ', '), '') AS replacement_types,
            COALESCE(CONCAT(t1.lastname, ' ', t1.name, ' ', t1.surname), '') AS was_teacher_fullname,
            COALESCE(CONCAT(d1.code, ' ', d1.name), '') AS was_discipline,
            COALESCE(s1.id, '') AS was_slot_id,
            COALESCE(rc1.cabinet, '') AS was_cabinet,

            -- Стало
            COALESCE(CONCAT(t2.lastname, ' ', t2.name, ' ', t2.surname), '') AS became_teacher_fullname,
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
                r.id = ?
            GROUP BY 
                r.id
            ORDER BY 
                r.id DESC;
        ");
        $statement->execute([$id]);
        $data = $statement->fetch();
        return $data;
    }

    // Получение доступных типов замен
    public function getTypes(){
        $statement = $this->pdo->prepare("SELECT * FROM `types` ORDER BY `id`");
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }

    public function isConfirmed($replacement_id){
        $statement = $this->pdo->prepare("SELECT confirmed FROM `replacements` WHERE `id` = :rep_id");
        $statement->execute(['rep_id' => $replacement_id]);
        $data = $statement->fetch();
        return $data['confirmed'];
    }

    // Получение доступных типов замен
    public function deleteReplace(){
        $statement = $this->pdo->prepare("SELECT * FROM `types` ORDER BY `id`");
        $statement->execute();
        $data = $statement->fetchAll();
        return $data;
    }

    // Подсказки дисциплин со проверкой связи с группой
    public function getDisciplineFields($table, $fieldName, $query, $group){
        $group_id = $this->getGroupID($group);
        $statement = $this->pdo->prepare("SELECT DISTINCT $fieldName FROM `disciplines`
                        WHERE (code LIKE :query OR name LIKE :query) AND id IN (
                            SELECT discipline_id FROM groups_disciplines WHERE group_id = :group_id) LIMIT 10;");
        $statement->execute(['query' => "%$query%", 'group_id' => $group_id]);
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
        $statement->execute(['query' => "$query%"]);
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

    // Проверка существования связи дисциплины и группы
    public function checkDisciplineRelation($group, $discipline){
        $group_id = $this->getGroupID($group);
        $discipline_id = $this->getDisciplineID($discipline);

        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM `groups_disciplines`
                        WHERE group_id = :group_id AND discipline_id = :discipline_id;");
        $statement->execute(['group_id' => $group_id, 'discipline_id' => $discipline_id]);
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

    // Получение ID замены по ID компонента
    public function getRepByComp($component_id){
        $statement = $this->pdo->prepare("SELECT id FROM replacements WHERE was_id = :comp_id OR became_id = :comp_id");
        $statement->execute(['comp_id' => $component_id]);
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

        if($data){
            $rep = $this->getRepByComp($data['id']);

            if ($rep){
                $data['replacement_id'] = $rep['id'];
            }
            else{
                $data['replacement_id'] = null;
            }
        }

            
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

        if($data){
            $rep = $this->getRepByComp($data['id']);

            if ($rep){
                $data['replacement_id'] = $rep['id'];
            }
            else{
                $data['replacement_id'] = null;
            }
        }

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
            if ($oldEmpty) $old_component = NULL;
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
            if ($replace['confirmed'] == 'true'){$replace['confirmed'] = 1;}
            else{$replace['confirmed'] = 0;}


            $statement = $this->pdo->prepare("INSERT INTO replacements (confirmed, date, author_id, group_id, group_part, reason, was_id, became_id) VALUES (:confirmed, :date, :author_id, :group_id, :group_part, :reason, :was_id, :became_id)");
            $statement->execute(['confirmed' => $replace['confirmed'], 'date' => $replace['date'], 'author_id' => $author_id, 'group_id' => $group_id, 'group_part' => $replace['groupPart'], 'reason' => $replace['reason'], 'was_id' => $old_component, 'became_id' => $new_component]);
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
            error_log("Ошибка при добавлении замены: " . $e->getMessage());
            return false; 
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

    // Изменение замены 
    public function editReplacement($replacement, $oldEmpty, $newEmpty, $replacement_id) {
        try {
            // Начинаем транзакцию
            $this->pdo->beginTransaction();
    
            $changes = [];
            
            // Обработка старого компонента (was)
            $old_component = null;
            if (!$oldEmpty) {
                $old_component = $this->addReplaceComponent(
                    $replacement['oldRoom'],
                    $replacement['oldTeacher'],
                    $replacement['oldPair'],
                    $replacement['oldDiscipline']
                );
            }
            
            // Обработка нового компонента (became)
            $new_component = null;
            if (!$newEmpty) {
                $new_component = $this->addReplaceComponent(
                    $replacement['newRoom'],
                    $replacement['newTeacher'],
                    $replacement['newPair'],
                    $replacement['newDiscipline']
                );
            }
            
            // Определение типов изменений
            if (!$oldEmpty && !$newEmpty) {
                // Если оба компонента указаны
                if ($replacement['oldTeacher'] !== $replacement['newTeacher']) {
                    $changes[] = 1; // Замена преподавателя
                }
                if (($replacement['oldRoom'] !== $replacement['newRoom']) && ($replacement['newRoom'] !== 'ДИСТАНТ')) {
                    $changes[] = 2; // Замена кабинета
                }
                if ($replacement['oldDiscipline'] !== $replacement['newDiscipline']) {
                    $changes[] = 3; // Замена дисциплины
                }
                if ($replacement['oldPair'] !== $replacement['newPair']) {
                    $changes[] = 7; // Перенос пары
                }
            } elseif ($oldEmpty && !$newEmpty) {
                // Если только новый компонент указан
                $changes[] = 5; // Добавление пары
            } elseif (!$oldEmpty && $newEmpty) {
                // Если только старый компонент указан
                $changes[] = 4; // Отмена пары
            }
            
            // Проверка на дистанционный формат
            if (isset($replacement['newRoom']) && $replacement['newRoom'] === 'ДИСТАНТ') {
                $changes[] = 6; // Дистанционный формат
            }
            
            // Получаем ID группы
            $group_id = $this->getGroupID($replacement['group']);


            // Обновляем запись о замене
            $statement = $this->pdo->prepare("
                UPDATE replacements 
                SET date = :date, 
                    group_id = :group_id, 
                    group_part = :group_part, 
                    reason = :reason, 
                    was_id = :was_id, 
                    became_id = :became_id 
                WHERE id = :replacement_id;
            ");
            
            $statement->execute([
                'date' => $replacement['date'],
                'group_id' => $group_id,
                'group_part' => $replacement['groupPart'],
                'reason' => $replacement['reason'],
                'was_id' => $old_component,
                'became_id' => $new_component,
                'replacement_id' => $replacement_id
            ]);

            
            // Обновляем типы замены
            // Сначала удаляем старые
            $this->pdo->prepare("DELETE FROM replacement_types WHERE replace_id = ?")->execute([$replacement_id]);
            
            // Затем добавляем новые
            foreach ($changes as $change_id) {
                $this->addReplacementType($replacement_id, $change_id);
            }

            $confirmed = $this->isConfirmed($replacement_id);
            
            // Фиксируем транзакцию
            $this->pdo->commit();
            return ["confirmed" => $confirmed, "id" => $replacement_id];
        } 
        catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            $this->pdo->rollBack();
            echo("Ошибка при редактировании замены: " . $e->getMessage());
            return ["id" => false];
        }
    }

    // Утверждение замены
    public function confirmReplacement($replacement_id) {
        $statement = $this->pdo->prepare("UPDATE replacements SET confirmed = 1 WHERE id = :replace_id;");
        $statement->execute(['replace_id' => $replacement_id]);
        return true;
    }

    // Отмека замены "Внесенной"
    public function introduceReplacement($replacement_id) {
        $statement = $this->pdo->prepare("UPDATE replacements SET is_introduced = 1 WHERE id = :replace_id;");
        $statement->execute(['replace_id' => $replacement_id]);
        return true;
    }

    // Отмека замены "Измененной"
    public function setReplacementChanged($replacement_id) {
        $statement = $this->pdo->prepare("UPDATE replacements SET is_introduced = 2 WHERE id = :replace_id;");
        $statement->execute(['replace_id' => $replacement_id]);
        return true;
    }
}


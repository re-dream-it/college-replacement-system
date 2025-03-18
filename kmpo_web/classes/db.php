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
                r.id;
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

    // Подсказки дисциплин
    public function getDisciplineFields($table, $fieldName, $query){
        $statement = $this->pdo->prepare("SELECT DISTINCT $fieldName FROM $table 
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
        $statement = $this->pdo->prepare("SELECT DISTINCT $fieldName FROM $table 
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
        $statement = $this->pdo->prepare("SELECT DISTINCT $fieldName FROM $table 
                        WHERE name LIKE :query
                        LIMIT 10;");
        $statement->execute(['query' => "%$query%"]);
        $data = $statement->fetchAll(PDO::FETCH_COLUMN);
        return $data;
    }

    // Проверка существования ФИО преподавателя
    public function checkNameValue($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM $table 
                        WHERE CONCAT(lastname, ' ', name, ' ', surname) = :query;");
        $statement->execute(['query' => $query]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Проверка существования дисциплины
    public function checkDisciplineValue($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM $table 
                        WHERE CONCAT(code, ' ', name) = :query;");
        $statement->execute(['query' => $query]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Проверка существования группы
    public function checkGroupValue($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM $table 
                        WHERE name = :query;");
        $statement->execute(['query' => $query]);
        $data = $statement->fetchColumn();
        return $data;
    }

    // Проверка существования слота (пары)
    public function checkSlotValue($table, $query){
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM $table 
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

    // Проверка занятости кабинета (пары)
    public function checkRoom($date, $room, $slot_id){
        $statement = $this->pdo->prepare("SELECT rc.id, rc.slot_id, g.name,
            COALESCE(CONCAT(LEFT(t.name, 1), '. ', LEFT(t.surname, 1), '. ', t.lastname), '') as teacher_fullname,
            COALESCE(CONCAT(d.code, ' ', d.name), '') as discipline_name
            FROM replacements r
            JOIN replacement_components rc ON r.became_id = rc.id OR r.was_id = rc.id
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

}

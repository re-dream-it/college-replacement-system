import mysql.connector
from mysql.connector import pooling, Error
import threading

# DATABASE CLASS
class DB:
    def __init__(self, host: str, user: str, password: str, database: str):
        """
        Инициализация класса DB для работы с MySQL.

        :param host: Хост базы данных (например, localhost)
        :param user: Имя пользователя базы данных
        :param password: Пароль пользователя
        :param database: Имя базы данных
        """
        self.host = host
        self.user = user
        self.password = password
        self.database = database
        self.lock = threading.Lock()  # Блокировка для потокобезопасности

        # Создаем пул соединений
        self.pool = pooling.MySQLConnectionPool(
            pool_name="my_pool",
            pool_size=5,  # Размер пула соединений
            host=self.host,
            user=self.user,
            password=self.password,
            database=self.database,
            pool_reset_session=True,  # Сбрасывать сессию при возвращении в пул
        )
        print("MySQL DataBase: OK")

    def get_connection(self):
        """
        Получение соединения из пула.
        """
        try:
            connection = self.pool.get_connection()
            # print("MySQL DataBase: CONNECTION TAKEN FROM POOL")
            return connection
        except Error as e:
            print(f"Ошибка при получении соединения из пула: {e}")
            return None

    def execute(self, query: str, params: tuple = None):
        """
        Выполнение SQL-запроса.

        :param query: SQL-запрос
        :param params: Параметры для запроса (опционально)
        :return: ID последней вставленной строки (для INSERT)
        """
        with self.lock:  # Обеспечиваем потокобезопасность
            connection = self.get_connection()
            if not connection:
                return None

            try:
                cursor = connection.cursor(dictionary=True)
                cursor.execute(query, params or ())
                connection.commit()
                lastrowid = cursor.lastrowid  # Возвращает ID последней вставленной строки
                cursor.close()
                return lastrowid
            except Error as e:
                print(f"Ошибка при выполнении запроса: {e}")
                return None
            finally:
                connection.close()  # Возвращаем соединение в пул

    def fetch_all(self, query: str, params: tuple = None):
        """
        Выполнение SQL-запроса и получение всех результатов.

        :param query: SQL-запрос
        :param params: Параметры для запроса (опционально)
        :return: Список строк результата
        """
        with self.lock:
            connection = self.get_connection()
            if not connection:
                return []

            try:
                cursor = connection.cursor(dictionary=True)
                cursor.execute(query, params or ())
                result = cursor.fetchall()
                cursor.close()
                return result
            except Error as e:
                print(f"Ошибка при выполнении запроса: {e}")
                return []
            finally:
                connection.close()  # Возвращаем соединение в пул

    def fetch_one(self, query: str, params: tuple = None):
        """
        Выполнение SQL-запроса и получение одной строки результата.

        :param query: SQL-запрос
        :param params: Параметры для запроса (опционально)
        :return: Одна строка результата или None
        """
        with self.lock:
            connection = self.get_connection()
            if not connection:
                return None

            try:
                cursor = connection.cursor(dictionary=True)
                cursor.execute(query, params or ())
                result = cursor.fetchone()
                cursor.close()
                return result
            except Error as e:
                print(f"Ошибка при выполнении запроса: {e}")
                return None
            finally:
                connection.close()  # Возвращаем соединение в пул

    # Добавление пользователя в базу данных
    async def add_user(self, id: int, un: str):
        query = "INSERT INTO `users` (`id`, `username`) VALUES (%s, %s)"
        return self.execute(query, (id, un))

    # Обновление юзернейма пользователя
    async def update_username(self, id: int, un: str):
        query = "UPDATE `users` SET `username` = %s WHERE `id` = %s"
        return self.execute(query, (un, id))

    # Изменение группы пользователя
    async def update_group(self, id: int, group_id: str):
        query = "UPDATE `users` SET `group_id` = %s WHERE `id` = %s"
        return self.execute(query, (group_id, id))

    # Изменение преподавателя пользователя
    async def update_teacher(self, id: int, teacher_id: str):
        query = "UPDATE `users` SET `teacher_id` = %s WHERE `id` = %s"
        return self.execute(query, (teacher_id, id))

    # Получение списка пользователей с лимитом
    async def get_users(self, limit: int = 0):
        query = "SELECT * FROM `users` LIMIT %s"
        return self.fetch_all(query, (limit,))

    # Получение списка пользователей по группе
    async def get_notify_users(self, group_id: int, was_teacher_id: int, became_teacher_id: int):
        query = "SELECT * FROM `users` WHERE group_id = %s OR teacher_id = %s OR teacher_id = %s"
        return self.fetch_all(query, (group_id, was_teacher_id, became_teacher_id,))

    # Получение конкретного пользователя по uid
    async def get_user(self, uid: int):
        query = "SELECT * FROM `users` WHERE `id` = %s "
        return self.fetch_one(query, (uid,))

    # Получение замены по id
    async def get_replace(self, id: int):
        query = """SELECT 
            -- Основные сведения
            r.id AS replacement_id,
            r.date,
            g.name AS group_name,
            g.id AS group_id,
            t1.id AS was_teacher_id,
            t2.id AS became_teacher_id,
            
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
                r.id = %s
            GROUP BY 
                r.id"""
        return self.fetch_one(query, (id,))

    # Получение группы по name
    async def get_group_byname(self, name: str):
        query = "SELECT * FROM `groups` WHERE `name` = %s"
        return self.fetch_one(query, (name,))

    # Получение группы по id
    async def get_group_byid(self, id: int):
        query = "SELECT * FROM `groups` WHERE `id` = %s"
        return self.fetch_one(query, (id,))

    # Получение ФИО учителя по id
    async def get_teacher_byid(self, id: int):
        query = "SELECT CONCAT(LEFT(name, 1), '. ', LEFT(surname, 1), '. ', lastname) AS fullname FROM `teachers` WHERE `id` = %s"
        return self.fetch_one(query, (id,))

    # Получение ФИО учителя по id
    async def get_teacher_byname(self, name: str):
        query = "SELECT * FROM `teachers` WHERE CONCAT(lastname, ' ', name, ' ', surname) = %s"
        return self.fetch_one(query, (name,))
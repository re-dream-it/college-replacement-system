import mysql.connector
from mysql.connector import Error
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
        self.connection = None
        self.cursor = None
        self.lock = threading.Lock()  # Блокировка для потокобезопасности

        self.connect()

    def connect(self):
        """
        Подключение к базе данных MySQL.
        """
        try:
            self.connection = mysql.connector.connect(
                host=self.host,
                user=self.user,
                password=self.password,
                database=self.database,
                pool_size=5,  # Размер пула соединений
                pool_reset_session=True,  # Сбрасывать сессию при возвращении в пул
                connect_timeout=28800  # Таймаут подключения (8 часов)
            )
            self.cursor = self.connection.cursor(dictionary=True)  # Используем словарь для результатов
            print("MySQL DataBase: OK")
        except Error as e:
            print(f"Ошибка при подключении к базе данных: {e}")

    def close(self):
        """
        Закрытие соединения с базой данных.
        """
        if self.connection and self.connection.is_connected():
            self.cursor.close()
            self.connection.close()
            print("MySQL DataBase: OFF")

    def execute(self, query: str, params: tuple = None):
        """
        Выполнение SQL-запроса.

        :param query: SQL-запрос
        :param params: Параметры для запроса (опционально)
        :return: ID последней вставленной строки (для INSERT)
        """
        with self.lock:  # Обеспечиваем потокобезопасность
            try:
                if not self.connection.is_connected():
                    self.connect()
                    print("MySQL DataBase: RECONNECTED")
                self.cursor.execute(query, params or ())
                self.connection.commit()
                return self.cursor.lastrowid  # Возвращает ID последней вставленной строки
            except Error as e:
                print(f"Ошибка при выполнении запроса: {e}")
                return None

    def fetch_all(self, query: str, params: tuple = None):
        """
        Выполнение SQL-запроса и получение всех результатов.

        :param query: SQL-запрос
        :param params: Параметры для запроса (опционально)
        :return: Список строк результата
        """
        with self.lock:
            try:
                if not self.connection.is_connected():
                    self.connect()
                self.cursor.execute(query, params or ())
                return self.cursor.fetchall()
            except Error as e:
                print(f"Ошибка при выполнении запроса: {e}")
                return []

    def fetch_one(self, query: str, params: tuple = None):
        """
        Выполнение SQL-запроса и получение одной строки результата.

        :param query: SQL-запрос
        :param params: Параметры для запроса (опционально)
        :return: Одна строка результата или None
        """
        with self.lock:
            try:
                if not self.connection.is_connected():
                    self.connect()
                self.cursor.execute(query, params or ())
                return self.cursor.fetchone()
            except Error as e:
                print(f"Ошибка при выполнении запроса: {e}")
                return None

    # Добавление пользователя в базу данных
    async def add_user(self, id: int, un: str):
        query = "INSERT INTO `users` (`id`, `username`) VALUES (%s, %s)"
        return self.execute(query, (id, un))
    
	# Добавление пользователя в базу данных
    async def update_username(self, id: int, un: str):
        query = "UPDATE `users` SET `username` = %s WHERE `id` = %s"
        return self.execute(query, (un, id))


    # Получение списка пользователей с лимитом
    async def get_users(self, limit: int = 0):
        query = "SELECT * FROM `users` LIMIT %s"
        return self.fetch_all(query, (limit,))

    # Получение конкретного пользователя по uid
    async def get_user(self, uid: int):
        query = "SELECT * FROM `users` WHERE `id` = %s"
        return self.fetch_one(query, (uid,))
    
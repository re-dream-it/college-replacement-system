from aiogram import BaseMiddleware
from aiogram.types import Message, CallbackQuery
from typing import Callable, Dict, Any, Awaitable
from datetime import datetime, timedelta

class ThrottlingMiddleware(BaseMiddleware):
    def __init__(self, limit: int = 3, interval: float = 1.0):
        self.limit = limit
        self.interval = timedelta(seconds=interval)
        self.users = {}  # {user_id: [timestamp1, timestamp2]}

    async def __call__(
        self,
        handler: Callable[[Message | CallbackQuery, Dict[str, Any]], Awaitable[Any]],
        event: Message | CallbackQuery,
        data: Dict[str, Any]
    ) -> Any:
        user_id = event.from_user.id
        now = datetime.now()

        # Очищаем старые запросы
        if user_id in self.users:
            self.users[user_id] = [
                t for t in self.users[user_id] 
                if now - t < self.interval
            ]

        # Проверяем лимит
        if user_id in self.users and len(self.users[user_id]) >= self.limit:
            if isinstance(event, Message):
                await event.answer("⏳ Слишком много запросов! Подождите секунду.")
            return

        # Добавляем текущий запрос
        if user_id not in self.users:
            self.users[user_id] = []
        self.users[user_id].append(now)

        return await handler(event, data)
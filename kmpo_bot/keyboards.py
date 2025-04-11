from aiogram.types import InlineKeyboardButton, KeyboardButton, InlineKeyboardMarkup, ReplyKeyboardMarkup
from loader import db

# KEYBOARDS FILE

async def main_keyboard():
    buttons = [
        [KeyboardButton(text="🏠 Главная"), KeyboardButton(text="ℹ️ Помощь")]
    ]
    keyboard = ReplyKeyboardMarkup(keyboard=buttons, resize_keyboard=True)
    return keyboard

async def main_menu_keyboard():
    buttons = [
        [InlineKeyboardButton(text="📚 Подписаться на группу", callback_data=f"subscribe_group")],
        [InlineKeyboardButton(text="👩‍🏫 Подписаться на преподавателя", callback_data=f"subscribe_teacher")],
        [InlineKeyboardButton(text="📋 Список замен", url=f"https://rep.serviceskmpo.ru/")]
    ]
    keyboard = InlineKeyboardMarkup(inline_keyboard=buttons)
    return keyboard

async def back_keyboard():
    buttons = [
        [InlineKeyboardButton(text="🔙 Назад", callback_data=f"back")]
    ]
    keyboard = InlineKeyboardMarkup(inline_keyboard=buttons)
    return keyboard

async def site_keyboard():
    buttons = [
        [InlineKeyboardButton(text="📋 Список замен", url=f"https://rep.serviceskmpo.ru/")]
    ]
    keyboard = InlineKeyboardMarkup(inline_keyboard=buttons)
    return keyboard
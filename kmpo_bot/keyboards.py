from aiogram.types import InlineKeyboardButton, KeyboardButton, InlineKeyboardMarkup
from loader import db

# KEYBOARDS FILE

async def main_menu_keyboard():
    buttons = [
        [InlineKeyboardButton(text="📚 Подписаться на группу", callback_data=f"subscribe_group")],
        [InlineKeyboardButton(text="👩‍🏫 Подписаться на преподавателя", callback_data=f"subscribe_teacher")]
    ]
    keyboard = InlineKeyboardMarkup(inline_keyboard=buttons)
    return keyboard

async def back_keyboard():
    buttons = [
        [InlineKeyboardButton(text="🔙 Назад", callback_data=f"back")]
    ]
    keyboard = InlineKeyboardMarkup(inline_keyboard=buttons)
    return keyboard
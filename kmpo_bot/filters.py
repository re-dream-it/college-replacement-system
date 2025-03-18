from aiogram import types, F
from config import ADMIN_IDS

admin_filter = F.from_user.id.in_(ADMIN_IDS)
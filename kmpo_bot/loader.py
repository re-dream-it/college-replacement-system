from aiogram import Bot, Dispatcher
from config import *
from db import DB

# CLASSES INIT

bot = Bot(BOT_TOKEN) # Bot
dp = Dispatcher() # Bot's dispatcher
db = DB('db.db') # Database
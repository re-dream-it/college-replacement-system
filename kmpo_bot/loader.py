from aiogram import Bot, Dispatcher
from config import *
from db import DB
from middlewares.ratelimiter import ThrottlingMiddleware 

# CLASSES INIT

bot = Bot(BOT_TOKEN) # Bot
dp = Dispatcher()
dp.message.middleware(ThrottlingMiddleware(limit=2, interval=1.0))
dp.callback_query.middleware(ThrottlingMiddleware(limit=2, interval=1.0))
db = DB(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME) # Database
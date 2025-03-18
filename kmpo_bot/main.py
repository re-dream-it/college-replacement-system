from loader import dp, bot
import handlers
import asyncio

# RUN SERVICES
    
async def main():
    print("Bot: OK")
    await dp.start_polling(bot)

if __name__ == '__main__':
    asyncio.run(main())

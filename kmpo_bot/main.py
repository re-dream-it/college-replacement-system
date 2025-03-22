from loader import dp, bot
import handlers
import asyncio
from aiohttp import web

# RUN SERVICES
async def start_http_server():
	app = web.Application()
	app.router.add_post('/replace_notify', handlers.accept_replace)
	runner = web.AppRunner(app)
	await runner.setup()
     
	https_site = web.TCPSite(runner, 'localhost', '305')
	await https_site.start()
	print("Web-server: OK")

async def main():
    print("Bot: OK")
    asyncio.create_task(start_http_server()) 
    await dp.start_polling(bot)

if __name__ == '__main__':
    asyncio.run(main())
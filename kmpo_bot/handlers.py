from loader import dp, bot, db
from aiogram import types, F
from states import Subscribe
from aiogram.fsm.context import FSMContext
from aiohttp import web
from time import sleep
from config import *
import asyncio
import keyboards

# BOT HANDLERS

# any message
async def check_user(id, username):
    user = await db.get_user(id)
    if not user:
        await db.add_user(id, username)
        user = await db.get_user(id)
        await bot.send_message(LOG_CHAN, f"Новый пользователь!\n\nID: {id}\nЮзернейм: @{username}")
    elif user['username'] != username:
        await db.update_username(id, username)
        await bot.send_message(LOG_CHAN, f"Изменен юзернейм!\n\nID: {id}\nЮзернейм: @{username}")
    return user

# form start message
async def start_text(id):
    user = await db.get_user(id)
    
    if user['group_id']: 
        group = await db.get_group_byid(user['group_id'])
        user['group_name'] = group['name']
    else: user['group_name'] = 'не выбрана'

    if user['teacher_id']: 
        teacher = await db.get_teacher_byid(user['teacher_id'])
        user['teacher_name'] = teacher['fullname']
    else: user['teacher_name'] = 'не выбран'

    text = f"🏠 *Главное меню*\n\nВаша группа: `{user['group_name']}`\nВыбранный преподаватель: `{user['teacher_name']}`"
    return text

async def replacement_text(replacement):
    text = f"""🔔 <b>Новая замена!</b>
<b>ID:</b> <code>{replacement['replacement_id']}</code>
<b>Изменения:</b> <code>{replacement['replacement_types']}</code>
<b>Группа:</b> <code>{replacement['group_name']}</code>
<b>Дата:</b> <code>{replacement['date']}</code>\n"""
    if not 'Добавление пары' in replacement['replacement_types']:
        text += f"""\n<b>✖️ Было</b>:
<b>Преподаватель:</b> <code>{replacement['was_teacher_fullname']}</code>
<b>Дисциплина:</b> <code>{replacement['was_discipline']}</code>
<b>Номер пары:</b> <code>{replacement['was_slot_id']}</code>
<b>Кабинет:</b> <code>{replacement['was_cabinet']}</code>\n"""
    if not 'Отмена пары' in replacement['replacement_types']:
        text += f"""\n<b>✔️ Стало</b>:
<b>Преподаватель:</b> <code>{replacement['became_teacher_fullname']}</code>
<b>Дисциплина:</b> <code>{replacement['became_discipline']}</code>
<b>Номер пары:</b> <code>{replacement['became_slot_id']}</code>
<b>Кабинет:</b> <code>{replacement['became_cabinet']}</code>"""
    return text



# start
@dp.message(F.text == ('/start'))
async def execute_command(message: types.Message, state: FSMContext):
    await state.clear()
    user = await check_user(message.from_user.id, message.from_user.username)
    text = await start_text(message.from_user.id)

    await bot.send_message(message.from_user.id, f"👋 *Добро пожаловать!* \nЭто бот, который сможет уведомлять вас о ваших заменах!\n\nДля начала работы, вам необходимо подписаться на свою группу или на преподавателя.", parse_mode='markdown', reply_markup=await keyboards.main_keyboard())
    await bot.send_message(message.from_user.id, text, parse_mode='markdown', reply_markup=await keyboards.main_menu_keyboard())

# main menu
@dp.message(F.text == ('🏠 Главная'))
async def execute_command(message: types.Message, state: FSMContext):
    await state.clear()
    user = await check_user(message.from_user.id, message.from_user.username)
    text = await start_text(message.from_user.id)

    await bot.send_message(message.from_user.id, text, parse_mode='markdown', reply_markup=await keyboards.main_menu_keyboard())

# help
@dp.message(F.text == ('ℹ️ Помощь'))
async def execute_command(message: types.Message, state: FSMContext):
    await state.clear()
    await bot.send_message(message.from_user.id, f'<b>Возникла проблема?\nРазработчик:</b> @re_dream', parse_mode='html')

# back
@dp.callback_query(lambda call: call.data == "back")
async def back(callback_query: types.CallbackQuery, state: FSMContext):
    await state.clear()
    text = await start_text(callback_query.from_user.id)

    await bot.edit_message_text(chat_id=callback_query.from_user.id, message_id=callback_query.message.message_id, text=text, parse_mode='markdown', reply_markup=await keyboards.main_menu_keyboard())

# subscribe_group
@dp.callback_query(lambda call: call.data == "subscribe_group")
async def subscribe_group(callback_query: types.CallbackQuery, state: FSMContext):
    await bot.edit_message_text(chat_id=callback_query.from_user.id, message_id=callback_query.message.message_id, text="📚 *Введите название группы*  \nПожалуйста, введите название вашей группы (например, `201-ИТ-23`).", parse_mode='markdown', reply_markup=await keyboards.back_keyboard())
    await state.set_state(Subscribe.group)

# subscribe_teacher
@dp.callback_query(lambda call: call.data == "subscribe_teacher")
async def subscribe_teacher(callback_query: types.CallbackQuery, state: FSMContext):
    await bot.edit_message_text(chat_id=callback_query.from_user.id, message_id=callback_query.message.message_id, text="👩‍🏫 *Введите полное ФИО преподавателя*  \nПожалуйста, введите ФИО вашего преподавателя (например, `Иванов Иван Иванович`).", parse_mode='markdown', reply_markup=await keyboards.back_keyboard())
    await state.set_state(Subscribe.teacher)

# group_entered
@dp.message(F.text, Subscribe.group)
async def group_entered(message: types.Message, state: FSMContext):
    group = await db.get_group_byname(message.text)
    if group:
        await db.update_group(message.from_user.id, group['id'])
        await state.clear()
        await bot.send_message(message.from_user.id, f"✅ *Вы успешно подписались на группу* `{message.text}`.", parse_mode='markdown')
    else:
        await bot.send_message(message.from_user.id, f"❌ *Группы* `{message.text}` *не существует.*\nПовторите ввод.", parse_mode='markdown')

# teacher_entered
@dp.message(F.text, Subscribe.teacher)
async def teacher_entered(message: types.Message, state: FSMContext):
    teacher = await db.get_teacher_byname(message.text)
    if teacher:
        await db.update_teacher(message.from_user.id, teacher['id'])
        await state.clear()
        await bot.send_message(message.from_user.id, f"✅ *Вы успешно подписались на преподавателя* `{message.text}`.", parse_mode='markdown')
    else:
        await bot.send_message(message.from_user.id, f"❌ *Преподавателя* `{message.text}` *не существует.*\nПовторите ввод.", parse_mode='markdown')

# Обрабатываем запрос на добавление замены, создаем задачу
async def accept_replace(request):
    data = await request.json()
    print(f'Got new replacement!\nID: {data['replacement_id']}')
    response = web.json_response({"status": "success", "message": "Рассылка начата"})
    asyncio.create_task(send_notifications_background(data))
    return response

# Рассылка замен
async def send_notifications_background(data):
    replacement = await db.get_replace(data['replacement_id'])
    text = await replacement_text(replacement)
    print('Sending notifications:')
    print(text)
    await bot.send_message(INFO_CHAN, text, parse_mode='html', reply_markup=await keyboards.site_keyboard())
    await bot.send_message(LOG_CHAN, f'Рассылка замены №{replacement['replacement_id']} начата!')

    i = 0
    users = await db.get_notify_users(replacement['group_id'], replacement['was_teacher_id'], replacement['became_teacher_id'])
    for user in users:
        try: 
            i+=1
            await bot.send_message(user['id'], text, parse_mode='html', reply_markup=await keyboards.site_keyboard())
            await asyncio.sleep(1.2)
        except Exception as e:
            await bot.send_message(LOG_CHAN, f'Ошибка в процессе рассылки №{replacement['replacement_id']}: <code>{str(e)}</code>', parse_mode='html')

    await bot.send_message(LOG_CHAN, f'Рассылка замены №{replacement['replacement_id']} окончена!\nОтправлено сообщений: {i}')

# Обрабатываем запрос на удаление замены, создаем задачу
async def delete_replace(request):
    data = await request.json()
    print(f'Replacement deleted!\nID: {data['replacement_id']}')
    response = web.json_response({"status": "success", "message": "Рассылка начата"})
    replacement = await db.get_replace(data['replacement_id'])
    asyncio.create_task(send_del_notifications_background(replacement))
    return response

# Рассылка замен
async def send_del_notifications_background(replacement):
    text = f"⚠️ Замена №{replacement['replacement_id']} была удалена и проводиться не будет!"
    print('Sending notifications:')
    print(text)
    await bot.send_message(INFO_CHAN, text, parse_mode='html', reply_markup=await keyboards.site_keyboard())
    await bot.send_message(LOG_CHAN, f'Рассылка удаления замены №{replacement['replacement_id']} начата!')

    i = 0
    users = await db.get_notify_users(replacement['group_id'], replacement['was_teacher_id'], replacement['became_teacher_id'])
    for user in users:
        try: 
            i+=1
            await bot.send_message(user['id'], text, parse_mode='html', reply_markup=await keyboards.site_keyboard())
            await asyncio.sleep(1.2)
        except Exception as e:
            await bot.send_message(LOG_CHAN, f'Ошибка в процессе рассылки удаления замены №{replacement['replacement_id']}: <code>{str(e)}</code>', parse_mode='html')

    await bot.send_message(LOG_CHAN, f'Рассылка удаления замены №{replacement['replacement_id']} окончена!\nОтправлено сообщений: {i}')
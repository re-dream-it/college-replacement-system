from loader import dp, bot, db
from aiogram import types, F
from filters import admin_filter
from states import Subscribe
from aiogram.fsm.context import FSMContext
import keyboards

# BOT HANDLERS

# start
@dp.message(F.text == ('/start'), admin_filter)
async def execute_command(message: types.Message, state: FSMContext):
    await state.clear()
    await bot.send_message(message.from_user.id, f"👋 *Добро пожаловать!* \nЭто бот, который сможет уведомлять вас о ваших заменах!\n\nДля начала работы, вам необходимо подписаться на свою группу или на преподавателя.", parse_mode='markdown', reply_markup=await keyboards.main_menu_keyboard())

# back
@dp.callback_query(F.text == ('back'))
async def back(callback_query: types.CallbackQuery, state: FSMContext):
    await state.clear()
    await bot.edit_message_text(chat_id=callback_query.from_user.id, message_id=callback_query.message.message_id, text=f"👋 *Добро пожаловать!* \nЭто бот, который сможет уведомлять вас о ваших заменах!\n\nДля начала работы, вам необходимо подписаться на свою группу или на преподавателя.", parse_mode='markdown', reply_markup=await keyboards.main_menu_keyboard())

# subscribe_group
@dp.callback_query(lambda call: call.data == "subscribe_group")
async def subscribe_group(callback_query: types.CallbackQuery, state: FSMContext):
    await bot.edit_message_text(chat_id=callback_query.from_user.id, message_id=callback_query.message.message_id, text="📚 *Введите название группы*  \nПожалуйста, введите название вашей группы (например, `201-ИТ-23`).", parse_mode='markdown')
    await state.set_state(Subscribe.group)

# subscribe_teacher
@dp.callback_query(lambda call: call.data == "subscribe_teacher")
async def subscribe_teacher(callback_query: types.CallbackQuery, state: FSMContext):
    await bot.edit_message_text(chat_id=callback_query.from_user.id, message_id=callback_query.message.message_id, text="👩‍🏫 *Введите полное ФИО преподавателя*  \nПожалуйста, введите ФИО вашего преподавателя (например, `Иванов Иван Иванович`).", parse_mode='markdown')
    await state.set_state(Subscribe.teacher)

# group_entered
@dp.message(F.text, Subscribe.group)
async def group_entered(message: types.Message, state: FSMContext):
    if message.text in ['201-ИТ-23', '213-СВ-22']:
        # await db.subscribe_group(message.from_user.id, message.text)
        await state.clear()
        await bot.send_message(message.from_user.id, f"✅ *Вы успешно подписались на группу* `{message.text}`.", parse_mode='markdown')
    else:
        await bot.send_message(message.from_user.id, f"❌ *Группы* `{message.text}` *не существует.*", parse_mode='markdown')

# teacher_entered
@dp.message(F.text, Subscribe.teacher)
async def teacher_entered(message: types.Message, state: FSMContext):
    if message.text in ['Иванов Иван Иванович', 'Петров Петр Петрович']:
        # await db.subscribe_teacher(message.from_user.id, message.text)
        await state.clear()
        await bot.send_message(message.from_user.id, f"✅ *Вы успешно подписались на преподавателя* `{message.text}`.", parse_mode='markdown')
    else:
        await bot.send_message(message.from_user.id, f"❌ *Преподавателя* `{message.text}` *не существует.*", parse_mode='markdown')

# test_notification
@dp.message(F.text == ('/test'), admin_filter)
async def test_notification(message: types.Message):
    await bot.send_message(message.from_user.id, f'🔔 *Замена*\n*Тип:* Замена преподавателя\n*Группа: 201-ИТ-23*\n\n*✖️ Было*:\n*Преподаватель:* Сидоров С.С.\n*Номер пары:* 5\n*Дисциплина:* СО.03.02 Физика \n*Кабинет: 415* \n\n*✔️ Стало*:\n*Преподаватель: * Кузнецов К.К.	\n*Номер пары: *5\n*Дисциплина: *СО.07.06 Программирование\n*Кабинет: * 105\n*Дата:* 12.03.2025', parse_mode='markdown')
    
   
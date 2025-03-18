from aiogram.fsm.state import State, StatesGroup

# STATES FILE

# Subscribe states
class Subscribe(StatesGroup):
    group = State()
    teacher = State()
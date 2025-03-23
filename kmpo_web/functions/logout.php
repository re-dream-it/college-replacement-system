<?php
// Разрыв сессии
session_start();
session_unset();
session_destroy();
header("Location: /login");
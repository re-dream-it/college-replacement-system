<? include "head.php"; ?>
<? session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}?>
<header>
    <div class="header-content">
        <img src="images/logo_bw.png" alt="Логотип колледжа" class="logo">
    </div>
</header>

<body>
     <!-- Основной контент -->
    <main>
        <h1>Авторизация</h1>

        <!-- Форма авторизации -->
        <form id="loginForm" action="functions/session_init.php" method="post">
            <div class="form-group">
                <label for="username">Логин:</label>
                <input type="text" id="username" name="username" placeholder="Введите ваш логин" required>
            </div>

            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" placeholder="Введите ваш пароль" required>
            </div>

            <div class="form-group">
                <button type="submit" class="submit-btn">Войти</button>
            </div>
        </form>
    </main>
</body>

<? include "footer.php"; ?>
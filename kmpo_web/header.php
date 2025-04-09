<!-- Хедер -->
<? session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /replacements");
    exit;
}?>
<header>
    <div class="header-content">
        <a href="/replacements"><img src="images/KPA_logo_square_white.svg" alt="Логотип колледжа" class="logo"></a>
        <nav>
            <ul>
                <li><a href="/">Главная</a></li>
                <li><a href="admin_replacements">Просмотр замен</a></li>
                <li><a href="add_replacement">Внесение замен</a></li>
                <li><a href="faq">FAQ</a></li>
            </ul>
        </nav>
        <div class="logout-section">
            <i class="fa-solid fa-user profile-icon"></i>
            <?=$_SESSION['username']?>
            <form action="functions/logout.php" class="logout" method="POST">
                <button type="submit" class="logout-btn">Выйти</button>
            </form>
        </div>
    </div>
</header>
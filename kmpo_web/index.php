<? include "head.php"; ?>
<? include "header.php"; ?>
<body>
    <!-- Основной контент -->
    <main>
        <!-- Приветственный блок -->
        <section class="welcome-section">
            <h1>Добро пожаловать в систему замен КМПО РАНХиГС!</h1>
            <p>Здесь вы можете просматривать актуальные замены, вносить изменения и находить ответы на часто задаваемые вопросы.</p>
            <div class="cta-buttons">
                <a href="admin_replacements" class="cta-btn">Просмотр замен</a>
                <a href="add_replacement" class="cta-btn">Внесение замен</a>
            </div>
        </section>

        <!-- Описание функционала -->
        <section class="features-section">
            <h2>Возможности системы</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Просмотр замен</h3>
                    <p>Удобный просмотр актуальных замен по дате, группе и преподавателю.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-edit"></i>
                    <h3>Внесение замен</h3>
                    <p>Легкое добавление и редактирование замен для администраторов.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-question-circle"></i>
                    <h3>FAQ</h3>
                    <p>Ответы на часто задаваемые вопросы по работе с системой.</p>
                </div>
            </div>
        </section>

    </main>
</body>
<? include "footer.php"; ?>
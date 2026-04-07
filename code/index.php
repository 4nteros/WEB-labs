<<<<<<< Updated upstream
<?php phpinfo(); ?>
=======
<?php 
session_start(); 
require_once 'UserInfo.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
</head>
<body>
    <h2>Результат регистрации:</h2>
    <?php if(isset($_SESSION['errors'])): ?>
        <div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            <p><b>Ой! Исправьте ошибки:</b></p>
            <ul>
                <?php foreach($_SESSION['errors'] as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if(isset($_SESSION['username'])): ?>
        <p>Данные из сессии:</p>
        <ul>
            <li>Имя: <?= $_SESSION['username'] ?></li>
            <li>Возраст: <?= $_SESSION['age'] ?></li>
        </ul>
    <?php else: ?>
        <p>Данных пока нет. Пожалуйста, заполните форму.</p>
    <?php endif; ?>

    <hr>

    <h3>Информация о пользователе:</h3>
    <div style="background: #f4f4f4; padding: 10px; border-radius: 5px;">
        <?php 
        $info = UserInfo::getInfo();
        foreach ($info as $key => $val) {
            echo "<b>" . htmlspecialchars($key) . "</b>: " . htmlspecialchars($val) . '<br>';
        }
        
        if (isset($_COOKIE['last_submission'])) {
            echo "<br><b>Последняя отправка формы:</b> " . htmlspecialchars($_COOKIE['last_submission']);
        }
        ?>
    </div>

    <hr>

    <h3>Данные из API (Регионы HH.ru):</h3>
    <button id="updateApiBtn" style="padding: 10px; cursor: pointer; margin-bottom: 10px;">Обновить данные (без перезагрузки)</button>
    <div id="apiError" style="color: red; margin-bottom: 10px;"></div>
    
    <div style="background: #282c34; color: #61dafb; padding: 15px; border-radius: 5px; max-height: 300px; overflow-y: auto;">
        <pre id="apiDataContainer"><?php 
            if (isset($_SESSION['api_data'])) {
                if (isset($_SESSION['api_data']['error'])) {
                    echo "<span style='color: #e06c75;'>Ошибка API: " . htmlspecialchars($_SESSION['api_data']['error']) . "</span>";
                } else {
                    echo htmlspecialchars(print_r($_SESSION['api_data'], true));
                }
            } else {
                echo "Нет данных API. Отправьте форму регистрации для загрузки.";
            }
        ?></pre>
    </div>

    <script>
        document.getElementById('updateApiBtn').addEventListener('click', function() {
            const btn = this;
            const container = document.getElementById('apiDataContainer');
            const errorDiv = document.getElementById('apiError');
            
            btn.disabled = true;
            btn.textContent = 'Обновление...';
            errorDiv.textContent = '';
            container.style.opacity = '0.5';

            fetch('api_update.php')
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        container.textContent = JSON.stringify(result.data, null, 4);
                    } else {
                        errorDiv.textContent = 'Ошибка API: ' + result.error;
                    }
                })
                .catch(err => {
                    errorDiv.textContent = 'Ошибка сети: ' + err.message;
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = 'Обновить данные (без перезагрузки)';
                    container.style.opacity = '1';
                });
        });
    </script>

    <hr>
    
    <a href="form.html">Заполнить форму</a> | 
    <a href="view.php">Посмотреть все данные</a>
</body>
</html>
>>>>>>> Stashed changes

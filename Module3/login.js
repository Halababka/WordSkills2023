// обработчик отправки формы
document.getElementById('login-form').addEventListener('submit', async (event) => {
    event.preventDefault(); // отменяем стандартное поведение формы

    // получаем данные формы
    const formData = new FormData(event.target);
    const username = formData.get('username');
    const password = formData.get('password');
    
    let isAuthorized = await checkCredentials(username, password);
    isAuthorized = localStorage.getItem('token')    

    if (isAuthorized != "undefined") {
        // если учетные данные верны, перенаправляем пользователя на другую страницу
        window.location.href = '../site/index.html'; // замените "/dashboard" на ссылку на нужную страницу
    } else {
        // если учетные данные неверны, показываем модальное окно с сообщением об ошибке
        const errorModal = document.getElementById('error-modal');
        const errorMessage = document.getElementById('error-message');

        errorMessage.innerText = 'Неверный логин или пароль'; // замените на нужное сообщение об ошибке

        // показываем модальное окно
        errorModal.style.display = 'block';

        // обработчик для закрытия модального окна
        const closeBtn = document.querySelector('.close');
        closeBtn.addEventListener('click', () => {
            errorModal.style.display = 'none'; // скрываем модальное окно
        });
    }
});

// функция для проверки учетных данных (замените на свою реализацию)
async function checkCredentials(username, password) {
    const response = await fetch('http://alatech-api/alatech/api/login', { // замените "/api/login" на ссылку на ваш API для проверки учетных данных
        method: 'POST',
        body: JSON.stringify({ username, password }),
        headers: { 'Content-Type': 'application/json' }
    });

    if(response.status == 403) {
        window.location.href = '../site/index.html';
    }
    const data = await response.json();
    localStorage.setItem('token', data.token)
    return data.token; // замените на то, как ваш сервер возвращает флаг, который указывает, что учетные данные верны
}
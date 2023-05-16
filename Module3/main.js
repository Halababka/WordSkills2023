const url = "http://alatech-api/alatech/api/";
const app = {
    data() {
        return {
            items: [{ message: 'Foo', name: "Fo" }, { message: 'Bar', name: "Ba" }],
            auth: false,
            login: '',
            pass: '',
        }
    },
    methods: {
        goauth() {ц
            const data = { username: this.login, password: this.pass };
            fetch(url + 'login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (typeof(data.token) != 'undefined') {
                        localStorage.setItem('token', data.token);
                        this.auth = true;
                        window.location.href = "./home.html";
                    }
                })
                .catch((error) => {
                    console.error('Error', error);
                });
        },
    },
    mounted() {
        if (localStorage.getItem('token')) {
            if (typeof(localStorage.getItem('token')) != 'undefined') {
                this.auth = true;
                window.location.href = "./home.html";
                alert('Вы уже авторизованы');
            } else {
                this.auth = false
            }

        }
        console.log(this.auth)
    }
}

Vue.createApp(app).mount('#app')
const url = "http://alatech-api/alatech/api/";
const app = {
    data() {
        return {
            auth: false,
            login: '',
            pass: '',
        }
    },  
    methods: {
        goauth() {
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
                    localStorage.setItem('token', data.token);
                    this.auth = true;
                })
                .catch((error) => {
                    console.error('Error', error);
                });
        },
    },
    mounted() {
        if (localStorage.getItem('token')) {
            if(localStorage.getItem('token') != 'undefined') {
                this.auth = true
            } else {
                this.auth = false
            }

        }
    }
}

Vue.createApp(app).mount('#app')
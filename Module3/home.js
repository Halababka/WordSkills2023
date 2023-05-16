const url = "http://alatech-api/alatech/api/";
const app = {
    data() {
        return {
            auth: false,
        }
    },
    mounted() {
        if (localStorage.getItem('token')) {
            if (typeof (localStorage.getItem('token')) != 'undefined') {
                this.auth = true
            } else {
                this.auth = false
                window.location.href = "./Auth.html";
            }
        } else {
            window.location.href = "./Auth.html";
        }
        fetch(url + 'processors', {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                console.log(data)
            })
            .catch((error)=> {
                console.error('Error', error);
            })
    }
}

Vue.createApp(app).mount('#app')
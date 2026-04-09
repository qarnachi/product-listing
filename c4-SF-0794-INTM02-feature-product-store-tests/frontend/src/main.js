import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import { useUserStore } from './stores/userStore'

const app = createApp(App)


app.use(createPinia())
app.use(router)
const userStore = useUserStore()
if (userStore.token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${userStore.token}`
}
app.mount('#app')

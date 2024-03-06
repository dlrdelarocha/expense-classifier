import './bootstrap'
import { createApp } from 'vue'
import router from './router/index.js'
import store from './store/store.js'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import MainComponent from '@/Layouts/MainComponent.vue'

const app = createApp({
  setup() {
    return {}
  }
})

app.component('main-component', MainComponent)
app.component('authenticated-layout', AuthenticatedLayout)

app.use(store)
app.use(router)

app.mount('#app')

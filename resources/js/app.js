import './bootstrap';

import { createApp } from 'vue'
import  { createPinia } from "pinia";
// import PrimeVue from 'primevue/config';
import router from './router';
import App from "./App.vue";
// import i18n from "./plugins/i18n";
import primeVue from "./plugins/primevue";


import 'primevue/resources/themes/saga-blue/theme.css';
import 'primevue/resources/primevue.min.css';

import 'primeicons/primeicons.css';

 
const pinia = createPinia();
const app = createApp(App)

primeVue.init(app);
app.use(pinia);
app.use(router);

app.mount('#app')
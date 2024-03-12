import './bootstrap';

import { createApp } from 'vue';
import Router from '@/router';
// import store from '@/store';
import App from './App.vue';

import { createPinia } from 'pinia';
import { useAuthUserStore } from '@/store/authUserStore';

const pinia = createPinia();
const app = createApp();

// Use Pinia before mounting the app
app.use(pinia).use(Router);

const authUserStore = useAuthUserStore();
authUserStore.initializeStore();

app.mount('#app');

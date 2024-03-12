import './bootstrap';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
import Router from '@/router';
// import store from '@/store';
import App from './App.vue';

import { createPinia } from 'pinia';
import { useAuthUserStore } from '@/store/authUserStore';

const pinia = createPinia();
const app = createApp();

// Use Pinia before mounting the app
app.use(pinia).use(Router);

// const authUserStore = useAuthUserStore();
// authUserStore.initializeStore();


// Router.beforeEach(async (to, from) => {
//     const authUserStore = useAuthUserStore();
//     if (authUserStore.user.name === '' && to.name !== 'login') {
//         await Promise.all([
//             authUserStore.getAuthUser(),
//         ]);
//     }
// });


app.mount('#app');

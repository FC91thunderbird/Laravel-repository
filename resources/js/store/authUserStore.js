import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";
import router from '@/router'

import { createPersistedState } from "pinia-plugin-persistedstate";

export const useAuthUserStore = defineStore('AuthUserStore', () => {

    const user = ref({
        name: '',
        username: '',
        email: '',
        roleId: '',
        image: '',
        isAuth: false,
    });

    const setting = ref({
        sitename:'',
        logo:''
    });


    const getAuthUser = async () => {
        try {
            const response = await axios.get('/api/profile');
            const { users, settings } = response.data;

            user.value = users;
            setting.value = settings;
            user.value.isAuth = true;

            // router.push({ name: 'dashboard' });
        } catch (error) {
            console.error('Error fetching auth user:', error);
        }
    }

    const logout = () => {
                    user.value = {
                        name: '',
                        username: '',
                        email: '',
                        roleId: '',
                        image: '',
                        isAuth: false,
                    };
                }

    return { user, setting, getAuthUser, logout };

});
// export const useAuthUserStore = defineStore('AuthUserStore', {
//     state: () => ({
//         user: {
//             name: '',
//             username: '',
//             email: '',
//             role: '',
//             image: '',
//             isAuth: false,
//         },
//         setting: {
//             sitename: '',
//             logo: ''
//         },
//     }),
//     plugins: [createPersistedState({ key: 'AuthUserStore' })],

//     actions: {
//         async initializeStore() {
//             const persistedState = this.$state;
//             if (persistedState) {
//                 this.user = persistedState.user;
//                 this.setting = persistedState.setting;
//             }

//             await this.getAuthUser();
//         },

//         async getAuthUser() {
//             try {
//                 const response = await axios.get('/api/profile');
//                 const { users, setting } = response.data;

//                 this.user = users;
//                 this.setting = setting;
//                 this.user.isAuth = true;

//                 // router.push({ name: 'dashboard' });
//             } catch (error) {
//                 console.error('Error fetching auth user:', error);
//             }
//         },

//         logout(){
//             this.user = {
//                 name: '',
//                 username: '',
//                 email: '',
//                 role: '',
//                 image: '',
//                 isAuth: false,
//             };

//             this.setting = {
//                 sitename:'',
//                 logo:'',
//             };
            
//             this.$reset();
//             console.log('logout', this.user)
//         },

//     },
// });

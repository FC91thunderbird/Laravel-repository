import axios from "axios";
import { defineStore } from "pinia";
import { ref } from "vue";

export default defineStore('customerStore', () => {
    const user = ref(null);
    const isCustomer = ref(false);

    const getCustomer = async () => {
        try {
            const response = await axios.get('/api/customer/profile');
            const { customer } = response.data.data;

            user.value = customer;
            isCustomer.value = true;
        } catch (error) {
            console.error('Error fetching auth user:', error);
        }
    }

    const logoutCustomer = () => {
        axios.post('/api/customer/logout').then((res) => {
            if (res.data.success === true) {
                user.value = null;
                isCustomer.value = false;
                }
        }).catch((error) => {
            console.log('Customer logout', error);
        })
    }

    return { user,isCustomer, getCustomer, logoutCustomer };
});
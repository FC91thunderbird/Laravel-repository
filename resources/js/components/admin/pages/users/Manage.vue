<template>
    <div class="grid grid-cols-1 lg:grid-cols-1 p-4 gap-4">

        <div
            class="relative flex flex-col min-w-0 mb-4 lg:mb-0 break-words bg-gray-50 dark:bg-gray-800 w-full shadow-lg rounded">
            <div class="rounded-t mb-0 px-0 border-0">
                <div class="flex flex-wrap items-center px-4 py-2">
                    <div class="relative w-full max-w-full flex-grow flex-1">
                        <h3 class="font-semibold text-base text-gray-900 dark:text-gray-50"> {{ title }}</h3>
                    </div>
                    <div class="relative w-full max-w-full flex-grow flex-1 text-right">
                        <RouterLink :to="{ name: 'users' }"
                            class="bg-blue-500 dark:bg-gray-100 text-white active:bg-blue-600 dark:text-gray-800 dark:active:text-gray-700 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150">
                            Back</RouterLink>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto px-5 py-5">
                    <form @submit.prevent="formSubmit" method="post">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                                <div class="mt-2">
                                    <input type="text" v-model="form.name"
                                        class="block px-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <ValidationMessage key="name_error" :modelValue="v$.name" label="Name" :show="v$.name.error" />
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                                <div class="mt-2">
                                    <input type="text" v-model="form.username"
                                        class="block px-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <ValidationMessage key="username_error" :modelValue="v$.username" label="Username"
                                    :show="v$.username.error" />
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                <div class="mt-2">
                                    <input type="email" v-model="form.email"
                                        class="block px-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <ValidationMessage key="email_error" :modelValue="v$.email" label="Email" :show="v$.email.error" />
                            </div>
                            <div class="sm:col-span-3" v-if="!route.params.id">
                                <label class="block text-sm font-medium leading-6 text-gray-900">password</label>
                                <div class="mt-2">
                                    <input type="password" v-model="form.password"
                                        class="block px-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <ValidationMessage key="password_error" :modelValue="v$.password" label="Password"
                                    :show="v$.password.error" />
                            </div>
                            <div class="sm:col-span-3" v-if="!route.params.id">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Confirm
                                    Password</label>
                                <div class="mt-2">
                                    <input type="password" v-model="form.confirm_password"
                                        class="block px-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                </div>
                                <ValidationMessage key="confirm_password_error" :modelValue="v$.confirm_password" label="Confirm Password" :show="v$.confirm_password.error" />
                            </div>


                            <div class="sm:col-span-3">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Role</label>
                                <div class="mt-2">
                                    <select v-model="form.role_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                        <option>Select Role</option>
                                        <option v-for="role in roles" :key="role.id" :value="role.id" :selected="role.id === form.role_id" >{{ role.name }}</option>
                                    </select>
                                </div>
                                <ValidationMessage key="role_id_error" :modelValue="v$.role_id" label="Role"
                                    :show="v$.role_id.error" />
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <RouterLink :to="{ name: 'users' }" class="text-sm font-semibold leading-6 text-gray-900">
                                    Cancel</RouterLink>
                                <button type="submit"
                                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{
                                    button }}</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'primevue/usetoast';
import { useUserStore } from '@/stores/user';
import { useVuelidate } from "@vuelidate/core"
import { email, required } from "@vuelidate/validators";

const userStore = useUserStore();

const loading = ref(false);
const route = useRoute();
const router = useRouter();
const toast = useToast();
const roles = ref([]);

const form = ref({
    name: null,
    username: null,
    email: null,
    password: null,
    role_id: null,
    confirm_password: null,
});

const rules = computed(() => ({
    name: { required },
    username: { required },
    email: { required, email },
    password: {  },
    confirm_password: {  },
    role_id: { required },
}));

const v$ = useVuelidate(rules, form);

const title = computed(() => {
    return route.name === 'user.edit' ? 'Edit User' : 'Create User';
});

const button = computed(() => {
    return route.name === 'user.edit' ? 'Update' : 'Save';
});

onMounted(() => {
    fetchRoles();
    if (route.name === 'user.edit') {
        getById();
    }
});

const fetchRoles = async () => {
    try {
        let response = await userStore.fetchRoles();
        if (response.success) {
            roles.value = response.data;
        }
    } catch (error) {
        console.log('role fetch', error);
    }
};

const getById = async () => {
    try {
        const response = await userStore.getById(route.params.id);
        if (response.success) {
            form.value = response.data.user
        } else {
            let message = response.message || 'something_went_wrong';
            toast.add({ severity: 'error', summary: 'error', detail: message, life: 3000 });
        }
    } catch (error) {
        toast.add({ severity: 'error', summary: 'error', detail: error.message, life: 3000 });
    }
};

const formSubmit = async () => {
    v$.value.$touch();
    if (v$.value.$error) {
        return;
    }
    // loading.value = true;
    try {
        const response = route.name === 'user.edit' ? await userStore.update(route.params.id, form.value) : await userStore.store(form.value);

        if (response.success) {
            // toast.add({ severity: 'success', summary: 'success', detail: 'User Saved Successfully', life: 3000 });
            router.push({ name: 'users' });
        } else {
            if (response.errors) {
                for (let key in response.errors) {
                    let error = response.errors[key];
                    v$.value[key].$serverError = error
                    v$.value[key].error = true
                }
            }
            let message = response.message || 'something_went_wrong';
            toast.add({ severity: 'error', summary: 'error', detail: message, life: 3000 });
        }
    } catch (error) {
        console.log('Catch error', error.response.errors);
        toast.add({ severity: 'error', summary: 'error', detail: error.message, life: 3000 });
    } 
};



</script>

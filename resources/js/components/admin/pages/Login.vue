<template>
   <!-- component -->
<div class="min-h-screen flex items-center justify-center w-full dark:bg-gray-950">
	<div class="bg-white dark:bg-gray-900 shadow-md rounded-lg px-8 py-6 max-w-md">
		<h1 class="text-2xl font-bold text-center mb-4 dark:text-gray-200">Welcome Back!</h1>
		<p class="text-md font-medium text-gray-700 dark:text-gray-300 text-center">{{ errors }}</p>
		<form @submit.prevent="formSubmit" method="post">
			<div class="mb-4">
				<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
				<input type="email" v-model="form.email" class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="your@email.com" >
				<ValidationMessage key="email_error" :modelValue="v$.email" label="email" :show="v$.email.error" />
			</div>
			<div class="mb-4">
				<a href="#" class="text-xs text-gray-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Forgot Password?</a>
				<label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
				<input type="password" v-model="form.password" class="shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter your password" >
				<ValidationMessage key="password_error" :modelValue="v$.password" label="password" :show="v$.password.error" />
			</div>
			<div class="flex items-center justify-between mb-4">
				<div class="flex items-center">
					<input type="checkbox" v-model="form.remember" id="remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 focus:outline-none" checked>
					<label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Remember me</label>
				</div>
				<button type="submit" class="btn btn-primary block bg-gray-900 rounded p-2 text-white">Login</button>
			</div>
			<a href="#" class="text-xs text-indigo-500 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Account</a>
        </form>
	</div>
</div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useVuelidate } from "@vuelidate/core"
import { email, required } from "@vuelidate/validators";
import { useToast } from 'primevue/usetoast';

import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore();
const router = useRouter();
const toast = useToast();

const errors = ref('');
const form = ref({
    email: null,
    password: null,
	remember: null
});


const rules = computed(() => ({
    email: { required },
    password: { required },
}));

const v$ = useVuelidate(rules, form);

const formSubmit = async() =>{
	errors.value = '';
	v$.value.$touch();
    if (v$.value.$error) {
        return;
    }

	let response = await authStore.login(form.value);
    if (response) {
        if (!response.success) {
			errors.value = response.message;
            if (response.errors) {
                for (let key in response.errors) {
                    let error = response.errors[key];
                    v$.value[key].$serverError = error
                    v$.value[key].error = true
                }
            }
            else if (response.message) {
                toast.add({ severity: 'error', summary: 'Error', detail: response.message, life: 3000 });
            }
        } else {
            router.push({ name: 'dashboard' })
        }
    }
}

</script>
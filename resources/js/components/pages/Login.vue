<template>
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>
                    <!-- <div v-if="validationErrors" class="alert alert-danger" role="alert">
                        {{ validationErrors }}
                    </div> -->

                    <div v-if="validationErrors.length > 0" class="alert alert-danger" role="alert">
                        <ul>
                            <!-- Display each error message as a list item -->
                            <li v-for="error in validationErrors" :key="error">{{ error }}</li>
                        </ul>
                    </div>



                    <!-- <div class="col-12" v-if="Object.values(validationErrors).length > 0">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
                            </ul>
                        </div>
                    </div> -->
                    <form action="javascript:void(0)" class="row" method="post">
                        <div class="input-group mb-3">
                            <input v-model="auth.email" type="text" class="form-control" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="auth.password" type="password" class="form-control" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block" :disabled="processing"
                                    @click="login">
                                    {{ processing ? "Please wait" : "Login" }}
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuthUserStore } from '@/store/authUserStore';
import { ref } from 'vue';
import router from '@/router'
const authUserStore = useAuthUserStore();

const auth = ref({
    email: '',
    password: '',
    accessToken: null,
});

const validationErrors = ref({});
const processing = ref(false);

const login = async () => {
    validationErrors.value = {};
    processing.value = true

    try {
        const { data } = await axios.post('/api/auth/login', auth.value);

        // if (data.success === true) {
            authUserStore.getAuthUser();
            auth.value.accessToken = data.access_token;
            router.push({ name: "dashboard" });
            // localStorage.setItem('access_token', auth.value.accessToken);
        // }
    } catch (error) {
        // console.log('login error',error.response);
        if (error.response && error.response.status === 422) {
            validationErrors.value = Object.values(error.response.data.message).flat();
        } else {
            validationErrors.value = {};
            alert(error.response);
        }
    } finally {
        processing.value = false;
    }


    // axios.post('/api/login', auth.value).then(({ data }) => {
    //     if (data.success === true) {
    //         authUserStore.getAuthUser();
    //         accessToken = data.access_token;
    //         $router.push({ name: "dashboard" });
    //         // localStorage.setItem('access_token', accessToken);
    //     }
    // }).catch((error) => {
    //     if (error.response && error.response.status === 422) {
    //         validationErrors.value = Object.values(error.response.data.message).flat();
    //     } else {
    //         validationErrors.value = {};
    //         alert(error.response);
    //     }
    // }).finally(() => {
    //     processing.value = false
    // })
}

</script>
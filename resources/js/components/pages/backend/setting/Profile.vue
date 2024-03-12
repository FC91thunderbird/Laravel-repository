<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <input type="file" @change="handleFileChange" ref="fileInput" class="d-none"
                                    accept=".png,.jpeg">
                                <img :src="'/storage/users/' + authUser?.user.image"
                                    class="profile-user-img img-fluid img-circle" :alt="authUser?.user.name"
                                    @click="openFileInput">
                            </div>
                            <h3 class="profile-username text-center">{{ authUser?.user.name }}</h3>

                            <p class="text-muted text-center">{{ authUser?.user.email }}</p>
                            <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">

                                <div class="active tab-pane" id="settings">
                                    <form class="form-horizontal" @submit.prevent="updateUser">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="name" v-model="authUser.user.name" class="form-control"
                                                    placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" v-model="authUser.user.email" class="form-control"
                                                    placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Username</label>
                                            <div class="col-sm-10">
                                                <input type="text" v-model="authUser.user.username" class="form-control"
                                                    placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { useAuthUserStore } from '@/store/authUserStore';


export default {
    name: "Profile",

    setup() {
        const authUser = useAuthUserStore();
        return {
            authUser
        }
    },
    methods: {
        openFileInput() {
            this.$refs.fileInput.click();
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            this.authUser.user.image = URL.createObjectURL(file);

            const formData = new FormData();
            formData.append('profile_picture', file);

            axios.post('/api/admin/upload-profile-image', formData)
                .then((response) => {
                    // toastr.success('Image uploaded successfully!');
                });
        },
        updateUser() {
            console.log('profile sub')
        }
    }
}

</script>
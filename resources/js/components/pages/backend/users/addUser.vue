<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ isEditMode ? 'Edit User' : 'Add New User' }}</h3>
                <RouterLink to="/admin/users" class="btn btn-sm btn-primary float-end">Back
                </RouterLink>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form @submit.prevent="saveUser" enctype="multipart/form-data" method="post">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" v-model="user.name">
                        <div class="error-message">{{ errors.name ? errors.name[0] : '' }}</div>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" v-model="user.username">
                        <div class="error-message">{{ errors.username ? errors.username[0] : '' }}</div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" v-model="user.email">
                        <div class="error-message">{{ errors.email ? errors.email[0] : '' }}</div>
                    </div>
                    <div class="form-group" v-if="!isEditMode">
                        <label>Password</label>
                        <input type="password" class="form-control" v-model="user.password">
                        <div class="error-message">{{ errors.password ? errors.password[0] : '' }}</div>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select v-model="user.roleId" class="form-control">
                            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                        </select>
                        <div class="error-message">{{ errors.roleId ? errors.roleId[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" class="form-control" @change="handleImageUpload" accept="image/jpeg">

                        <img v-bind:src="imagePreview == null ? '/storage/users/' + user.image : imagePreview" width="100"
                            height="100" />
                        <div class="error-message">{{ errors.image ? errors.image[0] : '' }}</div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">{{ isEditMode ? 'Update' : 'Save' }}</button>
                    <button type="button" @click="cancel" class="btn btn-info">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            user: {
                id: null,
                name: '',
                email: '',
                roleId: '',
                image: null
            },
            isEditMode: false,
            roles: [],
            errors: {},
            imagePreview: null,
        }
    },
    mounted() {
        this.isEditMode = this.$route.params.id !== undefined;
        if (this.isEditMode) {
            this.editUser();
        }

        this.fetchRoles();
    },
    methods: {
        fetchRoles() {
            axios.get(`/api/admin/roles`).then(response => {
                this.roles = response.data;
            }).catch(error => {
                console.log(error)
            })
        },

        handleImageUpload(event) {
            this.user.image = event.target.files[0];
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.imagePreview = reader.result;
            }.bind(this), false);
            if (this.user.image) {
                if (/\.(jpe?g|png|gif)$/i.test(this.user.image.name)) {
                    reader.readAsDataURL(this.user.image);
                }
            }
        },

        async saveUser() {
            if (this.isEditMode) {
                // Update existing user
                await axios.post(`/api/admin/users/${this.$route.params.id}`, this.user, {
                    headers: { 'Content-Type': 'multipart/form-data'},
                })
                    .then(resp => {
                        if (resp.data.success === true) {
                            this.$router.push({ name: "admin.users" })
                        }
                    }).catch((error) => {
                        if (error.response && error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                    })
            } else {
                await axios.post('/api/admin/users', this.user, { headers: { 'Content-Type': 'multipart/form-data' }, }).then(resp => {
                    if (resp.data.success === true) {
                        this.$router.push({ name: "admin.users" })
                    }
                }).catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error(error);
                    }
                })
            }
        },

        editUser() {
            axios.get(`/api/admin/users/${this.$route.params.id}`).then(response => {
                this.user = { ...response.data };
            }).catch(error => {
                console.log(error)
            })
        },
        cancel() {
            this.$router.push('/admin/users'); // Redirect to the users list when canceling
            this.isEditMode = this.$route.params.id !== undefined; // Set isEditMode based on the route
            this.user.id = null; // Set id to null explicitly
        },
    }
}

</script>
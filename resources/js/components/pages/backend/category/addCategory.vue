<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ isEditMode ? 'Edit Category' : 'Add New Category' }}</h3>
                <RouterLink to="/admin/category" class="btn btn-sm btn-primary float-end">Back
                </RouterLink>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form @submit.prevent="saveCategory" enctype="multipart/form-data" method="post">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" v-model="category.name">
                        <div class="error-message">{{ errors.name ? errors.name[0] : '' }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" class="form-control" @change="handleImageUpload" accept="image/jpeg">

                        <img v-bind:src="imagePreview == null ? '/storage/category/' + category.image : imagePreview" width="100"
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
import {useToastr} from '@/toastr'
const toastr = useToastr();
export default{
    name: 'Category',
    data(){
        return{
            category:{
                name:'',
                image:''
            },
            errors:'',
            isEditMode: false,
            imagePreview: null,
        }
    },
    mounted() {
        this.isEditMode = this.$route.params.id !== undefined;
        if (this.isEditMode) {
            this.editCategory();
        }
    },
    methods:{
        async saveCategory() {
            if (this.isEditMode) {
                // Update existing user
                await axios.post(`/api/admin/category/${this.$route.params.id}`, this.category, {
                    headers: { 'Content-Type': 'multipart/form-data'},
                }).then((resp) => {
                        if (resp.data.success === true) {
                            this.$router.push({ name: "admin.category" })
                            toastr.success(resp.data.message)
                        }else{
                            this.errors = resp.data.message
                        }
                    }).catch((error) => {
                        if (error.response && error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }
                    })
            } else {
                await axios.post('/api/admin/category', this.category, { headers: { 'Content-Type': 'multipart/form-data' }, }).then(resp => {
                    if (resp.data.success === true) {
                        this.$router.push({ name: "admin.category" })
                        toastr.success(resp.data.message)
                    }else{
                            this.errors = resp.data.message
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

        editCategory() {
            axios.get(`/api/admin/category/${this.$route.params.id}`).then(response => {
                this.category = { ...response.data };
            }).catch(error => {
                console.log(error)
            })
        },
        cancel() {
            this.$router.push('/admin/category'); // Redirect to the users list when canceling
            this.isEditMode = this.$route.params.id !== undefined; // Set isEditMode based on the route
            this.category.id = null; // Set id to null explicitly
        },


        handleImageUpload(event) {
            this.category.image = event.target.files[0];
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.imagePreview = reader.result;
            }.bind(this), false);
            if (this.category.image) {
                if (/\.(jpe?g|png|gif)$/i.test(this.category.image.name)) {
                    reader.readAsDataURL(this.category.image);
                }
            }
        },
    }
}

</script>
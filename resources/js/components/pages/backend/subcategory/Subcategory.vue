<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">SubCategory List ({{ totalRecord }})</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" v-model="searchTerm" @input="searchSubcategory"
                                class="form-control float-right mr-3" placeholder="search...">
                            <div class="input-group-append">
                                <button @click="addModal" class="btn btn-sm btn-primary">Add</button>
                                <!-- <router-link :to='{ name: "admin.addcategory" }' class="btn btn-sm btn-primary float-end">Add SubCategory </router-link> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>slug</th>
                                <th>Image</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="filter.length > 0">
                            <tr v-for="(subcat, key) in filter" :key="key">
                                <td>{{ subcat.id }}</td>
                                <td>{{ subcat.name }}</td>
                                <td>{{ subcat.category }}</td>
                                <td>{{ subcat.slug }}</td>
                                <td> <img :src="getImageUrl(subcat.image)" :alt="subcat.image" height="70"> </td>
                                <td>
                                    <button @click="editModal(subcat.id)" class="btn btn-sm btn-primary mr-1"><i
                                            class="fa fa-pencil-alt"></i></button>
                                    <button @click="deleteSubcategory(subcat.id)" class="btn btn-sm btn-danger"> <i
                                            class="fa fa-trash"></i> </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="6" align="center">No subcategory Found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="handlePageChange" />
            </div>
        </div>
    </div>

    <div class="modal fade" :class="{ 'show': showModal }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ isEdited ? 'Edit' : 'Add' }} Subcategory</h4>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form ref="myForm" @submit.prevent="formSubmit">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Category</label>
                            <select v-model="form.cat_id" class="form-control">
                                <option>Select Category</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <div class="error-message">{{ errors.cat_id ? errors.cat_id[0] : '' }}</div>
                        </div>

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" />
                            <div class="error-message">{{ errors.name ? errors.name[0] : '' }}</div>
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" class="form-control" @change="handleImageUpload" accept="image/jpeg" />
                            <img :src="imagePreview == null ? '/storage/subcategory/' + form.image : imagePreview"
                                width="100" height="100" />

                            <div class="error-message">{{ errors.image ? errors.image[0] : '' }}</div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" @click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary"> {{ isEdited ? 'Update' : 'Save' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Pagination from '../components/Pagination.vue';
import {useToastr} from '@/toastr';

const toastr = useToastr();

const form = ref({
    name: '',
    cat_id: '',
    image: null
});

const currentPage = ref(1);
const totalPages = ref(1)
const totalRecord = ref(0);

const subcategories = ref([]);
const filter = ref([]);
const isEdited = ref(false);
const searchTerm = ref('');
const showModal = ref(false);
const imagePreview = ref(null);
const errors = ref('');
const categories = ref([]);
const subid = ref('')

onMounted(() => {
    fetchSubcategory();
    category();
})

const fetchSubcategory = () => {
    axios.get(`/api/admin/subcategory?page=${currentPage.value}`).then((res) => {
        subcategories.value = res.data.data;
        filter.value = subcategories.value;
        totalRecord.value = res.data.meta.total;
        totalPages.value = res.data.meta.last_page;
    }).catch((error) => {
        console.log('subcategory', error);
        filter.value = [];
    })
};

const handlePageChange = (newPage) => {
    currentPage.value = newPage;
    fetchSubcategory();
}

const category = () => {
    axios.get('/api/admin/category').then((res) => {
        categories.value = res.data.data
    })
};

// modal
const addModal = () => {
    isEdited.value = false;
    showModal.value = true;
};

const editModal = (id) => {
    subid.value = id;
    isEdited.value = true;
    showModal.value = true;

    axios.get(`/api/admin/subcategory/${id}`).then(resp => {
        form.value = { ...resp.data };
    }).catch(error => {
        console.log(error)
    })
};

const closeModal = () => {
    fetchSubcategory();
    showModal.value = false;
    isEdited.value = false;
    form.value.name = '';
    form.value.cat_id = '';
    form.value.image = null;
    imagePreview.value = null;
    errors.value = '';
};

const deleteSubcategory = async (id) => {
    if (confirm('Are you sure to delete?')) {
        await axios.delete(`/api/admin/subcategory/${id}`).then((res) => {
            if (res.data.success === true) {
                fetchSubcategory();
                toastr.success(res.data.message)
            }
        }).catch((error) => {
            console.log('del subcat', error);
        })
    }
};

const searchSubcategory = () => {
    const searchTermLower = searchTerm.value.toLowerCase();
    filter.value = subcategories.filter(subcat =>
        subcat.name.toLowerCase().includes(searchTermLower)
    );
};

const getImageUrl = (image) => '/storage/subcategory/' + image;

const formSubmit = async () => {
    const formData = new FormData();
    formData.append('name', form.value.name);
    formData.append('cat_id', form.value.cat_id);
    formData.append('image', form.value.image);

    if (isEdited.value) {
        await axios.post(`/api/admin/subcategory/${subid.value}`, formData).then((resp) => {
            if (resp.data.success === true) {
                closeModal();
                toastr.success(resp.data.message)
            } else {
                errors.value = resp.data.message
            }
        }).catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        })
    } else {
        await axios.post('/api/admin/subcategory', formData).then(resp => {
            if (resp.data.success === true) {
                closeModal();
                toastr.success(resp.data.message)
            } else {
                errors.value = resp.data.message
            }
        }).catch((error) => {
            if (error.response && error.response.status === 422) {
                errors.value = error.response.data.errors;
            } else {
                console.error(error);
            }
        })
    }
};

const handleImageUpload = (event) => {
    form.value.image = event.target.files[0];
    let reader = new FileReader();
    reader.addEventListener("load", function () {
        imagePreview.value = reader.result;
    }.bind(this), false);
    if (form.value.image) {
        if (/\.(jpe?g|png|gif)$/i.test(form.value.image.name)) {
            reader.readAsDataURL(form.value.image);
        }
    }
};
</script>

<style scoped>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal.show {
    display: block;
}
</style>
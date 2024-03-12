<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ isEditMode ? 'Edit' : 'Add' }} Product</h3>
                <RouterLink to="/admin/product" class="btn btn-sm btn-primary float-right">Back </RouterLink>
            </div>
            <div class="card-body">
                <form @submit.prevent="saveProduct" enctype="multipart/form-data" method="post">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="form.title">
                        <div class="error-message">{{ errors.title ? errors.title[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" v-model="form.category" @change="fetchSubcategoies($event.target.value)">
                            <option value="" disabled>Select Category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name
                            }}</option>
                        </select>
                        <div class="error-message">{{ errors.category ? errors.category[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Subcategory</label>
                        <select class="form-control" v-model="form.subcategory">
                            <option value="" disabled>Select Subcategory</option>
                            <option v-for="subcat in subcategories" :key="subcat.id" :value="subcat.id">{{ subcat.name }}
                            </option>
                        </select>
                        <div class="error-message">{{ errors.subcategory ? errors.subcategory[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Buy Price</label>
                        <input type="number" class="form-control" v-model="form.buy_price">
                        <div class="error-message">{{ errors.buy_price ? errors.buy_price[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Sell Price</label>
                        <input type="number" class="form-control" v-model="form.price">
                        <div class="error-message">{{ errors.price ? errors.price[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Sizes</label>
                        <div v-for="size in sizes" :key="size">
                            <input type="checkbox" :id="'size_' + size" v-model="form.size" :value="size"
                                class="form-checkbox" />
                            <label :for="'size_' + size">{{ size }}</label>
                        </div>
                        <div class="error-message">{{ errors.size ? errors.size[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Colors</label>
                        <div v-for="(color, key) in colors" :key="key">
                            <input type="checkbox" :id="'color_' + color" v-model="form.color" :value="color"
                                class="form-checkbox" />
                            <label :for="'color_' + color">{{ color }}</label>
                        </div>
                        <div class="error-message">{{ errors.color ? errors.color[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" v-model="form.description"></textarea>
                        <div class="error-message">{{ errors.description ? errors.description[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" class="form-control" @change="handleImageUpload" accept="image/jpeg">

                        <img v-bind:src="imagePreview == null ? '/storage/product/' + form.image : imagePreview" width="100"
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

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import Routers from '@/router'
import { useToastr } from '@/toastr'

const toastr = useToastr();

const form = ref({
    title: '',
    category: '',
    subcategory: '',
    buy_price: '',
    price: '',
    size: [],
    color: [],
    description: '',
    image: null,
});

const categories = ref([]);
const subcategories = ref([]);


const sizes = ref(['large', 'medium', 'small']);
const colors = ref(['black', 'white', 'red', 'yellow']);
const errors = ref('');
const imagePreview = ref(null);
const isEditMode = ref(false);

const router = useRoute();

const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/admin/category');
        categories.value = response.data.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

const fetchSubcategoies = async (catId) => {
    if (catId) {
        try {
            const response = await axios.get(`/api/admin/categoryWiseSubcategory/${catId}`);
            subcategories.value = response.data;
        } catch (error) {
            console.error('fetch subcat', error);
        }
    }
}


onMounted(() => {
    fetchCategories();
    
    isEditMode.value = router.params.id !== undefined;
    if (isEditMode.value) {
        editProductById(router.params.id);
    }
});

const editProductById = async (id) => {
    await axios.get(`/api/admin/products/${id}`).then((res) => {
        form.value = res.data;
        fetchSubcategoies(res.data.category);
    }).catch((error) => {
        console.error('edit Prod', error);
    })
}

const saveProduct = async () => {
    errors.value = '';
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('category', form.value.category);
    formData.append('subcategory', form.value.subcategory);
    formData.append('buy_price', form.value.buy_price);
    formData.append('price', form.value.price);
    formData.append('size[]', form.value.size);
    formData.append('color[]', form.value.color);
    formData.append('description', form.value.description);
    formData.append('image', form.value.image);

    if (isEditMode.value) {
        await axios.post(`/api/admin/products/${router.params.id}`, formData).then((resp) => {
            if (resp.data.success === true) {
                Routers.push({ name: "admin.product" })
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
        await axios.post('/api/admin/products', formData).then(resp => {
            if (resp.data.success === true) {
                Routers.push({ name: "admin.product" })
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
}

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
}
const cancel = () => {
    Routers.push({ name: "admin.product" })
    isEditMode.value = false;
}
</script>


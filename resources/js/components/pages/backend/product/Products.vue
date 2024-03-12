<template>
    <Loader v-if="isLoading" />
    <div class="row" v-else>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products List ({{ totalRecord }})</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" v-model="searchTerm" @input="searchProduct"
                                class="form-control float-right mr-3" placeholder="search...">
                            <div class="input-group-append">
                                <router-link :to='{ name: "admin.addProduct" }' class="btn btn-sm btn-primary float-end">
                                    Add Product </router-link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Sizes</th>
                                <th>Colors</th>
                                <th>Buy Price</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="filters.length > 0">
                            <tr v-for="(product, key) in filters" :key="key">
                                <td>{{ product.id }}</td>
                                <td>{{ product.title }}</td>
                                <td>{{ product.category }}</td>
                                <td>{{ product.subcategory }}</td>
                                <td>
                                    <div v-for="sizes in product.size" :key="sizes"> <small class="badge badge-info">{{
                                        sizes }}</small> </div>
                                </td>
                                <td>
                                    <div v-for="colors in product.color" :key="colors">
                                        <small class="badge badge-info">{{ colors }}</small>
                                    </div>
                                </td>
                                <td>{{ product.buy_price }}</td>
                                <td>{{ product.price }}</td>
                                <td> <img :src="getImageUrl(product.image)" :alt="product.title" height="70"> </td>
                                <td><router-link :to='{ name: "admin.editProduct", params: { id: product.id } }'
                                        class="btn btn-sm btn-primary mr-1"> <i class="fa fa-pencil-alt"></i> </router-link>
                                    <button @click="deleteProduct(product.id)" class="btn btn-sm btn-danger"> <i
                                            class="fa fa-trash"></i> </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="7" align="center">No Products Found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :current-page="currentPage" :total-pages="totalPages" @page-change="handlePageChange" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Pagination from '../components/Pagination.vue'
import Loader from '../components/Loading.vue'

const isLoading = ref(false);

const filters = ref([]);
const products = ref([]);
const searchTerm = ref('');

const currentPage = ref(1);
const totalPages = ref(0);
const totalRecord = ref(0);

onMounted(()=>{
    fetchProducts();
    isLoading.value = true;

    setTimeout(()=>{
        isLoading.value = false;
    }, 1000)
})

const fetchProducts = async () => {
    await axios.get(`/api/admin/products?page=${currentPage.value}`).then((res) => {
        products.value = res.data.data;
        filters.value = products.value;
        totalRecord.value = res.data.meta.total;
        totalPages.value = res.data.meta.last_page;
    }).catch((err) => {
        console.log('product fetch', err);
        filters.value = [];
    })
};

const handlePageChange = (page) => {
    currentPage.value = page;
    fetchProducts();
}

const deleteProduct = async (id) => {
    if (confirm('Are you sure delete?')) {
        await axios.delete(`/api/admin/products/${id}`).then((res) => {
            if (res.data.success === true) {
                fetchProducts();
            }
        }).catch((error) => {
            console.log('Delete Product', error)
        })
    }
}

const searchProduct = () => {
    const searchTermLower = searchTerm.value.toLowerCase();
    filters.value = products.value.filter(product =>
        product.title.toLowerCase().includes(searchTermLower)
    );
};

const getImageUrl = (image) => '/storage/product/' + image;
</script>

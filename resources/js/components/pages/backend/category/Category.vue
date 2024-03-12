<template>
<Loader v-if="isLoading" />
    <div class="row" v-else>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Category List ({{ totalRecord }})</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" v-model="searchTerm" @input="searchCategory"
                                class="form-control float-right mr-3" placeholder="search...">
                            <div class="input-group-append">
                                <router-link :to='{ name: "admin.addcategory" }' class="btn btn-sm btn-primary float-end">
                                    Add
                                    Category </router-link>
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
                                <th>slug</th>
                                <th>Image</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="filterCategory.length > 0">
                            <tr v-for="(cat, key) in filterCategory" :key="key">
                                <td>{{ cat.id }}</td>
                                <td>{{ cat.name }}</td>
                                <td>{{ cat.slug }}</td>
                                <td> <img :src="getImageUrl(cat.image)" :alt="cat.image" height="70"> </td>
                                <td><router-link :to='{ name: "admin.editCat", params: { id: cat.id } }'
                                        class="btn btn-sm btn-primary mr-1"> <i class="fa fa-pencil-alt"></i> </router-link>
                                    <button @click="deleteCategory(cat.id)" class="btn btn-sm btn-danger"> <i
                                            class="fa fa-trash"></i> </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="4" align="center">No category Found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <pagination :current-page="currentPage" :total-pages="totalPages" @page-change="handlePageChange" />
            </div>
        </div>
    </div>
</template>

<script setup>
import Pagination from '../components/Pagination.vue'
import { ref, onMounted } from 'vue'
import { useToastr } from '@/toastr'
import Loader from '../components/Loading.vue'

const toastr = useToastr();
const isLoading = ref(false);

const categories = ref([]);
const filterCategory = ref([]);
const searchTerm = ref('');


const totalRecord = ref(0);
const currentPage = ref(1);
const totalPages = ref(1);

onMounted(()=>{
    fetchCategory();
    isLoading.value = true;
    setTimeout(() => {
      isLoading.value = false;
    }, 1000);
});

const fetchCategory = async () => {
    await axios.get(`/api/admin/category?page=${currentPage.value}`).then((res) => {
        categories.value = res.data.data;
        filterCategory.value = categories.value;
        totalRecord.value = res.data.total;
        totalPages.value = res.data.last_page;
    }).catch((error) => {
        console.log('category', error);
        filterCategory.value = [];
    })
}

const handlePageChange = (newPage) => {
    currentPage.value = newPage;
    fetchCategory();
}

const deleteCategory = async (id) => {
    if (confirm('Are you sure to delete?')) {
        await axios.delete(`/api/admin/category/${id}`).then((res) => {
            if (res.data.success === true) {
                fetchCategory();
                toastr.success(res.data.message)
            }
        }).catch((error) => {
            console.log('del cat', error);
        })
    }
}

const searchCategory = () => {
    const searchTermLower = searchTerm.value.toLowerCase();
    filterCategory.value = categories.value.filter(user =>
        user.name.toLowerCase().includes(searchTermLower)
    );
}

const getImageUrl = (image) => {
    return '/storage/category/' + image;
}
</script>
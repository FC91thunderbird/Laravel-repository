<template>
    <div class="card rounded-lg shadow-xs">
    <div class="grid grid-cols-1 lg:grid-cols-1 p-4 gap-4">
      <div class="mt-4 mx-2">
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
          <div class="w-full overflow-x-auto">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0 mb-4">
              <h1 class="text-xl text-gray-900 font-semibold"> Category Lists ({{ pagination?.total }}) </h1>
              <div class=" grid gap-2 grid-cols-2">
                <input type="search" placeholder="Search" v-model.lazy="searchTerm"
                  class="shadow-sm rounded-sm w-full px-3 py-1 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                <RouterLink :to="{ name: 'category.add' }" class="px-10 py-1 bg-gray-900 rounded p-2 text-white">
                  Add New</RouterLink>
              </div>
            </div>

            <table class="table w-full border-separate">
              <thead>
                <tr
                  class="text-xm font-semibold tracking-wide text-left text-white border-b dark:border-gray-700 bg-gray-700 dark:text-gray-400 dark:bg-gray-800">
                  <th class="px-4 py-3">Id</th>
                  <th class="px-4 py-3">Name</th>
                  <th class="px-4 py-3">Slug</th>
                  <th class="px-4 py-3">Image</th>
                  <th class="px-4 py-3">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" v-if="categories.length > 0">
                <tr v-for="category in categories" :key="category.id"
                  class="bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-700 dark:text-gray-400">
                  <td class="px-4 py-3"> {{ category.id }} </td>
                  <td class="px-4 py-3 text-sm">{{ category.name }}</td>
                  <td class="px-4 py-3 text-sm">{{ category.slug }}</td>
                  <td class="px-4 py-3 text-sm"> <img :src="'/storage/'+ category.image" :alt="category.name" :height="50" :width="50"> </td>
                  
                  <td class="px-4 py-3 text-xs">
                    <RouterLink :to='{ name: "category.edit", params: { id: category.id } }'
                      class="px-2 py-1 mr-1 leading-tight text-white bg-gray-700 dark:bg-gray-700 rounded dark:text-gray-100">
                      Edit</RouterLink>
                    <button @click="deleteItem(category.id)"
                      class="px-2 py-1 leading-tight text-white bg-red-700 dark:bg-gray-700 rounded dark:text-gray-100">Delete</button>
                  </td>
                </tr>
              </tbody>

              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800" v-else>
                <tr> <td colspan="5" class="bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-700 dark:text-gray-400 text-center">No Data Found</td></tr>
              </tbody>
            </table>

          </div>
          <pagination :current-page="pagination?.current_page" :total-pages="pagination?.total_pages"
            :from="pagination?.from" :to="pagination?.to" :records="pagination?.total" @page-change="handlePageChange" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router'
import Pagination from '@/services/Pagination.vue'
import ApiService from '@/services/ApiService'
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const router = useRouter();
const categories = ref([]);
const searchTerm = ref('');

const pagination = ref({
  current_page: 1,
  last_page: 0,
  per_page: 10,
  total_pages: 0,
  total: 0,
  from: 0,
  to: 0
});

onMounted(()=>{
    fetchCategory();
});

const headers = computed(() => ({
        Authorization: `Bearer ${authStore.token}`,
    }));

const fetchCategory = async () =>{
    let params = {
    per_page: pagination.value.per_page,
    page: pagination.value.current_page,
    search: searchTerm.value
  };
  
  try {
    let response = await ApiService.get("/api/admin/category", params.value, headers.value);

    if(response.success){
        categories.value = response.data.categories.data;
        pagination.value = response.data.categories.pagination;
    }else{
        if(response.status === 401){
            authStore.flushUser();
            router.push({ name: 'login' });
        }
    }
  } catch (error) {
    console.log('Category fetch', error);
  }
};

const handlePageChange = (newPage) => {
  pagination.value.current_page = newPage;
  fetchCategory();
};


const deleteItem = async (id) => {
  if (confirm('Are you sure to delete?')) {

    let response = await ApiService.delete(`/api/admin/category/${id}`, headers.value);

    if(response){
        if(!response.success){
            let message = 'Something went wrong';
            if(response.message){
                message = response.message;
            }
            // toastr
        }else{
            fetchCategory();
            let message = 'Category Deleted Successfull';
            // toastr
        }
    }
  }
};


</script>

<template>
  <div class="card rounded-lg shadow-xs">
    <div class="grid grid-cols-1 lg:grid-cols-1 p-4 gap-4">
      <div class="mt-4 mx-2">
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
          <div class="w-full overflow-x-auto">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-2 md:space-y-0 mb-4">
              <h1 class="text-xl text-gray-900 font-semibold"> Users Lists ({{ pagination.total }}) </h1>
              <div class=" grid gap-2 grid-cols-2">
                <input type="search" placeholder="Search" v-model.lazy="searchTerm"
                  class="shadow-sm rounded-sm w-full px-3 py-1 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" />
                <RouterLink :to="{ name: 'user.add' }" class="px-10 py-1 bg-gray-900 rounded p-2 text-white">
                  Add New</RouterLink>
              </div>
            </div>

            <table class="table w-full border-separate">
              <thead>
                <tr
                  class="text-xm font-semibold tracking-wide text-left text-white border-b dark:border-gray-700 bg-gray-700 dark:text-gray-400 dark:bg-gray-800">
                  <th class="px-4 py-3">Id</th>
                  <th class="px-4 py-3">Name</th>
                  <th class="px-4 py-3">Username</th>
                  <th class="px-4 py-3">Email</th>
                  <th class="px-4 py-3">Role</th>
                  <th class="px-4 py-3">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <tr v-for="user in users" :key="user.id"
                  class="bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-900 text-gray-700 dark:text-gray-400">
                  <td class="px-4 py-3">
                    {{ user.id }}
                  </td>
                  <td class="px-4 py-3 text-sm">{{ user.name }}</td>
                  <td class="px-4 py-3 text-sm">{{ user.username }}</td>
                  <td class="px-4 py-3 text-sm">{{ user.email }}</td>
                  <td class="px-4 py-3 text-sm"> <span
                      class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:bg-blue-700 dark:text-blue-100">
                      {{ user.role }} </span></td>
                  <td class="px-4 py-3 text-xs">
                    <RouterLink :to='{ name: "user.edit", params: { id: user.id } }'
                      class="px-2 py-1 mr-1 leading-tight text-white bg-gray-700 dark:bg-gray-700 rounded dark:text-gray-100">
                      Edit</RouterLink>
                    <button @click="deleteItem(user.id)"
                      class="px-2 py-1 leading-tight text-white bg-red-700 dark:bg-gray-700 rounded dark:text-gray-100">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>

          </div>
          <pagination :current-page="pagination.current_page" :total-pages="pagination.total_pages"
            :from="pagination.from" :to="pagination.to" :records="pagination.total" @page-change="handlePageChange" />



        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useUserStore } from '@/stores/user';
import { useToast } from 'primevue/usetoast';
import { useConfirm } from "primevue/useconfirm";
import Pagination from '@/services/Pagination.vue'


const userStore = useUserStore();
const toast = useToast();
const confirm = useConfirm();

const loading = ref(false);
const users = ref([]);
const searchTerm = ref('');

const sortBy = ref('created_at');
const sortDirection = ref('desc');
const pagination = ref({
  current_page: 1,
  last_page: 0,
  per_page: 10,
  total_pages: 0,
  total: 0,
  from: 0,
  to: 0
});


onMounted(() => {
  getList();
});


const getList = async () => {
  let params = {
    per_page: pagination.value.per_page,
    page: pagination.value.current_page,
    sortBy: sortBy.value,
    sortDirection: sortDirection.value,
    search: searchTerm.value
  };
  loading.value = true;
  try {
    let response = await userStore.getList(params);
    if (response.success) {
      users.value = response.data.users.data;
      pagination.value = response.data.users.pagination;
    } else {
      if (response.status == 401) {
        authStore.flushUser()
        router.push({ name: 'login' });
        return false;
      }
      let message = 'something_went_wrong';
      if (response.message) {
        message = response.message;
      }
      toast.add({ severity: 'error', summary: 'error', detail: message, life: 3000 });
    }
  } catch (error) {
    console.error(error);
    toast.add({ severity: 'error', summary: 'error', detail: 'something_went_wrong', life: 3000 });
  } finally {
    loading.value = false;
  }
};

const handlePageChange = (newPage) => {
  pagination.value.current_page = newPage;
  getList();
}

const deleteItem = async (record) => {
  // if (confirm('Are you sure to delete?')) {
  let response = await userStore.destroy(record);
  if (response) {
    if (!response.success) {
      let message = 'something_went_wrong';
      if (response.message) {
        message = response.message;
      }
      toast.add({ severity: 'error', summary: 'error', detail: message, life: 3000 });
    } else {
      getList();
      let message = 'user_deleted_successfully';
      toast.add({ severity: 'success', summary: 'deleted', detail: message, life: 3000 });
    }
  }
  // }
}


watch(searchTerm, (newSearchTerm, oldSearchTerm) => {
  if (newSearchTerm !== oldSearchTerm) {
    getList(newSearchTerm);
  }
});

</script>

<!-- Pagination.vue -->
<template>
    <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-right">
            <li class="page-item">
                <a class="page-link" @click="prevPage" :disabled="currentPage === 1">&laquo;</a>
            </li>
            <li v-for="page in totalPages" :key="page" class="page-item" :class="{ 'active': page === currentPage }">
                <a class="page-link" @click="goToPage(page)">{{ page }}</a>
            </li>
            <li class="page-item">
                <a class="page-link" @click="nextPage" :disabled="currentPage === totalPages">&raquo;</a>
            </li>
        </ul>
    </div>
</template>
  
<script setup>
import { defineProps,defineEmits } from 'vue'

const { currentPage, totalPages } = defineProps(['currentPage', 'totalPages']);
const emit = defineEmits();

const prevPage = () => {
    if (currentPage.value > 1) {
        emit('page-change', currentPage.value - 1);
    }
};
const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        emit('page-change', currentPage.value + 1);
    }
};
const goToPage = (page) => {
    emit('page-change', page);
};
</script>
  
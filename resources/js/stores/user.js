import { defineStore } from "pinia";
import { ref, computed } from "vue";
import ApiService from "../services/ApiService";
import { useAuthStore } from "./auth";

export const useUserStore = defineStore("user", () => {
    const authStore = useAuthStore();

    const headers = computed(() => ({
        Authorization: `Bearer ${authStore.token}`,
    }));

    const getList = async (params = {}) => {
        const queryParams = {
            page: params.page || 1,
            search: params.search || null,
            sort_by: params.sortBy || null,
            sort_direction: params.sortDirection || null,
            filters: params.filters || null,
            per_page: params.per_page || null,
        };
        const response = await ApiService.get(
            "/api/users",
            queryParams,
            headers.value
        );
        return response;
    };

    const getById = async (id) => {
        const response = await ApiService.get(
            `/api/users/${id}`,
            {},
            headers.value
        );
        return response;
    };

    const fetchRoles = async () => {
        const response = await ApiService.get(
            `/api/users/getRoles`,
            {},
            headers.value
        );
        return response;
    };

    const store = async (data) => {
        const response = await ApiService.post(
            "/api/users",
            data,
            headers.value
        );
        return response;
    };

    const update = async (id, data) => {
        const response = await ApiService.put(
            `/api/users/${id}`,
            data,
            headers.value
        );
        return response;
    };

    const destroy = async (id) => {
        const response = await ApiService.delete(
            `/api/users/${id}`,
            headers.value
        );
        return response;
    };

    const exportList = async (params = {}) => {
        const queryParams = {
            page: params.page || 1,
            search: params.search || null,
            sort_by: params.sortBy || null,
            sort_direction: params.sortDirection || null,
            filters: params.filters || null,
            per_page: params.per_page || null,
        };
        const response = await ApiService.get(
            "/api/users/export",
            queryParams,
            headers.value
        );
        return response;
    };

    

    return {
        getList,
        getById,
        store,
        update,
        destroy,
        exportList,
        fetchRoles
    };
});

<template>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3>Dashboard</h3>
                </div>
                <div class="card-body">
                    <p class="mb-0">You are logged in as <b>{{ authUser.user.email }}</b></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <select v-model="selectUserStatus" @change="getUserCount()"
                                class="form-control float-right mr-3">
                                <option value="all">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <b class="text-center"> {{ userCount?.user_count }}</b>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Category</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <select v-model="selectCategoryStatus" @change="getCategoryCount()"
                                class="form-control float-right mr-3">
                                <option value="all">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <b class="text-center"> {{ categoryCount?.category_count }}</b>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useAuthUserStore } from '../../store/authUserStore';

export default {
    name: "dashboard",
    data() {
        return {
            userCount: '',
            categoryCount: '',
            selectCategoryStatus: 'all',
            selectUserStatus: 'all'
        }
    },
    setup() {
        const authUser = useAuthUserStore();
        return {
            authUser
        }
    },
    mounted() {
        this.getUserCount();
        this.getCategoryCount();
    },
    methods: {
        async fetchStatitics() {
            await axios.get('/api/admin/statistics').then((res) => {
                this.stats = res.data;
            })
        },
        async getCategoryCount() {
            await axios.get('/api/admin/activeCategory', {
                params: { status: this.selectCategoryStatus, }
            }).then((response) => {
                this.categoryCount = response.data;
            })
        },
        async getUserCount() {
            await axios.get('/api/admin/getUserCount', { params: { status: this.selectUserStatus }, }).then((res) => {
                this.userCount = res.data;
            })
        }
    }
}
</script>
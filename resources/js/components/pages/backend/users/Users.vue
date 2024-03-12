<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users List ({{ filteredUsers.length }})</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" v-model="searchTerm" @input="searchUser"
                                class="form-control float-right mr-3" placeholder="search...">
                            <div class="input-group-append">
                                <router-link :to='{ name: "admin.adduser" }' class="btn btn-sm btn-primary float-end"> Add
                                    User </router-link>
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
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="filteredUsers.length > 0">
                            <tr v-for="(user, key) in filteredUsers" :key="key">
                                <td>{{ user.id }}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.username }}</td>
                                <td>{{ user.email }}</td>
                                <td> <select class="form-control" @change="roleChanged(user, $event.target.value)" >
                                <option v-for="role in roles" :key="role.id" :value="role.id" :selected="(role.name === user.role)">{{ role.name }}</option>
                                
                                </select>  {{ capitalWords(user.role) }}</td>
                                <td><img :src="getImageUrl(user.image)" :alt="user.name" height="70"></td>
                                <td><router-link :to='{ name: "admin.userEdit", params: { id: user.id } }'
                                        class="btn btn-sm btn-primary mr-1"><i class="fa fa-pencil-alt"> </i> </router-link>
                                    <button type="button" @click="deleteUser(user.id)" class="btn btn-sm btn-danger"><i
                                            class="fa fa-trash"> </i> </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="7" align="center">No Users Found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>

        </div>
    </div>
</template>

<script>
import { useToastr } from '@/toastr';
const toastr = useToastr();

export default {
    name: "Users",
    data() {
        return {
            users: [],
            filteredUsers: [],
            isEditMode: false,
            searchTerm: '',
            roles:[],

            currentPage: 1,
            totalPages: 1,
        }
    },
    mounted() {
        this.getUsers();
        this.fetchRoles();
    },
    methods: {
        async fetchRoles(){
            const { data } = await axios.get('/api/admin/roles');
            this.roles = data
        },
        async getUsers() {
            await axios.get(`/api/admin/users?page=${this.currentPage}`).then(response => {
                this.users = response.data;
                this.filteredUsers = response.data;
                // this.totalPages = response.data.length /2;
            }).catch(error => {
                console.log(error)
                this.users = []
            })
        },
        deleteUser(id) {
            if (confirm("Are you sure to delete this category ?")) {
                this.axios.delete(`/api/admin/users/${id}`).then(response => {
                    this.getUsers()
                }).catch(error => {
                    console.log(error)
                })
            }
        },
        searchUser() {
            const searchTermLower = this.searchTerm.toLowerCase();
            this.filteredUsers = this.users.filter(user =>
                user.name.toLowerCase().includes(searchTermLower) ||
                user.email.toLowerCase().includes(searchTermLower)
            );
        },
        getImageUrl(image) {
            return '/storage/users/' + image;
        },

        async roleChanged(user,role){
            await axios.patch(`/api/admin/users/roleUpdate/${user.id}`, {role:role}).then((res)=>{
                if(res.data.success === true){
                    this.getUsers();
                    toastr.success(res.data.message)
                }
            });
        },

        capitalWords(words){
            return words.split(' ').map(word=> word.charAt(0).toUpperCase() + word.slice(1)).join();
        }
    }
}
</script>
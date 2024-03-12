<template>
      <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ isEditMode ? 'Edit' : 'Add' }} Appointment</h3>
                <RouterLink to="/admin/appointment" class="btn btn-sm btn-primary float-end">Back
                </RouterLink>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form @submit.prevent="saveForm" enctype="multipart/form-data" method="post">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" v-model="form.title">
                        <div class="error-message">{{ errors.title ? errors.title[0] : '' }}</div>
                    </div>
                    <div class="form-group">
                        <label>Descripton</label>
                        <textarea v-model="form.description" class="form-control" />
                        <div class="error-message">{{ errors.description ? errors.description[0] : '' }}</div>
                    </div>
                    <div class="form-group">
                        <label>Users</label>
                        <select v-model="form.user_id" class="form-control">
                            <option value="" >Select User</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                        </select>
                        <div class="error-message">{{ errors.user_id ? errors.user_id[0] : '' }}</div>
                    </div>

                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="datetime-local" v-model="form.start_time" class="form-control">
                        <div class="error-message">{{ errors.start_time ? errors.start_time[0] : '' }}</div>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="datetime-local" v-model="form.end_time" class="form-control">
                        <div class="error-message">{{ errors.end_time ? errors.end_time[0] : '' }}</div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" v-model="form.status">
                            <option v-for="(stat,index) in statuses" :key="index" :value="index+1" :selected="form.status === index+1">{{ stat }}</option>
                        </select>
                        <div class="error-message">{{ errors.status ? errors.status[0] : '' }}</div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">{{ isEditMode ? 'Update' : 'Save' }}</button>
                    <button type="button" @click="cancel" class="btn btn-info">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import { useToastr } from '@/toastr';
import { useRoute} from 'vue-router';
import Routers from '@/router'

const toastr = useToastr();
const router = useRoute();

const statuses = ref(['Schedule', 'confirm', 'cancel']);

const form = ref({
    title: '',
    user_id: '',
    description:'',
    start_time: '',
    end_time:'',
    status:''
});

const errors = ref('');
const isEditMode = ref(false);
const users = ref([]);

onMounted(() => {
    fetchUsers();

    isEditMode.value = router.params.id !== undefined;
    if (isEditMode.value) {
        editAppointment(router.params.id);
    }
});

const fetchUsers = () =>{
    axios.get('/api/admin/usersFetch').then((res)=>{
        users.value = res.data;
    })
};

const editAppointment = async(appointmentId) =>{
    await axios.get(`/api/admin/appointments/${appointmentId}`).then((res)=>{
        form.value = res.data;
    })
}

 const saveForm = async () =>{
    const formData = new FormData();
    formData.append('title', form.value.title)
    formData.append('user_id', form.value.user_id)
    formData.append('description', form.value.description)
    formData.append('start_time', form.value.start_time)
    formData.append('end_time', form.value.end_time)
    formData.append('status', form.value.status)
        if(isEditMode.value){
            await axios.post(`/api/admin/appointments/${router.params.id}`, formData).then((res)=>{
                if(res.data.success === true){
                    toastr.success(res.data.message);
                    Routers.push({name: 'admin.appointment'})
                }
            }).catch((error)=>{
                if(error.response && error.response.status === 422){
                    errors.value = error.response.data.errors;
                }
                console.log('appoinment update: ', error);
            });
        }else{
            await axios.post('/api/admin/appointments', formData).then((res)=>{
                if(res.data.success === true){
                    toastr.success(res.data.message);
                    Routers.push({name: 'admin.appointment'});
                }
            }).catch((error)=>{
                if(error.response && error.response.status ===422){
                    errors.value = error.response.data.errors;
                }
                console.log('insert error', error);
            })
        }
 };

 const cancel = () =>{
    Routers.push({ name: "admin.appointment"})
 }

</script>

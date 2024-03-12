<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Appointment List ({{ totalRecord }})</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" v-model="searchTerm" @input="Search" class="form-control float-right mr-3"
                                placeholder="search...">
                            <div class="input-group-append">
                                <button class="btn btn-sm"
                                    :class="[typeof selectedStatus === 'undefined' ? 'btn-success' : 'btn-default']"
                                    @click="fetchAppointment()">All <span class="badge badge-pill badge-info">{{
                                        appointmentCount }}</span></button>
                                <button class="btn btn-sm btn-primary" v-for="status in appointmentStatus"
                                    @click="fetchAppointment(status.value)"
                                    :class="[selectedStatus == status.value ? 'btn-success' : 'btn-default']">{{ status.name }}
                                    <span class="badge badge-pill badge-info">{{ status.count }}</span></button>

                                <router-link :to='{ name: "admin.addAppointment" }'
                                    class="btn btn-sm btn-primary float-end"> Add Appointment </router-link>
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
                                <th>Client</th>
                                <th>Start-time</th>
                                <th>End-time</th>
                                <th>Status</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody v-if="appointments.length > 0">
                            <tr v-for="(appointment, key) in appointments" :key="key">
                                <td>{{ appointment.id }}</td>
                                <td>{{ appointment.title }}</td>
                                <td>{{ appointment.user }}</td>
                                <td>{{ appointment.start_time }}</td>
                                <td>{{ appointment.end_time }}</td>
                                <td> <span class="badge" :class="`badge-${appointment.status.color}`">{{
                                    appointment.status.name }} </span> </td>
                                <td>
                                    <router-link :to="{ name: 'admin.editAppointment', params: { id: appointment.id } }"
                                        class="btn btn-sm btn-primary mr-1"><i class="fa fa-pencil-alt"></i> </router-link>
                                    <button @click="deleteAppointment(appointment.id)" class="btn btn-sm btn-danger"> <i
                                            class="fa fa-trash"></i> </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="8" align="center">No category Found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- <pagination :current-page="currentPage" :total-pages="totalPages" @page-change="handlePageChange" /> -->
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useToastr } from '@/toastr'

const toastr = useToastr();

const appointments = ref([]);
const totalRecord = ref(0);
const searchTerm = ref('');
const appointmentStatus = ref([]);
const selectedStatus = ref('');


onMounted(() => {
    fetchAppointment();
    getAppointmentStatus();
});


const fetchAppointment = async (status) => {
    selectedStatus.value = status;
    const params = {};
    if (status) {
        params.status = status;
    }
    await axios.get(`/api/admin/appointments`, { params: params }).then((res) => {
        appointments.value = res.data.data;
        totalRecord.value = res.data.total;
    })
};

const getAppointmentStatus = () => {
    axios.get(`/api/admin/appointment-status`).then((res) => {
        appointmentStatus.value = res.data;
    })
}

const appointmentCount = computed(() => {
    return appointmentStatus.value.map(status => status.count).reduce((acc, value) => acc + value, 0);
});

const deleteAppointment = async (id) => {
    if (confirm('Are you sure to delete?')) {
        await axios.delete(`/api/admin/appointments/${id}`).then((res) => {
            if (res.data.success === true) {
                fetchAppointment();
                toastr.success(res.data.message);
            }
        }).catch((error)=>{
            console.log('delete', error.response);
        })
    }
}

const Search = () => {
    const searchLower = searchTerm.value.toLowerCase();
    appointments.value = appointments.value.filter(appointment =>
        appointment.title.toLowerCase().includes(searchLower));
};
</script>

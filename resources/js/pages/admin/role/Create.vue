<template>
    <Layout title="Create - Role">
        <v-container>
            <v-row no-gutters justify="center">
                <v-col cols="10">
                    <v-card flat>
                        <v-alert class="grey lighten-4 primary--text text-h4">
                            <inertia-link :href="$route('roles.index')" class="mdi mdi-arrow-left"></inertia-link>
                            Add New Role<v-icon large class="mr-3" color="primary">mdi-plus</v-icon>
                        </v-alert>
                        <v-form ref="roleForm" @submit.prevent="submit"  method="post">
                            <v-row no-gutters >
                                <v-col cols="12" md="5">
                                    <v-text-field outlined required v-model="title" :rules="[v => !!v || 'Role title is required', v => (v && v.length > 3) || 'You Must input Minimum 3 characters' ]"  label="Role Title"></v-text-field>
                                </v-col>
                                <v-spacer></v-spacer>
                                <v-col cols="12">
                                    <v-data-table
                                    :headers="headers"
                                    :items="permissions"
                                    hide-default-footer
                                    class="elevation-1"
                                    >
                                    <template v-slot:item.view="{ item }">
                                        <v-checkbox color="primary" v-if="item.view.value" :value="item.view.value" v-model="item.view.select"></v-checkbox>
                                        <span v-else class="text-center">-</span>
                                    </template>
                                    <template v-slot:item.create="{ item }">
                                        <v-checkbox color="primary" v-if="item.create.value" :value="item.create.value" v-model="item.create.select"></v-checkbox>
                                        <span v-else class="text-center">-</span>
                                    </template>
                                    <template v-slot:item.update="{ item }">
                                        <v-checkbox color="primary" v-if="item.update.value" :value="item.update.value" v-model="item.update.select"></v-checkbox>
                                        <span v-else class="text-center">-</span>
                                    </template>
                                    <template v-slot:item.delete="{ item }">
                                        <v-checkbox color="primary" v-if="item.delete.value" :value="item.delete.value" v-model="item.delete.select"></v-checkbox>
                                        <span v-else class="text-center">-</span>
                                    </template>
                                </v-data-table>
                                </v-col>
                                <v-col cols="4" class="mt-5">
                                    <v-btn color="primary" type="submit">Submit</v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message || text}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                Close
                </v-btn>
            </template>
        </v-snackbar>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
export default {
    data: ()=>({
        snackbar: false,
        text: '',
        title: '',
        permissions: [
            {
            page: 'Student',
            view: {value: 'student_view', select: false},
            create: {select: false},
            update: {value: 'student_update', select: false},
            delete: {value: 'student_delete',select: false},
          },
          {
            page: 'Course',
            view: {value: 'course_view', select: false},
            create: {value: 'course_create', select: false},
            update: {value: 'course_update', select: false},
            delete: {select: false},
          },
          {
            page: 'Course Category',
            view: {value: 'course_cate_view', select: false},
            create: {value: 'course_cate_create', select: false},
            update: {value: 'course_cate_update', select: false},
            delete: {select: false},
          },
          {
            page: 'Service',
            view: {value: 'service_view', select: false},
            create: {value: 'service_create', select: false},
            update: {value: 'service_update', select: false},
            delete: {value: 'service_delete', select: false},
          },
          {
            page: 'Client',
            view: {value: 'client_view', select: false},
            create: {value: 'client_create', select: false},
            update: {value: 'client_update', select: false},
            delete: {value: 'client_delete', select: false},
          },
          {
            page: 'Client Category',
            view: {value: 'client_cate_view', select: false},
            create: {value: 'client_cate_create', select: false},
            update: {value: 'client_cate_update', select: false},
            delete: {value: 'client_cate_delete', select: false},
          },
          {
            page: 'Batch',
            view: {value: 'batch_view', select: false},
            create: {value: 'batch_create', select: false},
            update: {value: 'batch_update', select: false},
            delete: {value: 'batch_delete', select: false},
          },
          {
            page: 'Gallery&Slide',
            view: {value: 'gallery_view', select: false},
            create: {value: 'gallery_create', select: false},
            update: {value: 'gallery_update', select: false},
            delete: {value: 'gallery_delete', select: false},
          },
          {
            page: 'Review',
            view: {value: 'review_view', select: false},
            create: {select: false},
            update: {value: 'review_update', select: false},
            delete: {value: 'review_delete', select: false},
          }
          
        ],
        headers: [
          {
            text: 'Permissions',
            align: 'start',
            sortable: false,
            value: 'page',
          },
          { text: 'View', value: 'view' },
          { text: 'Create', value: 'create' },
          { text: 'Update', value: 'update' },
          { text: 'Delete', value: 'delete' },
        ],
    }),
    mounted(){
        this.snackbar = $page.successMessage.success;
    },
    methods:{
        submit(){
            if(this.$refs.roleForm.validate()){
                if(this.checkValidation){
                   let permissionArray = [];
                   this.permissions.forEach(permission => {
                        if(permission.view.select){
                            permissionArray.push(permission.view.select);
                        }  
                        if(permission.create.select){
                            permissionArray.push(permission.create.select);
                        }
                        if(permission.update.select){
                            permissionArray.push(permission.update.select);
                        }
                        if(permission.delete.select){
                            permissionArray.push(permission.update.select);
                        }
                    })
                    let formData = new FormData();
                    formData.append('title',this.title);
                    formData.append('permissions',permissionArray);
                    this.$inertia.post(this.$route('roles.store'), formData, {
                        replace: false,
                        preserveState: true,
                        preserveScroll: false,
                        only: [],
                    }).then(()=>{
                        this.snackbar = true
                        if(this.$page.successMessage.success){
                            this.$refs.roleForm.reset()
                            this.title = '';
                            this.permissions.forEach(permission => {
                               permission.view.select = false,
                               permission.create.select = false,
                               permission.update.select = false,
                               permission.delete.select = false;
                            })
                        }
                    }).catch(err => console.log(err))
                    
                }
                else{
                    this.text = 'You must be select permissions'
                    this.snackbar = true
                }
            }
        },
    },
    computed:{
        checkValidation(){
            let ret = '';
            this.permissions.forEach(permission => {
                if(permission.view.select || permission.create.select || permission.update.select || permission.delete.select){
                    ret =  true;
                }
            })
            return ret ||  false
        }
    },
    components:{Layout}
}
</script>
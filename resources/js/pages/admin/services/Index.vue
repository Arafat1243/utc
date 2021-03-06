<template>
    <Layout title="Service">
        <v-data-table
            :search="search"
            :headers="headers"
            :items="onlyServices"
            item-key="id"
            hide-default-footer
            class="elevation-1"
        >
            <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Services List</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
                <v-spacer></v-spacer>
                    <inertia-link :href="$route('services.create')" v-if="role.can('service_create')" class="v-btn v-btn--active v-btn--flat v-btn--router theme--light v-size--default primary">
                       <span class="v-btn__content">Add New Service</span>
                   </inertia-link>
            </v-toolbar>
            </template>
            <template v-slot:item.banner_img="{item}">
                <v-avatar size="80">
                    <v-img :src="item.banner_img"></v-img>
                </v-avatar>
            </template>
            <template v-slot:item.actions="{ item }">
                <div class="d-flex">
                    <div v-for="(ui,i) in uiManage" :key="i">
                    <v-tooltip bottom v-if="ui.can">
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon v-if="!ui.herf" small class="mr-2" :color="ui.color"
                            v-bind="attrs" v-on="on" @click="ui.text === 'Preview' ? preview(item) : openDeleteDialog(item)">
                            {{ui.icon}}
                        </v-icon>
                        <inertia-link v-if="ui.herf" style="font-size:16px" :href="$route('services.edit',item.id)" v-bind="attrs" v-on="on" class="v-icon notranslate mr-2 v-icon--link mdi mdi-pencil theme--light success--text"></inertia-link>
                        </template>
                        <span>{{ui.text}}</span>
                    </v-tooltip>
                    <v-chip color="warning" v-if="!ui.can && i==0">no Actions</v-chip>
                </div>
            </div> 
            </template>
        </v-data-table>
        <Pagination class="mt-4" :links="this.services"/>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message}}
            <template v-slot:action="{ attrs }">
                <v-btn text v-bind="attrs" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
        <v-dialog v-model="dialog" persistent max-width="290">
        <v-card color="warning white--text">
            <v-card-title class="headline">Delete Service?</v-card-title>
            <v-card-text class="white--text">Are you sure you want to delete this item?</v-card-text>
            <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="white darken-1" text @click="()=>{this.dialog = false;this.deleteItem = {};}">No</v-btn>
            <v-btn color="white darken-1" text @click="deleteFun">Yes</v-btn>
            </v-card-actions>
        </v-card>
        </v-dialog>
        <v-dialog v-model="detailsDialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{singleservice.title}}</span>
                </v-card-title>
                <v-img :src="singleservice.banner_img" height="300"></v-img>
                <v-card-text v-html="singleservice.details">
                    
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="detailsDialog = false">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </Layout>
</template>
<script>
import Layout from '@/shared/admin/Layout'
import Pagination from '@/shared/admin/components/Pagination'
import Auth from '@/auth'
export default {
    data: () => ({
      role: '',
      search: '',
      snackbar: false,
      dialog: false,
      detailsDialog: false,
      singleservice: [],
      content: [],
      headers: [
        {
          text: 'Service Name',
          align: 'start',
          value: 'title',
          width: 200
        },
        { text: 'Slug', value: 'slug'},
        { text: 'Banner Img', value: 'banner_img',sortable: false },
        { text: 'Created At', value: 'created_at'},
        { text: 'Updated At', value: 'updated_at'},
        { text: 'Actions', value: 'actions', sortable: false},
      ],
      deleteItem: {},
      onlyServices: [],
      uiManage:[]
    }),
    props:['services'],
    mounted(){
        this.snackbar = this.$page.successMessage.success;
        this.init()
    },
    methods: {
      preview (item) {
        this.singleservice = item;
        this.detailsDialog = true
      },
      openDeleteDialog (item) {
        this.dialog = true;
        this.deleteItem = item;
      },
      deleteFun(){
          if(this.deleteItem && this.role.can('service_delete')){
             this.$inertia.delete(this.$route('services.destroy',this.deleteItem.id), {
                replace: false,
                preserveState: false,
                preserveScroll: false,
                only: [],
                }).then(re=>{
                    this.deleteItem = {};
                    this.dialog = false;
                    this.snackbar = true;  
                    this.init()                
                }).catch(err => {console.log(err)})
          }
      },
      init(){
          this.onlyServices = this.services.data;
        this.role = new Auth(this.$page.auth.roles);
        this.uiManage = [
            {text: 'Preview',icon: 'mdi-eye',color: 'primary',can: this.role.can('service_view')},
            {text: 'Edit',herf: 'mdi-eye',can: this.role.can('service_update')},
            {text: 'Delete',icon: 'mdi-delete',color: 'error',can: this.role.can('service_delete')}
        ]
      }
    },
    components:{Layout,Pagination}
}
</script>
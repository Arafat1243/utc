<template>
    <Layout title="Add - Client">
        <v-container>
            <v-row no-gutters justify="center">
                <v-col cols="10">
                    <v-card flat>
                        <v-alert class="grey lighten-4 primary--text text-h4"><inertia-link :href="$route('clients.index')" class="mdi mdi-arrow-left"></inertia-link>Add New Client<v-icon large class="mr-3" color="primary">mdi-plus</v-icon></v-alert>
                        <v-form ref="serviceForm" @submit.prevent="submit" method="post" enctype="multipart/form-data">
                            <v-row justify="center">
                                <v-col cols="12" md="5"><v-text-field outlined :rules="inputRules" v-model="form.title" label="Company Name"></v-text-field></v-col>
                                <v-spacer></v-spacer>
                                <v-col cols="12" md="5">
                                    <v-select :items="categories" v-model="form.cat_id" :rules="selectRules" label="Client Category" outlined></v-select>
                                </v-col>
                                <v-col cols="12" md="7">
                                    <v-img height="240" width="240" :src="imagePath"></v-img>
                                    <v-file-input @change="setPreview" required :rules="imageRules" show-size counter accept="image/*"  v-model="form.banner_img" label="Choose Company Logo"></v-file-input>
                                </v-col>
                                <v-col cols="12">
                                    <label>Company Details</label>
                                    <Editor v-model="form.details" api-key="1cxlzarpttg73bds7kj5tgppkshscdcyqpdhdlbe6yl4im2n"
                                        :init="{
                                            height: 450,
                                            menubar: false,
                                            plugins: [
                                            'advlist autolink lists link charmap preview anchor',
                                            'searchreplace visualblocks code fullscreen',
                                            'insertdatetime media table paste code help wordcount'
                                            ],
                                            toolbar:
                                            'undo redo | formatselect | bold italic backcolor | \
                                            alignleft aligncenter alignright alignjustify | \
                                            bullist numlist outdent indent | removeformat | help'
                                        }"
                                        />
                                </v-col>
                                <v-col cols="4" class="mt-5">
                                    <v-btn color="primary" block type="submit">Submit</v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-snackbar top v-model="snackbar" :color="$page.successMessage.success ? 'success' : 'error'">
            {{$page.successMessage.message}}
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
import Editor from '@tinymce/tinymce-vue';
export default {
    data: ()=>({
        snackbar: false,
        imagePath: '',
        form: {
            title: '',
            banner_img: [],
            details: '',
            cat_id: 0
        },
        inputRules:[
            v => !!v || 'Feild is required',
            v => (v && v.length >= 3) || 'You Must input Minimum 3 characters'
        ],
        selectRules:[
            v =>  (v !== 0) || 'You must be select a category'
        ],
        imageRules:[
            v => !!v || 'Banner Image is required',
            value => (value && value.size < 2000000) || 'Banner size should be less than 2 MB!',
        ],
    }),
    props:['categories'],
    mounted(){
        this.snackbar = this.$page.successMessage.success;
    },
    methods:{
        submit(){
            if(this.$refs.serviceForm.validate() && this.form.details){
                let formData = new FormData();
                formData.append('title',this.form.title);
                formData.append('details',this.form.details);
                formData.append('banner_img',this.form.banner_img);
                formData.append('cat_id',this.form.cat_id);
              this.$inertia.post(this.$route('clients.store'), formData)
                .then(re => {
                    this.snackbar = true;
                    if(this.$page.successMessage.success){
                        this.$refs.serviceForm.reset();
                        this.form.title = '',
                        this.form.details = '',
                        this.form.cat_id = 0,
                        this.form.banner_img = [],
                        this.imagePath = ''
                    }
                })
                .catch(err => console.log(err));
            }
        },
        setPreview(file){
            if (file) {
                var reader = new FileReader();
                const _this = this; 
                reader.addEventListener('load',function(){
                    _this.imagePath = this.result
                })
                reader.readAsDataURL(file); // convert to base64 string
            }   
        }
    },
    components:{
        Layout,Editor
    }
}
</script>
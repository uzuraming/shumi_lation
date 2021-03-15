<template>

  <div class="">
    <mdb-card class="card-body" style="">

        <mdb-card-title v-if="pageName != 'editWork'" class="text-center">新規作成</mdb-card-title>
        <mdb-card-title v-if="pageName == 'editWork'" class="text-center">{{title}}の編集</mdb-card-title>
        <div class="flex-row">
        <form @submit.prevent="pageName=='editWork'? editWork() : postWork()" class="mt-5">
        <mdb-input label="title" v-model="title" type="text"/>
        <select v-model="genre" class="browser-default custom-select mb-2">
            <option value="文学" selected>文学</option>
            <option value="エッセイ">エッセイ</option>
            <option value="ライトノベル">ライトノベル</option>
            <option value="ファンタジー">ファンタジー</option>
            <option value="恋愛">恋愛</option>
            <option value="SF">SF</option>
            <option value="other">その他</option>
        </select>
        <vue-editor v-model="content" />
        <div class="text-center mt-4">
            <button class="btn btn-dark shadow-none rounded-0" :disabled="content.length<=0 || title.length<=0 || !isFormBtnActive"  type="submit">{{ formBtnMsg }}</button>
        </div>
    </form>
        </div>
    </mdb-card>
    <h2 v-if="pageName != 'editWork'" class="text-center">新規作成</h2>
    <h2 v-if="pageName == 'editWork'" class="text-center">{{title}}の編集</h2>
    
    <form @submit.prevent="pageName=='editWork'? editWork() : postWork()" class="mt-5">
        <mdb-input label="title" v-model="title" type="text"/>
        <vue-editor v-model="content" />
        <div class="text-center mt-4">
            <button class="btn btn-dark shadow-none rounded-0" :disabled="content.length<=0 || title.length<=0 || !isFormBtnActive"  type="submit">{{ formBtnMsg }}</button>
        </div>
    </form>
    <button v-if="pageName == 'editWork'" class="btn btn-danger shadow-none rounded-0" @click="confirmation_modal = true">delete</button>
    <mdb-modal :show="confirmation_modal">
        <mdb-modal-header :close="false" >
            <mdb-modal-title>Confirmation</mdb-modal-title>
        </mdb-modal-header>
        <mdb-modal-body>
            本当に削除しますか？
        </mdb-modal-body>

        <mdb-modal-footer>
            <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="mdb-color" @click.native="confirmation_modal = false">Cancel</mdb-btn>
            <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="danger " @click.native="deleteWork()">{{deleteBtnMsg}}</mdb-btn>
        </mdb-modal-footer>
    </mdb-modal>

        


  </div>
</template>

<script>
import { CREATED } from '../util'
import { OK } from '../util'
import { VueEditor } from "vue2-editor";
import { mdbBtn, 
        mdbInput, 
        mdbModal, 
        mdbModalHeader, 
        mdbModalTitle, 
        mdbModalBody, 
        mdbModalFooter,
        mdbCard, mdbCardTitle, mdbCardText} from 'mdbvue';


export default {
    components: { VueEditor, mdbInput, mdbBtn, mdbModal, 
        mdbModalHeader, 
        mdbModalTitle, 
        mdbModalBody, 
        mdbModalFooter,
        mdbCard, mdbCardTitle, mdbCardText },
    data(){
        return{
            content: "",
            title:"",
            isFormBtnActive:true,
            formBtnMsg:'SEND',
            confirmation_modal:false,
            isDeleteBtnActive:true,
            deleteBtnMsg:'delete',
            genre:'文学'
          
        }
    },
    props:{
        pageName:String,
    },
    methods:{
        async postWork(){  
                this.isFormBtnActive = false;
                this.formBtnMsg = 'Sending...';
                const response = await axios.post('/api/works', {'content': this.content, 'title': this.title, 'genre':this.genre});
             
                    // 戻る
                    this.$router.back()     
       
        },
        async editWork(){  
                this.isFormBtnActive = false;
                this.formBtnMsg = 'Sending...';
                const response = await axios.put(`/api/works/${this.$route.params.work_id}`, {'content': this.content, 'title': this.title, 'genre':this.genre});
             
                // 戻る
                this.$router.back()     
       
        },
        async deleteWork(){  
                this.isDeleteBtnActive = false;
                this.deleteBtnMsg = 'deleting...';
                const response = await axios.delete(`/api/works/${this.$route.params.work_id}`);
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                };

                // 戻る
                this.$router.push('/works')     
       
        },
        async fetchWork(){
            const response = await axios.get(`/api/works/${this.$route.params.work_id}`);
            if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                };
                if(!response.data.its_mine){
                    this.$router.back() 
                }else{
                    console.log(response);
                    this.title = response.data.title;
                    this.content = response.data.content;
                };
                
        },


    },
    watch: {
            $route: {
            async handler () {
                if(this.pageName == 'editWork'){
                    await this.fetchWork();
                }
                
            },
            immediate: true
        }
    },
    

}
</script>

<style>

</style>
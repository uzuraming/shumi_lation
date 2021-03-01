<template>
  <div>
      <form @submit.prevent="putUser" class="mt-5">
        <mdb-input label="User Name" v-model="user.name" type="text"/>
        <mdb-textarea class="mt-5" label="Profile" rows="3" v-model="user.profile" />
  
        <div class="text-center mt-4">
            <button class="btn btn-dark shadow-none rounded-0" :disabled="!isFormBtnActive "  type="submit">{{ formBtnMsg }}</button>
        </div>
    </form>
    <button class="btn btn-danger shadow-none rounded-0" @click="confirmation_modal=true"  type="submit">delete</button>


    <mdb-modal :show="confirmation_modal">
        <mdb-modal-header :close="false" >
            <mdb-modal-title>Confirmation</mdb-modal-title>
        </mdb-modal-header>
        <mdb-modal-body>
            本当に削除しますか？
        </mdb-modal-body>

        <mdb-modal-footer>
            <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="mdb-color" @click.native="confirmation_modal = false">Cancel</mdb-btn>
            <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="danger" @click.native="destroyUser()">delete</mdb-btn>
        </mdb-modal-footer>
    </mdb-modal>

  </div>
</template>

<script>
import { OK } from '../../util'
import { mdbBtn, 
        mdbInput, 
        mdbModal, 
        mdbModalHeader, 
        mdbTextarea,
        mdbModalTitle, 
        mdbModalBody, 
        mdbModalFooter,} from 'mdbvue';
        
export default {
    data(){
        return {
            isFormBtnActive:true,
            formBtnMsg:'send',
            confirmation_modal:false,
            isDeleteBtnActive:true,
            user:{
                name:"",
                profile:"",
            }
        }
    },
    components: { 
        mdbInput, 
        mdbBtn, 
        mdbModal, 
        mdbTextarea,
        mdbModalHeader, 
        mdbModalTitle, 
        mdbModalBody, 
        mdbModalFooter, 
    },
    props:{
        user_id:String,
    },
    methods:{
        async fetchUser(){
            const response = await axios.get(`/api/users/${this.user_id}`);
            if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
            };
            
            if(response.data.its_me){
                this.user = response.data.user;
                console.log(this.user)
            }else{
                
                this.$router.back() ;
            }
            

           
        },
        async putUser(){
            this.isFormBtnActive = false;
            this.formBtnMsg = 'Sending...';
            const response = await axios.put(`/api/users/${this.user_id}`,
                     {'name': this.user.name, 'profile': this.user.profile});
            // 戻る
            this.$router.back() ;
        },
        async destroyUser(){
            isDeleteBtnActive = false;
            const response = await axios.delete(`/api/users/${this.user_id}`);
            if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
            };

            // 戻る
            this.$router.push('/')   
            isDeleteBtnActive = true;

        }

    },
    watch: {
            $route: {
            async handler () {
                await this.fetchUser();
            },
            immediate: true
        }
    },

}
</script>

<style>

</style>
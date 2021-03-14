<template>


  <div>
      <div class="h4" v-if="requests.length <= 0">
          チャットリクエストはありません。
      </div>
      <mdb-list-group flush v-if="requests.length > 0">
            <mdb-list-group-item v-for="(request, index) in requests" :key="index">
                <div>
                    <div class="d-flex w-100 justify-content-between">
                    <small>
                        <router-link class="mb-2" :to="{ name: 'userDetail', 
                        params: { user_id: request.id }}">{{ request.name }}</router-link >
                    </small>
                    </div>
                    <p v-html="request.pivot.message" class="mb-2">
                    </p>
                    <small>{{request.pivot.created_at}}

                        <span :class="{active_fav : is_active}" @click="acceptRequests(request.id, index)" class="ml-1 h3 mousepointer-hand"><mdb-icon icon="check" color="success"/></span>
                        <span :class="{active_fav : is_active}" @click="refuseRequests(request.id, index)" class="ml-1 h3 mousepointer-hand"><mdb-icon icon="times" color="danger" /></span>
                    
                    </small>
                </div>
            </mdb-list-group-item>
        </mdb-list-group>

        <div class="d-flex justify-content-center" >
            <Pagination :component="'requests'" :current-page="currentPage" :last-page="lastPage" />
        </div>
  </div>
</template>

<script>

import Pagination from '../components/Pagination.vue' // ★ 追加
import { mdbBtn, 
         mdbListGroup, 
         mdbListGroupItem, 
         mdbInput, 
         mdbTextarea, 
         mdbFormInline, 
         mdbModal, 
         mdbModalHeader, 
         mdbModalTitle, 
         mdbModalBody, 
         mdbModalFooter,
         mdbIcon  } from 'mdbvue';
import { CREATED, OK } from '../util';
export default {
    data(){
        return{
            requests:[],
            currentPage: 0,
            lastPage: 0,
            is_active:false,
        }
    },
    components: {
            mdbBtn,
            mdbListGroup,
            mdbListGroupItem,
            mdbInput, 
            mdbTextarea,
            mdbFormInline,
            mdbModal, 
            mdbModalHeader, 
            mdbModalTitle, 
            mdbModalBody, 
            mdbModalFooter,
            mdbIcon,
            Pagination 
    },
    methods:{
        async fetchRequests(){
            const response = await axios.get(`/api/requests`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            };
            this.requests = response.data.data;
            console.log(this.requests);

            this.currentPage = response.data.current_page
            this.lastPage = response.data.last_page
     
        },
        async refuseRequests(id,index){
            this.is_active = true;
            const response = await axios.delete(`/api/requests/${id}`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            };

            this.fetchRequests();
            this.is_active = false;
           
        },
        async acceptRequests(id,index){
            this.is_active = true;
            const response = await axios.put(`/api/requests/${id}`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            };

            this.fetchRequests();
            this.is_active = false;
            
        },
        removeRequest(index){
            delete this.requests[index];
        }
    },
    watch: {
        $route: {
        async handler () {
            await this.fetchRequests();
        },
        immediate: true
        }
    },

}
</script>

<style>

</style>
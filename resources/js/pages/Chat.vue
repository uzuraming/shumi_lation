<template>
  <div class="mt-2">
    
    <!-- ロード中 -->
    <div class="d-flex justify-content-center align-center m-5"  v-if="isLoad">
        <div class="spinner-grow spinner" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

        
   
    <div v-if="!isLoad">
        <mdb-list-group flush class="">
            <mdb-list-group-item  v-for="(chat, index) in chats" :key="index" class="w-100" >
                <div class="w-100" @click="$router.push(`chats/${chat.id}`)" style="">
                    <div v-for="(d, index) in chat.chat_room_mate" :key="index" class="row w-100">
                        <div class="col-3 col-lg-1 p-0 wrapper-img" @click.stop="$router.push(`users/${d.id}`)">
                            <div class="img-div">
                                <img v-if="d.url" :src="d.url" class="rounded-circle circle_img" alt="...">
                                <img v-if="!d.url" src="/images/vendor/mdbvue/lib/svg/iconmonstr-user-5.svg" class="rounded-circle circle_img" alt="...">
                            </div>
                        </div>
                
                        <div class="col-8 col-lg-11 w-100">
                            <div class="font-weight-bold user-link">
                                <a class="font-weight-bold" style="text-decoration:underline;font-weight:400; color:#9e9e9e;" href="#" @click.stop.prevent="$router.push(`users/${d.id}`)">{{ d.name }}</a >
                            </div>
                            <div class="w-100 d-flex align-items-center">
                                <div class="mx-2" v-if="chat.recent_message">
                                    {{　chat.recent_message.pivot.message　}}
                                </div>
                            </div>
                            <small v-if="chat.recent_message">
                                {{　chat.recent_message.pivot.created_at }}
                            </small>                       
                        </div>                     
                    </div>
                </div>
            </mdb-list-group-item >
        </mdb-list-group>
        <div class="mb-4 mt-4 d-flex justify-content-end h5">
            <router-link v-if="request_count>=1 && !isLoad" :to="{path:'/requests'}">
                >>{{request_count}}件のリクエスト
            </router-link>
        </div>

        <div class="d-flex justify-content-center" >
            <Pagination :component="'chats'" :current-page="currentPage" :last-page="lastPage" />
        </div>
    </div>
        
        
        <!--  -->

        
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
        mdbIcon,
        mdbCard, mdbCardBody  } from 'mdbvue';
import { CREATED, OK } from '../util'
    
export default {
    data(){
        return{
            chats:{
                
            },
            request_count:0,
            isLoad:true,
            currentPage: 0,
            lastPage: 0,

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
            Pagination,
            mdbCard, mdbCardBody
    },
    props: {
        page: {
            type: Number,
            required: false,
            default: 1
        }
    },
    methods:{
        // チャットを取得
        async fetchChats(){
                const response = await axios.get(`/api/chats/?page=${this.page}`)
                const response2 = await axios.get(`/api/requests_count`)
        
                if (response.status !== OK || response2.status !== OK ) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.request_count = response2.data;
                this.chats = response.data.data
                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },

    },
    watch: {
        $route: {
        async handler () {
            await this.fetchChats();
            this.isLoad = false;
        },
        immediate: true
        }
    },

}
</script>

<style scoped>
.circle_img {
    border: 4px solid #a1887f ; /* 枠線を付加 */
}
.img-div {
  width: 100%;/*上下のpaddingと同じにする*/
  height: 0;
  padding-top: 100%;/*widthと同じにする*/
  position: relative;
  border-radius: 50%;
  overflow: hidden;
}
.img-div img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
  
}




</style>
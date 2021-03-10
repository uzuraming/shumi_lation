<template>
  <div>
      <mdb-list-group flush>
            <mdb-list-group-item v-for="(chat, index) in chats" :key="index">
                <div>
                     <div v-for="(d, index) in chat.chat_room_mate" :key="index">
                         {{d.name}}
                         <router-link class="mb-2" :to="{ name: 'chatDetail', params: { chat_room_id: chat.id }}" >{{ d.name }}</router-link >
                    
                     </div>
                        <!-- <router-link class="mb-2" :to="{ name: 'userDetail', params: { user_id: timeline.user.id }}" >{{ timeline.user.name }}</router-link > -->
                    <small>{{chat.created_at}}</small>
                </div>
                <button @click="test(chat.chat_room_mate[0])"> aaa</button>
            </mdb-list-group-item>
        </mdb-list-group>

        <div class="d-flex justify-content-center" >
            <Pagination :component="'chats'" :current-page="currentPage" :last-page="lastPage" />
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
import { CREATED, OK } from '../util'
    
export default {
    data(){
        return{
            chats:{
                
            },
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
            Pagination 
    },
    props: {
        page: {
                type: Number,
                required: false,
                default: 1
        }
    },
    methods:{
        test(a){
            console.log(a);
        },
        async fetchChats(){
                const response = await axios.get(`/api/chats/?page=${this.page}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.chats = response.data.data
        
                console.log(response)
                

                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },

    },
    watch: {
        $route: {
        async handler () {
            await this.fetchChats();
        },
        immediate: true
        }
    },

}
</script>

<style>

</style>
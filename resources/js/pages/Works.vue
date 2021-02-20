<template>
    <div>
        <mdb-list-group flush>
            <mdb-list-group-item v-for="work in works" :key="work.id">
                <div>
                    <div class="d-flex w-100 justify-content-between">
                    <small>
                        作者：<router-link class="mb-2" :to="{ name: 'userDetail', params: { user_id: work.user.id }}" >{{ work.user.name }}</router-link >
                    
                    </small>
                    </div>
                    <p>
                        <router-link class="" :to="{ name: 'workDetail', params: { work_id: work.id }}" >{{work.title}}</router-link >
                    </p>
                    <small>{{work.created_at}}</small>  
                </div>
            </mdb-list-group-item>
        </mdb-list-group>

        <div v-if="isLogin" @click="$router.push('works/create')" class="btn-circle-flat shadow"><span class="h2">+</span></div>
    
        <div class="d-flex justify-content-center" >
            <Pagination :component="'works'" :current-page="currentPage" :last-page="lastPage" />
        </div>
        
<!-- 
        <mdb-modal removeBackdrop :show="timelinesModal" @close="timelinesForm = ''">
            <mdb-modal-header :close="false" >
                <mdb-modal-title>New Post</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                <mdb-textarea rows="3" v-model="timelinesForm" />
            </mdb-modal-body>
            <mdb-modal-footer>
                
                <mdb-btn class="shadow-none" color="danger" @click.native="clearTimelineModal()">Close</mdb-btn>
                <mdb-btn class="shadow-none" :disabled="timelinesForm.length>255" color="mdb-color" @click.native="postTimeline()">send</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>
        <div v-if="isLogin" @click="timelinesModal=true" class="btn-circle-flat shadow"><span class="h2">+</span></div>
     -->

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
    import { OK } from '../util'
    export default {
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
        data(){
            return{
                works:[],
                timelinesModal:false,
                timelinesForm:"",
                currentPage: 0,
                lastPage: 0
            }
        },
        props: {
            page: {
                type: Number,
                required: false,
                default: 1
            }
        },

        methods:{
            async fetchWorks(){
                const response = await axios.get(`/api/works/?page=${this.page}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.works = response.data.data;
                console.log(response)
                

                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },
            // モーダルを初期化する関数
            // clearTimelineModal(){
                
            //     this.timelinesModal = false;
            //     this.timelinesForm = "";
            // },
            // async postTimeline(){  
            //     const response = await axios.post('/api/timelines', {'content': this.timelinesForm});
            //     this.clearTimelineModal();
            //     this.fetchTimelines();
       
            // }
        },
        watch: {
            $route: {
            async handler () {
                await this.fetchWorks()
            },
            immediate: true
            }
        },
        computed: {
            isLogin () {
                return this.$store.getters['auth/check']
            },
            username () {
                return this.$store.getters['auth/username']
            }
        }
    }

</script>

<style scoped>
    .btn-circle-flat {
        display: inline-block;
        text-decoration: none;
        background: #2E2E2E;
        color: #FFF;
        width: 120px;
        height: 120px;
        line-height: 120px;
        border-radius: 50%;
        text-align: center;
        overflow: hidden;
        transition: .4s;
        position: fixed;
        bottom: 15px; 
        right: 10px;
        display: -webkit-flex;
 display: flex;
 -webkit-align-items: center; /* 縦方向中央揃え（Safari用） */
 align-items: center; /* 縦方向中央揃え */
 -webkit-justify-content: center; /* 横方向中央揃え（Safari用） */
 justify-content: center; /* 横方向中央揃え */
       

    }

    .btn-circle-flat:hover {
        background: #4B515D;
    }
   
</style>
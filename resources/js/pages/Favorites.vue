<template>
    <div>
        <mdb-list-group flush>
            <mdb-list-group-item v-for="(timeline, index) in timelines" :key="index">
                <div>
                    <div class="d-flex w-100 justify-content-between">
                    <small>
                        <router-link class="mb-2" :to="{ name: 'userDetail', params: { user_id: timeline.user.id }}" >{{ timeline.user.name }}</router-link >
                    </small>
                    </div>
                    <p v-html="timeline.content" class="mb-2">
                    </p>
                    <small>{{timeline.created_at}}
                    </small>
                    <span :class="{active_fav : is_favoriting}" @click="favorite(timeline.id, index)" class="ml-1 mousepointer-hand" v-if="!timeline.favorited_by_user"><mdb-icon color="pink" class="mr-1" far icon="heart" />{{timeline.favorite_count}}</span>
                    <span :class="{active_fav : is_favoriting}" @click="unFavorite(timeline.id, index)" class="ml-1 mousepointer-hand" v-if="timeline.favorited_by_user"><mdb-icon  color="pink"  class="mr-1" icon="heart" />{{timeline.favorite_count}}</span>
                    
                </div>
            </mdb-list-group-item>
        </mdb-list-group>
        <div class="d-flex justify-content-center" >
            <Pagination :component="pageName" :current-page="currentPage" :last-page="lastPage" />
        </div>
        

        <mdb-modal :show="timelinesModal" @close="timelinesForm = ''">
            <mdb-modal-header :close="false" >
                <mdb-modal-title>New Post</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                <mdb-textarea rows="3" v-model="timelinesForm" />
            </mdb-modal-body>
            <mdb-modal-footer>
                
                <mdb-btn class="shadow-none" color="danger" @click.native="clearTimelineModal()">Close</mdb-btn>
                <mdb-btn class="shadow-none" :disabled="timelinesForm.length>255 || timelinesForm.length<=0" color="mdb-color" @click.native="postTimeline()">send</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>

        <mdb-modal :show="warnModal">
            <mdb-modal-header>
                <mdb-modal-title>警告</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                いいねにはユーザー登録が必要です。
            </mdb-modal-body>
            <mdb-modal-footer>    
                <mdb-btn class="shadow-none" color="elegant" @click.native="warnModal=false">Close</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>


        <div v-if="isLogin && pageName == 'timelines'" @click="timelinesModal=true" class="btn-circle-flat shadow"><span class="h2 mousepointer-hand">+</span></div>
    

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
                timelines:[],
                timelinesModal:false,
                timelinesForm:"",
                currentPage: 0,
                lastPage: 0,
                warnModal:false,
                is_favoriting:false,
            }
        },
        props: {
            page: {
                type: Number,
                required: false,
                default: 1
            },
            pageName:String,
        },

        methods:{
            async fetchTimelines(){

              
                const response = await axios.get(`/api/${this.pageName}/?page=${this.page}`);
            
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.timelines = response.data.data;
                console.log(response)
                

                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },
            async favorite(id, index){
                this.is_favoriting = true;
                if(this.isLogin){
                    const response = await axios.post(`/api/favorites/${id}`);
        
                    if (response.status !== OK) {
                        this.$store.commit('error/setCode', response.status);
                        return false;
                    }
                    this.timelines[index].favorited_by_user = true;
                    this.timelines[index].favorite_count += 1;
                }else{
                    this.warnModal =true;
                }
                this.is_favoriting = false;
            },
            async unFavorite(id,index){
                this.is_favoriting = true;
                if(this.isLogin){
                    const response = await axios.delete(`/api/favorites/${id}`);
        
                    if (response.status !== OK) {
                        this.$store.commit('error/setCode', response.status);
                        return false;
                    };

                    this.timelines[index].favorited_by_user = false;
                    this.timelines[index].favorite_count -= 1;
                }else{
                    this.warnModal =true;
                };

                this.is_favoriting = false;
                
            },





            // モーダルを初期化する関数
            clearTimelineModal(){
                
                this.timelinesModal = false;
                this.timelinesForm = "";
            },
            async postTimeline(){  
                const response = await axios.post('/api/timelines', {'content': this.timelinesForm});
         
                this.clearTimelineModal();
                this.fetchTimelines();
       
            }
        },
        watch: {
            $route: {
            async handler () {
                await this.fetchTimelines();
            },
            immediate: true
            }
        },
        computed: {
            isLogin () {
                return this.$store.getters['auth/check']&& this.$store.getters['auth/is_verified'];
            },
            username () {
                return this.$store.getters['auth/username'];
            }
        }
    }

</script>

<style>
    

    .mousepointer-hand {
        cursor: pointer;
    }
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

    .active_fav {
        pointer-events: none;
    }
   
</style>
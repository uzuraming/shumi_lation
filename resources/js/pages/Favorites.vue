<template>
    <div class="pb-5">
        <!-- ロード中 -->
        <div class="d-flex justify-content-center align-center m-5"  v-if="isLoad">
            <div class="spinner-grow spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>
        
        
        <div v-if="!isLoad" >
            <!-- 作品一覧 -->
            <mdb-card v-for="(timeline, index) in timelines" :key="index" class="my-2 shadow">
                <mdb-card-body class="row">
                    <div class="col-3 col-lg-1 p-0 wrapper-img" @click="$router.push(`users/${timeline.user.id}`)">
                        <div class="img-div">
                            <img v-if="timeline.user.url" :src="timeline.user.url" class="rounded-circle circle_img" alt="...">
                            <img v-if="!timeline.user.url" src="/images/vendor/mdbvue/lib/svg/iconmonstr-user-5.svg" class="rounded-circle circle_img" alt="...">
                        </div>
                    </div>
                    <div class="col-8 col-lg-11">
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

                    
                </mdb-card-body>
            </mdb-card>
            <!-- ページネーション -->
            <div class="d-flex justify-content-center" >
                <Pagination :component="pageName" :current-page="currentPage" :last-page="lastPage" />
            </div>
            
        </div>
        <!-- 新規投稿ボタン -->
        <div>
            <mdb-modal :show="timelinesModal" @close="timelinesModal = false">
            <mdb-modal-header >
                <mdb-modal-title>新規投稿</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                <mdb-textarea rows="3" v-model="timelinesForm" />
            </mdb-modal-body>
            <mdb-modal-footer>
                
                <mdb-btn class="shadow-none" color="danger" @click.native="clearTimelineModal()">閉じる</mdb-btn>
                <mdb-btn class="shadow-none" :disabled="timelinesForm.length>255 || timelinesForm.length<=0" color="mdb-color" @click.native="postTimeline()">送信</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>
        </div>
        
        
        <!-- ゲストユーザーがいいねボタンを押したときのモーダル -->
        <mdb-modal :show="warnModal" @close="warnModal = false">
            <mdb-modal-header>
                <mdb-modal-title>始めよう！</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                いいねにはユーザー登録が必要です！<br><br>
                会員登録をすれば、<br>タイムラインの投稿、いいね、作品の投稿、ブックマーク、チャットが可能です！
            </mdb-modal-body>
            <mdb-modal-footer>    
                 <mdb-btn class="shadow-none" color="success" @click.native="$router.push('signup')">会員登録する！</mdb-btn>
                <mdb-btn class="shadow-none" color="elegant" @click.native="warnModal=false">閉じる</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>
        

        

        

        


        <!-- 新規作成 -->
        <div v-if="isLogin && pageName == 'timelines'" @click="timelinesModal=true" class="btn-circle-flat shadow "><span class="h2 mousepointer-hand">+</span></div>
    

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
        data(){
            return{
                timelines:[],
                timelinesModal:false,
                timelinesForm:"",
                currentPage: 0,
                lastPage: 0,
                warnModal:false,
                is_favoriting:false,
                isLoad:true
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
            // タイムラインを取得
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
            // いいね
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
            // いいねを外す
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
                this.isLoad = false;
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

<style scoped>

    .mousepointer-hand {
        cursor: pointer;
    }
    .btn-circle-flat {
        display: inline-block;
        text-decoration: none;
        background: #2E2E2E;
        color: #FFF;
        width: 70px;
        height: 70px;
        line-height: 120px;
        border-radius: 50%;
        text-align: center;
        overflow: hidden;
        transition: .4s;
        position: fixed;
        bottom: 100px; 
        right: 20px;
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

    /* .circle {
        object-fit:cover;
        width: 50px;
        height: 50px ;

    } */

    

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
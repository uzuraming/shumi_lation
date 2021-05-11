<template>
    <div class="mb-5 pb-5">
        <div class="d-flex justify-content-center align-center m-5"  v-if="isLoad">
            <div class="spinner-grow spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        
        
        <!-- 並び替えフォーム -->
        <div v-if="!isLoad">
            <input id="acd-check1" class="acd-check" type="checkbox" v-if="this.pageName == 'works'">
            <label v-if="this.pageName == 'works'" @click="isAcdActive=!isAcdActive" class="acd-label" for="acd-check1"><mdb-icon v-if="!isAcdActive" class="" icon="angle-double-down" /><mdb-icon v-if="isAcdActive" class="" icon="angle-double-up" /></label>
            <div class="acd-content" v-if="this.pageName == 'works'" >
                <div contextmenu="acd-child">
                    <select v-model="genre" @change="fetchWorksByGenre" class="browser-default custom-select mb-2">
                        <option value="all" selected>すべて</option>
                        <option value="文学">文学</option>
                        <option value="エッセイ">エッセイ</option>
                        <option value="ライトノベル">ライトノベル</option>
                        <option value="ファンタジー">ファンタジー</option>
                        <option value="恋愛">恋愛</option>
                        <option value="SF">SF</option>
                        <option value="other">その他</option>
                    </select>
                    <select v-model="is_rank" @change="fetchWorksByGenre"  class="browser-default custom-select mb-2">
                        <option value="created_at" selected>新しい順</option>
                        <option value="bookmarking_user_count">ブックマークの多い順</option>
                    </select>
                    <div v-if="this.pageName == 'works'" class="input-group mb-2">
                        <input v-model="search_word" type="text" class="form-control">
                        <div class="input-group-btn mx-0">
                            <button :disabled="search_word.length<=0" class="btn btn-elegant my-0 py-2 h-100 shadow-none" type="button" @click="searchWorks">検索</button>
                        </div>
                    </div>
                </div>
                
            </div>
        <div v-if="works.length <=　0 && !isLoad" >
            <Nothing :text="'作品はありません'" />
        </div>
        

        <!-- 作品一覧 -->
        <div class="row d-flex justify-content-center mt-2 z-1 ">
            
            <mdb-card class="col-5 col-lg-3 mb-1 mr-1 p-0 text-white " v-for="(work, index) in works" :key="index">
                <div class="w-100" @click="$router.push(`/works/${work.id}`)">
                    <div class=" img-div view">
                    <img v-if="work.url" :src="work.url" alt="" class="img-box">
                    <img v-if="!work.url" src="/images/vendor/mdbvue/lib/svg/f_f_business_41_svg_f_business_41_0bg.svg" alt="" class="img-box">
                    
                    <div class="mask flex-center rgba-black-light">
                  
                    </div>
                    
                    <div class=" w-100 img-writer p-2 z-2">
                        <small>
                            作者：<a class="mb-2" href="#" @click.stop.prevent="$router.push(`/users/${work.user.id}`)">{{ work.user.name }}</a >
                        </small>
                        <p>
                            <a href="#" class="" @click.stop.prevent="$router.push(`/works/${work.id}`)">{{work.title}}</a >
                        </p>
                    </div>
                    <div class="img-bottom text-right p-2 z-2">
                        
                        <small>{{work.created_at}}</small><br>
                        <div :class="{active_fav : is_bookmarking}" @click.stop.prevent="bookmark(work.id, index)" class="mousepointer-hand w-100" v-if="!work.bookmarked_by_user"><mdb-icon color="orange" class="mr-1" far icon="bookmark" />{{work.bookmark_count}}</div>
                        <div :class="{active_fav : is_bookmarking}" @click.stop.prevent="unBookmark(work.id, index)" class="mousepointer-hand w-100" v-if="work.bookmarked_by_user"><mdb-icon  color="orange"  class="mr-1" icon="bookmark" />{{work.bookmark_count}}</div>                    
                    </div>
                    
            </div>

                </div>
                
            </mdb-card>
        </div>
         <div  v-if="isLogin && this.pageName == 'works'" @click="$router.push({ name:'workCreate' }) " class="btn-circle-flat shadow mousepointer-hand"><span class="h2">+</span></div>
    
        <div class="d-flex justify-content-center" >
            <Pagination :component="this.pageName" :current-page="currentPage" :last-page="lastPage" />
        </div>
        


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

     <!-- ゲストがブックマークしたときのモーダル -->
        <mdb-modal :show="warnModal" @close="warnModal=false" >
            <mdb-modal-header>
                <mdb-modal-title>始めよう！</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                ブックマークにはユーザー登録が必要です！<br><br>
                会員登録をすれば、<br>タイムラインの投稿、いいね、作品の投稿、ブックマーク、チャットが可能です！
            </mdb-modal-body>
            <mdb-modal-footer>    
                <mdb-btn class="shadow-none" color="success" @click.native="$router.push('/signup')">会員登録する！</mdb-btn>
                <mdb-btn class="shadow-none" color="elegant" @click.native="warnModal=false">閉じる</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>


    
    </div>
    
</template>
<script>
    import Pagination from '../components/Pagination.vue' // ★ 追加
    import Nothing from '../components/Nothing.vue' // ★ 追加
    import { mdbBtn, 
        mdbListGroup, 
        mdbListGroupItem, 
        mdbInput, 
        mdbTextarea, 
        mdbFormInline, 
        mdbCard,
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
            mdbCard,
            mdbFormInline,
            mdbModal, 
            mdbModalHeader, 
            mdbModalTitle, 
            mdbModalBody, 
            mdbModalFooter,
            mdbIcon,
            Pagination,
            Nothing
        },
        data(){
            return{
                works:[],
                timelinesModal:false,
                timelinesForm:"",
                currentPage: 0,
                lastPage: 0,
                genre:'all',
                search_word:"",
                is_bookmarking:false,
                is_rank:'created_at',
                warnModal:false,
                isAcdActive:false,
                isLoad:true,
            }
        },
        props: {
            page: {
                type: Number,
                required: false,
                default: 1,
                
                
            },
            pageName:String,
        },

        methods:{
            async fetchWorks(){
                const response = await axios.get(`/api/works/?page=${this.page}&genre=${this.genre}&is_rank=${this.is_rank}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.works = response.data.data;
                

                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },
            async fetchBookmarks(){
                const response = await axios.get(`/api/bookmarks/?page=${this.page}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.works = response.data.data;

                

                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },
            async fetchWorksByGenre(){
                const response = await axios.get(`/api/works/?page=1&genre=${this.genre}&is_rank=${this.is_rank}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.works = response.data.data;
                
                

                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },
            
            async searchWorks(){
                const response = await axios.get(`/api/works/search/?page=1&search_word=${this.search_word}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                this.works = response.data.data;
                
                

                this.currentPage = response.data.current_page
                this.lastPage = response.data.last_page
            },
            async bookmark(id, index){
                this.is_bookmarking = true;
                if(this.isLogin){
                    const response = await axios.post(`/api/bookmarks/${id}`);
        
                    if (response.status !== OK) {
                        this.$store.commit('error/setCode', response.status);
                        return false;
                    }
                    this.works[index].bookmarked_by_user = true;
                    this.works[index].bookmark_count += 1;
                }else{
                    this.warnModal =true;
                }
                this.is_bookmarking = false;
            },
            async unBookmark(id,index){
                this.is_bookmarking = true;
                if(this.isLogin){
                    const response = await axios.delete(`/api/bookmarks/${id}`);
        
                    if (response.status !== OK) {
                        this.$store.commit('error/setCode', response.status);
                        return false;
                    };

                    this.works[index].bookmarked_by_user = false;
                    this.works[index].bookmark_count -= 1;
                }else{
                    this.warnModal =true;
                };

                this.is_bookmarking = false;
                
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
                if(this.pageName == 'bookmarks'){
                    await this.fetchBookmarks()
                }else{
                    await this.fetchWorks()
                }
                
                this.isLoad = false;
            },
            immediate: true
            }
        },
        computed: {
            isLogin () {
                return this.$store.getters['auth/check']&& this.$store.getters['auth/is_verified']
            },
            username () {
                return this.$store.getters['auth/username']
            }
        }
    }

</script>

<style scoped>
    a {
        color:#fff;
        text-decoration:underline;
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
        z-index:3
       

    }     

   

    .btn-circle-flat:hover {
        background: #4B515D;
    }



    .acd-check{
  display: none;
}
.acd-label{
  background: #333;
  color: #fff;
  display: block;
  margin-bottom: 1px;
  padding: 10px;
}
.acd-content{
  height: 0;
  opacity: 0;
  padding: 0 10px;
  transition: .5s;
  visibility: hidden;
}
.acd-check:checked + .acd-label + .acd-content{

  opacity: 1;
  padding: 10px;
  visibility: visible;
  width: 100%;
  display: table;
  height: inherit;
}

.acd-child{
    display: table-cell;
}

.img-div {
  width: 100%;/*上下のpaddingと同じにする*/
  height: 0;
  padding-top: 100%;/*widthと同じにする*/
  position: relative;
  overflow: hidden;
}
.img-div .img-box {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
}
.img-writer{
    position: absolute;
    top: 0;
    left: 0;
}
.img-bottom{
    position: absolute;
    bottom: 0;
    right: 0;
}
.z-2{
    z-index: 2;
}
.z-1{
 z-index: 1;
}

   
</style>
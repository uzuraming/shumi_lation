<template>
    <div>
        <!-- ロード中の画面 -->
        <div class="d-flex justify-content-center align-center m-5"  v-if="isLoad">
            <div class="spinner-grow spinner" role="status">
                <span class="sr-only">ロード中...</span>
            </div>
        </div>



        <div class="container mt-3" v-if="!isLoad">
            <div class="row">
                <div class="col-sm-6">
                    
                    
                    <mdb-card class="shadow-none">
                        <mdb-card-body class="pt-0">
                            <!-- プロフィール画像 -->
                            <div class="text-center">
                                <div class="p-5 wrapper-img">
                                    <div class="img-div">
                                        <img v-if="userInfo.user.url" :src="userInfo.user.url" class="rounded-circle circle_img" alt="...">
                                        <img v-if="!userInfo.user.url" src="/images/vendor/mdbvue/lib/svg/iconmonstr-user-5.svg" class="rounded-circle circle_img" alt="...">
                                    </div>
                                </div>
                            </div>
                            <!-- ユーザー名 -->
                            <mdb-card-title class="mb-3 text-center nav-style">{{userInfo.user.name}}</mdb-card-title>

                            <!-- フォロー、アンフォローボタン -->
                            <div v-if="this.isLogin && !userInfo.its_me" class="row">
                                <div class="col-6 text-center">
                                    <div v-if='!userInfo.is_following'><mdb-btn class="px-3 shadow-none round-circle" :disabled="is_processing" @click="follow"  color="elegant">フォロー</mdb-btn></div>
                                    <div v-if='userInfo.is_following'><mdb-btn class="px-3 shadow-none round-circle" :disabled="is_processing" @click="unFollow" color="blue-grey">フォロー解除</mdb-btn></div>

                                </div>
                                <!--　チャット申請ボタン -->
                                <div class="col-6 text-center">
                                    <div v-if="!userInfo.is_accepted_request && !userInfo.is_accepted_request_by_you && !userInfo.is_send_request && !userInfo.is_sent_request_by_you" class="">
                                    <mdb-btn class="px-3 shadow-none " @click="requestModal = true" :disabled="is_processing" color="success">チャット申請</mdb-btn>
                                </div>
                                <!-- 申請解除ボタン -->
                                <div v-if="!userInfo.is_accepted_request && !userInfo.is_accepted_request_by_you && userInfo.is_send_request && !userInfo.is_sent_request_by_you" class="">
                                    <mdb-btn class="px-3 shadow-none " :disabled="is_processing" @click="removeRequest"  color="mdb-color">申請中</mdb-btn>
                                </div>
                                <!-- （相手から申請されている場合）申請許可ボタン -->
                                <div v-if="!userInfo.is_accepted_request && !userInfo.is_accepted_request_by_you && userInfo.is_sent_request_by_you" class="">
                                    <mdb-btn class="px-3 shadow-none " :disabled="is_processing" @click="removeRequest"  color="warning">許可</mdb-btn>
                                </div>
                                    
                                </div>      
                            </div>
                            
                            
                            <!-- フォロー、フォロワー -->
                            <div class="row mt-3 text-center">
                                <div class="col-6">
                                    <div class="h4 brown-text"> {{userInfo.user.followers_count}}</div>
                                    <mdb-card-text>フォロワー</mdb-card-text>
                                </div>
                                <div class="col-6">
                                    <div class="h4 brown-text"> {{userInfo.user.followings_count}}</div>
                                    <mdb-card-text>フォロー</mdb-card-text>
                                </div>
                            </div>
                            <!-- プロフィール -->
                            <div class="mt-3 mx-5 h5 grey-text" style="white-space: pre-wrap;">{{userInfo.user.profile}}</div>           
                        </mdb-card-body>
                    </mdb-card>             
                </div>
                



                <div class="col-sm-6 mt-2">
                    <!-- タイムラインと作品の切り替えタブ -->
                    <div d-flex class="d-flex w-100 justify-content-between white">
                        <button :class="{'selected': tab=='timeline', 'unselected':tab=='work'}" @click="tab='timeline'" class=" w-100 btn shadow-none m-0 ">タイムライン</button>
                        <button :class="{'selected': tab=='work', 'unselected':tab=='timeline'}" @click="tab='work'" class="w-100 btn shadow-none m-0 ">作品</button>
                    </div>

                
                    <!-- 作品一覧 -->
                    <div class="col-12 p-0" v-if="tab=='work'">
                        <mdb-list-group flush v-for="work in userInfo.works" :key="work.id">
                            <mdb-list-group-item v-for="d in work" :key="d.id">
                                <div>
                                    <div class="d-flex w-100 justify-content-between">
                                    </div>
                                    <p class="mb-2">
                                        <router-link class="mb-2" :to="{ name: 'workDetail', params: { work_id: d.id }}" >{{d.title}}</router-link >
                                    </p>
                                    <small>{{d.created_at}}</small>
                                    
                                </div>
                            </mdb-list-group-item>
                            

                        </mdb-list-group>
                        <div class=" d-flex w-100 justify-content-between">
                            <button :disabled="!this.moreButtonStat.work.isActive" v-if="tab=='work'" @click="moreWork" class="btn btn-elegant w-100 m-0">{{this.moreButtonStat.work.sentence}}</button>
                        </div>
                    </div>
                    
                    <!-- タイムライン一覧 -->
                    <div class="col-12 p-0" v-if="tab=='timeline'" >
                        <mdb-list-group flush  v-for="timeline in userInfo.timelines" :key="timeline.id">
                        
                                <mdb-list-group-item v-for="d in timeline" :key="d.id" class="">
                                    <div class="d-box">
                                     
                                        <p class="mb-2 ">
                                            {{d.content}}
                                        </p>
                                        <small>{{d.created_at}}</small>
                                        
                                    </div>
                                </mdb-list-group-item>
                        
                            
                        </mdb-list-group>
                        <div class="d-flex w-100 justify-content-between">
                            <button :disabled="!this.moreButtonStat.timeline.isActive" v-if="tab=='timeline'" @click="moreTimeline" class="btn btn-elegant w-100 m-0">{{this.moreButtonStat.timeline.sentence}}</button>
                        </div>
                    </div>


                </div>
                

                    


                    <!-- <div class="d-flex justify-content-center" >
                        <Pagination :component="'timelines'" :current-page="currentPage" :last-page="lastPage" />
                    </div> -->
           
            </div>
            
        </div>
        <!-- プロフィール編集ボタン -->
         <div v-if="userInfo.its_me" @click="$router.push({name:'editUser', params:user_id})" class="btn-circle-flat shadow"><span class="h2 mousepointer-hand"><mdb-icon icon="pen" /></span></div>

        <!-- リクエストを送るときの、メッセージ作成モーダル -->
         <mdb-modal :show="requestModal" @close="requestModal = false">
            <mdb-modal-header >
                <mdb-modal-title>リクエストメッセージ</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                <mdb-textarea rows="3" v-model="requestForm" />
            </mdb-modal-body>
            <mdb-modal-footer>
                
                <mdb-btn class="shadow-none" color="danger" @click.native="clearRequestModal()">閉じる</mdb-btn>
                <mdb-btn class="shadow-none" @click="sendRequest" :disabled="requestForm.length>255 || requestForm.length<=0" color="mdb-color">送る</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>
    
    </div>
    
</template>

<script>
import { OK } from '../../util'
import { mdbCard, mdbCardImage, mdbCardBody, mdbCardTitle, mdbCardText, mdbBtn,mdbListGroup, 
        mdbListGroupItem, mdbTabs, mdbIcon, mdbModal, 
            mdbModalHeader, 
            mdbModalTitle, 
            mdbModalBody, 
            mdbModalFooter,
            mdbTextarea,}
            
         from 'mdbvue'
export default {
    data(){
        return{
            isLoad:true,
            userInfo:{
                user:[],
                timelines:[],
                works:[],
                is_following:'',
                its_me:false,
                is_send_request:false,
                is_sent_request_by_you:false,
                is_accepted_request_by_you:false,
                is_accepted_request:false,

            },
            tab:'timeline',
            pagination:{
                timeline:1,
                work:1,
            },
            buttonMore:{
                timeline:true,
                work:true,
            },
            moreButtonStat:{
                timeline:{
                    isActive:true,
                    sentence:'▼'
                },
                work:{
                    isActive:true,
                    sentence:'▼'
                },

            },
            is_processing:false,
            requestForm:'',
            requestModal:false,
        }
    },
    components:{
        mdbCard,
			mdbCardImage,
			mdbCardBody,
			mdbCardTitle,
			mdbCardText,
            mdbBtn,
            mdbListGroup, 
            mdbListGroupItem,
            mdbTabs,
            mdbIcon,
            mdbModal, 
            mdbModalHeader, 
            mdbModalTitle, 
            mdbModalBody, 
            mdbModalFooter,
            mdbTextarea,
    },
    props:{
        user_id:String,
    },
    methods:{
        // ユーザー情報取得
        async fetchUserDetail(){
            // ボタンの文字を変える
            this.moreButtonStat.work.isActive = false;
            this.moreButtonStat.work.sentence = 'ロード中...';

            this.moreButtonStat.timeline.isActive = false;
            this.moreButtonStat.timeline.sentence = 'ロード中...';


            
            const response = await axios.get(`/api/users/${this.user_id}?timelinepage=${this.pagination.timeline}&workpage=${this.pagination.work}`)
    
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status)
                return false
            }
            // タイムラインのMoreボタンが押された場合、buttonMore.timelineがtrueになって以下の処理が行われる
            if(this.buttonMore.timeline){
                this.userInfo.timelines.push(response.data.timelines.data)

            // 作品のMoreボタンが押された場合も同様
            }
            if(this.buttonMore.work){
                this.userInfo.works.push(response.data.works.data);
            }
            // ユーザーのふぉろーやリクエスト関連の情報
            this.userInfo.is_following = response.data.is_following;
            this.userInfo.its_me = response.data.its_me;
            this.userInfo.user = response.data.user;
            this.userInfo.is_send_request = response.data.is_send_request;
            this.userInfo.is_accepted_request_by_you = response.data.is_accepted_request_by_you;
            this.userInfo.is_accepted_request = response.data.is_accepted_request;
            this.userInfo.is_sent_request_by_you = response.data.is_sent_request_by_you;
            // this.currentPage = response.data.current_page
            // this.lastPage = response.data.last_page
            




            // MoreボタンでTrueになったものを初期化する
            this.buttonMore.work =false;
            this.buttonMore.timeline =false;

            // ボタンの見た目を直す

            this.moreButtonStat.work.isActive = true;
            this.moreButtonStat.work.sentence = '▼';

            this.moreButtonStat.timeline.isActive = true;
            this.moreButtonStat.timeline.sentence = '▼';

            // ページネーションが最後まで行ったらボタンを無効化
            if(response.data.works.current_page >= response.data.works.last_page){
                this.moreButtonStat.work.isActive = false;
            }

            if(response.data.timelines.current_page >= response.data.timelines.last_page){
                this.moreButtonStat.timeline.isActive = false;
            }

                

        },
        // フォローの処理
        async follow(){
            this.is_processing = true;
            const response = await axios.post(`/api/users/${this.user_id}/follow`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            }else{
                this.userInfo.is_following = true;
                this.userInfo.user.followers_count += 1;
            }
            this.is_processing = false;

        
        },
        // アンフォローの処理
        async unFollow(){
            this.is_processing = true;
            const response = await axios.delete(`/api/users/${this.user_id}/unfollow`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            }else{
                this.userInfo.is_following = false;
                this.userInfo.user.followers_count -= 1;
            }
            this.is_processing = false;

        },
        // リクエスト送信
        async sendRequest(){
            this.is_processing = true;
            const response = await axios.post(`/api/users/${this.user_id}/send_request` ,{'message': this.requestForm});
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            }else{
                this.userInfo.is_send_request = true;
            }
            this.requestModal = false;
            this.is_processing = false;

        },
        // リクエスト解除
        async removeRequest(){
            this.is_processing = true;
            const response = await axios.delete(`/api/users/${this.user_id}/send_request`);
            if (response.status !== OK) {
                this.$store.commit('error/setCode', response.status);
                return false;
            }else{
                this.userInfo.is_send_request = false;
            }
            this.is_processing = false;

        },
        // リクエストメッセージの内容を削除
        clearRequestModal(){
                this.requestModal = false;
                this.requestForm = "";
        },
        // 作品のmoreボタンが押された場合
        moreWork(){
            
            this.pagination.work += 1;
            this.buttonMore.work =true;
            

            this.fetchUserDetail();
            
        },
        // タイムラインのmoreボタンが押された場合
        moreTimeline(){
            this.pagination.timeline += 1;
            this.buttonMore.timeline =true;
            this.fetchUserDetail();

        }

    },
    computed: {
        // ログインしているか
        isLogin () {
            return this.$store.getters['auth/check']&& this.$store.getters['auth/is_verified']
        },
        // ログインしているユーザーの名前
        username () {
            return this.$store.getters['auth/username']
        }
    },
    watch: {
            $route: {
            async handler () {
                await this.fetchUserDetail()
                this.isLoad=false
            },
            immediate: true
        }
    },

}
</script>

<style scoped>
.circle_img {
    border: 9px solid #a1887f ; /* 枠線を付加 */
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

    .selected{
        border-bottom: 3px solid #5ab4bd;
        background-color:white; 
        pointer-events: none;
    }
    .unselected{
        background-color:#f5f5f5; 
    }
    .d-box{
        max-width: 100%;
        overflow-wrap:  break-word;
    }


</style>
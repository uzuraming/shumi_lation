<template>
    <div>
        <!-- ロード中 -->
        <div class="d-flex justify-content-center align-center m-5"  v-if="isLoad">
            <div class="spinner-grow spinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>

        </div>


        <!-- コメントがない場合 -->
        <div v-if="comments.length <=　0 && !isLoad" >
            <Nothing :text="'コメントはありません'" />
        </div>
        <!-- コメントリスト -->
        <mdb-list-group v-if="comments.length >0  && !isLoad" flush>
            <mdb-list-group-item v-for="(comment, index) in comments" :key="index">
                <div>
                    <div class="d-flex w-100 justify-content-between">
                    <small>
                        <router-link class="mb-2" :to="{ name: 'userDetail', params: { user_id: comment.user.id }}" >{{ comment.user.name }}</router-link >
                    </small>
                    </div>
                    <p v-html="comment.comment" class="mb-2">
                    </p>
                    <small>{{comment.created_at}}
                        <!-- コメントかコメントされている作品が自分のものであれば削除ボタンを表示する -->
                        <span @click="tmp_for_delete_id = comment.id, confirmation_modal = true" class="mr-2" v-if="its_mine || comment.is_your_comment"><mdb-icon color="red" icon="trash-alt" /></span>
                    </small>
                    
                </div>
            </mdb-list-group-item>
        </mdb-list-group>
        <!-- ページネーション -->
        <div class="d-flex justify-content-center" v-if="comments.length >0  && !isLoad">
            <Pagination :component="`works/${$route.params.work_id}/comments`" :current-page="currentPage" :last-page="lastPage" />
        </div>
        

        <!-- コメント新規作成モーダル -->
        <mdb-modal :show="commentsModal" @close="commentsModal = false">
            <mdb-modal-header >
                <mdb-modal-title>コメントの新規作成</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                <mdb-textarea rows="3" v-model="commentsForm" />
            </mdb-modal-body>
            <mdb-modal-footer>
                
                <mdb-btn class="shadow-none" :disabled="formBtn" color="danger" @click.native="clearCommentsModal()">閉じる</mdb-btn>
                <mdb-btn class="shadow-none" :disabled="commentsForm.length>255 || commentsForm.length<=0 || formBtn" color="mdb-color" @click.native="postComments()">{{formMsg}}</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>

        
        <!-- 削除確認モーダル -->
        <mdb-modal :show="confirmation_modal" @close="confirmation_modal=false">
            <mdb-modal-header>
                <mdb-modal-title>確認</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                本当に削除しますか？
            </mdb-modal-body>

            <mdb-modal-footer>
                <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="mdb-color" @click.native="confirmation_modal = false, tmp_for_delete_id = null">キャンセル</mdb-btn>
                <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="danger " @click.native="deleteComment(tmp_for_delete_id)">{{deleteBtnMsg}}</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>


        <div v-if="isLogin" @click="commentsModal=true" class="btn-circle-flat shadow"><span class="h2 mousepointer-hand">+</span></div>
    

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
            Pagination,
            Nothing
        },
        data(){
            return{
                comments:[],
                commentsModal:false,
                commentsForm:"",
                currentPage: 0,
                lastPage: 0,
                warnModal:false,
                its_mine:false,
                confirmation_modal:false,
                tmp_for_delete_id:null,
                confirmation_modal:false,
                isDeleteBtnActive:true,
                deleteBtnMsg:'削除',
                formBtn:false,
                formMsg:'送信',
                isLoad:true
             
            }
        },
        props: {
            page: {
                type: Number,
                required: false,
                default: 1
            },
            work_id:Number
        },

        methods:{
            // コメント取得
            async fetchComments(){
                
                const response = await axios.get(`/api/works/${this.$route.params.work_id}/comments?page=${this.page}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }

                this.comments = response.data.comments.data;
                this.its_mine = response.data.its_mine;

                this.currentPage = response.data.comments.current_page
                this.lastPage = response.data.comments.last_page
            },
            // コメント削除
            async deleteComment(id){  
                this.isDeleteBtnActive = false;
                this.deleteBtnMsg = '削除中...';
                const response = await axios.delete(`/api/works/${this.$route.params.work_id}/comments/${id}`);
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                };
                // 戻る
                this.$router.push(`/works/${this.$route.params.work_id}`)     
       
            },
          


            // モーダルを初期化する関数
            clearCommentsModal(){
                
                this.commentsModal = false;
                this.commentsForm = "";
            },
            // コメント送信
            async postComments(){ 
                this.formBtn = true;
                this.formMsg = "送信中...";

                const response = await axios.post(`/api/works/${this.$route.params.work_id}/comments`, {'comment': this.commentsForm});
           
                this.clearCommentsModal();
                this.fetchComments();

                this.formBtn = false;
                this.formMsg = "send";
       
            }
        },
        watch: {
            $route: {
            async handler () {
                await this.fetchComments();
                this.isLoad=false;
            },
            immediate: true
            }
        },
        computed: {
            // ログインしているか
            isLogin () {
                return this.$store.getters['auth/check'];
            },
            // ユーザーの名前
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
   
</style>
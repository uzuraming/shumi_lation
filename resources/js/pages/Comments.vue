<template>
    <div>
        <div v-if="comments.length <=　0">
            コメントはありません。
        </div>
        <mdb-list-group v-if="comments.length >0" flush>
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
                        <span @click="tmp_for_delete_id = comment.id, confirmation_modal = true" class="mr-2" v-if="its_mine || comment.is_your_comment"><mdb-icon color="red" icon="trash-alt" /></span>
                    </small>
                    
                </div>
            </mdb-list-group-item>
        </mdb-list-group>
        <div class="d-flex justify-content-center" >
            <Pagination :component="`works/${$route.params.work_id}/comments`" :current-page="currentPage" :last-page="lastPage" />
        </div>
        

        <mdb-modal :show="commentsModal" @close="commentsForm = ''">
            <mdb-modal-header :close="false" >
                <mdb-modal-title>New Post</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                <mdb-textarea rows="3" v-model="commentsForm" />
            </mdb-modal-body>
            <mdb-modal-footer>
                
                <mdb-btn class="shadow-none" :disabled="formBtn" color="danger" @click.native="clearCommentsModal()">Close</mdb-btn>
                <mdb-btn class="shadow-none" :disabled="commentsForm.length>255 || commentsForm.length<=0 || formBtn" color="mdb-color" @click.native="postComments()">{{formMsg}}</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>

        <mdb-modal :show="confirmation_modal">
            <mdb-modal-header :close="false" >
                <mdb-modal-title>Confirmation</mdb-modal-title>
            </mdb-modal-header>
            <mdb-modal-body>
                本当に削除しますか？
            </mdb-modal-body>

            <mdb-modal-footer>
                <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="mdb-color" @click.native="confirmation_modal = false, tmp_for_delete_id = null">Cancel</mdb-btn>
                <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="danger " @click.native="deleteComment(tmp_for_delete_id)">{{deleteBtnMsg}}</mdb-btn>
            </mdb-modal-footer>
        </mdb-modal>


        <div v-if="isLogin" @click="commentsModal=true" class="btn-circle-flat shadow"><span class="h2 mousepointer-hand">+</span></div>
    

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
                deleteBtnMsg:'delete',
                formBtn:false,
                formMsg:'send',
             
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
            async fetchComments(){
                
                const response = await axios.get(`/api/works/${this.$route.params.work_id}/comments?page=${this.page}`)
        
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                }
                console.log(response.data)
                this.comments = response.data.comments.data;
                this.its_mine = response.data.its_mine;
                
                

                this.currentPage = response.data.comments.current_page
                this.lastPage = response.data.comments.last_page
            },
            async deleteComment(id){  
                this.isDeleteBtnActive = false;
                this.deleteBtnMsg = 'deleting...';
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
            async postComments(){ 
                this.formBtn = true;
                this.formMsg = "sending...";

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
            },
            immediate: true
            }
        },
        computed: {
            isLogin () {
                return this.$store.getters['auth/check'];
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
<template>

  <div class="">
    <mdb-card class="card-body" style="">
        <!-- 入力フォーム -->
        <mdb-card-title v-if="pageName != 'editWork'" class="text-center">新規作成</mdb-card-title>
        <mdb-card-title v-if="pageName == 'editWork'" class="text-center">{{title}}の編集</mdb-card-title>
        <div class="flex-row">
            <form @submit.prevent="pageName=='editWork'? editWork() : postWork()" class="mt-5">
                <!-- <mdb-input label="タイトル" v-model="title" type="text"/> -->
                <div class="form-group">
                    <label for="work-title" >タイトル</label>
                    <input type="password" v-model="title"  id="work-title" class="form-control">
                </div>
                <select v-model="genre" class="browser-default custom-select mb-2">
                    <option value="文学" selected>文学</option>
                    <option value="エッセイ">エッセイ</option>
                    <option value="ライトノベル">ライトノベル</option>
                    <option value="ファンタジー">ファンタジー</option>
                    <option value="恋愛">恋愛</option>
                    <option value="SF">SF</option>
                    <option value="other">その他</option>
                </select>

                <div class="p-2 border border-light">

                    <div class="input-group">
                    <div class="custom-file">
                        <input type="file" accept="image/*" @change="onFileChange" class="custom-file-input" id="home-inputfile" data-browse="参照" aria-describedby="inputGroupFileAddon01">
                        <label class="custom-file-label" for="inputGroupFile01">
                            
                            {{img_name}}
                        </label>
                    </div>
                    </div>

                    <!-- ここに添付した画像が表示される -->
                    <div class="position-relative" v-if="uploadedImage">
                    <img :src="uploadedImage" style="width:100%;"  />
                    <mdb-btn  class="z-depth-2 position-absolute remove-carousel" @click="removeUploadedImg()" color="danger">削除</mdb-btn>
                    
                    </div>


                    

                </div>



                <vue-editor v-model="content" />
                <div class="text-center mt-4">
                    <button class="btn btn-dark shadow-none rounded-0" :disabled="content.length<=0 || title.length<=0 || !isFormBtnActive || img_condition"  type="submit">{{ formBtnMsg }}</button>
                    <button v-if="pageName == 'editWork'" class="btn btn-danger shadow-none rounded-0" @click="confirmation_modal = true" type="button">削除</button>
                </div>
            </form>
        </div>
    </mdb-card>
    <!-- <h2 v-if="pageName != 'editWork'" class="text-center">新規作成</h2>
    <h2 v-if="pageName == 'editWork'" class="text-center">{{title}}の編集</h2>
     -->
    <!-- <form @submit.prevent="pageName=='editWork'? editWork() : postWork()" class="mt-5">
        <mdb-input label="title" v-model="title" type="text"/>
        <vue-editor v-model="content" />
        <div class="text-center mt-4">
            <button class="btn btn-dark shadow-none rounded-0" :disabled="content.length<=0 || title.length<=0 || !isFormBtnActive"  type="submit">{{ formBtnMsg }}</button>
        </div>
    </form> -->


    <!-- 削除するときの確認 -->
    <mdb-modal :show="confirmation_modal" @close="confirmation_modal=false">
        <mdb-modal-header  >
            <mdb-modal-title>確認</mdb-modal-title>
        </mdb-modal-header>
        <mdb-modal-body>
            本当に削除しますか？
        </mdb-modal-body>

        <mdb-modal-footer>
            <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="mdb-color" @click.native="confirmation_modal = false">キャンセル</mdb-btn>
            <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="danger " @click.native="deleteWork()">{{deleteBtnMsg}}</mdb-btn>
        </mdb-modal-footer>
    </mdb-modal>

        


  </div>
</template>

<script>
import { CREATED } from '../util'
import { OK } from '../util'
import { VueEditor } from "vue2-editor";
import { mdbBtn, 
        mdbInput, 
        mdbIcon,
        mdbModal, 
        mdbModalHeader, 
        mdbModalTitle, 
        mdbModalBody, 
        mdbModalFooter,
        mdbCard, mdbCardTitle, mdbCardText} from 'mdbvue';


export default {
    components: { VueEditor, mdbInput, mdbBtn, mdbModal, 
        mdbModalHeader, 
        mdbIcon ,
        mdbModalTitle, 
        mdbModalBody, 
        mdbModalFooter,
        mdbCard, mdbCardTitle, mdbCardText },
    data(){
        return{
            content: "",
            title:"",
            isFormBtnActive:true,
            formBtnMsg:'送信',
            confirmation_modal:false,
            isDeleteBtnActive:true,
            deleteBtnMsg:'削除',
            genre:'文学',
            uploadedImage: '',
            img_name: 'ファイルを選択',
            inputImg:null, // アップロードした画像の情報
          
        }
    },
    props:{
        pageName:String,
    },
    methods:{
        // 作品を投稿
        async postWork(){  
                this.isFormBtnActive = false;
                this.formBtnMsg = '送信中...';

                const formData = new FormData()
                if(this.inputImg){
                    formData.append('img_path', this.inputImg[0])
                }
                
                formData.append('content', this.content)
                formData.append('title', this.title)
                formData.append('genre', this.genre)

                console.log(formData)
                const response = await axios.post('/api/works', formData, {
                    headers: {
                    'Content-Type': 'multipart/form-data'
                    }
                });
             
                    // 戻る
                    this.$router.back()     
       
        },
        // 作品を編集
        async editWork(){  
                this.isFormBtnActive = false;
                this.formBtnMsg = '送信中...';
                const formData = new FormData()
                if(this.inputImg === "default"){
                    formData.append('img_path', "default")
                }else if(this.inputImg){
                    formData.append('img_path', this.inputImg[0])
                }
   
                formData.append('content', this.content)
                formData.append('title', this.title)
                formData.append('genre', this.genre)
                const response = await axios.post(`/api/works/${this.$route.params.work_id}`, formData, {
                    headers: {
                    'X-HTTP-Method-Override': 'PUT'
                    }
                })
                // 戻る
                this.$router.back()     
       
        },
        // 作品を削除
        async deleteWork(){  
                this.isDeleteBtnActive = false;
                this.deleteBtnMsg = '削除中...';
                const response = await axios.delete(`/api/works/${this.$route.params.work_id}`);
                if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                };

                // 戻る
                this.$router.push('/works')     
       
        },
        // 作品を取得（編集時のみ）
        async fetchWork(){
            const response = await axios.get(`/api/works/${this.$route.params.work_id}`);
            if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
                };
                if(!response.data.its_mine){
                    this.$router.back() 
                }else{
                    console.log(response);
                    this.title = response.data.title;
                    this.content = response.data.content;
                    this.uploadedImage = response.data.url;
                    // 初期状態のばあい "default"を代入しておく。
                    this.inputImg = "default";
                };
                
        },
        // 画像を削除する関数
        removeUploadedImg(){
            this.uploadedImage="";
            this.inputImg="";
            this.img_name="ファイルを選択"
            document.getElementById("home-inputfile").value = '';

        },
        // ファイルが添付された時に発動する関数
        onFileChange(e) {
            console.log('imgUpload')
            let files = e.target.files || e.dataTransfer.files;
            this.img_name = files[0].name;
            if(files[0]){
                    this.inputImg = files
                    this.createImage(files[0]);
            }
            console.log(this.inputImg)
        },
        // アップロードした画像を表示
        createImage(file) {
            console.log('imgCreated')
            let reader = new FileReader();
            reader.onload = (e) => {
            this.uploadedImage = e.target.result;

            };
            reader.readAsDataURL(file);
        },


    },
    watch: {
            $route: {
            async handler () {
                if(this.pageName == 'editWork'){
                    await this.fetchWork();
                }
                
            },
            immediate: true
        }
    },
    computed:{
        img_condition(){
            if(!this.inputImg){
                return false;
            }
            else if(!this.inputImg[0]){
                return false;
            }else if(this.inputImg == "default"){
                return false
            }else if(this.inputImg[0].type != 'image/jpeg' && this.inputImg[0].type != 'image/gif' && this.inputImg[0].type != 'image/png' && this.inputImg[0].type != 'application/pdf'){
                return true
            // 大きすぎる画像が添付されている場合
            }else if(this.inputImg[0].size > 3000000){
                return true
            }else{
                return false
            }
        }
    }
    

}
</script>

<style>
    .remove-carousel{
    bottom:2rem;
    right:2rem
    }

</style>
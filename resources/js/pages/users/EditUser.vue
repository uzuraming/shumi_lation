<template>
  <div>
      <form @submit.prevent="putUser" class="mt-5  ">

        <div class="row white p-2">
            <!-- 画像フォーム -->
            <div class="p-2 border border-light col-sm-6">

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
                <mdb-btn  class="shadow-none position-absolute remove-carousel" @click="removeUploadedImg()" color="danger">削除</mdb-btn>
                
                </div> 
            </div>


            <div class="col-sm-6">
                <!-- ユーザー名入力フォーム -->
                <!-- <mdb-input label="名前" v-model="user.name" type="text"/> -->

                <div class="form-group">
                    <label for="user-edit-name" >名前</label>
                    <input type="text" v-model="user.name"  id="user-edit-name" class="form-control">
                </div>


                <!-- プロフィール入力フォーム -->
                <mdb-textarea class="mt-5" label="プロフィール" rows="5" v-model="user.profile" />

            </div>
            
    
        </div>

        <!-- 送信ボタン -->
        <div class="text-center mt-4">
            <button class="btn btn-dark shadow-none rounded-0" :disabled="user.name.length<=0 ||!isFormBtnActive || img_condition"  type="submit">{{ formBtnMsg }}</button>
            <!-- 削除ボタン -->
            <button class="btn btn-danger shadow-none rounded-0" @click="confirmation_modal=true"  type="button">削除</button>
        </div>
        
    </form>

    


    <!-- 削除確認モーダル -->
    <mdb-modal :show="confirmation_modal">
        <mdb-modal-header :close="false" >
            <mdb-modal-title>Confirmation</mdb-modal-title>
        </mdb-modal-header>
        <mdb-modal-body>
            本当に削除しますか？
        </mdb-modal-body>

        <mdb-modal-footer>
            <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="mdb-color" @click.native="confirmation_modal = false">キャンセル</mdb-btn>
            <mdb-btn :disabled="!isDeleteBtnActive" class="shadow-none" color="danger" @click.native="destroyUser()">削除</mdb-btn>
        </mdb-modal-footer>
    </mdb-modal>

  </div>
</template>

<script>
import { OK } from '../../util'
import { mdbBtn, 
        mdbModal, 
        mdbModalHeader, 
        mdbTextarea,
        mdbModalTitle, 
        mdbModalBody, 
        mdbModalFooter,} from 'mdbvue';
        
export default {
    data(){
        return {
            isFormBtnActive:true, 
            formBtnMsg:'更新',
            confirmation_modal:false,
            isDeleteBtnActive:true,
            user:{
                name:"",
                profile:"",
                inputImg:null, // アップロードした画像の情報
            },
            uploadedImage: '',
            img_name: 'ファイルを選択',
        }
    },
    components: { 

        mdbBtn, 
        mdbModal, 
        mdbTextarea,
        mdbModalHeader, 
        mdbModalTitle, 
        mdbModalBody, 
        mdbModalFooter, 
    },
    props:{
        user_id:String,
    },
    methods:{
        // ユーザー情報を取得
        async fetchUser(){
            const response = await axios.get(`/api/users/${this.user_id}`);
            if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
            };
            
            // データ上のits_meで自分かどうかを判断し、そうでなかったらブラウザバックさせる
            if(response.data.its_me){
     
                this.user.name =response.data.user.name;
                this.user.profile =response.data.user.profile;
                this.uploadedImage =response.data.user.url;
                console.log(this.user)
            }else{
                this.$router.back() ;
            }
    
        },
        // ユーザー情報をアップデート
        async putUser(){
            // ボタンを送信状態にする
            this.isFormBtnActive = false;
            this.formBtnMsg = '送信中...';

            // フォームオブジェクトを作成
            const formData = new FormData()
            if(this.inputImg === "default"){
                // 画像データが初期状態だったら"default"
                formData.append('img_path', "default")
            }else if(this.inputImg){
                // 画像データがあったら
                formData.append('img_path', this.inputImg[0])
            };

            // 名前情報とプロフィールを入れる
            formData.append('name', this.user.name)
            formData.append('profile', this.user.profile)
            const response = await axios.post(`/api/users/${this.user_id}`, formData, {
                headers: {
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            this.$router.back();
        },
        // ユーザー削除
        async destroyUser(){
            isDeleteBtnActive = false;
            const response = await axios.delete(`/api/users/${this.user_id}`);
            if (response.status !== OK) {
                    this.$store.commit('error/setCode', response.status)
                    return false
            };

            // 戻る
            this.$router.push('/')   
            isDeleteBtnActive = true;

        },
        // 画像を削除する関数
        removeUploadedImg(){
            this.uploadedImage="";
            this.user.inputImg="";
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
                await this.fetchUser();
            },
            immediate: true
        }
    },
    computed:{
        // 送信できる状態の画像が添付されているかを判定する関数
        img_condition(){
            
            if(!this.user.inputImg){
                // 添付されていない場合
                return false;
            }
            else if(!this.user.inputImg[0]){
                // 添付されていない場合
                return false;
            }else if(this.user.inputImg == "default"){
                // 画像が初期状態の場合
                return false
            }else if(this.user.inputImg[0].type != 'image/jpeg' && this.user.inputImg[0].type != 'image/gif' && this.user.inputImg[0].type != 'image/png' && this.user.inputImg[0].type != 'application/pdf'){
                // 画像が画像以外の場合
                return true
            // 大きすぎる画像が添付されている場合
            }else if(this.user.inputImg[0].size > 3000000){
                return true
            }else{
                return false
            }
        }
    }

}
</script>

<style scoped>
.remove-carousel{
    bottom:2rem;
    right:2rem
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

</style>
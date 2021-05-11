<template>
    <!-- 作品やタイムラインなどがなにもないときの画面 -->
    <div class="mb-4">
    
            <mdb-card class="shadow-none mb-4">
                <mdb-card-body class="text-center">
                <mdb-card-title >{{this.text}}</mdb-card-title>
                    <!-- 戻るボタン -->
                    <button @click="$router.back()" class="btn btn-primary shadow-none">
                        戻る
                    </button>
                </mdb-card-body>
            </mdb-card>
          

  </div>
</template>

<script>
import {mdbCard,
        mdbCardImage,
        mdbCardBody,
        mdbCardTitle,
        mdbCardText,
        mdbBtn} from 'mdbvue';
export default {
    data(){
        return{

        }
    },
    components:{
        mdbBtn,
        mdbCard,
        mdbCardImage,
        mdbCardBody,
        mdbCardTitle,
        mdbCardText,
		
    },
    props:{
        text:String,
    },
    name:'Nothing',
}
</script>

<style scoped>
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
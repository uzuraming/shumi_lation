<template>
  <div>

      <mdb-card class="card-body shadow-none" style="margin-top: 1rem;">
          <mdb-card-body>
              <mdb-card-title class="text-center">
          404
      </mdb-card-title>
        <mdb-card-text class="text-center">
            <p>ページが存在しません。</p>
            <button class="btn btn-primary shadow-none" @click="$router.push({path:'/'})">
                ホームに戻る
            </button>
        </mdb-card-text>
              
          </mdb-card-body>
        
        
    </mdb-card>
      
      
  </div>
</template>

<script>
import { mdbCard, mdbCardTitle, mdbCardText, mdbBtn, mdbCardBody, } from 'mdbvue';

export default {
    components: {
      mdbCard,
      mdbCardTitle,
      mdbCardText,
      mdbCardBody,
    }

}
</script>

<style>

</style>
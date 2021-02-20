<template>
  <!-- Footer -->
  <mdb-footer color="grey lighten-2" class="font-small pt-4 mt-4 text-dark">
    
    <div class="footer-copyright text-center py-3 text-dark">
      <mdb-container fluid class="text-dark">
        &copy; 2020 Copyright: <a class="text-dark" href="https://www.MDBootstrap.com"> MDBootstrap.com </a>
      </mdb-container>
    </div>
  </mdb-footer>
  <!-- Footer -->
</template>

<script>
  import { mdbFooter, mdbContainer, mdbRow, mdbCol } from 'mdbvue';
  export default {
    name: 'Footer',
    components: {
      mdbFooter,
      mdbContainer,
      mdbRow,
      mdbCol
    }
  }
</script>
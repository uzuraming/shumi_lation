<template>
  <mdb-tabs class="fixed-bottom"
    :active="0"
    pills
    fill
    justify
    :links="[
      { text: 'Active' },
      { text: 'Link' },
      { text: 'Link' },
      { text: 'Disabled', disabled: true }]"
  />
</template>

<script>
  import { mdbTabs } from 'mdbvue';
  export default {
    name: 'Tabs',

    components: {
      mdbTabs
    }
  }
</script>

<style>
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {

    color: #fff !important;

    background-color: #2E2E2E !important;

}
    
</style>
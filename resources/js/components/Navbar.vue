<template>
  <mdb-navbar class="shadow-none" expand="large" color="transprant" light>
    <mdb-navbar-brand href="#">
      Navbar
    </mdb-navbar-brand>
    <mdb-navbar-toggler>
      <mdb-navbar-nav right>
        <mdb-nav-item to="/">Home</mdb-nav-item>
        <mdb-nav-item to="/timelines">TimeLine</mdb-nav-item>
        <mdb-nav-item to="/works">Work</mdb-nav-item>
        <mdb-nav-item v-if="!isLogin" to="/login">login</mdb-nav-item>
        <mdb-nav-item v-if="!isLogin" to="/signup">signup</mdb-nav-item>
        <mdb-nav-item v-if="isLogin" href="#" @click.prevent="logout">logout</mdb-nav-item>
      </mdb-navbar-nav>
    </mdb-navbar-toggler>
  </mdb-navbar>
</template>

<script>
  import { mdbDropdown, mdbDropdownToggle, mdbDropdownMenu, mdbDropdownItem, mdbContainer, mdbNavbar, mdbNavbarBrand, mdbNavbarToggler, mdbNavbarNav, mdbNavItem } from 'mdbvue';
  export default {
    name: 'Navbar',
    components: {
      mdbNavbar,
      mdbNavbarBrand,
      mdbNavbarToggler,
      mdbNavbarNav,
      mdbNavItem,
      mdbContainer,
      mdbDropdown,
      mdbDropdownToggle,
      mdbDropdownMenu,
      mdbDropdownItem
    },
    methods:{
      async logout () {
        await this.$store.dispatch('auth/logout')
        this.$router.push('/login')
      }
    },
    computed: {
      isLogin () {
        return this.$store.getters['auth/check']
      },
      username () {
        return this.$store.getters['auth/username']
      }
    }
  }
</script>
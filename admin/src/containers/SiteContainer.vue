<template>
  <div class="app">
    <AppHeader fixed>
      <SidebarToggler class="d-lg-none" display="md" mobile />
      <b-link class="navbar-brand" to="#">
        <img class="navbar-brand-full" src="/img/brand/logo.svg" width="89" height="25" alt="CoreUI Logo">
        <img class="navbar-brand-minimized" src="/img/brand/sygnet.svg" width="30" height="30" alt="CoreUI Logo">
      </b-link>
      <SidebarToggler class="d-md-down-none" display="lg" />
      <b-navbar-nav class="d-md-down-none">
        <b-nav-item class="px-3" to="/">Dashboard</b-nav-item>
        <b-nav-item class="px-3" to="/users" exact>Users</b-nav-item>
        <b-nav-item class="px-3" :to="{name: 'Sites'}" exact>Sites</b-nav-item>
        <b-nav-item class="px-3">Settings</b-nav-item>
      </b-navbar-nav>
      <b-navbar-nav class="ml-auto">
        <b-nav-item class="d-md-down-none">
          <i class="icon-bell"></i>
          <b-badge pill variant="danger">5</b-badge>
        </b-nav-item>
        <b-nav-item class="d-md-down-none">
          <i class="icon-list"></i>
        </b-nav-item>
        <b-nav-item class="d-md-down-none">
          <i class="icon-location-pin"></i>
        </b-nav-item>
        <DefaultHeaderDropdownAccnt/>
      </b-navbar-nav>
      <AsideToggler class="d-none d-lg-block" />
      <!--<AsideToggler class="d-lg-none" mobile />-->
    </AppHeader>
    <div class="app-body">
      <AppSidebar fixed>
        <div class="site">{{ site.domain }}</div>
        <SidebarNav :navItems="links"></SidebarNav>
        <SidebarMinimizer/>
      </AppSidebar>
      <main class="main">
        <div class="container-fluid">
          <router-view :site="site"></router-view>
        </div>
      </main>
      <AppAside fixed>
        <!--aside-->
        <DefaultAside/>
      </AppAside>
    </div>
  </div>
</template>

<script>
import nav from '@/views/sites/_nav'
import { Header as AppHeader, SidebarToggler, Sidebar as AppSidebar, SidebarFooter, SidebarForm, SidebarHeader, SidebarMinimizer, SidebarNav, Aside as AppAside, AsideToggler, Footer as TheFooter, Breadcrumb } from '@coreui/vue'
import DefaultAside from './DefaultAside'
import DefaultHeaderDropdownAccnt from './DefaultHeaderDropdownAccnt'
import api from '@/shared/api'

export default {
  name: 'SiteContainer',
  components: {
    AsideToggler,
    AppHeader,
    AppSidebar,
    AppAside,
    TheFooter,
    Breadcrumb,
    DefaultAside,
    DefaultHeaderDropdownAccnt,
    SidebarForm,
    SidebarFooter,
    SidebarToggler,
    SidebarHeader,
    SidebarNav,
    SidebarMinimizer
  },
  data () {
    return {
      nav: nav.items,
      site: {}
    }
  },
  beforeRouteEnter (to, from, next) {
    Promise.all([api.get('/sites/' + to.params.siteId)])
      .then(values => {
        next(vm => vm.setData(null, values[0]))
      })
      .catch(error => {
        next(vm => vm.setData(error))
      });
  },
  beforeRouteUpdate (to, from, next) {
    Promise.all([api.get('/sites/' + to.params.siteId)])
      .then(values => {
        this.setData(null, values[0])
        next()
      })
      .catch(error => {
        this.setData(error)
      });
  },
  methods: {
    setData (err, info) {
      if (err) {
        this.error = err.toString()
      } else {
        this.site = info.data
      }
    }
  },
  computed: {
    links () {
      let n = [];
      for (let i = 0; i < nav.items.length; i++) {
        n.push({
          name: nav.items[i].name,
          url: '/sites/' + this.site.id + nav.items[i].url,
          icon: nav.items[i].icon,
          badge: nav.items[i].badge
        })
      }
      return n;
    },
    name () {
      return this.$route.name
    },
    list () {
      return this.$route.matched.filter((route) => route.name || route.meta.label )
    }
  }
}
</script>
<style lang="scss">
  .site {
    padding: 0.5rem;
  }
</style>

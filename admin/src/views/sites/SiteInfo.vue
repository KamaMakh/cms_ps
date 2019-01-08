<template>
  <div class="animated fadeIn page-site-info">
    <b-row>
      <b-col sm="6" lg="3">
        <b-button block variant="primary" :to="mainMenu ? { name: 'SiteMenus', params: { menuId: mainMenu.id }} : null" :disabled="!mainMenu">Edit menu</b-button>
        <b-button block variant="primary">Settings</b-button>
      </b-col>
      <b-col sm="6" lg="3">
        <b-card no-body class="bg-danger">
          <b-card-body class="pb-0">
            <b-dropdown class="float-right" variant="transparent p-0" right>
              <template slot="button-content">
                <i class="icon-settings"></i>
              </template>
              <b-dropdown-item>Action</b-dropdown-item>
              <b-dropdown-item>Another action</b-dropdown-item>
              <b-dropdown-item>Something else here...</b-dropdown-item>
              <b-dropdown-item disabled>Disabled action</b-dropdown-item>
            </b-dropdown>
            <h4 class="mb-0">9.823</h4>
            <p>Members online</p>
          </b-card-body>
          <card-bar-chart-example chartId="card-chart-04" class="chart-wrapper px-3" style="height:70px;" height="70"/>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import api from '@/shared/api'
import CardBarChartExample from '../dashboard/CardBarChartExample'

export default {
  name: 'SiteInfo',
  components: {
    CardBarChartExample,
  },
  props: ['id'],
  data: function () {
    return {
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
    this.site = {}

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
    mainMenu () {
      if (this.site.menus && this.site.menus.length) {
        for (let i = 0; i < this.site.menus.length; i++) {
          if (this.site.menus[i].is_main) {
            console.log(this.site.menus[i]);
            return this.site.menus[i];
            break;
          }
        }
      }
      return null
    }
  }
}
</script>

<style lang="scss">
.page-site-info {
}
</style>

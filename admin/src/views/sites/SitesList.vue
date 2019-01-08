<template>
  <div class="animated fadeIn">
      <b-card class="bg-secondary" v-for="site in list">
        <div slot="header">
          {{ site.name }} <a v-bind:href="'http://' + site.domain">{{ site.domain }}</a>
          <div class="card-header-actions">
            <router-link :to="{ name: 'SiteInfo', params: { siteId: site.id }}" class="card-header-action btn-setting"><i class="icon-settings"></i></router-link>
            <b-link class="card-header-action btn-minimize" v-b-toggle.collapse1>
              <i class="icon-arrow-up"></i>
            </b-link>
            <b-link href="#" class="card-header-action btn-close" v-on:click="show = !show">
              <i class="icon-close"></i>
            </b-link>
          </div>
        </div>
        <b-card-body>
          Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
        </b-card-body>
      </b-card>
  </div>
</template>

<script>

import api from '@/shared/api'

export default {
  name: 'SitesList',
  components: {
  },
  data: function () {
    return {
      list: []
    }
  },
  beforeRouteEnter (to, from, next) {
    api
      .get('/sites')
      .then(response => {
        next(vm => vm.setData(null, response))
      })
      .catch(error => {
        next(vm => vm.setData(error))
      });
  },
  // если путь изменяется, а компонент уже отображён,
  // то логика будет немного иной
  beforeRouteUpdate (to, from, next) {
    this.list = []
    api
      .get('/sites')
      .then(response => {
        this.setData(null, response)
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
        this.list = info.data
      }
    }
  },
}
</script>

<style>

</style>

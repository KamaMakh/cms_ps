<template>
  <div class="animated fadeIn page-design-list">
      <b-card class="bg-secondary" v-for="template in list">
        <div slot="header">
          {{ template.name }}
          <div class="card-header-actions">
            <router-link :to="{ name: 'TemplateEdit', params: { id: template.id }}" class="card-header-action btn-setting"><i class="icon-settings"></i></router-link>
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
  name: 'TemplatesList',
  components: {
  },
  data: function () {
    return {
      list: []
    }
  },
  beforeRouteEnter (to, from, next) {
    api
      .get('/templates')
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
      .get('/templates')
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

<style lang="scss">
</style>

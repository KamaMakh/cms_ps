<template>
  <div class="wrapper">
    <b-list-group flush>
      <b-list-group-item href="#" v-for="m in data" @click="select(m)">{{ m.name }}</b-list-group-item>
    </b-list-group>
  </div>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import {app} from '@/main'
import api from '@/shared/api'

export default {
  name: 'TextVarsGetOne',
  props: {
    data: {
      type: Array,
      default: function () {
        return []
      }
    },
    site: Object,
    menu: Object,
    page: Object,
    variable: Object
  },
  components: {
    ckeditor: CKEditor.component
  },
  data () {
    return {
      editor: ClassicEditor,
      empty: {title: '', body: ''}
    }
  },
  methods: {
    select: function(menu) {
      api.put('/sites/' + this.site.id + '/menus/' + this.menu.id + '/pages/' + this.page.id + '/variables/' + this.variable.id, menu)
    }
  },
  moduleMeta: {
    popup: {
      size: 'md',
      bodyNoPadding: true,
      buttons: {
        ok: false,
        cancel: false
      }
    }
  },
  created: function () {
    app.$on('variableDataSubmit', this.submit)
  },
  beforeDestroy: function () {
    app.$off('variableDataSubmit', this.submit)
  }
}
</script>

<style lang="scss">
  .wrapper {
    .ck-editor__editable {
      min-height: 400px;
    }
  }
</style>

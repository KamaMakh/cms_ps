<template>
  <div class="wrapper">
    <b-form-group>
      <b-form-input type="text" placeholder="Title" v-if="data" v-model="data.title"></b-form-input>
      <b-form-input type="text" placeholder="Title" v-if="!data && empty" v-model="empty.title"></b-form-input>
    </b-form-group>
    <ckeditor :editor="editor" v-if="data" v-model="data.body"></ckeditor>
    <ckeditor :editor="editor" v-if="!data && empty" v-model="empty.body"></ckeditor>
  </div>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

import {app} from '@/main'
import api from '@/shared/api'

export default {
  name: 'MenuVarsGetOne',
  props: {
    data: {
      type: Object,
      default: function () {
        return {title: '', body: ''}
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
    submit: function() {
      let d = this.data || this.empty;
      api.put('/sites/' + this.site.id + '/menus/' + this.menu.id + '/pages/' + this.page.id + '/variables/' + this.variable.id, d)
    }
  },
  moduleMeta: {
    popup: {
      buttons: {
        ok: true,
        cancel: true
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

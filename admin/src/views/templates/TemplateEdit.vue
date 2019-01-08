<template>
  <div class="animated fadeIn page-template-edit">
    <div class="info">
      <span class="design-name">
        {{ template.id }}: {{ template.name }}
      </span>
      <span class="buttons">
        <b-button variant="success" size="sm" v-on:click="save">Save</b-button>
      </span>
    </div>
    <div class="editor">
      <codemirror v-model="template.body" :options="cmOptions"></codemirror>
    </div>
  </div>
</template>

<script>

import api from '@/shared/api'
import { codemirror } from 'vue-codemirror'

// language
import 'codemirror/mode/htmlmixed/htmlmixed.js'

// require styles
import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/mbo.css'

export default {
  name: 'TemplateEdit',
  components: {
    codemirror
  },
  props: ['id'],
  data: function () {
    return {
      template: {},
      cmOptions: {
        // codemirror options
        tabSize: 2,
        mode: 'htmlmixed',
        theme: 'mbo',
        lineNumbers: true,
        line: true,
      }
    }
  },
  beforeRouteEnter (to, from, next) {
    api
      .get('/templates/' + to.params.id)
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
    this.template = {}
    api
      .get('/templates/' + to.params.id)
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
        this.template = info.data
      }
    },
    save () {
      api
        .put('/templates/' + this.$props.id, {
          body: this.template.body
        })
        .then((response) => {

        })
        .catch((error) => {

        });
    }
  },
}
</script>

<style lang="scss">
.page-template-edit {
  margin: -1.5rem -30px 0;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  .info {
    display: flex;
    padding: 0.75rem 1rem;
    list-style: none;
    background-color: #fff;
    align-items: center;
    .buttons {
      margin-left: auto;
    }

  }
  .editor {
    display: flex;
    flex-grow: 1;
  }
  .vue-codemirror {
    width: 100%;
    overflow: auto;
  }
  .CodeMirror {
    min-height: 100%;
  }
}
</style>

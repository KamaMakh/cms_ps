<template>
  <div class="animated fadeIn page-design-edit">
    <div class="template">
      <div class="info">
      <span class="design-name">
        {{ design.id }}: {{ design.name }}
      </span>
        <span class="buttons">
        <b-button variant="success" size="sm" v-on:click="save">Save</b-button>
      </span>
      </div>
      <div class="editor">
        <codemirror v-model="design.body" :options="cmOptions"></codemirror>
      </div>
    </div>
    <div class="settings">
      <DesignSettings v-bind:design="design" fixed />
    </div>

  </div>
</template>

<script>
import api from '@/shared/api'
import { codemirror } from 'vue-codemirror'
import DesignSettings from './DesignSettings'

// language
import 'codemirror/mode/htmlmixed/htmlmixed.js'

// require styles
import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/mbo.css'

export default {
  name: 'DesignEdit',
  components: {
    codemirror,
    DesignSettings
  },
  props: ['id'],
  data: function () {
    return {
      design: {},
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
    Promise.all([api.get('/designs/' + to.params.id), api.get('/variables')])
      .then(values => {
        next(vm => vm.setData(null, values[0]))
      })
      .catch(error => {
        next(vm => vm.setData(error))
      });
  },
  // если путь изменяется, а компонент уже отображён,
  // то логика будет немного иной
  beforeRouteUpdate (to, from, next) {
    this.design = {}

    Promise.all([api.get('/designs/' + to.params.id), api.get('/variables')])
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
        this.design = info.data
      }
    },
    save () {
      api
        .put('/designs/' + this.$props.id, {
          body: this.design.body
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
.page-design-edit {
  margin: -1.5rem -30px 0;
  flex-grow: 1;
  display: flex;
  .template {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    width: 75%;
    .info {
      display: flex;
      padding: 0.75rem 1rem;
      list-style: none;
      background-color: #fff;
      align-items: center;
      .buttons {
        margin: -5px 0 -5px auto;
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
  .settings {
    width: 25%;
    display: flex;
    flex-direction: column;
    .nav-tabs {
      height: 46px;
      background: #fff;
      .nav-link {
        border-radius: 0;
        border-top: 0;
        padding: 0.75rem 1rem;
      }
    }
    .tab-content {
      .tab-pane {
        padding: 0;
      }
    }
  }

}
</style>

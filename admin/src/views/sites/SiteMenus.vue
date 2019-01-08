<template>
  <div class="animated fadeIn page-site-menus">
    <b-nav pills v-if="menus.length > 1">
      <b-nav-item v-for="m in menus">{{ m.name }}</b-nav-item>
    </b-nav>

    <div class="menu-actions">
      <b-button variant="success" size="sm" @click="showSettings">Add page</b-button>
    </div>

    <div v-if="menu.pages" class="pages">
      <Tree :data="menu.pages" draggable="draggable" :getTriggerEl="dragEl">
        <div slot-scope="{data, store}">
          <template v-if="!data.isDragPlaceHolder">
            <span class="dragger"></span>
            <span class="opener"><span v-if="data.children && data.children.length" @click="store.toggleOpen(data)">{{data.open ? '-' : '+'}}</span></span>
            <span class="name">
              <b-dropdown variant="link" no-caret>
                <template slot="button-content">
                  {{data.name}}
                </template>
                <b-dropdown-item @click="editSettings(data)">Settings</b-dropdown-item>
                <b-dropdown-item @click="editData(data)">Edit data</b-dropdown-item>
                <b-dropdown-item @click="deletePage(data)">Delete page</b-dropdown-item>
              </b-dropdown>
              </span>
            <a class="link" target="_blank" v-bind:href="'http://' + site.domain + data.url">{{ data.url }}</a>
          </template>
        </div>
      </Tree>
    </div>
    <b-modal id="pageSettings" :title="pageSettings.id ? 'Edit page' : 'New Page'" v-model="pageSettingsVisibility" @ok.prevent="saveSettings(activePage)">
      <b-form class="needs-validation" novalidate @submit.prevent="saveSettings(activePage)">
        <b-row>
          <b-col sm="12">
            <b-form-group>
              <label>Name</label>
              <b-form-input type="text" v-model="pageSettings.name" required></b-form-input>
              <b-form-invalid-feedback>
                Please provide a required input.
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="12">
            <b-form-group>
              <label>URL</label>
              <b-form-input type="text" v-model="pageSettings.url" required></b-form-input>
              <b-form-invalid-feedback>
                Please provide a required input.
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="12">
            <b-form-group>
              <label>Вид страницы</label>
              <b-form-select :options="pageSettings.templates" v-model="pageSettings.template_id"></b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-modal>

    <b-modal id="pageDatas" :title="activePage && activePage.name" v-model="pageDataVisibility" hide-footer cancel-disabled ok-disabled>
      <b-list-group flush v-if="activePage && activePage.variables">
        <b-list-group-item class="variable" v-for="v in activePage.variables">
          <a class="name" @click.prevent="editVariableData(activePage, v)">{{ v.name }}</a>
        </b-list-group-item>
      </b-list-group>
    </b-modal>

    <b-modal id="variableData"
             v-model="variableDataVisibility"
             :class="{ 'body-no-padding': variableDataBodyPadding }"
             :size="variableDataSize"
             :hide-footer="!variableDataButtonsOK && !variableDataButtonsCANCEL"
             :cancel-disabled="!variableDataButtonsCANCEL"
             :ok-disabled="!variableDataButtonsOK"
             @ok="submitVariableData"
    >
      <component
        v-bind:is="variableComponent"
        :data="variableData"
        :site="site"
        :menu="menu"
        :page="activePage"
        :variable="activeVariable"
      ></component>
    </b-modal>
    <b-modal ref="pageDeleteConfirm" hide-footer title="Delete Page">
      <div class="d-block text-center">
        <h3>Delete page</h3>
      </div>
      <b-btn class="mt-3" variant="outline-danger" block @click="confirmDelete">YES! Delete it</b-btn>
    </b-modal>
  </div>
</template>

<script>
import {app} from '@/main'
import upperFirst from 'lodash/upperFirst'
import camelCase from 'lodash/camelCase'
import api from '@/shared/api'
import {DraggableTree} from 'vue-draggable-nested-tree'

let components = {};

const requireComponent = require.context(
  // Относительный путь до каталога компонентов
  '@/views/modules',
  // Обрабатывать или нет подкаталоги
  true,
  // Регулярное выражение для определения файлов базовых компонентов
  /[A-Z]\w+\.vue$/
)

requireComponent.keys().forEach(fileName => {
  const componentConfig = requireComponent(fileName)
  const componentName = upperFirst(
    camelCase(
      fileName.replace(/^\.\/(.*)\.\w+$/, '$1')
    )
  )
  components[componentName] = componentConfig.default || componentConfig
})

components['Tree'] = DraggableTree

export default {
  name: 'SiteMenus',
  inherit: true,
  components,
  props: ['site', 'menuId'],
  data: function () {
    return {
      menus: [],
      menu: {},
      pageSettings: {},
      activePage: null,
      activeVariable: null,
      pageSettingsVisibility: false,
      pageDataVisibility: false,
      variableDataVisibility: false,
      variableComponent: null,
      variableData: null,
      variableDataButtonsOK: false,
      variableDataButtonsCANCEL: false,
      variableDataSize: 'lg',
      variableDataBodyPadding: false,
    }
  },
  beforeRouteEnter (to, from, next) {
    Promise.all([api.get('/sites/' + to.params.siteId + '/menus'), api.get('/sites/' + to.params.siteId + '/menus/' + to.params.menuId)])
      .then(values => {
          next(vm => {
            vm.setMenus(null, values[0])
            vm.setMenu(null, values[1])
          })
      })
      .catch(error => {
        next(vm => vm.setMenu(error))
      });
  },
  beforeRouteUpdate (to, from, next) {
    this.menus = []
    this.menu = {}

    Promise.all([api.get('/sites/' + this.site.id + '/menus'), api.get('/sites/' + this.site.id + '/menus/' + to.params.menuId)])
      .then(values => {
        this.setMenus(null, values[0])
        this.setMenu(null, values[1])
        next()
      })
      .catch(error => {
        this.setData(error)
      });
  },
  methods: {
    setMenus (err, info) {
      if (err) {
        this.error = err.toString()
      } else {
        this.menus = info.data
      }
    },
    setMenu (err, info) {
      if (err) {
        this.error = err.toString()
      } else {
        this.menu = info.data
      }
    },
    onOpen () {
      this.$refs.pageSettings.$emit('open')
    },
    showSettings (page) {
      Promise.all([api.get('/sites/' + this.site.id + '/templates/pages')])
        .then(values => {
          let templates = []
          if (values[0].data) {
            values[0].data.forEach((t) => {
              templates.push({
                text: t.name,
                value: t.id
              });
            })
          }

          this.pageSettings = {
            templates,
            template_id: templates[0].value,
            name: '',
            url: ''
          }
          this.pageSettingsVisibility = true
        })
        .catch(error => {
          // show error
        });
    },
    saveSettings (page) {
      let request
      if (page && page.id) {
        request = api.put('/sites/' + this.site.id + '/menus/' + this.menu.id + '/pages/' + page.id, {
          name: this.pageSettings.name,
          url: this.pageSettings.url,
          template_id: this.pageSettings.template_id
        })
      } else {
        request = api.post('/sites/' + this.site.id + '/menus/' + this.menu.id + '/pages', {
          name: this.pageSettings.name,
          url: this.pageSettings.url,
          template_id: this.pageSettings.template_id
        })
      }

      request.then(response => {
        if (page) {
          for (let i in response.data) {
            if (response.data.hasOwnProperty(i)) {
              page[i] = response.data[i]
            }
          }
        } else {
          this.menu.pages.push(response.data);
        }
        this.pageSettingsVisibility = false
        })
      .catch(error => {
        // show error
      });
    },
    editSettings (page) {
      Promise.all([api.get('/sites/' + this.site.id + '/templates/pages'), api.get('/sites/' + this.site.id + '/menus/' + this.menu.id + '/pages/' + page.id)])
        .then(values => {
          let templates = []
          if (values[0].data) {
            values[0].data.forEach((t) => {
              templates.push({
                text: t.name,
                value: t.id
              });
            })
          }

          this.activePage = page

          this.pageSettings = {
            id: page.id,
            templates,
            template_id: values[1].data.template_id,
            name: values[1].data.name,
            url: values[1].data.url.replace(/^\//g, '')
          }
          this.pageSettingsVisibility = true
        })
        .catch(error => {
          // show error
        });
    },
    editData (page) {
      Promise.all([api.get('/sites/' + this.site.id + '/menus/' + this.menu.id + '/pages/' + page.id + '/variables')])
        .then(values => {
          this.activePage = page
          this.pageDataVisibility = true

          this.activePage.variables = values[0].data
        })
        .catch(error => {
          // show error
        });
    },
    deletePage: async function(page){
      this.activePage = page;
      this.$refs.pageDeleteConfirm.show()
    },
    confirmDelete: async function() {
      await api.delete('/sites/' + this.site.id + '/menus/' + this.menu.id + '/pages/' + this.activePage.id)
      this.$refs.pageDeleteConfirm.hide()
    },
    editVariableData (page, variable) {
      this.variableData = null
      this.activeVariable = variable
      this.variableComponent = variable.type + 'Vars' + variable.method;
      let v = components[this.variableComponent];

      if (v) {
        this.variableDataButtonsOK = v.moduleMeta && v.moduleMeta.popup && v.moduleMeta.popup.buttons && v.moduleMeta.popup.buttons.ok
        this.variableDataButtonsCANCEL = v.moduleMeta && v.moduleMeta.popup && v.moduleMeta.popup.buttons && v.moduleMeta.popup.buttons.cancel
        this.variableDataSize = v.moduleMeta && v.moduleMeta.popup && v.moduleMeta.popup.size ? v.moduleMeta.popup.size : 'lg'
        this.variableDataBodyPadding = v.moduleMeta && v.moduleMeta.popup && v.moduleMeta.popup.bodyNoPadding
        Promise.all([api.get('/sites/' + this.site.id + '/menus/' + this.menu.id + '/pages/' + page.id + '/variables/' + variable.id)])
          .then(values => {
            this.activePage = page
            this.variableData = values[0].data
            this.variableDataVisibility = true

          })
          .catch(error => {
            // show error
          });
      }
    },
    submitVariableData (page, variable) {
      app.$emit('variableDataSubmit');
    },
    cancelVariableData (page, variable) {
      app.$emit('variableDataCancel');
    },
    dragEl(nodeVm) {
      return nodeVm.$el.querySelector('.dragger')
    }
  },
}
</script>

<style lang="scss">
.page-site-menus {
  .pages {
    display: inline-block;
    margin-top: 1.5rem;
  }
  .tree-node-inner {
    padding: 5px;
    border: 1px solid transparent;
    > div {
      display: flex;
      .dragger {
        width: 10px;
        background: #000;
        cursor: grab;
      }
      .opener {
        width: 1rem;
        margin: 0 5px;
        display: flex;
        > * {
          width: 100%;
          text-align: center;
          cursor: pointer;
        }
      }
      .name {
        margin-right: 1rem;
        cursor: pointer;
        button {
          padding: 0;
          font-size: 1.2em;
          color: #3e3e3e;
          border: 0;
        }
      }
    }
  }
  .dragging {
    .tree-node-inner {
      > div {
        .dragger {
          cursor: grabbing;
        }
      }
    }
  }
  .draggable-placeholder-inner {
    border: 1px dashed #0088F8;
    box-sizing: border-box;
    background: rgba(0, 136, 249, 0.09);
    color: #0088f9;
    text-align: center;
    padding: 0;
    display: flex;
    align-items: center;
  }
  .body-no-padding {
    .modal-body {
      padding: 0;
    }
  }
}
</style>

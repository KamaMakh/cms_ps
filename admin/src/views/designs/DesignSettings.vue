<template>
  <div class="design-settings">
    <b-tabs :class="{'fixed': fixed}">
      <b-tab>
        <template slot="title">
          <i class='icon-list'></i>
        </template>
        <b-list-group class="list-group-accent">
          <b-list-group-item class="list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase small">
            <b-button class="pull-right add-variable" variant="primary" size="sm" @click="openVariable()">add</b-button>
            Variables
          </b-list-group-item>
          <div class="no-variables" v-if="!design.variables || !design.variables.length">empty</div>
          <b-list-group-item class="list-group-item-accent-warning list-group-item-divider variable" v-for="v in design.variables">
            <div class="actions float-right">
              <b-button class="remove-variable" variant="danger" size="sm" @click="deleteVariable(v)">remove</b-button>
            </div>
            <div>
              <b-link @click.prevent="openVariable(v)">
                {{ v.title }}
                <strong><span>&#123;</span><span>&#123;</span> {{ v.name }} <span>&#125;</span><span>&#125;</span></strong>
              </b-link>
            </div>
            <small class="text-muted mr-3">
              <i class="cui-file icons"></i>&nbsp;&nbsp;{{ v.type }}
            </small>
            <small class="text-muted mr-3">
              <i class="icon-graph"></i>&nbsp;&nbsp;{{ v.method }}
            </small>
            <small class="text-muted">
              <i class="fa fa-object-group" v-if="v.shared" title="Shared"></i>
              <i class="fa fa-object-ungroup" v-if="!v.shared" title="No Shared"></i>
            </small>
          </b-list-group-item>
          <hr class="transparent mx-3 my-0">
          <b-list-group-item class="list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase small">
            Settings
          </b-list-group-item>
          <b-list-group-item href="#" class="list-group-item-accent-danger list-group-item-divider">
            <div>New UI Project - <strong>deadline</strong>
            </div>
            <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp;&nbsp;10 - 11pm</small>
            <small class="text-muted"><i class="icon-home"></i>&nbsp;&nbsp;creativeLabs HQ</small>
            <div class="avatars-stack mt-2">
              <div class="avatar avatar-xs">
                <img class="img-avatar" src="/img/avatars/2.jpg" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img class="img-avatar" src="/img/avatars/3.jpg" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img class="img-avatar" src="/img/avatars/4.jpg" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img class="img-avatar" src="/img/avatars/5.jpg" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img class="img-avatar" src="/img/avatars/6.jpg" alt="admin@bootstrapmaster.com">
              </div>
            </div>
          </b-list-group-item>
          <b-list-group-item href="#" class="list-group-item-accent-success list-group-item-divider">
            <div><strong>#10 Startups.Garden</strong> Meetup</div>
            <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small>
            <small class="text-muted"><i class="icon-location-pin"></i>&nbsp; Palo Alto, CA</small>
          </b-list-group-item>
          <b-list-group-item href="#" class="list-group-item-accent-primary list-group-item-divider">
            <div><strong>Team meeting</strong></div>
            <small class="text-muted mr-3"><i class="icon-calendar"></i>&nbsp; 4 - 6pm</small>
            <small class="text-muted"><i class="icon-home"></i>&nbsp; creativeLabs HQ</small>
            <div class="avatars-stack mt-2">
              <div class="avatar avatar-xs">
                <img src="/img/avatars/2.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img src="/img/avatars/3.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img src="/img/avatars/4.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img src="/img/avatars/5.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img src="/img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img src="/img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
              </div>
              <div class="avatar avatar-xs">
                <img src="/img/avatars/8.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
              </div>
            </div>
          </b-list-group-item>
        </b-list-group>
      </b-tab>
      <b-tab>
        <template slot="title">
          <i class='icon-settings'></i>
        </template>
        <div class="p-3">
          <b-row>
            <b-col sm="12">
              <b-form-group>
                <label for="name">Name</label>
                <b-form-input type="text" id="name" placeholder="Enter design name" v-model="design.name"></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
          <hr>
          <h6>Settings</h6>
          <div class="text-uppercase mb-1 mt-4">
            <small><b>CPU Usage</b></small>
          </div>
          <b-progress height={} class="progress-xs" variant="info" :value="25"></b-progress>
          <small class="text-muted">348 Processes. 1/4 Cores.</small>
          <div class="text-uppercase mb-1 mt-2">
            <small><b>Memory Usage</b></small>
          </div>
          <b-progress height={} class="progress-xs" variant="warning" :value="70"></b-progress>
          <small class="text-muted">11444GB/16384MB</small>
          <div class="text-uppercase mb-1 mt-2">
            <small><b>SSD 1 Usage</b></small>
          </div>
          <b-progress height={} class="progress-xs" variant="danger" :value="95"></b-progress>
          <small class="text-muted">243GB/256GB</small>
          <div class="text-uppercase mb-1 mt-2">
            <small><b>SSD 2 Usage</b></small>
          </div>
          <b-progress height={} class="progress-xs" variant="success" :value="10"></b-progress>
          <small class="text-muted">25GB/256GB</small>
        </div>
      </b-tab>
      <b-tab>
        <template slot="title">
          <i class='icon-speech'></i>
        </template>
        <div class="p-3">
          <div class="message">
            <div class="py-3 pb-5 mr-3 float-left">
              <div class="avatar">
                <img src="/img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                <b-badge variant="success" class="avatar-status"></b-badge>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted float-right mt-1">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
            <div class="py-3 pb-5 mr-3 float-left">
              <div class="avatar">
                <img src="/img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                <b-badge variant="danger" class="avatar-status"></b-badge>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted float-right mt-1">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
            <div class="py-3 pb-5 mr-3 float-left">
              <div class="avatar">
                <img src="/img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                <b-badge variant="info" class="avatar-status"></b-badge>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted float-right mt-1">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
            <div class="py-3 pb-5 mr-3 float-left">
              <div class="avatar">
                <img src="/img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                <b-badge variant="warning" class="avatar-status"></b-badge>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted float-right mt-1">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
          <hr>
          <div class="message">
            <div class="py-3 pb-5 mr-3 float-left">
              <div class="avatar">
                <img src="/img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                <b-badge variant="dark" class="avatar-status"></b-badge>
              </div>
            </div>
            <div>
              <small class="text-muted">Lukasz Holeczek</small>
              <small class="text-muted float-right mt-1">1:52 PM</small>
            </div>
            <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
            <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
          </div>
        </div>
      </b-tab>
    </b-tabs>

    <b-modal id="designVariable" title="Design variable" v-model="designVariableVisibility" @ok.prevent="saveVariable">
      <b-form class="needs-validation" novalidate @submit.prevent="saveVariable">
        <b-row>
          <b-col sm="12">
            <b-form-group>
              <label>Title</label>
              <b-form-input type="text" v-model="activeDesignVariable.title" required></b-form-input>
              <b-form-invalid-feedback>
                Please provide a required input.
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="12">
            <b-form-group>
              <label>Variable name</label>
              <b-form-input type="text" v-model="activeDesignVariable.name" required></b-form-input>
              <b-form-invalid-feedback>
                Please provide a required input.
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="12">
            <b-form-group>
              <label>Type</label>
              <b-form-select :options="types" v-model="activeDesignVariable.type" @input="setUpMethods" required></b-form-select>
              <b-form-invalid-feedback>
                Please provide a required option.
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="12">
            <b-form-group>
              <label>Method</label>
              <b-form-select :options="methods" v-model="activeDesignVariable.method" required></b-form-select>
              <b-form-invalid-feedback>
                Please provide a required option.
              </b-form-invalid-feedback>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="12">
            <b-form-group>
              <b-form-checkbox id="variableShared" v-model="activeDesignVariable.shared" value="1" unchecked-value="0">
                Shared
              </b-form-checkbox>
            </b-form-group>
          </b-col>
        </b-row>

      </b-form>
    </b-modal>

    <b-modal ref="variableDeleteConfirm" hide-footer title="Delete Variable">
      <div class="d-block text-center">
        <h3>Delete variable from design</h3>
      </div>
      <b-btn class="mt-3" variant="outline-danger" block @click="confirmDelete">YES! Delete it</b-btn>
    </b-modal>
  </div>
</template>

<script>
import api from '@/shared/api'
import { Switch as cSwitch } from '@coreui/vue'
export default {
  name: 'DesignSettings',
  props: {
    fixed: {
      type: Boolean,
      default: false
    },
    design: {
      type: Object
    }
  },
  components: {
    cSwitch
  },
  data: function () {
    return {
      designVariableVisibility: false,
      activeDesignVariable: {},
      types: [],
      methods: []
    }
  },
  methods: {
    openVariable: async function(v) {
      this.activeDesignVariable = v || {}

      this.designVariableVisibility = true;

      if (!this.types.length) {
        let typesData = await api.get('/designs/' + this.design.id + '/variables/types')
        this.types = typesData.data
      }
      if (this.activeDesignVariable.type) {

      } else {
        this.activeDesignVariable.type = this.types[0].value;
      }
      this.setUpMethods();
    },
    setUpMethods() {
      if (this.types.length && this.activeDesignVariable && this.activeDesignVariable.type) {
        let type = null;
        this.types.forEach((e) => {
          if (e.value === this.activeDesignVariable.type) {
            type = e;
          }
        });

        if (type) {
          this.methods = type.methods;
          if (!this.activeDesignVariable.method) {
            this.activeDesignVariable.method = this.methods[0].value
          }
          return;
        }
      }
      this.methods = [];
    },
    updateVariablesList: async function() {
      let variablesData = await api.get('/designs/' + this.design.id + '/variables')
      this.design.variables = variablesData.data
    },
    saveVariable: async function() {
      if (this.activeDesignVariable.title && this.activeDesignVariable.name && this.activeDesignVariable.type && this.activeDesignVariable.method) {
        let data = {
          title: this.activeDesignVariable.title,
          name: this.activeDesignVariable.name,
          type: this.activeDesignVariable.type,
          method: this.activeDesignVariable.method,
          shared: this.activeDesignVariable.shared
        }
        if (this.activeDesignVariable.id) {
          await api.put('/designs/' + this.design.id + '/variables/' + this.activeDesignVariable.id, data)
        } else {
          await api.post('/designs/' + this.design.id + '/variables', data)
        }
        this.designVariableVisibility = false;
        await this.updateVariablesList();
      }
    },
    deleteVariable: async function(variable){
      this.activeDesignVariable = variable;
      this.$refs.variableDeleteConfirm.show()
    },
    confirmDelete: async function() {
      await api.delete('/designs/' + this.design.id + '/variables/' + this.activeDesignVariable.id)
      await this.updateVariablesList();
      this.$refs.variableDeleteConfirm.hide()
    }
  },
}
</script>

<style lang="scss">
  .design-settings {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    .add-variable {
      margin: -0.4rem 0;
    }
    .no-variables {
      padding: 0.5rem;
      text-align: center;
      color: gray;
    }
    .tabs {
      &.fixed {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        .tab-content {
          flex: 1 0 0px;
          overflow: auto;
        }
      }
    }
  }
</style>

<template>
    <div>
        <b-container class="buttons-wrap">
            <b-row md="2">
                <b-col>
                    <div class="add-action">
                        <b-button  v-if="!uploadButtonClose" variant="success" size="sm" @click="showUploadInput">Add</b-button>
                        <b-button  v-if="uploadButtonClose" variant="warning" size="sm" @click="closeUploadInput">Close</b-button>
                        <b-button  variant="success" size="sm" class="ml-2" @click="showAddPicModal">Add by URL</b-button>
                    </div>
                </b-col>

                <b-col>
                    <b-container class="upload-wrap"  v-bind:class="{ active: showUpload }">
                        <b-row>
                            <b-col v-if="file" class="flex-grow-0 mr-2">
                                <div class="save-file" v-bind:class="{ active: file }">
                                    <b-button variant="success" size="sm" @click="saveFile">Save</b-button>
                                </div>
                            </b-col>
                            <b-col class="flex-grow-1">
                                <b-form-file v-model="file" :state="Boolean(file)" placeholder="Choose a file..."></b-form-file>
                            </b-col>
                        </b-row>
                        <!--<div class="upload-wrap"  v-bind:class="{ active: showUpload }">-->
                            <!--<b-form-file v-model="file" :state="Boolean(file)" placeholder="Choose a file..."></b-form-file>-->
                            <!--<div class="save-file">-->
                                <!--<b-button variant="success" size="sm" @click="saveFile">Save</b-button>-->
                            <!--</div>-->
                        <!--</div>-->
                    </b-container>

                </b-col>

            </b-row>
        </b-container>


        <b-container fluid class="p-4 bg-light images-list" v-cloak>
            <b-row>
                <b-col data-img-id="1">
                    <div class="image-wrap">
                        <div class="icons-wrap">
                            <b-badge @click="showDeleteModal" class="bg-red"><i class="icon-trash text-light"></i></b-badge>
                            <b-badge @click="showEditModal" class="bg-yellow"><i class="icon-pencil text-light"></i></b-badge>
                        </div>
                        <b-img thumbnail fluid src="https://picsum.photos/250/250/?image=54" alt="Thumbnail"/>
                    </div>

                    <p class="my-2 text-center">Pic1</p>
                </b-col>
                <b-col data-img-id="2">
                    <div class="image-wrap">
                        <div class="icons-wrap">
                            <b-badge @click="showDeleteModal" class="bg-red"><i class="icon-trash text-light"></i></b-badge>
                            <b-badge @click="showEditModal" class="bg-yellow"><i class="icon-pencil text-light"></i></b-badge>
                        </div>
                        <b-img thumbnail fluid src="https://picsum.photos/250/250/?image=58" alt="Thumbnail" />
                    </div>

                    <p class="my-2 text-center">Pic2</p>
                </b-col>
                <b-col data-img-id="3">
                    <div class="image-wrap">
                        <div class="icons-wrap">
                            <b-badge @click="showDeleteModal" class="bg-red"><i class="icon-trash text-light"></i></b-badge>
                            <b-badge @click="showEditModal" class="bg-yellow"><i class="icon-pencil text-light"></i></b-badge>
                        </div>
                        <b-img thumbnail fluid src="https://picsum.photos/250/250/?image=59" alt="Thumbnail" />
                    </div>
                    <p class="my-2 text-center">Pic3</p>
                </b-col>
            </b-row>
        </b-container>


        <!--modals-->
        <b-modal id="delete" title="Delete image" ref="deleteModalRef" hide-footer class="delete-modal-wrap">
            <p class="my-4 text-center">Are you sure?</p>
            <div class="btn-wrap text-center" >
                <b-btn  variant="outline-success"  inline @click="confirmDelete">Yes</b-btn>
                <b-btn  variant="outline-danger"  inline @click="hideDeleteModal">Cancel</b-btn>
            </div>
        </b-modal>

        <b-modal id="edit" title="Edit image" ref="editModalRef" hide-footer class="edit-modal-wrap">
            <b-form class="needs-validation" novalidate @submit.prevent="saveEditedPic()">
                <b-row>
                    <b-col sm="12">
                        <b-form-group>
                            <label>Name</label>
                            <b-form-input type="text"  required></b-form-input>
                            <b-form-invalid-feedback>
                                Please provide a required input.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col sm="12">
                        <b-form-group>
                            <label>Filename</label>
                            <b-form-input type="text"  required></b-form-input>
                            <b-form-invalid-feedback>
                                Please provide a required input.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>
            <div class="btn-wrap text-center">
                <b-btn  variant="outline-success" class="mr-1"  inline @click="saveEditedPic">Save</b-btn>
                <b-btn  variant="outline-danger" class="ml-1"  inline @click="hideEditModal">Cancel</b-btn>
            </div>
        </b-modal>

        <b-modal id="addByUrl" title="Upload image" ref="addPicModalRef" hide-footer class="add-pic-modal-wrap">
            <b-form class="needs-validation" novalidate @submit.prevent="savePicByUrl()">
                <b-row>
                    <b-col sm="12">
                        <b-form-group>
                            <label>Url</label>
                            <b-form-input type="text" required></b-form-input>
                            <b-form-invalid-feedback>
                                Please provide a required input.
                            </b-form-invalid-feedback>
                        </b-form-group>
                    </b-col>
                </b-row>
            </b-form>
            <div class="btn-wrap text-center">
                <b-btn  variant="outline-success" class="mr-1"  inline @click="savePicByUrl">Save</b-btn>
                <b-btn  variant="outline-danger" class="ml-1"  inline @click="hideAddPicModal">Cancel</b-btn>
            </div>
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
        name: 'SiteImages',
        inherit: true,
        components,
        props: ['site', 'menuId'],
        data: function () {
            return {
                images: [],
                image: {},
                file: null,
                showUpload: false,
                uploadButtonClose: false,
                picBadge: false,
                deleteModal: false

            }
        },

        methods: {
            showUploadInput(){
                this.showUpload = true
                this.uploadButtonClose = true
            },
            closeUploadInput(){
                this.showUpload = false
                this.uploadButtonClose = false
                this.file = null
            },
            saveFile(){

                let name = this.file.name.split('.')[0],
                    filename = this.file.name.split('.')[0],
                    width = 250,
                    height = 250,
                    ext = this.file.type.split('/')[1],
                    size = this.file.size,
                    site_id = this.site.id,
                    request

                console.log({name,filename,width,height,ext,size, site_id})

                request = api.post('/sites/' + this.site.id + '/images', {
                    name: name,
                    filename: filename,
                    width: width,
                    height: height,
                    ext: ext,
                    size: size
                })
            },
            showDeleteModal(){
                this.$refs.deleteModalRef.show()
            },
            showEditModal(){
                this.$refs.editModalRef.show()
            },
            showAddPicModal(){
                this.$refs.addPicModalRef.show()
            },
            hideDeleteModal(){
                this.$refs.deleteModalRef.hide()
            },
            hideEditModal(){
                this.$refs.editModalRef.hide()
            },
            hideAddPicModal(){
                this.$refs.addPicModalRef.hide()
            },
            confirmDelete: async function(){

                // let imageId = someId

                // await api.delete('/sites/' + this.site.id + '/images',{
                //     imageId: imageId
                // })
                this.$refs.deleteModalRef.hide()
            },
            saveEditedPic(){
              //
            },
            savePicByUrl(){

            }
        },
    }
</script>

<style scoped lang="scss">
    .buttons-wrap{
        margin-bottom: 20px;
        .col{
            display: flex;
            align-items: center;
            padding: 0;
        }
        .add-action{
            button{
                transition: 0.3s;
            }
        }
        .upload-wrap{
            transition: all 0.3s;
            opacity: 0;
            flex: 1;
            .save-file{
                display: none;
                opacity: 0;
                transition: all 0.3s;
                &.active{
                    display: block;
                    opacity: 1;
                }
            }
            &.active{
                opacity: 1;
            }
        }
    }

    .images-list{
        .row{
            .col{
                position: relative;
                flex: 0 1 120px;
                padding: 0;
                margin: 0 10px;
                .image-wrap{
                    width: 120px;
                    height: 120px;
                }
                img{
                    max-width: 100%;
                }
                .icons-wrap{
                    display: none;
                    opacity:0;
                    transition: all 0.3s;
                    position: absolute;
                    top: 10px;
                    left: 10px;
                    cursor: pointer;
                    .badge{
                        margin: 2px 0;
                    }
                    i{
                        transition: all 0.3s;
                        &:before{
                            transition: all 0.3s;
                        }
                    }
                }
                &:hover{
                    .icons-wrap{
                        display: flex;
                        flex-flow:wrap column;
                        opacity:1;
                        transition: 0.3s;
                    }
                }
            }
        }
    }
    [v-cloak] {
        display: none;
    }

    .delete-modal-wrap{
        .btn-wrap{
            display: flex;
            justify-content: center;
            flex-flow: wrap row;
            button{
                flex: 0 1 70px;
                margin: 0 5px;
            }
        }
    }

</style>
<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.code}">
                            <label>Código</label>
                            <el-input v-model="form.code" :maxlength="4" :disabled="true"></el-input>
                            <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.identity_document_type_id}">
                            <label class="control-label">Tipo Doc<span class="text-danger">*</span></label>
                            <el-select v-model="form.identity_document_type_id" filterable popper-class="el-select-identity_document_type" dusk="identity_document_type_id">
                                <el-option v-for="option in identity_document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.identity_document_type_id" v-text="errors.identity_document_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.number}">
                            <label>Número</label>
                            <div v-if="api_service_token != false">
                                <x-input-service :identity_document_type_id="form.identity_document_type_id" v-model="form.number" @search="searchNumber"></x-input-service>
                            </div>
                            <div v-else>
                                <el-input v-model="form.number" :maxlength="maxLength" dusk="number">
                                    <template v-if="form.identity_document_type_id === '6' || form.identity_document_type_id === '1'">
                                        <el-button type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="searchCustomer">
                                            <template v-if="form.identity_document_type_id === '6'">
                                                SUNAT
                                            </template>
                                            <template v-if="form.identity_document_type_id === '1'">
                                                RENIEC
                                            </template>
                                        </el-button>
                                    </template>
                                </el-input>
                            </div>
                            <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label>Descripción<span class="text-danger">*</span></label>
                            <el-input v-model="form.description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.address}">
                            <label>Dirección Fiscal<span class="text-danger">*</span></label>
                            <el-input v-model="form.address"></el-input>
                            <small class="form-control-feedback" v-if="errors.address" v-text="errors.address[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.department_id}">
                            <label>Departamento<span class="text-danger">*</span></label>
                            <el-select v-model="form.department_id" filterable @change="filterProvince">
                                <el-option v-for="option in all_departments" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.department_id" v-text="errors.department_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.province_id}">
                            <label>Provincia<span class="text-danger">*</span></label>
                            <el-select v-model="form.province_id" filterable @change="filterDistrict">
                                <el-option v-for="option in provinces" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.province_id" v-text="errors.province_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.province_id}">
                            <label>Distrito<span class="text-danger">*</span></label>
                            <el-select v-model="form.district_id" filterable>
                                <el-option v-for="option in districts" :key="option.id" :value="option.id" :label="option.description"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.district_id" v-text="errors.district_id[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.telephone}">
                            <label>Teléfono</label>
                            <el-input v-model="form.telephone" type="tel"></el-input>
                            <small class="form-control-feedback" v-if="errors.telephone" v-text="errors.telephone[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.email}">
                            <label>Correo de contacto</label>
                            <el-input v-model="form.email" type="email"></el-input>
                            <small class="form-control-feedback" v-if="errors.email" v-text="errors.email[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.web_address}">
                            <label>Dirección web</label>
                            <el-input v-model="form.web_address"></el-input>
                            <small class="form-control-feedback" v-if="errors.web_address" v-text="errors.web_address[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 form-group mt-4 text-center">
                        <img v-if="preview" :src="preview" alt="Vista previa" class="img-fluid img-thumbnail mb-2" width="300px">
                        <input type="file" ref="inputFile" class="hidden" @change="onSelectImage" accept="image/png, image/jpeg, image/jpg">
                        <el-button class="btn-block text-primary mb-1" @click="onOpenFileLogo"><i class="fa fa-camera"></i> Seleecionar Logo</el-button>
                        <span class="text-muted text-small">Se recomienda resoluciones 700x300 </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mt-4" v-if="form.state">
                        <div class="form-group" >
                            <label class="control-label">Estado del Contribuyente</label>
                            <template v-if="form.state == 'ACTIVO'">
                                <el-alert   :title="`${form.state}`"  type="success"   show-icon :closable="false"></el-alert>
                            </template>
                            <template v-else>
                                <el-alert   :title="`${form.state}`"  type="error"   show-icon :closable="false"></el-alert>
                            </template>
                        </div>

                    </div>
                    <div class="col-md-6 mt-4" v-if="form.condition">
                        <div class="form-group" >
                            <label class="control-label">Condición del Contribuyente</label>
                            <template v-if="form.condition == 'HABIDO'">
                                <el-alert   :title="`${form.condition}`"  type="success"   show-icon :closable="false"></el-alert>
                            </template>
                            <template v-else>
                                <el-alert   :title="`${form.condition}`"  type="error"   show-icon :closable="false"></el-alert>
                            </template>
                        </div>

                    </div>
                </div>
            </div>
            <!--Input Country | Trade Address-->
            <el-input v-model="form.country_id" :value="form.country_id" type="hidden"></el-input>
            <el-input v-model="form.trade_address" type="hidden"></el-input>
            <div class="form-actions text-right">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    import {serviceNumber} from '../../../mixins/functions'

    export default {
        mixins: [serviceNumber],
        props: ['showDialog', 'recordId', 'external', 'document_type_id','input_person'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'establishments',
                errors: {},
                form: {},
                countries: [],
                all_departments: [],
                all_provinces: [],
                all_districts: [],
                provinces: [],
                districts: [],
                file: null,
                preview: null,
                api_service_token:false,
                loading_search: false,
                identity_document_types: []
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {
                    this.countries = response.data.countries
                    this.all_departments = response.data.departments
                    this.all_provinces = response.data.provinces
                    this.all_districts = response.data.districts
                    this.api_service_token = response.data.api_service_token
                    this.identity_document_types = response.data.identity_document_types;
                })
        },
        computed: {
            maxLength: function () {
                if (this.form.identity_document_type_id === '6') {
                    return 11
                }
                if (this.form.identity_document_type_id === '1') {
                    return 8
                }
            }
        },
        methods: {
            onSelectImage(event) {
                const files = event.target.files;
                if (files) {
                    const file = files[0];
                    this.preview = URL.createObjectURL(file);
                    this.file = file;
                } else {
                    this.preview = null;
                    this.file = null;
                }
            },
            onOpenFileLogo() {
                this.$refs.inputFile.click();
            },
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    description: null,
                    country_id: 'PE',
                    department_id: null,
                    province_id: null,
                    district_id: null,
                    address: null,
                    telephone: null,
                    email: null,
                    code: this.code,
                    number: '',
                    trade_address: null,
                    web_address: null,
                    condition: null,
                    state: null,
                    aditional_information: null,
                    identity_document_type_id: '6',
                }
                this.file = null;
                this.preview = null;
            },
            async create() {
                this.titleDialog = (this.recordId) ? 'Editar Establecimiento' : 'Nuevo Establecimiento'
                if (this.recordId) {
                    await this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            if (response.data !== '') {
                                this.form = response.data.data;
                                this.preview = this.form.logo;
                                delete this.form.logo;
                                this.filterProvinces()
                                this.filterDistricts()
                            }
                        })
                }
                else{
                    this.getCode()
                }
            },
            validateDigits(){
                const pattern_number = new RegExp('^[0-9]+$', 'i');

                if (this.form.identity_document_type_id === '6') {
                    if(this.form.number.length !== 11){
                        return {
                            success: false,
                            message: `El campo número debe tener 11 dígitos.`
                        }
                    }
                    if(!pattern_number.test(this.form.number)){
                        return {
                            success: false,
                            message: `El campo número debe contener solo números`
                        }
                    }
                }

                if (this.form.identity_document_type_id === '1') {
                    if(this.form.number.length !== 8){
                        return {
                            success: false,
                            message: `El campo número debe tener 8 dígitos.`
                        }
                    }
                    if(!pattern_number.test(this.form.number)){
                        return {
                            success: false,
                            message: `El campo número debe contener solo números`
                        }
                    }
                }

                if(['4', '7', '0'].includes(this.form.identity_document_type_id)){

                    const pattern = new RegExp('^[A-Z0-9\-]+$', 'i');

                    if(!pattern.test(this.form.number)){
                        return {
                            success: false,
                            message: `El campo número no cumple con el formato establecido`
                        }
                    }
                }

                return {
                    success: true
                }
            },
            submit() {
                const data = new FormData();
                for (var key in this.form) {
                    const value = this.form[key];
                    if (value) {
                        data.append(key, value);
                    }
                }
                if (this.file) {
                    data.append('file', this.file);
                }

                this.loading_submit = true

                this.$http.post(`/${this.resource}`, data)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            filterProvince() {
                this.form.province_id = null
                this.form.district_id = null
                this.filterProvinces()
            },
            filterProvinces() {
                this.provinces = this.all_provinces.filter(f => {
                    return f.department_id === this.form.department_id
                })
            },
            filterDistrict() {
                this.form.district_id = null
                this.filterDistricts()
            },
            filterDistricts() {
                this.districts = this.all_districts.filter(f => {
                    return f.province_id === this.form.province_id
                })
            },
            getCode() {
                this.$http.get(`/${this.resource}/code`)
                    .then(response => {
                        this.form.code = response.data
                    })
            },
            searchCustomer() {
                this.searchServiceNumberByType()
            },
            searchNumber(data) {
                this.form.description = (this.form.identity_document_type_id === '1') ? data.nombre_completo : data.nombre_o_razon_social;
                this.form.address = data.direccion;
                this.form.department_id = (data.ubigeo) ? (data.ubigeo[0] != '-' ? data.ubigeo[0] : null) : null;
                this.form.province_id = (data.ubigeo) ? (data.ubigeo[1] != '-' ? data.ubigeo[1] : null) : null;
                this.form.district_id = (data.ubigeo) ? (data.ubigeo[2] != '-' ? data.ubigeo[2] : null) : null;
                this.form.condition = data.condicion;
                this.form.state = data.estado;
                this.filterProvinces()
                this.filterDistricts()
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>

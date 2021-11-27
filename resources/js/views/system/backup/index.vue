<template>
    <div class="row">
        <div class="card col-md-8">
            <div class="card-header border-primary">
                <h3 class="text-info">Generar Backups</h3>
                <el-button @click.prevent="start()" :loading="loading_submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-database" aria-hidden="true"></i> Iniciar Proceso
                </el-button>
            </div>
            <div class="card-body">
                <div v-if="db.status == 'success'" class="pt-2">
                    <el-alert type="success" show-icon :closable="true">Base de datos
                        <span v-if="db.error == ''" class="text-success"></span>
                        <span>Log: {{db.content}}</span>
                    </el-alert>
                    <span v-if="db.error ==! ''" class="text-danger">{{db.error}}</span>
                </div>
                <div v-if="files.status == 'success'" class="pt-2">
                    <el-alert type="success" show-icon :closable="true">Archivos
                        <span v-if="files.error == ''" class="text-success"></span>
                        <span>Log: {{files.content}}</span>
                    </el-alert>
                    <span class="text-danger">{{files.error}}</span><br>
                </div>
                <hr>
                <div>
                    <p v-if="newLastZip !== ''">Ultimo Backup generado: <strong>{{newLastZip.name}} | </strong><span class="text-info"> {{newLastZipDate}}</span></p>
                    <el-button @click.prevent="clickDownload()" class="btn btn-success btn-sm"><i class="fa fa-download"></i> Descargar</el-button>
                    <hr>
                    <p class="mb-2">Para restaurar una base de datos debe ejecutar los siguientes comandos.</p>
                    <code>mysql -u [user] -p [database_name] < [filename].sql</code>
                    <br>
                    <hr>
                    <p class="mb-2">Para restaurar los archivos descargados debe copiar todas carpetas dentro de la carpeta del cliente.</p>
                    <code>cp [path_del_zip]/signed storage/app/tenancy/tenants/tenancy_[subdominio del cliente]</code> <br>
                    <code>cp [path_del_zip]/unsigned storage/app/tenancy/tenants/tenancy_[subdominio del cliente]</code> <br>
                    <code>cp [path_del_zip]/cdr storage/app/tenancy/tenants/tenancy_[subdominio del cliente]</code> <br>
                    <code>cp [path_del_zip]/pdf storage/app/tenancy/tenants/tenancy_[subdominio del cliente]</code> <br>
                    <p>Repetir para todas las carpetas que estan dentro del .zip</p>
                </div>
            </div>
        </div>
        <div class="card col-md-4 mt-0">
            <div class="card-header">
                Enviar por FTP último backup generado
            </div>
            <div class="card-body">
                <p v-if="newLastZip !== ''">Ultimo Backup generado: {{newLastZip.name}}</p>
                <small class="text-muted">Por seguridad sus datos FTP no son guardados</small>
                <form v-if="newLastZip !== ''">
                    <div class="form-group" :class="{'has-danger': errors.host}">
                        <label class="control-label">Host/IP</label>
                        <el-input v-model="form.host"></el-input>
                    </div>
                    <div class="form-group" :class="{'has-danger': errors.port}">
                        <label class="control-label">Puerto</label>
                        <el-input v-model="form.port"></el-input>
                    </div>
                    <div class="form-group" :class="{'has-danger': errors.username}">
                        <label class="control-label">Usuario</label>
                        <el-input v-model="form.username"></el-input>
                    </div>
                    <div class="form-group" :class="{'has-danger': errors.password}">
                        <label class="control-label">Contraseña</label>
                        <el-input v-model="form.password"></el-input>
                    </div>
                    <div v-if="newLastZip !== ''" class="form-group">
                        <el-button @click.prevent="uploadFtp()" :loading="loading_upload">Enviar</el-button>

                        <el-button @click.prevent="clickDownload()" >Descargar</el-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['storageSize','discUsed', 'lastZip'],
        data() {
            return {
                newLastZipDate: '',
                headers: null,
                resource: 'backup',
                errors: {},
                form: {},
                loading_submit: false,
                loading_upload: false,
                db: {
                    error: '',
                    content: '',
                    status: '',
                },
                files: {
                    error: '',
                    content: '',
                    status: '',
                },
                newLastZip: '',
                message: 'Error',
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            onGenerateNameLastFile() {
                if (this.newLastZip) {
                    if (this.newLastZip.date) {
                        this.newLastZipDate = `Creado el ${this.newLastZip.date}`;
                    }
                }
            },
            clickDownload() {
                window.open(`/${this.resource}/download/${this.newLastZip.name}`, '_blank');
            },
            initForm(){
                this.form = {
                    host: null,
                    port: null,
                    username: null,
                    password: null,
                }
                this.newLastZip = this.lastZip;
                this.onGenerateNameLastFile();
            },
            async start() {
                this.initContent()
                this.loading_submit = true
                this.backupDb()
            },
            initContent() {
                this.db.error = ''
                this.db.content = ''
                this.db.status = false
                this.files.error = ''
                this.files.content = ''
                this.files.status = false
            },
            backupDb() {
                this.$http.get(`/${this.resource}/db`)
                .then(response => {
                    if (response.data !== '') {
                        this.db.content = response.data
                        if (response.status === 200) {
                            this.db.status = 'success'
                        }
                        this.backupFiles()
                    }
                }).catch(error => {
                    const status = error.response.status;
                    if (status === 422) {
                        this.errors = error.response.data;
                    } else if (status !== 200) {
                        this.db.error = error.response.data.message
                        this.db.status = 'false'
                    }
                }).finally(() => this.loading_submit = false);
            },
            backupFiles() {
                this.$http.get(`/${this.resource}/files`)
                    .then(response => {
                        if (response.data !== '') {
                            this.files.content = response.data
                            if (response.status === 200) {
                                this.files.status = 'success'
                                this.mostRecent()
                            }
                            this.loading_submit = false
                        }
                    }).catch(error => {
                    const status = error.response.status;
                    if (status === 422) {
                        this.errors = error.response.data;
                    } else if (status !== 200) {
                        this.db.error = error.response.data.message
                        this.db.status = 'false'
                    }
                })

            },
            mostRecent(){
            this.$http.get(`/${this.resource}/last-backup`)
                .then(response => {
                    if (response.data !== '') {
                        this.newLastZip = response.data
                        this.loading_submit = false
                        this.onGenerateNameLastFile();
                    }
                }).catch(error => {
                    if (error.response.status !== 200) {
                        this.files.error = error.response.data.message
                    } else {
                        console.log(error)
                    }
                })
            },
            uploadFtp() {
                this.loading_upload = true
                this.sendFtp()
            },
            sendFtp() {
                this.$http.post(`${this.resource}/upload`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.loading_upload = false
                            this.initForm()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        }else if(error.response.status === 500){
                            this.$message.error(error.response.data.message);
                        }
                         else {
                            console.log(error.response)
                        }
                    })
                    .then(()=>{
                        this.loading_upload = false
                    })


            }
        }
    }
</script>

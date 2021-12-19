<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
        </div>

        <div class="card" v-loading="loading">
            <div class="card-header bg-info">
                <h3 class="my-0">{{ title }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                        <div class="col-md-4">
                            <label>Periodo</label>
                            <el-date-picker v-model="form.month" type="month"
                                            value-format="yyyy-MM" format="MM/yyyy" :clearable="false"></el-date-picker>
                        </div>
                    <div class="col-md-3">
                        <label>Exportar a</label>
                        <el-select v-model="form.type">
                            <el-option key="concar" value="concar" label="CONCAR"></el-option>
                            <el-option key="siscont" value="siscont" label="SISCONT"></el-option>
                            <el-option key="foxcont" value="foxcont" label="FOXCONT"></el-option>
                            <el-option key="contasis" value="contasis" label="CONTASIS"></el-option>
                            <el-option key="adsoft" value="adsoft" label="ADSOFT"></el-option>
                            <el-option key="sumerius" value="sumerius" label="SUMERIUS"></el-option>
                        </el-select>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button type="primary" :loading="loading_submit" @click.prevent="clickDownload">
                    <template v-if="loading_submit">
                        Generando...
                    </template>
                    <template v-else>
                        Generar
                    </template>
                </el-button>
            </div>
        </div>
    </div>
</template>

<script>
    import queryString from 'query-string'

    export default {
        data() {
            return {
                loading: false,
                loading_submit: false,
                title: null,
                resource: 'account',
                error: {},
                form: {},

            }
        },
        async created() {
            this.initForm();
            this.title = 'Exportar';
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    month: moment().format('YYYY-MM'),
                    type: 'concar'
                }
            },
            clickDownload() {
                this.loading_submit = true;
                let query = queryString.stringify({
                    ...this.form
                });
                window.open(`/${this.resource}/download?${query}`, '_blank');
                this.loading_submit = false;
            }
        }
    }
</script>

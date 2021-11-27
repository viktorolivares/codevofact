<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Establecimientos</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" v-if="typeUser != 'integrator'" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de establecimientos</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Descripción</th>
                        <th>RUC</th>
                        <th class="text-right">Código</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.description }}</td>
                        <td>{{ row.number }}</td>
                        <td class="text-right">{{ row.code }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" v-if="typeUser != 'integrator'" @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                 </data-table>
            </div>
            <establishments-form :showDialog.sync="showDialog" :recordId="recordId"></establishments-form>
            <establishment-series :showDialog.sync="showDialogSeries" :establishmentId="recordId"></establishment-series>
        </div>
    </div>
</template>

<script>

    import EstablishmentsForm from './form.vue'
    import {deletable} from '../../../mixins/deletable'
    import DataTable from '../../../components/DataTable.vue'
    import EstablishmentSeries from './partials/series.vue'

    export default {
        props:['typeUser'],
        mixins: [deletable],
        components: {EstablishmentsForm, EstablishmentSeries, DataTable},
        data() {
            return {
                showDialog: false,
                resource: 'establishments',
                recordId: null,
                records: [],
                showDialogSeries: false,
                establishment: null,
                columns: {},
            }
        },
        created() {
            this.$eventHub.$on('reloadData')
        },
        methods: {
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            },
            clickSeries(recordId = null) {
                this.recordId = recordId
                this.showDialogSeries = true
            },
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>

<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <!-- <div v-if="typeUser == 'admin'" class="right-wrapper pull-right">
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate('input')"><i class="fa fa-plus-circle"></i> Ingreso</button>
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickOutput()"><i class="fa fa-minus-circle"></i> Salida</button>
            </div> -->
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Producto</th>
                        <th>Almacén</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.item_fulldescription }}</td>
                        <td>{{ row.warehouse_description }}</td>
                        <td class="text-right">{{ row.stock }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                @click.prevent="clickMove(row.id)">Trasladar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-primary"
                                @click.prevent="clickAdd('input', row.id)">Ingreso</button>
                            <button v-if="typeUser == 'admin'" type="button"
                                class="btn waves-effect waves-light btn-xs btn-warning"
                                @click.prevent="clickRemove(row.id)">Remover</button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <!-- <inventories-form :showDialog.sync="showDialog" :type="typeTransaction"></inventories-form> -->
            <!-- <inventories-form-output :showDialog.sync="showDialogOutput"></inventories-form-output> -->

            <inventories-form :showDialog.sync="showDialog" :type="typeTransaction" :recordId="recordId"></inventories-form>
            <inventories-move :showDialog.sync="showDialogMove" :recordId="recordId"></inventories-move>
            <inventories-remove :showDialog.sync="showDialogRemove" :recordId="recordId"></inventories-remove>
        </div>
    </div>
</template>

<script>

import InventoriesForm from './form.vue'
import InventoriesFormOutput from './form_output.vue'

import InventoriesMove from './move.vue'
import InventoriesRemove from './remove.vue'
import DataTable from '../../../../../../resources/js/components/DataTable.vue'

export default {
    props: ['type', 'typeUser'],
    components: { DataTable, InventoriesForm, InventoriesMove, InventoriesRemove, InventoriesFormOutput },
    data() {
        return {
            title: null,
            showDialog: false,
            showDialogMove: false,
            showDialogRemove: false,
            showDialogOutput: false,
            resource: 'inventory',
            recordId: null,
            typeTransaction: null,
        }
    },
    created() {
        this.title = 'Inventario'
    },
    methods: {
        clickMove(recordId) {
            this.recordId = recordId
            this.showDialogMove = true
        },
        clickAdd(type, recordId) {
            this.recordId = recordId
            this.showDialog = true
            this.typeTransaction = type
        },
        // clickCreate(type) {
        //     this.recordId = null
        //     this.typeTransaction = type
        //     this.showDialog = true
        // },
        clickRemove(recordId) {
            this.recordId = recordId
            this.showDialogRemove = true
        },
        clickOutput() {
            this.recordId = null
            this.showDialogOutput = true

        }

    }
}
</script>

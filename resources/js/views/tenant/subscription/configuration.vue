<template>
  <div class="card col-md-5">
    <div class="card-header bg-info">
      <h3 class="my-0">Tipo de Plan de la Empresa</h3>
    </div>
    <div class="card-body">
      <form autocomplete="off" >
        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Plan</label>
                <el-select  @change="alertPlan" v-model="form.plan_id">
                  <el-option
                    v-for="(option, index) in plans"
                    :key="index"
                    :value="option.id"
                    :label="option.name"
                  ></el-option>
                </el-select>
              </div>
            </div>
          </div>
        </div>
        <div class="form-actions text-right pt-2">
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading_submit: false,
      resource: "subscription",
      form: {},
      configuration: {},
      plans:[]

    };
  },
  async created() {
    await this.initForm();
    await this.$http.get(`/${this.resource}/tables`).then(response => {
      this.plans = response.data.plans;
      this.configuration = response.data.configuration
      if(this.configuration.plan)
      {
          this.form.plan_id = this.configuration.plan.id
      }

    });

  },
  methods: {
    alertPlan()
    {

       this.$confirm('Para cambiar su Plan, Comuniquese con el Administrador', 'Warning', {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning',
          showCancelButton: false
        }).then(() => {

        }).catch(() => {

        });

    },
    initForm() {
      this.errors = {};
      this.form = {
        plan_id: null,
      };
    },

  }
};
</script>

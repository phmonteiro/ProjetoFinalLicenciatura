<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <edit-support
      :support="currentSupport"
      @save-support="saveSupport()"
      @cancel-edit="cancelEdit()"
    ></edit-support>

    <b-container>
      <b-row>
        <b-col>
          <h2>Lista de apoios</h2>
          <b-table
            striped
            hover
            v-if="supports!=null"
            :items="supports"
            :fields="fields"
            :per-page="perPage"
            :current-page="currentPage"
          >
            <template slot="actions" slot-scope="row">
              <button
                class="btn btn-secondary"
                v-on:click.prevent="editSupport(row.item)"
                v-if="row.item.number != user.number"
              >Editar</button>
              <button
                class="btn btn-danger"
                v-on:click.prevent="deleteSupport(row.item)"
                v-if="row.item.number != user.number"
              >Apagar</button>
            </template>
          </b-table>

          <b-pagination
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            aria-controls="my-table"
            v-if="supports"
          ></b-pagination>

          <b-input-group v-if="supports" prepend="Novo Apoio" class="mt-3">
            <b-form-input v-model="newSupport.text"></b-form-input>
            <b-input-group-append>
              <b-button variant="outline-success" v-on:click.prevent="createSupport()">Criar</b-button>
            </b-input-group-append>
          </b-input-group>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      rows: "",
      currentPage: 1,
      perPage: 5,
      supports: null,
      fields: [
        {
          key: "text",
          label: "Apoio",
          sortable: true
        },

        {
          key: "actions",
          label: "Opcoes"
        }
      ],
      currentSupport: null,
      newSupport: {
        text: ""
      }
    };
  },
  methods: {
    getSupports() {
      axios
        .get("api/getSupports")
        .then(response => {
          this.supports = response.data;
          this.rows = this.supports.length;
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
        });
    },

    editSupport(row) {
      this.currentSupport = Object.assign({}, row);
    },
    cancelEdit: function() {
      this.currentSupport = null;
    },
    createSupport() {
      axios
        .post("api/createSupport/", this.newSupport)
        .then(response => {
          this.getSupports();
          this.$toasted.success("Apoio criado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error("Erro ao criar apoio.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        });
    },
    deleteSupport(row) {
      axios
        .delete("api/deleteSupport/" + row.value)
        .then(response => {
          this.getSupports();
          this.$toasted.success("Apoio apagado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao apagar apoio. Certifique-se que ninguem tem o apoio atribuido.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    saveSupport() {
      axios
        .patch(
          "api/editSupport/" + this.currentSupport.value,
          this.currentSupport
        )
        .then(response => {
          this.getSupports();
          this.currentSupport = null;
          this.$toasted.success("Apoio editado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao editar apoio. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    }
  },
  created() {
    this.getSupports();
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

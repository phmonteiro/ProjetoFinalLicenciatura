<template>
  <div>
      <br>
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

      <b-container>
          <div class="row">
              <div class="col-sm-6 text-center">
                  <b-button
                      v-on:click="showCategories()"
                      style="height:50px;width:400px"
                      class="btn-xl"
                  >
                      Mostrar Categorias
                  </b-button>
              </div>
              <div class="col-sm-6 text-center">
                  <b-button
                      v-on:click="showSupports()"
                      style="height:50px;width:400px"
                      class="btn-xl"
                  >
                      Mostrar Apoios
                  </b-button>
              </div>
          </div>
      </b-container>
      <br>
    <b-container>
      <b-row>
        <b-col>
          <h2 v-if="boolShowCategories">Lista de Categorias de Apoios</h2>
          <h2 v-else>Lista de Apoios</h2>
          <b-table
              v-if="boolShowCategories"
            striped
            hover
            :items="items"
            :fields="categoryFields"
            :per-page="perPage"
            :current-page="currentPage"
          >
            <template v-slot:cell(actions)="row">
              <button
                class="btn btn-secondary"
                v-on:click.prevent="editSupportCategory(row.item)"
                v-if="row.item.number != user.number"
              >Editar</button>
              <button
                class="btn btn-danger"
                v-on:click.prevent="deleteSupportCategory(row.item)"
                v-if="row.item.number != user.number"
              >Apagar</button>
            </template>
          </b-table>

            <b-table
                v-if="!boolShowCategories"
                striped
                hover
                :items="items"
                :fields="supportFields"
                :per-page="perPage"
                :current-page="currentPage"
            >
                <template v-slot:cell(id_category)="row">

                </template>
                <template v-slot:cell(actions)="row">
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

          <b-input-group v-if="boolShowCategories" prepend="Nova Categoria" class="mt-3">
            <b-form-input v-model="newSupport.text"></b-form-input>
            <b-input-group-append>
              <b-button variant="outline-success" v-on:click.prevent="createSupportCategory()">Criar</b-button>
            </b-input-group-append>
          </b-input-group>

            <div  v-else>
            <br>
        <div v-if="!editingSupport">
            <h4>Criar Apoio</h4>
            <ValidationObserver v-slot="{ handleSubmit }">
                <label for>Categoria de Apoio</label>
                <ValidationProvider name="categoriaApoio" rules="required" v-slot="{ errors }">
                    <b-form-select v-model="dataNewSupport.id_category" >
                        <b-form-select-option :value="cat.id" v-for="cat in supportCategories">
                            {{cat.name}}
                        </b-form-select-option>
                        <template v-slot:first>
                            <b-form-select-option :value="null" disabled>-- Selecione uma categoria --</b-form-select-option>
                        </template>
                    </b-form-select>
                    <code>{{ errors[0] }}</code>
                </ValidationProvider>
                <br><br>
                <label for>Nome do Apoio</label>
                <ValidationProvider name="nomeDoApoio" rules="required" v-slot="{ errors }">
                    <b-form-input placeholder="Nome do apoio" v-model="dataNewSupport.name"  ></b-form-input>
                    <code>{{ errors[0] }}</code>
                </ValidationProvider>
                <br>
                    <b-button variant="outline-success" v-on:click.prevent="handleSubmit(createSupport)">Criar</b-button>
            </ValidationObserver>

        </div>
        <div v-else>
            <h4>Editar Apoio</h4>
            <br>
            <label for>Categoria de Apoio</label>
            <ValidationObserver v-slot="{ handleSubmit }">
                <ValidationProvider name="categoriaApoio" rules="required" v-slot="{ errors }">
                    <b-form-select v-model="dataNewSupport.id_category" >
                        <b-form-select-option :value="cat.id" v-for="cat in supportCategories">
                            {{cat.name}}
                        </b-form-select-option>
                        <template v-slot:first>
                            <b-form-select-option :value="null" disabled>-- Selecione uma categoria --</b-form-select-option>
                        </template>
                    </b-form-select>
                    <code>{{ errors[0] }}</code>
                </ValidationProvider>
                <br><br>
                <label for>Nome do Apoio</label>
                <ValidationProvider name="nomeDoApoio" rules="required" v-slot="{ errors }">
                    <b-form-input placeholder="Nome do apoio" v-model="dataNewSupport.name"  ></b-form-input>
                    <code>{{ errors[0] }}</code>
                </ValidationProvider>
                <br>

                    <b-button type="submit" variant="outline-success" v-on:click.prevent="handleSubmit(saveSupport)">Atualizar</b-button>
                    <b-button v-on:click.prevent="cancelEditingSupport()">Cancelar</b-button>
            </ValidationObserver>
        </div>
            </div>

        </b-col>
      </b-row>
    </b-container>
      <edit-support
          v-if="boolShowCategories"
          :support="currentSupport"
          @save-support="saveSupportCategory()"
          @cancel-edit="cancelEdit()"
      ></edit-support>

  </div>
</template>

<script>
export default {
  data() {
    return {
      items:null,
      loading: true,
      rows: "",
      currentPage: 1,
      perPage: 10,
      supports: null,
      supportCategories: null,
      boolShowCategories:true,
      editingSupport:false,
      dataNewSupport:{
            id_category:null,
            name:""
      },
      categoryFields: [
        {
          key: "name",
          label: "Categoria",
          sortable: true
        },

        {
          key: "actions",
          label: "Opções"
        }
      ],
        supportFields: [
            {
                key: "categoryName",
                label: "Categoria",
                sortable: true
            },

            {
                key: "supportName",
                label: "Apoio"
            },
            {
                key: "actions",
                label: "Opções"
            }
        ],
      currentSupport: null,
      newSupport: {
        text: ""
      }
    };
  },
  methods: {
      cancelEditingSupport(){
          this.editingSupport=false;
          this.dataNewSupport.name="";
          this.dataNewSupport.id_category=null;
      },
    createSupport() {
            axios.post('api/createSupport',this.dataNewSupport)
        .then(response=>{
            this.$toasted.success("Apoio criado com sucesso.", {
                duration: 4000,
                position: "top-center",
                theme: "bubble"
            });

            this.supports.push(response.data[0]);
            this.dataNewSupport.name="";
            this.dataNewSupport.id_category=null;

        }).catch(error=>{
            console.log(error);
                this.$toasted.error(
                    "Erro ao criar apoio. Certifique-se que inseriu os dados corretamente.",
                    {
                        duration: 4000,
                        position: "top-center",
                        theme: "bubble"
                    }
                );
            })
      },
      editSupport(row){
        this.currentSupport=row;
        this.editingSupport=true;
        this.dataNewSupport.name = row.supportName;

        this.supportCategories.forEach(cat => {
           if(cat.name === row.categoryName){
               this.dataNewSupport.id_category=cat.id;
           }
        });

      },
      saveSupport(){
          axios
              .patch(
                  "api/updateSupport/" + this.currentSupport.supportId,
                  {'name': this.dataNewSupport.name,'id_category':this.dataNewSupport.id_category}
              )
              .then(response => {
                  this.$toasted.success("Apoio editado com sucesso.", {
                      duration: 4000,
                      position: "top-center",
                      theme: "bubble"
                  });
                  this.supports.forEach(support => {
                      if(support.supportId === this.currentSupport.supportId){
                          support.supportName = this.dataNewSupport.name;

                          this.supportCategories.forEach(cat => {
                              if(cat.id === this.dataNewSupport.id_category){
                                  support.categoryName = cat.name;
                              }
                          });
                      }
                  })
                  this.currentSupport = null;
                  this.dataNewSupport.name="";
                  this.dataNewSupport.id_category=null;
                  this.editingSupport=false;
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
      },
      deleteSupport(row){
          axios
              .delete("api/deleteSupport/" + row.supportId)
              .then(response => {
                  this.$toasted.success("Apoio apagado com sucesso.", {
                      duration: 4000,
                      position: "top-center",
                      theme: "bubble"
                  });
                  let index = this.supports.findIndex((sc => {
                      return sc.id === row.id
                  }));
                  if (index !== -1) this.supports.splice(index, 1)
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
    showCategories(){
        this.boolShowCategories=true;
        this.items = this.supportCategories;
      },
    showSupports(){
        this.boolShowCategories=false;
        this.items = this.supports;
    },
    getSupportCategories() {
      axios
        .get("api/getSupportCategories")
        .then(response => {
          this.supportCategories = response.data;
          this.rows = this.supportCategories.length;
          this.loading = false;
          this.items=this.supportCategories;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getSupports(){
        axios
            .get("api/getSupports")
            .then(response=>{
                this.supports=response.data;
                this.loading=false;
            })
            .catch(error => {
            console.log(error);
            });
    },
    editSupportCategory(row) {
      this.currentSupport = Object.assign({}, row);
    },
    cancelEdit: function() {
      this.currentSupport = null;
    },
      createSupportCategory() {
      axios
        .post("api/createSupportCategory/", this.newSupport)
        .then(response => {
          this.$toasted.success("Apoio criado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
          this.supportCategories.push(response.data);
          this.newSupport.text="";

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
    deleteSupportCategory(row) {
      axios
        .delete("api/deleteSupportCategory/" + row.id)
        .then(response => {
          this.getSupports();
          this.$toasted.success("Apoio apagado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
            let index = this.supportCategories.findIndex((sc => {
                return sc.id === row.id
            }));
            if (index !== -1) this.supportCategories.splice(index, 1)
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
    saveSupportCategory() {
      axios
        .patch(
          "api/editSupportCategory/" + this.currentSupport.id,
          this.currentSupport
        )
        .then(response => {
          this.getSupports();
          this.$toasted.success("Apoio editado com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
          this.supportCategories.forEach(cat => {
              if(cat.id === this.currentSupport.id){
                  cat.name = this.currentSupport.name;
              }
          })
            this.currentSupport = null;
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
    this.getSupportCategories();
    this.getSupports();

  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

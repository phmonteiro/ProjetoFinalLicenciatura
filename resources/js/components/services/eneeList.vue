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
    <div class="container">
      <h2>Lista de ENEs</h2>
      <div v-if="users!=null && users.length!==0">
          <b-table striped hover :items="users" :fields="fields">
              <template v-slot:cell(actions)="row">
                  <button
                      type="button"
                      class="btn btn-secondary"
                      data-toggle="modal"
                      data-target="#exampleModal"
                      @click.prevent="atribuirId(row.item)"
                  >Nova interação</button>
                  <router-link class="nav-link" :to="{name: 'contactDetails', params: {id: row.item.id }}">
                      <button class="btn btn-secondary">Histórico</button>
                  </router-link>
                  <div
                      class="modal fade"
                      id="exampleModal"
                      tabindex="-1"
                      role="dialog"
                      aria-labelledby="exampleModalLabel"
                      aria-hidden="true"
                  >
                      <div class="modal-dialog" role="document">
                          <ValidationObserver v-slot="{ handleSubmit }">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Interação com {{row.item.name}}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                              <div class="modal-body">
                                  <div class="form-group">
                                      <ValidationProvider name="service" rules="required" v-slot="{ errors }">
                                      <b-form-select v-model="contact.service" class="mb-3">
                                          <template slot="first">
                                              <option :value="null" disabled>-- Selecione o serviço --</option>
                                          </template>

                                          <option value="SAPE">Serviço de Apoio ao Estudante</option>
                                          <option value="CRIDE">Centro de Recursos para a Inclusão Digital</option>
                                          <option value="SAS">Serviços de Ação Social</option>
                                          <option value="UED">Unidade de Ensino à Distância</option>
                                          <option value="DST">Direção de Serviços Técnicos</option>
                                          <option value="SA">Serviços Académicos</option>
                                          <option value="Escola">Escola</option>
                                          <option value="Biblioteca">Biblioteca</option>
                                          <option value="Direção">Direção</option>
                                          <option value="Professor-Tutor">Professor Orientador</option>
                                          <option value="Gestor-Caso">Gestor de Caso</option>
                                      </b-form-select>
                                          <br>
                                          <code>{{ errors[0] }}</code>
                                      </ValidationProvider>
                                  </div>

                                  <div class="form-group">
                                      <label for="information">Informação</label>
                                      <ValidationProvider name="information" rules="required" v-slot="{ errors }">
                                      <textarea
                                          class="form-control"
                                          id="information"
                                          v-model="contact.information"
                                          name="information"
                                          rows="3"
                                      ></textarea>
                                      <br>
                                      <code>{{ errors[0] }}</code>
                                      </ValidationProvider>
                                  </div>

                                  <div class="form-group">
                                      <label for="date">Data do Contacto</label>
                                      <ValidationProvider name="date" rules="required" v-slot="{ errors }">
                                      <input
                                          id="date"
                                          type="date"
                                          class="form-control"
                                          v-model="contact.date"
                                          name="date"
                                      />
                                          <br>
                                          <code>{{ errors[0] }}</code>
                                      </ValidationProvider>
                                  </div>

                                  <div class="form-group">
                                      <label for>Hora</label>
                                      <ValidationProvider name="time" rules="required" v-slot="{ errors }">
                                      <vue-timepicker :minute-interval="5" format="HH:mm" v-model="contact.time"></vue-timepicker>
                                      <br>
                                      <code>{{ errors[0] }}</code>
                                      </ValidationProvider>
                                  </div>

                                  <div class="form-group">
                                      <label>Meio de Contacto:</label>
                                      <ValidationProvider name="contactMedium" rules="required" v-slot="{ errors }">
                                          <b-form-select name="contactMedium" id="contactMedium"  v-model="contact.contactMedium" class="mb-3">
                                              <option :value="null" disabled>-- Selecione o meio de contacto --</option>
                                              <option value="conferencia">Video Conferência</option>
                                              <option value="presencial">Presencial</option>
                                              <option value="telefone">Telefone</option>
                                          </b-form-select>
                                          <code>{{ errors[0] }}</code>
                                      </ValidationProvider>
                                  </div>

                                  <div class="form-group" v-if="contact.contactMedium==='conferencia'">
                                      <label for="software">Software:</label>
                                      <ValidationProvider name="Software" rules="required" v-slot="{ errors }">
                                          <input class="form-control" v-model="contact.software" type="text" id="software" name="software">
                                          <br>
                                          <code>{{ errors[0] }}</code>
                                      </ValidationProvider>
                                  </div>

                                  <div class="form-group" v-if="contact.contactMedium==='presencial'">
                                      <label for="local">Local:</label>
                                      <ValidationProvider name="local" rules="required" v-slot="{ errors }">
                                          <input class="form-control" v-model="contact.place" type="text" id="local" name="local">
                                          <br>
                                          <code>{{ errors[0] }}</code>
                                      </ValidationProvider>
                                  </div>

                                  <div class="form-group p-2">
                                      <label for="files">Anexos Opcionais</label>
                                      <div class="field p-1">
                                          <input type="file" id="files" ref="files" v-on:change="handleFiles()" multiple>
                                      </div>
                                  </div>

                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                      <button
                                          type="submit"
                                          class="btn btn-primary"
                                          v-on:click.prevent="handleSubmit(setContact)"
                                      >Confirmar</button>
                                  </div>
                              </div>
                          </div>
                          </ValidationObserver>
                      </div>
                  </div>
              </template>
          </b-table>
          <nav v-if="users" aria-label="Page navigation">
              <ul class="pagination">
                  <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                      <a
                          class="page-link"
                          href="#"
                          @click.prevent="getEnee(pagination.prev_page_url)"
                      >{{$t('anterior')}}</a>
                  </li>

                  <li class="page-item disabled">
                      <a
                          class="page-link text-dark"
                          href="#"
                      >Página {{ pagination.current_page }} de {{ pagination.last_page }}</a>
                  </li>

                  <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                      <a class="page-link" href="#" @click.prevent="getEnee(pagination.next_page_url)">{{$t('próximo')}}</a>
                  </li>
              </ul>
          </nav>
      </div>
      <div v-else>
          <br>
          <h4>Não existem ENEs registados na plataforma.</h4>
      </div>

    </div>
<!--      Estes <br> servem para adicionar espaço no fim da pagina para melhorar a visualização do modal-->
      <br><br><br><br><br><br><br><br><br><br><br>
  </div>
</template>

<script>
    import VueTimepicker from 'vue2-timepicker/src/vue-timepicker.vue';

    export default {
  components:{
      VueTimepicker
  },
  data() {
    return {
      loading: true,
      pagination: {},
      users: null,
      userId:null,
      currentUser:null,
      files:[],
      fields: [
        {
          key: "name",
          label: "Nome",
          sortable: true
        },
        {
          key: "email",
          label: "Email",
          sortable: true
        },
        {
          key: "number",
          label: "Número",
          sortable: true
        },
        {
          key: "course",
          label: "Curso",
          sortable: true
        },
        {
          key: "school",
          label: "Escola",
          sortable: true
        },
        {
          key: "actions",
          label: "Ações Utilizador"
        }
      ],
      contact: {
        service: null,
        information: null,
        date: null,
        contactMedium: null,
        place: null,
        time: null,
        software: null,
      }
    };
  },
  methods: {
      handleFiles() {
          this.files = [];
          let uploadedFiles = this.$refs.files.files;
          for (var i = 0; i < uploadedFiles.length; i++) {
              this.files.push(uploadedFiles[i]);
          }
      },
    atribuirId(user){
          this.userId=user.id;
          this.currentUser=user;
      },
    downloadPDF(user) {
      axios({
        url: "api/downloadHistory/" + user.id,
        method: "GET",
        responseType: "blob"
      })
        .then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", user.number + ".zip");
          document.body.appendChild(link);
          link.click();
          this.$toasted.success(
            "Download do histórico do estudante feito com sucesso.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        })
        .catch(error => {
          this.$toasted.error(
            "Error ao fazer download do histórico do estudante. Por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    setContact() {
        const formData = new FormData();
        for (var i = 0; i < this.files.length; i++) {
            formData.append("file" + i, this.files[i]);
        }

        this.contact.email = this.currentUser.email;
        this.contact.time = this.contact.time.HH + ":" + this.contact.time.mm;

        if(this.contact.software==null){
            this.contact.software = '-';
        }

        if(this.contact.place==null){
            this.contact.place = '-';
        }

        formData.append("email", this.contact.email);
        formData.append("information", this.contact.information);
        formData.append("date", this.contact.date);
        formData.append("time", this.contact.time);
        formData.append("contactMedium", this.contact.contactMedium);
        formData.append("software", this.contact.software);
        formData.append("place", this.contact.place);
        formData.append("service", this.contact.service);
        formData.append("numberFiles", this.files.length);

        axios
        .post("api/setContact/" + this.userId, formData)
        .then(response => {
          $('#exampleModal').modal('hide');
          this.$toasted.success("Interação com estudante criada com sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao criar interação com estudante, por favor tente novamente.",
            {
              duration: 4000,
              position: "top-center",
              theme: "bubble"
            }
          );
        });
    },
    getEnee(page_url) {
      let pg = this;
      page_url = page_url || "api/getEnees?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.users = response.data.data;
          pg.makePagination(response.data.meta, response.data.links);
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };
      this.pagination = pagination;
    }
  },
  created() {
    this.getEnee();
  }
};
</script>

<style>
</style>

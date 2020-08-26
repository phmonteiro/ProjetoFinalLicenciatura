<template>
    <ValidationObserver v-slot="{ handleSubmit }">
    <form @submit.prevent="handleSubmit(setMeeting)">
      <div class="loader">
          <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50" />
      </div>
    <b-container v-if="!loading">
      <b-row>
        <b-col class="top100">
          <h2>{{ $t('pedir_agendamento_reunião') }}</h2>
            <hr>
            <br>
          <div class="form-group">

              <ValidationProvider name="service" rules="required" v-slot="{ errors }">
                  <b-form-select
                      v-model="meeting.service"
                      name="service"
                      class="mb-3"
                      aria-label="Escolher serviço"
                  >
                      <template slot="first">
                          <option :value="null" disabled>-- {{ $t('selecionar_serviço') }} --</option>
                      </template>
                      <option v-for="service in serviceOptions.name">{{ service }}</option>
                  </b-form-select>
                  <code>{{ errors[0] }}</code>
              </ValidationProvider>
              <div v-if="meeting.service != null && meeting.service != 'Gestor de Caso'">
                    <h4>Atenção: ao submeter um pedido de reunião com um docente, estará a enviar um correio electrónico para o docente seleccionado</h4>
              </div>
          </div>
          <div class="form-group">
            <span for="comment">{{ $t('comentário') }}</span>

              <ValidationProvider name="comment" rules="required" v-slot="{ errors }">
            <textarea
                aria-label="Texto para escrever o comentário"
                class="form-control"
                id="decision"
                v-model="meeting.comment"
                name="comment"
                rows="3"
            ></textarea>
                  <code>{{ errors[0] }}</code>
              </ValidationProvider>
          </div>
          <button type="submit" class="btn btn-primary">{{ $t('pedir_reunião') }}</button>
        </b-col>
      </b-row>
    </b-container>
  </form>
    </ValidationObserver>
</template>

<script>
export default {
  data() {
    return {
      meeting: {
        service: null,
        comment: null,
      },
        serviceOptions:{
          name:[],
          email:[]
        },
        teachers:[],
        loading:true,
    };
  },
  methods: {

    getTeachersStudent() {
          axios
              .get("api/getTeachersStudent/" + this.user.id)
              .then(response => {
                  console.log(response.data);

                  this.teachers = response.data;

                  this.loading =  false;

                  this.serviceOptions.name.push("Gestor de Caso");
                  this.serviceOptions.email.push("Gestor-Caso");

                  this.teachers.forEach(teacher=>{
                      let nomeParts = teacher.name.split(" ");
                      let nome = nomeParts[0]+" "+nomeParts[nomeParts.length-1];

                      this.serviceOptions.name.push(nome + " - " + teacher.subject);
                      this.serviceOptions.email.push(teacher.email);
                  })
              })
              .catch(error => {console.log(error)});

      },
    setMeeting() {
        if(this.meeting.service == "Gestor de Caso")
            this.meeting.service = "Gestor-Caso";

      axios
        .post("api/setMeeting", this.meeting)
        .then(response => {
          this.meeting.service=null;
          this.meeting.comment=null;
          this.$toasted.success("Pedido de reunião realizado sucesso.", {
            duration: 4000,
            position: "top-center",
            theme: "bubble"
          });
        })
        .catch(error => {
          console.log(error);
          this.$toasted.error(
            "Erro ao fazer pedido de reunião. Por favor tente novamente.",
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
      this.getTeachersStudent();
  },
    computed: {
    user: function() {
      return this.$store.state.user;
    }
  }
};
</script>

<style>
</style>

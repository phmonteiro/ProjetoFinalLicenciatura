<template>
  <div>
    <b-container>
      <b-row>
        <b-col></b-col>
        <b-col>
          <div class="loader">
            <ClipLoader sizeUnit="px" class="loading" v-if="loading" :size="50"/>
          </div>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-container>
    <b-container>
      <b-row>
        <b-col class="top100">
          <div v-if="supports">
            <h2>{{$t('apoios_usufruidos')}}</h2>
              <label for><b>Categorias de Apoios</b></label>
              <div id="app">
              <span v-for="category in categories">
                  <li style="list-style-type:none">
<!--                      <b-form-checkbox disabled v-if="!category.supports.length" v-model="selectedCategories" :value="category.id">-->
<!--                              {{ category.name }}-->
<!--                          </b-form-checkbox>-->
                      <label>
                          {{category.name}}
                      </label>
                  </li>

                  <ul>
                          <b-form-checkbox disabled v-model="selectedSupports" :value="sup.id" v-for="sup in category.supports">
                              {{ sup.name }}
                          </b-form-checkbox>
                  </ul>
              </span>

              </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      pagination: {},
      supports: [],
      loading: true,
      selectedSupports:[],
      selectedCategories:[],
      categories:[],
    };
  },
  methods: {
      getSupportsByStudent() {
      axios
        .get("api/getSupportsByStudent")
        .then(response => {
          this.loading = false;
          this.supports = response.data;
          this.supports.forEach(support => {
              this.selectedSupports.push(support.id);
          })

          console.log(this.supports);
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },
  getSupports(){
      axios.get('api/getSupportsByCategory')
          .then(response => {
              this.categories = response.data;
          })
          .catch(error=>{
              console.log(error);
          });

        },
  },
  created() {
    this.getSupportsByStudent();
    this.getSupports();
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

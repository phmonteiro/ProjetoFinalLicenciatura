<template>
  <div>
    <div class="languages">
      <div v-if="language=='pt'">
        <a href="#" v-on:click.prevent="changeLanguage()" onClick="window.location.reload();">
          <img :src="'/imagens/iconfinder_Portugal.png'" alt="Bandeira Portuguesa" tabindex="-1" />
        </a>
      </div>
      <div v-else>
        <a href="#" v-on:click.prevent="changeLanguage()" onClick="window.location.reload();">
          <img :src="'/imagens/iconfinder_UnitedKingdom.png'" alt="Bandeira Inglesa" tabindex="-1" />
        </a>
      </div>
    </div>
    <nav-bar v-if="user!=null"></nav-bar>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  data() {
    return {
      language: ""
    };
  },
  methods: {
    changeLanguage() {
      if (this.$i18n.locale == "pt") {
        this.$i18n.locale = "en";
        //this.$store.languagePref = "PT";
        this.$store.commit("setLang", "en");
        this.language = "pt";
      } else {
        this.$i18n.locale = "pt";
        //this.$store.languagePref = "EN";
        this.$store.commit("setLang", "pt");
        this.language = "en";
      }
    },
    toggleNav() {
      this.$emit("toggleNav");
    }
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  created() {
    var languageStore = this.$store.state.languagePref;
    this.$i18n.locale = languageStore;
    if (languageStore == "en") {
      this.language = "pt";
    } else {
      this.language = "en";
    }
  }
};
</script>
<style>
.languages {
  position: absolute;
  top: 95px;
  right: 10px;
  padding: 10px;
  z-index: 300;
}
.languages img {
  width: 25px;
  margin-right: 200px;
  z-index: 200;
}

.navbar-light .navbar-toggler {
  z-index: 100;
}
@media only screen and (max-width: 991px) {
  .languages img {
    margin-top: 75px !important;
  }
}
</style>
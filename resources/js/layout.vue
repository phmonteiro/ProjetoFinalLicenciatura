<template>
  <div>
    <div class="languages">
      <div v-if="language=='pt'">
        <a href="#" v-on:click.prevent="changeLanguage()" >
          <img :src="'/imagens/iconfinder_Portugal.png'" alt="Bandeira Portuguesa" tabindex="-1" />
        </a>
      </div>
      <div v-else>
        <a href="#" v-on:click.prevent="changeLanguage()" >
          <img :src="'/imagens/iconfinder_UnitedKingdom.png'" alt="Bandeira Inglesa" tabindex="-1" />
        </a>
      </div>
    </div>
    <nav-bar v-if="user!=null"></nav-bar>
      <router-view></router-view>
    </div>
</template>

<script>
    import { localize } from 'vee-validate';

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
        localize("en");
        //this.$store.languagePref = "PT";
        this.$store.commit("setLang", "en");
        this.language = "pt";
      } else {
        this.$i18n.locale = "pt";
        localize("pt");

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
      this.language = "en";
    } else {
      this.language = "pt";
    }
  }
};
</script>
<style>
.languages {
  position: absolute;
  top: 150px;
  right: 30px;
  padding: 10px;
  z-index: 1;
}
.languages img {
  width: 25px;
}

@media only screen and (max-width: 991px) {
  .languages{
    top: 10px !important;
    right: 240px;
    z-index: 1;
  }
}
</style>

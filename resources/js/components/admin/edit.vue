<template>
  <div class="container mt-3" v-if="user">
    <div class="form-group">
      <label for="inputName">Nome</label>
      <input
        type="text"
        class="form-control"
        v-model="user.name"
        name="name"
        id="inputName"
        disabled
      >
    </div>

    <div class="form-group">
      <label for="inputEmail">E-mail</label>
      <input
        type="text"
        class="form-control"
        v-model="user.email"
        name="email"
        id="inputEmail"
        disabled
      >
    </div>

    <div class="form-group">
      <label for="inputType">Tipo de utilizador:</label>
      <select class="form-control" v-model="user.type" id="type" name="type">
        <option>Administrador</option>
        <option>Professor</option>
        <option>Services</option>
        <option>Estudante</option>
        <option>CaseManagerResponsible</option>
        <option>CaseManager</option>
      </select>
    </div>

    <div class="form-group">
      <label for="date">Data de expiração do login</label>
      <input
        id="loginExpirationDate"
        type="date"
        v-model="user.loginExpirationDate"
        class="form-control"
        name="loginExpirationDate"
      >
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-secondary" name="ok" v-on:click.prevent="save()">Guardar</button>
      <button class="btn btn-secondary" v-on:click.prevent="cancel()">Cancelar</button>
    </div>
  </div>
</template>
<script>
export default {
  props: ["user"],
  data: function() {
    return {};
  },
  methods: {
    cancel() {
      this.$emit("cancel-edit");
    },
    save: function() {
      this.$emit("save-user");
    }
  }
};
</script>

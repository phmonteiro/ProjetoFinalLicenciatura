<template>
  <div>
    <div class="sidenav">
      <a href="#">
        <router-link class="nav-link" :to="{name: 'contact'}">Contactos</router-link>
      </a>
      <a href="#">
        <router-link class="nav-link" :to="{name: 'myMeetings'}">Reuniões</router-link>
      </a>
      <a href="#">
        <router-link class="nav-link" :to="{name: 'setMeeting'}">Agendar reunião</router-link>
      </a>

      <a href="#">
        <router-link class="nav-link" :to="{name: 'usedServices'}">Apoios usufruidos</router-link>
      </a>
      <a href="#">
        <router-link class="nav-link" :to="{name: 'serviceRequest'}">Pedido de apoios</router-link>
      </a>
    </div>

    <router-view></router-view>
  </div>
</template>
<script>
export default {
  data() {
    return {};
  },
  methods: {}
};
</script>

<style>
.sidenav {
  width: 130px;
  position: fixed;
  z-index: 1;
  top: 90px;
  left: 10px;
  background: #6c757d;
  overflow-x: hidden;
  padding: 8px 0;
}

.sidenav a {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 16px;
  color: white;
  display: block;
}

.sidenav a:hover {
  color: black;
}

.main {
  margin-left: 140px; /* Same width as the sidebar + left position in px */
  padding: 0px;
}

@media screen and (max-height: 450px) {
  .sidenav {
    padding-top: 15px;
  }
  .sidenav a {
    font-size: 18px;
  }
}
</style>


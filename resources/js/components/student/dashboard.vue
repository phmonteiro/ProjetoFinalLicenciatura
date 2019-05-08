<template>
    <div>
      <div class="sidenav">
        <a href="#"><router-link class="nav-link" :to="{name: 'contact'}">Contacts</router-link></a>
        <a href="#"><router-link class="nav-link" :to="{name: 'myMeetings'}">Meetings</router-link></a>
        <a href="#"><router-link class="nav-link" :to="{name: 'serviceRequest'}">Request Service</router-link></a>
        <a href="#"><router-link class="nav-link" :to="{name: 'setMeeting'}">Set meeting</router-link></a>
        <a href="#"><router-link class="nav-link" :to="{name: 'usedServices'}">Used Services</router-link></a>
      </div>

      <div class="main">
        <b-container>
                  <b-row align-v="center">
                      <b-col>
                          <router-view></router-view>
                      </b-col>
                  </b-row>
              </b-container>
      </div> 
        <!-- <router-link class="nav-link" :to="{name: 'contact'}">Contact</router-link>
    <router-link class="nav-link" :to="{name: 'myMeetings'}">Meetings</router-link>
    <router-link class="nav-link" :to="{name: 'serviceRequest'}">Service request</router-link>
    <router-link class="nav-link" :to="{name: 'setMeeting'}">Set meeting</router-link>
    <router-link class="nav-link" :to="{name: 'usedServices'}">Used Services</router-link> -->
        
    </div>
</template>
<script>
    export default {
        data() {
            return {

            };

        },
        methods: {}
    }

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
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
</style>


<template>
  <div>
    <router-link class="nav-link" :to="{name: 'contact'}">Contact</router-link>
    <router-link class="nav-link" :to="{name: 'myMeetings'}">Meetings</router-link>
    <router-link class="nav-link" :to="{name: 'serviceRequest'}">Service request</router-link>
    <router-link class="nav-link" :to="{name: 'setMeeting'}">Set meeting</router-link>
    <router-link class="nav-link" :to="{name: 'usedServices'}">Used Services</router-link>
    <b-container>
      <b-row align-v="center">
        <b-col>
          <router-view></router-view>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
export default {};
</script>

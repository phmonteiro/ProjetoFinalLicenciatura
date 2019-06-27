<template>
    <div>
        <div class="container pt-3">
            <h4 class="welcome-msg">Bem vindo, {{user.name}}</h4>
            <h3>Sou Gestor de Caso</h3>
        </div>

        <section id="sidebar-menu">
            <a href="#" id="btn-menu" @click.prevent="toggleNav()">
                <font-awesome-icon icon="bars" size="lg" />
            </a>
            <div class="sidenav" :class="{ active: active}">
                <router-link class="nav-link" :to="{name: 'cmEneeList'}">Meus ENEE</router-link>
                <router-link class="nav-link" :to="{name: 'meetingsEneeList'}">Reuniões</router-link>
                <router-link class="nav-link" :to="{name: 'statistics'}">Estatísticas</router-link>
            </div>
        </section>
        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                currentUser: null,
                active: true
            };
        },
        methods: {
            toggleNav() {
                this.active = !this.active;
                console.log(this.active);
            }
        },
        created() {},
        computed: {
            user: function () {
                return this.$store.state.user;
            }
        }
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
        margin-left: 140px;
        /* Same width as the sidebar + left position in px */
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

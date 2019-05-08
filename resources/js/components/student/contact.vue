<template>
    <div>
        <b-container>
            <b-row>
                <b-col>

                </b-col>
                <b-col>
                    <div class="loader">
                        <ClipLoader  sizeUnit="px" class="loading" v-if="loading" :size="50" />
                    </div>
                </b-col>
                <b-col>

                </b-col>
            </b-row>
        </b-container>
        <b-container>
            <b-row>
                <b-col class="top100">
                    <div v-if="contacts">
                        <div v-if="this.$store.state.languagePref=='pt'">
                            <h2>Contactos</h2>
                            <b-table striped hover v-if="contacts!=null" :items="contacts" :fields="fields"></b-table>
                        </div>
                        <div v-else>
                            <h2>Contact</h2>
                            <b-table striped hover v-if="contacts!=null" :items="contacts" :fields="fields2"></b-table>
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
                contacts: null,
                loading: true,
                fields: [{
                        key: "service",
                        label: "Serviço",
                        sortable: true
                    },
                    {
                        key: "date",
                        label: "Data",
                        sortable: true
                    },
                    {
                        key: "nextContact",
                        label: "Próximo Contacto",
                        sortable: true
                    }
                ],
                fields2: [{
                        key: "service",
                        label: "Service",
                        sortable: true
                    },
                    {
                        key: "date",
                        label: "Date",
                        sortable: true
                    },
                    {
                        key: "nextContact",
                        label: "Next contact",
                        sortable: true
                    }
                ]
            };
        },
        methods: {
            getContacts() {
                axios
                    .get("api/getContacts/" + this.user.id)
                    .then(response => {
                        this.contacts = response.data.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },
        created() {
            this.getContacts();
        },
        computed: {
            user: function () {
                return this.$store.state.user;
            }
        }
    };

</script>

<style>
</style>

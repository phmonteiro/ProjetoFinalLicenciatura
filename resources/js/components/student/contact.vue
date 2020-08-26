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
                    <div v-if="contacts">
                        <h2>{{ $t('contatos') }}</h2>
                        <hr>
                        <div v-if="contacts!=null && contacts.length!==0">
                            <b-table striped hover :items="contacts" :fields="fields">
                                <template v-slot:cell(contactMedium)="{ value }">
                                    <p v-if="value==='conferencia'">Video-Conferência</p>
                                    <p v-if="value==='telefone'">Telefone</p>
                                    <p v-if="value==='presencial'">Presencial</p>
                                </template>
                            </b-table>
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="getContacts(pagination.prev_page_url)"
                                        >Anterior</a>
                                    </li>

                                    <li class="page-item disabled">
                                        <a
                                            class="page-link text-dark"
                                            href="#"
                                        >Página {{ pagination.current_page }} de {{ pagination.last_page }}</a>
                                    </li>

                                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                                        <a
                                            class="page-link"
                                            href="#"
                                            @click.prevent="getContacts(pagination.next_page_url)"
                                        >Próximo</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div v-else>
                            <br>
                            <h5>{{ $t('contactos_vazio') }}</h5>
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
                contacts: null,
                loading: true,
                fields: [
                    {
                        key: 'service',
                        label: this.$t('serviço'),
                        sortable: true,
                    },
                    {
                        key: 'date',
                        label: this.$t('data'),
                        sortable: true,
                    },
                    {
                        key: 'time',
                        label: this.$t('hora'),
                        sortable: true,
                    },
                    {
                        key: 'contactMedium',
                        label: this.$t('meio_contacto'),
                        sortable: true,
                    },
                    {
                        key: 'software',
                        label: this.$t('aplicacao'),
                        sortable: true,
                    },
                    {
                        key: 'place',
                        label: this.$t('local'),
                        sortable: true,
                    },
                ],
            };
        },
        methods: {
            getContacts(page_url) {
                let pg = this;
                page_url = page_url || 'api/getContacts?page=1';
                axios
                    .get(page_url)
                    .then(response => {
                        this.loading = false;
                        this.contacts = response.data.data;
                        pg.makePagination(response.data.meta, response.data.links);
                    })
                    .catch(error => {
                        console.log(error);
                        this.loading = false;
                    });
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev,
                };
                this.pagination = pagination;
            },
        },
        created() {
            this.getContacts();
        },
        computed: {
            user: function() {
                return this.$store.state.user;
            },
        },
    };
</script>

<style>
</style>

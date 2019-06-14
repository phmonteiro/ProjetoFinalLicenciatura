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
          <div v-if="meetings">
            <h2>{{ $t('pedidos_agendamento_reunião') }}</h2>
            <b-table striped hover v-if="meetings!=null" :items="meetings" :fields="fields">
              <template slot="actions" slot-scope="row">
                <b-row class="text-center">
                  <b-col md="4" sm="12">
                    <b-form-checkbox v-model="row.detailsShowing" @change="row.toggleDetails"></b-form-checkbox>
                    <div v-if="row.detailsShowing" style="margin-left: -8px;">
                      <font-awesome-icon icon="eye"/>
                    </div>
                    <div v-if="!row.detailsShowing" style="margin-left: -8px;">
                      <font-awesome-icon icon="eye-slash"/>
                    </div>
                  </b-col>
                </b-row>
              </template>
              <template slot="row-details" slot-scope="row">
                <b-card>
                  <b-row class="mb-2">
                    <b-col class="text">
                      <b>{{ $t('informação') }}</b>
                      {{row.item.info}}
                    </b-col>
                  </b-row>
                  <b-row class="mb-2">
                    <b-col class="text">
                      <b>{{ $t('comentário') }}</b>
                      {{row.item.comment}}
                    </b-col>
                  </b-row>
                  <b-button size="sm" @click="row.toggleDetails">{{ $t('esconder') }}</b-button>
                </b-card>
              </template>
            </b-table>
            <nav aria-label="Page navigation">
              <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="getMyMeetings(pagination.prev_page_url)"
                  >{{ $t('anterior') }}</a>
                </li>

                <li class="page-item disabled">
                  <a
                    class="page-link text-dark"
                    href="#"
                  >{{ $t('página') }} {{ pagination.current_page }} {{ $t('de') }} {{ pagination.last_page }}</a>
                </li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                  <a
                    class="page-link"
                    href="#"
                    @click.prevent="getMyMeetings(pagination.next_page_url)"
                  >{{ $t('próximo') }}</a>
                </li>
              </ul>
            </nav>
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
      meetings: null,
      loading: true,
      fields: [
        {
          key: "service",
          label: this.$t("serviço"),
          sortable: true
        },
        {
          key: "place",
          label: this.$t("local"),
          sortable: true
        },
        {
          key: "date",
          label: this.$t("data"),
          sortable: true
        },
        {
          key: "time",
          label: this.$t("hora"),
          sortable: true
        },
        {
          key: "actions",
          label: this.$t("ação"),
          sortable: true
        }
      ],
      meeting: {
        info: null,
        date: null
      }
    };
  },
  computed: {
    user: function() {
      return this.$store.state.user;
    }
  },
  methods: {
    getMyMeetings(page_url) {
      let pg = this;
      page_url = page_url || "api/getMyMeetings/?page=1";
      axios
        .get(page_url)
        .then(response => {
          this.loading = false;
          this.meetings = response.data.data;
          console.log(this.meetings);

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
        prev_page_url: links.prev
      };
      this.pagination = pagination;
    }
  },
  created() {
    this.getMyMeetings();
  }
};
</script>

<style>
</style>

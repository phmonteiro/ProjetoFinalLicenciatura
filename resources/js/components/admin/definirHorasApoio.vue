<template>
    <div>
        <b-container>
            <br>
            <h4>Quantidade de horas de apoio por aluno: {{limitHours}}</h4>
            <label>Novo limite de horas de apoio:</label>

            <input type="number" data-vv-as="numeric" v-validate="'numeric|required'" name="numeric_field" v-model="newLimitHours">
            <span v-show="errors.has('numeric_field')" class="help is-danger">{{ errors.first('numeric_field') }}</span>
            <br>
            <button
                type="submit"
                class="btn btn-secondary"
                data-dismiss="modal"
                v-on:click.prevent="save()"
            >{{ $t('gravar') }}
            </button>
        </b-container>
    </div>
</template>

<script>
    export default {
        name: 'definirHorasApoio',
        data: function() {
            return {
                limitHours: null,
                newLimitHours: null,

            };
        },
        methods: {
            getLimitHours() {
                axios.get('api/getHoursLimit')
                    .then(response => {
                        this.limitHours = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            save() {
                this.$validator.validateAll();
                if (this.errors.any()) {
                    alert('Introduza valor numérico!')
                }

                if(Number(this.newLimitHours)) {
                    var confirmed = confirm('Tem a certeza que pretende mudar a quantidade de horas de apoio por predefinição para ' + this.newLimitHours + "?");

                    if (confirmed == true) {
                        axios.post('api/setHoursLimit', {"newLimitHours": this.newLimitHours})
                            .then(response => {

                                this.$toasted.success('Guardado com sucesso.', {
                                    duration: 4000,
                                    position: 'top-center',
                                    theme: 'bubble',
                                });

                                this.limitHours=this.newLimitHours;
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    }
                }
            },
        },
        created() {
            this.getLimitHours();
        },
    };
</script>

<style scoped>

</style>

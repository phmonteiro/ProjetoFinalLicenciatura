<template>
    <div>
        <b-container>
            <br>
            <h4>Quantidade de horas de apoio por aluno: {{limitHours}} horas</h4>
            <label>Novo limite de horas de apoio:</label>
            <ValidationObserver v-slot="{ handleSubmit }">
            <ValidationProvider name="hoursLimit" rules="required|numeric" v-slot="{ errors }">
                <input type="number" name="numeric_field" v-model="newLimitHours">

                <code>{{ errors[0] }}</code>
            </ValidationProvider>
            <br>
            <button
                type="submit"
                class="btn btn-secondary"
                data-dismiss="modal"
                v-on:click.prevent="handleSubmit(save)"
            >{{ $t('gravar') }}
            </button>
                </ValidationObserver>
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

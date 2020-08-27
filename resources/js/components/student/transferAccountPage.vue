<template>
    <div>
        <b-container fluid>
            <b-row>
                <b-col>
                    <div class="text-center">
                        <h2>{{$t('bem_vindo')}}</h2>
                    </div>
                </b-col>
            </b-row>
            <br>
                    <div v-if="showSubscriptionButtons">
                    <div class="text-center">
                        <h6>Olá {{user.name}}!</h6><br>
                    </div>

                        <div class="form-group">
                    <b-row>
                        <b-col></b-col>
                        <b-col>
                            <div>
                                <label for="inputEmail">E-mail</label>
                            </div>
                        </b-col>
                        <b-col></b-col>
                    </b-row>
                    <b-row>
                        <b-col></b-col>
                        <b-col>
                            <div class="text-center">
                                <b-form-input
                                    type="text"
                                    class="form-control"
                                    v-model="user.email"
                                    name="email"
                                    id="inputEmail"
                                    disabled
                                />
                            </div>
                        </b-col>
                        <b-col></b-col>
                    </b-row>
                    </div>
                        <br>
                        <b-row>
                            <b-col class="col-sm-3"></b-col>
                            <b-col class="col-sm-6">
                                <div class="text-center">
                                    <b-container class="jumbotron">
                                        <b-row>
                                            <b>{{$t('aviso_deteçao_conta')}}</b>
                                        </b-row>
                                        <br>
                                        <b-row>
                                            <b>{{$t('aviso_transferencia_conta')}}</b>
                                        </b-row>
                                    </b-container>
                                </div>
                            </b-col>
                            <b-col class="col-sm-3"></b-col>
                        </b-row>

                    </div>
                    <div v-if="showSubscriptionButtons">
                        <b-row>
                            <b-col>
                                <div class="text-center">
                                        <a class="btn btn-secondary" v-on:click.prevent="transfer()" href>{{$t('transferir_conta')}}</a>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
        </b-container>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                showSubscriptionButtons:true,
            };
        },
        methods: {
            transfer() {
                axios
                    .post('api/transferAccountStatus')
                    .then(response=>{
                        this.$toasted.success(this.$t('transferencia_sucesso'),
                            {
                                duration: 10000,
                                position: "top-center",
                                theme: "bubble"
                            }
                        );
                        this.$router.push("/subscription");
                    })
                    .catch(error=>{
                        console.log(error);
                    })
            },
        },
        computed: {
            user: function() {
                return this.$store.state.user;
            }
        }
    };
</script>
<style>
    .center {
        text-align: center;
        margin-top: 20%;
    }
</style>


<!--<template>-->
<!--    <div>-->
<!--        <div>-->
<!--            <span>{{$t('aviso_transferencia_conta')}}</span>-->
<!--            <b-button v-if="!proceedTransfer" @click="transfer()">{{$t('continuar')}}</b-button>-->
<!--        </div>-->
<!--        <div v-if="proceedTransfer">-->
<!--        <ValidationObserver v-slot="{ handleSubmit }">-->
<!--        <div class="col-lg-6 p-0 d-flex align-items-center right-login">-->
<!--            <div class="col-lg-3"></div>-->
<!--            <div class="col-lg-6">-->
<!--                <form-->
<!--                    class="justify-content-center"-->
<!--                    @submit.prevent="handleSubmit(login)"-->
<!--                    autocomplete="on"-->
<!--                >-->
<!--                    <div class="row">-->
<!--                        <div class="col">-->
<!--                            <div class="form-group">-->
<!--                                <span aria-label="Label Emal" class="numero">{{ $t('nome_utilizador') }}</span>-->

<!--                                <ValidationProvider name="email" rules="required|email" v-slot="{ errors }">-->
<!--                                    <input-->
<!--                                        aria-label="Email"-->
<!--                                        id="email"-->
<!--                                        v-model="user.email"-->
<!--                                        type="text"-->
<!--                                        class="form-control"-->
<!--                                        name="email"-->
<!--                                        placeholder="email@my.ipleiria.pt"-->
<!--                                        required-->
<!--                                        autofocus-->
<!--                                    />-->
<!--                                    <span>{{ errors[0] }}</span>-->
<!--                                </ValidationProvider>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="row">-->
<!--                        <div class="col">-->
<!--                            <div class="form-group">-->
<!--                                <span aria-label="Label Password" class="senha">{{ $t('palavra_pass') }}</span>-->

<!--                                <ValidationProvider name="password" rules="required" v-slot="{ errors }">-->
<!--                                    <input-->
<!--                                        aria-label="Password"-->
<!--                                        id="password"-->
<!--                                        v-model="user.password"-->
<!--                                        type="password"-->
<!--                                        class="form-control"-->
<!--                                        name="password"-->
<!--                                        required-->
<!--                                    />-->
<!--                                    <span>{{ errors[0] }}</span>-->
<!--                                </ValidationProvider>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <button class="btn btn-secondary" type="submit">Login</button>-->
<!--                </form>-->
<!--            </div>-->
<!--            <div class="col-lg-3"></div>-->
<!--        </div>-->
<!--        </ValidationObserver>-->
<!--            <b-button @click="cancel()"> {{$t('cancelar')}}</b-button>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<!--<script>-->
<!--  export default {-->
<!--    name: 'transferAccountStatus',-->
<!--    data(){-->
<!--        return{-->
<!--            user:{-->
<!--                password,-->
<!--                email-->
<!--            },-->
<!--            proceedTransfer:false,-->
<!--        }-->
<!--      },-->
<!--      methods:{-->
<!--          login(){-->
<!--              axios-->
<!--                  .post("api/transferAccountStatus")-->
<!--                  .then(response=>{-->

<!--                  })-->
<!--                  .catch(error=>{-->

<!--                  });-->
<!--          },-->
<!--          transfer(){-->
<!--              this.proceedTransfer = true;-->
<!--          },-->
<!--          cancel(){-->
<!--              this.proceedTransfer = false;-->
<!--          }-->
<!--      }-->

<!--  };-->
<!--</script>-->

<!--<style scoped>-->

<!--</style>-->

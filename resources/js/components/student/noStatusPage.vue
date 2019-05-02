<template>
    <div>
        <b-container fluid>
            <b-row>
                <b-col>
                    <h4>Ir para</h4>
                    <a class="btn btn-secondary" href="https://ead.ipleiria.pt/">EAD</a>
                </b-col>
                <b-col>
                    <h4>Não tem estatuto de ENEE</h4>
                    <a class="btn btn-secondary" v-on:click.prevent="subscribe()" href="">Pedir</a>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        methods: {
            subscribe() {
                this.$router.push("/subscription");
            }
        },
    }

</script>

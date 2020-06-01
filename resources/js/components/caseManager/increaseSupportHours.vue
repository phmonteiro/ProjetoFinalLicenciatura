<template>
    <div>
        <b-container>
            <h3>Limite de horas de apoio atual: {{student.supportHours}}</h3>
            <br>
            <label for="newTotal">Novo limite de horas de apoio:</label>
            <input type="number" v-model="newTotalHours" id="newTotal" >
            <code v-if="showError">O valor de horas tem que ser superior ao valor atual</code>
            <br><br>
            <button
                type="submit"
                class="btn btn-secondary"
                data-dismiss="modal"
                v-on:click.prevent="save"
            >{{ $t('gravar') }}</button>
            <button
                type="submit"
                class="btn btn-secondary"
                data-dismiss="modal"
                v-on:click.prevent="cancel"
            >{{ $t('cancelar') }}</button>
        </b-container>
    </div>
</template>

<script>
  export default {
    name: 'increaseSupportHours',
    props:["student"],
    data: function(){
        return {
            newTotalHours:null,
            showError:null
        };
    },
    methods:{
        cancel() {
            this.$emit("cancel-increase");
        },
        save: function() {

            if(Number(this.newTotalHours)<=Number(this.student.supportHours)){
                this.showError=true;
            }else {
                this.$emit("save-increase",this.newTotalHours);
            }
            }
    }
  };
</script>

<style scoped>

</style>

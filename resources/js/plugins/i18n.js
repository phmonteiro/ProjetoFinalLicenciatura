import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

const messages = {
        'pt': {
            nome_utilizador: 'Nome de Utilizador',
            cartao_cidadao: 'Cartão de Cidadão',
            palavra_pass: 'Palavra Pass'

        },
        'en': {
            nome_utilizador: 'Username',
            cartao_cidadao: 'Citizen Card',
            palavra_pass: 'Password'
        }
};

const i18n = new VueI18n({
    locale: 'pt', // set locale
    fallbackLocale: 'en', // set fallback locale
    messages, // set locale messages
});

export default i18n;
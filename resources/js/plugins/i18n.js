import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

const messages = {
  'pt': {
    formulario: 'Formulário para o estatuto de ENEE',
    adicionar_aluno: 'Adicionar aluno com estatuto de ENEE',
    nome_utilizador: 'Nome de Utilizador',
    cartao_cidadao: 'Cartão de Cidadão',
    palavra_pass: 'Palavra Pass',
    contatos: 'Contactos',
    reuniões: 'Reuniões',
    agendar_reunião: 'Agendar reunião',
    apoios_usufruidos: 'Apoios usufruidos',
    pedidos_apoios: 'Pedido de apoios',
    editar_perfil: 'Editar perfil',
    acompanhamento: 'Acompanhamento',
    mensagem_estudante: 'O seu pedido ao estatuto de estudante com necessidades educativas especais está no estado de:',
    reprovado: 'Reprovado',
    aguardar_aprovação: 'A aguardar avaliação',
    serviço: 'Serviço',
    data: 'Data',
    proximo_contacto: 'Próximo contacto',
    email: 'Email',
    email_principal: 'Email principal',
    email_secundário: 'Email secundário',
    identificação_responsável: 'Identificação responsável',
    contacto_telefónico: 'Contacto telefónico',
    parentesco: 'Parentesco',
    contacto_emergencia: 'Identificação do contacto de emergência',
    nome: 'Nome',
    confirmar: 'Confirmar',
    disciplina: 'Disciplina',
    quantidade_horas: 'Quantidade de horas',
    gravar: 'Gravar',
    cancelar: 'Cancelar',
    pedidos_agendamento_reunião: 'Pedidos de agendamento de reunião',
    informação: 'Informação',
    comentário: 'Comentário',
    esconder: 'Esconder',
    anterior: 'Anterior',
    próximo: 'Próximo',
    página: 'Página',
    de: 'de',
    local: 'Local',
    hora: 'Hora',
    ação: 'Ações',
    pedido_apoio: 'Pedido apoio',
    selecionar_serviço: 'Selecione o serviço',
    prioridade: 'Prioridade',
    apoio_sala_aula: 'Apoios em sala de aula',
    apoio_componente_letiva: 'Apoio à componente letiva',
    acompanhamento_individualizado: 'Acompanhamento individualizado',
    acompanhamento_professor_tutor: 'Acompanhamento por professor tutor',
    provas_adaptadas: 'Métodos e provas de avaliação adaptados',
    apoio_social: 'Apoio social',
    epoca_especial: 'Acesso a épocas especiais de exame',
    local_aulas: 'Adequação na atribuição de local para realização das unidades curriculares de estágio, de educação clínica, de ensino clínico e de práticas pedagógicas',
    motivo: 'Motivo',
    pedir_serviço: 'Pedir apoio',
    pedir_agendamento_reunião: 'Pedir agendamento de reunião',
    pedir_reunião: 'Pedir reunião',
    gestor_caso: 'Gestor de caso',
    quantidade_horas_utilizadas: 'Quantidade de horas utilizadas',
    ano: 'ano',
    editar_quantidade_horas_utilizadas: 'Editar quantidade de horas',
    semestre: 'Semestre',
    horas_acompanhamento: 'Horas de acompanhamento',
    nome_serviço: 'Nome do serviço',
    formulário: 'Formulário de candidatura ao estatuto de ENEE',
    identificação_estudante: 'Identificação do estudante',
    numero_estudante: 'Número de estudante',
    data_nascimento: 'Data de nascimento',
    género: 'Género',
    masculino: 'Masculino',
    feminino: 'Feminino',
    outro: 'Outro',
    residencia: 'Residência em época de aulas',
    codigo_postal: 'Código postal',
    localidade: 'Localidade',
    doc_identificação: 'Documento de identificação',
    carta_conducao: 'Carta de condução',
    passaporte: 'Passaporte',
    ano_curricular: 'Ano curricular',
    ano_matricula: 'Ano 1º matricula',
    tipos_nee: 'Tipos de necessidade educativas especiais',
    visão: 'Visão',
    dislexia: 'Dislexia/Disortografia/Disgrafia',
    audição: 'Audição',
    asperger: 'Síndrome de Asperger, Deficit atenção',
    motora: 'Motora',
    psicológico: 'Doenças do Foro Psicológico, neurológico ou psiquiátrico',
    outras_doenças: 'Outras doenças',
    analise_funcional: 'Análise funcional do problema / Informação sobre implicações no desempenho académico',
    descrição_apoio: 'Descrição de apoios anteriormente usufruidos',
    relatorio_medico: 'Relatório médico',
    submeter: 'Submeter',
    ficheiro: 'Escolha os ficheiros',
    adicionar: 'Adicionar',
    a_carregar: 'A carregar',
    bem_vindo: 'Bem vindo à plataforma 100%IN',
    pedir_estatuto: 'Pedir estatuto ENEE'
  },
  'en': {
    nome_utilizador: 'Username',
    cartao_cidadao: 'Citizen Card',
    palavra_pass: 'Password',
    contatos: 'Contacts',
    reuniões: 'Meetings',
    agendar_reunião: 'Schedule meeting',
    apoios_usufruidos: 'Supports',
    pedidos_apoios: 'Request for support',
    editar_perfil: 'Edit profile',
    acompanhamento: 'Custom support',
    mensagem_estudante: 'Your request for the status of student with special educational needs is in the state of:',
    reprovado: 'Denied',
    aguardar_aprovação: 'Waiting evaluation',
    serviço: 'Service',
    data: 'Date',
    proximo_contacto: 'Next contact',
    email: 'Email',
    email_principal: 'Main email',
    email_secundário: 'Secondary email',
    identificação_responsável: 'Responsible Identification',
    contacto_telefónico: 'Phone contact',
    parentesco: 'Kinship',
    contacto_emergencia: 'Identification emergency contact',
    nome: 'Name',
    confirmar: 'Confirm',
    disciplina: 'Subject',
    quantidade_horas: 'Amount of hours',
    gravar: 'Save',
    cancelar: 'Cancel',
    pedidos_agendamento_reunião: 'Meeting Scheduling Requests',
    informação: 'Information',
    comentário: 'Comment',
    esconder: 'Hide',
    anterior: 'Previous',
    próximo: 'Next',
    página: 'Page',
    de: 'of',
    local: 'Local',
    hora: 'Time',
    ação: 'Actions',
    pedido_apoio: 'Support request',
    selecionar_serviço: 'Select the service you want',
    prioridade: 'Priority',
    apoio_sala_aula: 'Classroom support',
    apoio_componente_letiva: 'Support for the teaching component',
    acompanhamento_individualizado: 'Individualized support',
    acompanhamento_professor_tutor: 'Support by teacher-tutor',
    provas_adaptadas: 'Appropriate assessment methods and tests',
    apoio_social: 'Social support',
    epoca_especial: 'Access to special examination periods',
    local_aulas: 'Adequacy in the assignment of place for accomplishment of the curricular units of stage, of clinical education, of clinical teaching and of pedagogical practices',
    motivo: 'Reason',
    pedir_serviço: 'Ask for support',
    pedir_agendamento_reunião: 'Request meeting schedule',
    pedir_reunião: 'Schedule meeting',
    gestor_caso: 'Case manager',
    quantidade_horas_utilizadas: 'Number of hours used',
    ano: 'year',
    editar_quantidade_horas_utilizadas: 'Edit number of hours',
    semestre: 'Semester',
    horas_acompanhamento: 'Custom support hours',
    nome_serviço: 'Service name',
    formulario: 'Application form for the ENEE statute',
    identificação_estudante: 'Student identification',
    numero_estudante: 'Student number',
    data_nascimento: 'Birth date',
    género: 'Gender',
    masculino: 'Male',
    feminino: 'Female',
    outro: 'Other',
    residencia: 'Address during studying period',
    codigo_postal: 'Zip/Postal Code',
    localidade: 'State/Province/Region',
    doc_identificação: 'Identification document',
    carta_conducao: 'Driving license',
    passaporte: 'Passport',
    ano_curricular: 'Curricular year',
    ano_matricula: '1st year enrollment',
    tipos_nee: 'Types of special educational needs',
    visão: 'Vision',
    dislexia: 'Dyslexia/Disortography/Dysgraphia',
    audição: 'Hearing',
    asperger: 'Asperger Syndrome, Attention Deficit',
    motora: 'Motor',
    psicológico: 'Psychological, neurological or psychiatric form',
    outras_doenças: 'Other',
    analise_funcional: 'Functional analysis of the problem / Information about implications for academic performance',
    descrição_apoio: 'Description of previously used supports',
    relatorio_medico: 'Medical report',
    submeter: 'Submit',
    ficheiro: 'Choose files',
    adicionar_aluno: 'Add student with ENEE status',
    adicionar: 'Add',
    a_carregar: 'Loading',
    bem_vindo: 'Welcome to the plataform 100%IN',
    pedir_estatuto: 'Request ENEE status'

  },
};

const i18n = new VueI18n({
  locale: 'pt', // set locale
  fallbackLocale: 'en', // set fallback locale
  messages, // set locale messages
});

export default i18n;

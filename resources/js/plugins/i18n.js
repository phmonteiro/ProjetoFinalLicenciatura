import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

const messages = {
  'pt': {
    a_carregar: 'A carregar',
    acompanhamento: 'Acompanhamento',
    acompanhamento_individualizado: 'Acompanhamento individualizado',
    acompanhamento_professor_tutor: 'Acompanhamento por professor tutor',
    adicionar: 'Adicionar',
    adicionar_aluno: 'Adicionar aluno com estatuto de ENEE',
    agendar_reunião: 'Agendar reunião',
    aguardar_aprovação: 'A aguardar avaliação',
    analise_funcional: 'Análise funcional do problema / Informação sobre implicações no desempenho académico',
    ano: 'ano',
    ano_curricular: 'Ano curricular',
    ano_matricula: 'Ano 1º matricula',
    anterior: 'Anterior',
    aplicacao: 'Aplicação',
    apoio_componente_letiva: 'Apoio à componente letiva',
    apoio_sala_aula: 'Apoios em sala de aula',
    apoio_social: 'Apoio social',
    apoios_usufruidos: 'Apoios usufruidos',
    asperger: 'Síndrome de Asperger, Deficit atenção',
    audição: 'Audição',
    ação: 'Ações',
    bem_vindo: 'Bem vindo à plataforma 100%IN',
    cancelar: 'Cancelar',
    carta_conducao: 'Carta de condução',
    cartao_cidadao: 'Cartão de Cidadão',
    codigo_postal: 'Código postal',
    comentário: 'Comentário',
    confirmar: 'Confirmar',
    contacto_emergencia: 'Identificação do contacto de emergência',
    contacto_telefónico: 'Contacto telefónico',
    contatos: 'Contactos',
    data: 'Data',
    data_interação: 'Data da interação',
    data_nascimento: 'Data de nascimento',
    de: 'de',
    decisão: 'Decisão',
    descrição_apoio: 'Descrição de apoios anteriormente usufruidos',
    disciplina: 'Disciplina',
    dislexia: 'Dislexia/Disortografia/Disgrafia',
    doc_identificação: 'Documento de identificação',
    editar_perfil: 'Editar perfil',
    editar_quantidade_horas_utilizadas: 'Editar quantidade de horas',
    email: 'Email',
    email_principal: 'Email principal',
    email_secundário: 'Email secundário',
    epoca_especial: 'Acesso a épocas especiais de exame',
    esconder: 'Esconder',
    falta_meio_contacto: 'Obrigatório indicar o meio de contacto.',
    feminino: 'Feminino',
    ficheiro: 'Escolha os ficheiros',
    formulario: 'Formulário para o estatuto de ENEE',
    formulário: 'Formulário de candidatura ao estatuto de ENEE',
    gestor_caso: 'Gestor de caso',
    gravar: 'Gravar',
    género: 'Género',
    hora: 'Hora',
    horas_acompanhamento: 'Horas de acompanhamento',
    identificação_estudante: 'Identificação do estudante',
    identificação_responsável: 'Identificação do Responsável pelo ENEE',
    informação: 'Informação',
    indique_local_interação: 'Indique o local da interação',
    local: 'Local',
    local_aulas: 'Adequação na atribuição de local para realização das unidades curriculares de estágio, de educação clínica, de ensino clínico e de práticas pedagógicas',
    localidade: 'Localidade',
    masculino: 'Masculino',
    meio_contacto: 'Meio de Contacto',
    mensagem_estudante: 'O seu pedido ao estatuto de estudante com necessidades educativas especais está no estado de:',
    motivo: 'Motivo',
    motora: 'Motora',
    nome: 'Nome',
    nome_serviço: 'Nome do serviço',
    nome_utilizador: 'Nome de Utilizador',
    numero_estudante: 'Número de estudante',
    opcional: 'Opcional',
    outras_doenças: 'Outras doenças',
    outro: 'Outro',
    palavra_pass: 'Palavra Passe',
    parentesco: 'Parentesco',
    passaporte: 'Passaporte',
    pedido_apoio: 'Pedido apoio',
    pedidos_agendamento_reunião: 'Pedidos de agendamento de reunião',
    pedidos_apoios: 'Pedido de apoios',
    pedir_agendamento_reunião: 'Pedir agendamento de reunião',
    pedir_estatuto: 'Pedir estatuto ENEE',
    pedir_reunião: 'Pedir reunião',
    pedir_serviço: 'Pedir apoio',
    prioridade: 'Prioridade',
    provas_adaptadas: 'Métodos e provas de avaliação adaptados',
    proximo_contacto: 'Próximo contacto',
    próximo: 'Próximo',
    psicológico: 'Doenças do Foro Psicológico, neurológico ou psiquiátrico',
    página: 'Página',
    quantidade_horas: 'Quantidade de horas',
    quantidade_horas_utilizadas: 'Quantidade de horas utilizadas',
    quantidade_horas_total: 'Quantidade de horas total',
    relatorio_medico: 'Relatório médico',
    reprovado: 'Reprovado',
    residencia: 'Residência em época de aulas',
    reuniões: 'Reuniões',
    selecionar_serviço: 'Selecione o serviço',
    semestre: 'Semestre',
    serviço: 'Serviço',
    submeter: 'Submeter',
    tipos_nee: 'Tipos de necessidade educativas especiais',
    visão: 'Visão',
    indique_software_interação: 'Indique o software para a interação',
    selecione_meio_contacto: 'Selecione o meio de contacto',
    biblioteca: 'Biblioteca',
    direção: 'Direção',
    escola: 'Escola',
    numero_identificação: 'Número de Identificação'
  },
  'en': {
    numero_identificação: 'Identification Number',
    escola: 'School Services',
    direção: 'Direction',
    biblioteca: 'Library',
    selecione_meio_contacto: 'Select the contact medium',
    indique_software_interação: 'Designate the software to be used in the interaction',
    a_carregar: 'Loading',
    acompanhamento: 'Custom support',
    acompanhamento_individualizado: 'Individualized support',
    acompanhamento_professor_tutor: 'Support by teacher-tutor',
    adicionar: 'Add',
    adicionar_aluno: 'Add student with ENEE status',
    agendar_reunião: 'Schedule meeting',
    aguardar_aprovação: 'Awaiting evaluation',
    analise_funcional: 'Functional analysis of the problem / Information about implications for academic performance',
    ano: 'year',
    ano_curricular: 'Curricular year',
    ano_matricula: '1st year enrollment',
    anterior: 'Previous',
    aplicacao: 'Software',
    apoio_componente_letiva: 'Support for the teaching component',
    apoio_sala_aula: 'Classroom support',
    apoio_social: 'Social support',
    apoios_usufruidos: 'Supports',
    asperger: 'Asperger Syndrome, Attention Deficit',
    audição: 'Hearing',
    ação: 'Actions',
    bem_vindo: 'Welcome to the plataform 100%IN',
    cancelar: 'Cancel',
    carta_conducao: 'Driving license',
    cartao_cidadao: 'Citizen Card',
    codigo_postal: 'Zip/Postal Code',
    comentário: 'Comment',
    confirmar: 'Confirm',
    contacto_emergencia: 'Identification emergency contact',
    contacto_telefónico: 'Phone contact',
    contatos: 'Contacts',
    data: 'Date',
    data_interação: 'Interaction date',
    data_nascimento: 'Birth date',
    de: 'of',
    decisão: 'Decision',
    descrição_apoio: 'Description of previously used supports',
    disciplina: 'Subject',
    dislexia: 'Dyslexia/Disortography/Dysgraphia',
    doc_identificação: 'Identification document',
    editar_perfil: 'Edit profile',
    editar_quantidade_horas_utilizadas: 'Edit number of hours',
    email: 'Email',
    email_principal: 'Main email',
    email_secundário: 'Secondary email',
    epoca_especial: 'Access to special examination periods',
    esconder: 'Hide',
    falta_meio_contacto: 'Please fill the contact medium.',
    feminino: 'Female',
    ficheiro: 'Choose files',
    formulario: 'Application form for the ENEE statute',
    gestor_caso: 'Case manager',
    gravar: 'Save',
    género: 'Gender',
    hora: 'Time',
    horas_acompanhamento: 'Custom support hours',
    identificação_estudante: 'Student identification',
    identificação_responsável: 'Identification of ENEE Carer',
    informação: 'Information',
    indique_local_interação: 'Designate the interaction local',
    local: 'Local',
    local_aulas: 'Adequacy in the assignment of place for accomplishment of the curricular units of stage, of clinical education, of clinical teaching and of pedagogical practices',
    localidade: 'State/Province/Region',
    masculino: 'Male',
    meio_contacto: 'Contact Medium',
    mensagem_estudante: 'Your request for the status of student with special educational needs is in the state of:',
    motivo: 'Reason',
    motora: 'Motor',
    nome: 'Name',
    nome_serviço: 'Service name',
    nome_utilizador: 'Username',
    numero_estudante: 'Student number',
    outras_doenças: 'Other',
    outro: 'Other',
    palavra_pass: 'Password',
    parentesco: 'Kinship',
    passaporte: 'Passport',
    pedido_apoio: 'Support request',
    pedidos_agendamento_reunião: 'Meeting Scheduling Requests',
    pedidos_apoios: 'Request for support',
    pedir_agendamento_reunião: 'Request meeting',
    pedir_estatuto: 'Request ENEE status',
    pedir_reunião: 'Schedule meeting',
    pedir_serviço: 'Ask for support',
    prioridade: 'Priority',
    provas_adaptadas: 'Appropriate assessment methods and tests',
    proximo_contacto: 'Next contact',
    próximo: 'Next',
    psicológico: 'Psychological, neurological or psychiatric form',
    página: 'Page',
    quantidade_horas: 'Amount of hours',
    quantidade_horas_utilizadas: 'Number of hours used',
    quantidade_horas_total: 'Total number of hours',
    relatorio_medico: 'Medical report',
    reprovado: 'Denied',
    residencia: 'Address during studying period',
    reuniões: 'Meetings',
    selecionar_serviço: 'Select the service you want',
    semestre: 'Semester',
    serviço: 'Service',
    submeter: 'Submit',
    tipos_nee: 'Types of special educational needs',
    visão: 'Vision',
    opcional: 'Optional',
  },
};

const i18n = new VueI18n({
  locale: 'pt', // set locale
  fallbackLocale: 'en', // set fallback locale
  messages, // set locale messages
});

export default i18n;

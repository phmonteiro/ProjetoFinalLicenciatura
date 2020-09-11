import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

const messages = {
  'pt': {
    a_carregar: 'A carregar',
    aceitar: 'Aceitar',
    acompanhamento: 'Cartão de Horas de Apoio',
    acompanhamento_individualizado: 'Acompanhamento individualizado',
    acompanhamento_professor_tutor: 'Acompanhamento por professor tutor',
    adicionar: 'Adicionar',
    adicionar_aluno: 'Adicionar aluno com estatuto de ENE',
    agendar_reunião: 'Agendar reunião',
    aguardar_aprovação: 'A aguardar avaliação',
    analise_funcional: 'Análise funcional do problema / Informação sobre implicações no desempenho académico',
    ano: 'ano',
    ano_curricular: 'Ano curricular',
    ano_matricula: 'Ano da 1ª matrícula',
    anterior: 'Anterior',
    aplicacao: 'Aplicação',
    aprovado: 'Aprovado',
    apoio_componente_letiva: 'Apoio à componente letiva',
    apoio_sala_aula: 'Apoios em sala de aula',
    apoio_social: 'Apoio social',
    apoios_usufruidos: 'Apoios usufruídos',
    asperger: 'Síndrome de Asperger, Deficit atenção',
    audição: 'Audição',
    ação: 'Ações',
    bem_vindo: 'Bem-vindo à Plataforma 100%IN',
    cancelar: 'Cancelar',
    carta_conducao: 'Carta de condução',
    contactos_vazio: 'Ainda não tem nenhum contacto registado na plataforma.',
    cartao_cidadao: 'Cartão de Cidadão',
    codigo_postal: 'Código postal',
    comentário: 'Comentário',
    confirmar: 'Confirmar',
    contacto_emergencia: 'Identificação do Contacto de Emergência',
    contacto_telefónico: 'Contacto telefónico',
    contatos: 'Contactos',
    data: 'Data',
    data_interação: 'Data da interação',
    data_nascimento: 'Data de nascimento',
    de: 'de',
    decisão: 'Decisão',
    descrição_apoio: 'Descrição de apoios anteriormente usufruídos',
    disciplina: 'Disciplina',
    dislexia: 'Dislexia/Disortografia/Disgrafia',
    doc_identificação: 'Documento de identificação',
    editar_perfil: 'Editar perfil',
    em_avaliação: 'Em avaliação',
    email: 'Email',
    email_principal: 'Email principal',
    email_secundário: 'Email secundário',
    epoca_especial: 'Acesso a épocas especiais de exame',
    esconder: 'Esconder',
    falta_meio_contacto: 'Obrigatório indicar o meio de contacto.',
    feminino: 'Feminino',
    ficheiro: 'Escolha os ficheiros',
    formulario: 'Formulário para o estatuto de ENE',
    formulário: 'Formulário de candidatura ao estatuto de ENE',
    gestor_caso: 'Gestor de caso',
    gravar: 'Gravar',
    género: 'Género',
    hora: 'Hora',
    horas_acompanhamento: 'Horas de acompanhamento',
    identificação_estudante: 'Identificação do estudante',
    identificação_responsável: 'Identificação do Responsável pelo ENE (Opcional)',
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
    palavra_pass: 'Palavra-passe',
    parentesco: 'Parentesco',
    passaporte: 'Passaporte',
    pedido_apoio: 'Pedido de apoio',
    pedidos_agendamento_reunião: 'Pedidos de agendamento de reunião',
    pedidos_apoios: 'Pedido de apoio',
    pedir_agendamento_reunião: 'Pedir agendamento de reunião',
    pedir_estatuto: 'Pedir estatuto ENE',
    pedir_reunião: 'Pedir reunião',
    pedir_serviço: 'Pedir apoio',
    prioridade: 'Prioridade',
    info_documents: 'Pode anexar aqui os relatórios médicos e outros documentos relevantes para a apreciação do seu pedido',
    provas_adaptadas: 'Métodos e provas de avaliação adaptados',
    proximo_contacto: 'Próximo contacto',
    próximo: 'Próximo',
    psicológico: 'Doenças do Foro Psicológico, neurológico ou psiquiátrico',
    página: 'Página',
    quantidade_horas: 'Quantidade de horas',
    quantidade_horas_utilizadas: 'Quantidade de horas utilizadas',
    quantidade_horas_total: 'Quantidade de horas total',
    documentacao_comprovativa: 'Documentação comprovativa',
    reprovado: 'Reprovado',
    residencia: 'Residência em época de aulas',
    reuniões: 'Reuniões',
    selecionar_serviço: 'Selecione o serviço',
    semestre: 'Semestre',
    serviço: 'Serviço',
    submeter: 'Submeter',
    tipos_nee: 'Tipos de necessidades específicas',
    visão: 'Visão',
    indique_software_interação: 'Indique o software para a interação',
    selecione_meio_contacto: 'Selecione o meio de contacto',
    biblioteca: 'Biblioteca',
    direção: 'Direção',
    escola: 'Escola',
    numero_identificação: 'Número de Identificação',
    detalhes: 'Detalhes',
    fechar: 'Fechar',
    quantidade_horas_disponiveis: 'Quantidade de horas disponíveis',
    quantidade_horas_por_uc: 'Horas de apoio por UC',
    renovar_estatuto: 'Renovar estatuto',
    aviso_transferencia_conta: 'Atenção: Ao continuar estará a transferir todo o historial e informação da sua antiga conta para uma nova conta. A conta antiga será desativada e ficará inacessível. Todas as funcionalidades continuarão disponíveis na nova conta.',
    continuar: 'Aceitar e continuar',
    transferir_conta: 'Transferir Conta',
    aviso_deteçao_conta: 'Detetámos já haver uma conta sua no nosso sistema. Devido a que cada aluno pode apenas ter uma conta ativa, sugerimos que realize o processo de transferência de conta. Este processo consiste em migrar todos os seus dados para esta sua nova conta que daqui em diante deverá usar para aceder à plataforma 100%IN.',
    transferencia_sucesso: 'Transferência de conta concluída com sucesso.',
    actualizar_dados: 'Actualizar Dados',
    mensagem_renovar_estatuto: 'O seu estatuto expirou, por favor inicie o processo de renovação de estatuto',
    reunioes_vazio: 'Ainda não tem nenhuma reunião registada na plataforma.',
    solicitar_horas_apoio: 'Solicitar horas de apoio',
    pedidos_horas_apoio: 'Pedidos de Horas de Apoio',
    ver_decisao: 'Ver Decisão',
    relatorio_medico: 'Documentos Comprovativos',
    msg_diretor_estatuto_rejeitado: 'O seu pedido de estatuto anterior foi rejeitado devido a: ',
    sem_inscricoes: 'Não está inscrito em quaisquer Unidades Curriculares.',
    sem_pedidos_apoio: 'Não existem pedidos de horas de apoio.',
    justificacao_rejeicao: 'Justificação da rejeição',
  },
  'en': {
    justificacao_rejeicao: 'Refusal reason',
    sem_pedidos_apoio: 'You have no support requests at the moment',
    sem_inscricoes: 'You are not registered in any subjects at the moment',
    aceitar: 'Accept',
    pedidos_horas_apoio: 'Support hours requests',
    solicitar_horas_apoio: 'Request support hours',
    msg_diretor_estatuto_rejeitado: 'Your last request was rejected due to:',
    relatorio_medico: 'Medical Report',
    reunioes_vazio: 'You don\'t have any meeting registered on the platform.',
    mensagem_renovar_estatuto: 'Your status has expired, please renew it.',
    actualizar_dados: 'Update Account',
    transferencia_sucesso: 'Account has been successfully transferred',
    aviso_deteçao_conta: 'We\'ve detected that you are already registered on our system. Since a student can only have one active account, we suggest that you to complete the account transfer. From now on you must access this platform with your new account.',
    transferir_conta: 'Transfer Account',
    continuar: 'Accept and proceed',
    aviso_transferencia_conta: 'Warning: By proceeding you\'ll be transferring all the history and information of your old account to a new one. The old account will be deactivated and it will become inaccessible. All the previous functionalities will remain on the new account.',
    renovar_estatuto: 'Renew status',
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
    adicionar_aluno: 'Add student with ENE status',
    agendar_reunião: 'Schedule meeting',
    aguardar_aprovação: 'Awaiting evaluation',
    analise_funcional: 'Functional analysis of the problem / Information about implications for academic performance',
    ano: 'year',
    ano_curricular: 'Curricular year',
    ano_matricula: 'First enrollment year',
    anterior: 'Previous',
    aplicacao: 'Software',
    apoio_componente_letiva: 'Class curriculum support',
    apoio_sala_aula: 'Classroom support',
    apoio_social: 'Social support',
    apoios_usufruidos: 'Supports',
    asperger: 'Asperger Syndrome, Attention Deficit',
    audição: 'Hearing',
    ação: 'Actions',
    bem_vindo: 'Welcome to the 100%IN Platform',
    cancelar: 'Cancel',
    carta_conducao: 'Driving license',
    cartao_cidadao: 'Citizen Card',
    codigo_postal: 'Zip/Postal Code',
    comentário: 'Comment',
    confirmar: 'Confirm',
    contactos_vazio: 'You don\'t have any contact registered on the platform.',
    contacto_emergencia: 'Identification Emergency Contact',
    contacto_telefónico: 'Phone contact',
    contatos: 'Contacts',
    data: 'Date',
    data_interação: 'Interaction date',
    data_nascimento: 'Birth date',
    de: 'of',
    decisão: 'Decision',
    detalhes: 'Details',
    descrição_apoio: 'Description of previously used supports',
    disciplina: 'Subject',
    dislexia: 'Dyslexia/Disortography/Dysgraphia',
    doc_identificação: 'Identification document',
    editar_perfil: 'Edit profile',
    em_avaliação: 'Evaluating',
    email: 'Email',
    email_principal: 'Main email',
    email_secundário: 'Secondary email',
    epoca_especial: 'Access to special examination periods',
    esconder: 'Hide',
    falta_meio_contacto: 'Please fill the contact medium.',
    feminino: 'Female',
    fechar: 'Close',
    ficheiro: 'Choose files',
    formulario: 'Application form for the ENE statute',
    gestor_caso: 'Case manager',
    gravar: 'Save',
    género: 'Gender',
    hora: 'Time',
    horas_acompanhamento: 'Custom support hours',
    identificação_estudante: 'Student identification',
    identificação_responsável: 'Identification of ENE Carer (Optional)',
    informação: 'Information',
    indique_local_interação: 'Designate a place',
    local: 'Local',
    local_aulas: 'Adequacy in the assignment of place for accomplishment of the curricular units of stage, of clinical education, of clinical teaching and of pedagogical practices',
    localidade: 'State/Province/Region',
    masculino: 'Male',
    meio_contacto: 'Contact Medium',
    mensagem_estudante: 'The state of your request for the status of student with special educational needs is:',
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
    pedir_estatuto: 'Request ENE status',
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
    documentacao_comprovativa: 'Attestation documents',
    info_documents: 'You can upload here your medical files or any other atestation documents',
    reprovado: 'Denied',
    residencia: 'Address during studying period',
    reuniões: 'Meetings',
    selecionar_serviço: 'Select the service you want',
    semestre: 'Semester',
    serviço: 'Service',
    submeter: 'Submit',
    tipos_nee: 'Types of specific needs',
    visão: 'Vision',
    ver_decisao: 'See decision',
    opcional: 'Optional',
    quantidade_horas_disponiveis: 'Available support hours',
    quantidade_horas_por_uc: 'Available hours by each subject',
  },
};

const i18n = new VueI18n({
  locale: 'pt', // set locale
  fallbackLocale: 'en', // set fallback locale
  messages, // set locale messages
});

export default i18n;

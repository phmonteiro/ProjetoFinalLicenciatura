<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Histórico do aluno {{$user->name}}</title>
</head>

<body class="mainPdf">
    <h4>Histórico do Aluno {{$user->name}}</h4>
    <p>Nome: {{$user->name}}</p>
    <p>Número de estudante {{$user->number}}</p>
    <p>Data de nascimento: {{$user->birthDate}}</p>
    <p>Documento de identificação: {{$user->identificationDocument}} {{$user->numberDocument}}</p>
    <p>Número de identificação fiscal: {{$user->nif}}</p>
    <p>Número de identificação da Segurança Social: {{$user->niss}}</p>
    <p>Curso do estudante: {{$user->course}}</p>
    <p>Tipo de necessidade especial: {{$user->neeType}}</p>
    <p>Percentagem de incapacidade: {{$user->neeSeverity}}</p>
    <p>Tutor: {{$tutor}}</p>
    <p>Gestor de caso: {{$caseManager}}</p>
</body>

</html>
<style>
    html body {
        font-family: 'Montserrat', sans-serif;
    }

    .mainPdf {
        background-color: #6c757d;
        color: black;
    }
</style>
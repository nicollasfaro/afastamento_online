<?php defined('_JEXEC') or die; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js/minified/introjs.min.css">
<script src="https://cdn.jsdelivr.net/npm/intro.js/minified/intro.min.js"></script>

<?php
// Verifica se o usuário está logado usando o objeto JFactory
$user = JFactory::getUser();
?>

<style>
    /* Estilos gerais do formulário */
    form {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    }

    form label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }

    form input[type="text"],
    form input[type="date"],
    form select,
    form textarea {
        width: 100%;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 14px;
        color: #333;
        height: 30px;
    }

    form textarea {
        resize: vertical;
        min-height: 80px;
    }

    form input[type="submit"],
    form button {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        color: #fff;
    }

    form input[type="submit"] {
        background-color: #28a745;
        margin-bottom: 10px;
    }

    form button {
        background-color: #007bff;
    }

    form a button {
        margin-top: 5px;
        background-color: #007bff;
    }

    /* Hover effects */
    form input[type="submit"]:hover {
        background-color: #218838;
    }

    form button:hover {
        background-color: #0069d9;
    }

    /* Estilo do link de download */
    form a {
        text-decoration: none;
    }

    /* Estilo para as abas */
    .tab-container {
        display: flex;
        cursor: pointer;
        justify-content: center;
        margin-bottom: 20px;
    }

    .tab {
        padding: 10px 20px;
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        border-bottom: none;
        margin-right: 5px;
        border-radius: 8px 8px 0 0;
    }

    .tab.active {
        background-color: #ffffff;
        font-weight: bold;
        color: #333;
    }

    .tab-content {
        display: none;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 0 0 8px 8px;
    }

    .tab-content.active {
        display: block;
    }

    .error-message {
        color: red;
        font-size: 0.9em;
        display: none;
        /* A mensagem de erro começa oculta */
    }


    .sem-retorno {
        background-color: #ffcccc;
        /* Cor de fundo vermelho claro */
        font-weight: bold;
        /* Texto em negrito */
    }

    .alerta-retorno {
        color: #d9534f;
        /* Cor de alerta - vermelho escuro */
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .alerta-retorno::before {
        content: '⚠️';
        /* Ícone de alerta */
        margin-right: 5px;
        /* Espaço entre ícone e texto */
        font-size: 1.2em;
        /* Tamanho do ícone */
    }

    .nao-visualizado {
        font-weight: bold;
        background-color: #f9f9e3;
        /* Cor de fundo clara para destacar */
    }

    .marcar-lido {
        font-size: 1rem;
        color: #007bff;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: transform 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        padding: 5px;
        border-radius: 5px;
        /* Adiciona cantos arredondados */
    }

    .marcar-lido:hover {
        color: #0056b3;
        transform: scale(1.1);
    }

    span.lido {
        font-size: 1rem;
        color: green;
        font-weight: bold;
    }

    .visualizar:hover {
        text-decoration: underline;
        cursor: pointer;
    }

    #paginacao button {
        padding: 8px 12px;
        background-color: #007bff;
        ;
        border: none;
        border-radius: 5px;
        color: #ecf0f1;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #paginacao button:disabled {
        background-color: #3b4a57;
        cursor: not-allowed;
        font-weight: normal;
    }

    #paginacao button:hover:not(:disabled) {
        background-color: rgb(204, 204, 204);
        color: #007bff;
    }

    #paginacao button.pagina-ativa {
        background-color: rgb(1, 61, 126);
        color: rgb(255, 255, 255);
        font-weight: bold;
        cursor: default;
    }

    #assistente-virtual {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #assistente-virtual {
        transition: transform 0.2s;
    }

    #assistente-virtual:hover {
        transform: scale(1.1);
        /* Efeito de zoom ao passar o mouse */
    }

    #modal-assistente {
        font-family: Arial, sans-serif;
    }

    #modal-assistente h3 {
        margin: 0 0 10px 0;
        font-size: 18px;
        color: #333;
    }

    /* Estilo do overlay */
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Estilo do spinner */
    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .destaque-retorno {
        background-color: rgb(145, 21, 21);
        /* Fundo vermelho claro */
        font-weight: bold;
        /* Texto em negrito */
        color: white;
    }


    /* Estilização básica da tabela */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }

    thead {
        background-color: #007bff;
        color: #fff;
        text-transform: uppercase;
        font-size: 14px;
    }

    thead th {
        padding: 12px;
    }

    tbody td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #dee2e6;
    }

    tbody tr:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }

    tbody tr.destaque-retorno {
        background-color: #f8d7da;
        /* Vermelho claro */
        color: #721c24;
        font-weight: bold;
    }

    /* Modal estilos */
    .modal-personalizado {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        /* Fundo escuro com opacidade */
    }

    .modal-conteudo {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        /* Largura do modal */
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .fechar {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .fechar:hover,
    .fechar:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Animação para o modal */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/9.1.1/shepherd.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/shepherd.js/9.1.1/shepherd.min.js"></script>

<div id="loading-overlay" style="display: none;">
    <div class="spinner"></div>
</div>

<div class="tab-container" style="
    margin-bottom: -20px;
">
    <div class="tab active" id="afastamento" onclick="openTab('ferias')">Afastamento</div>
    <div class="tab" id="retorno" onclick="openTab('retornotab')">Retorno</div>
    <div class="tab" id="consulta" onclick="openTab('consultatab')">Consulta</div>
    <?php if (!$user->guest): ?>
        <div class="tab" onclick="openTab('consulta_adm')">Consulta ADM</div>
    <?php endif; ?>
</div>

<div id="ferias" class="tab-content active">
    <button id="iniciar-tour"
        style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
        Iniciar Tour novamente
    </button>
    <!-- Formulário de Solicitação de Férias (já existente) -->
    <form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="solicitacao_ferias_form">

        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" onblur="buscarDadosPorCPF()" required>
        <span id="cpf-error" class="error-message">CPF inválido</span><br>

        <label for="nome_completo">Nome Completo:</label>
        <input type="text" id="nome_completo" name="nome" required>

        <label for="nome_guerra">Nome de Guerra:</label>
        <input type="text" id="nome_guerra" name="nome_guerra" required>

        <label for="situacao">Situação:</label>
        <div id="situacao" style="display:flex">
            <label style="margin-right:10px">
                <input type="radio" name="situacao" value="ATIVA"> ATIVA
            </label>
            <label>
                <input type="radio" name="situacao" value="PTTC" required> PTTC
            </label>
        </div>

        <label for="posto_grad">Posto/Grad:</label>
        <select id="posto_grad" name="pg" required>
            <option value="Gen Ex">Gen Ex</option>
            <option value="Gen Div">Gen Div</option>
            <option value="Gen Bda">Gen Bda</option>
            <option value="Cel">Cel</option>
            <option value="Ten Cel">Ten Cel</option>
            <option value="Maj">Maj</option>
            <option value="Cap">Cap</option>
            <option value="1º Ten">1º Ten</option>
            <option value="2º Ten">2º Ten</option>
            <option value="Asp OF">Asp OF</option>
            <option value="Sub Ten">Sub Ten</option>
            <option value="1º Sgt">1º Sgt</option>
            <option value="2º Sgt">2º Sgt</option>
            <option value="3º Sgt">3º Sgt</option>
            <option value="TM">TM</option>
            <option value="T1">T1</option>
            <option value="T2">T2</option>
            <option value="Cb">Cb</option>
            <option value="Sd">Sd</option>
        </select>

        <label for="secao">Seç/Asse/Emp:</label>
        <select name="sec" id="secao" required>
            <option value="AAAJ">AAAJ</option>
            <option value="AACIA">AEL</option>
            <option value="ACS">ARICS</option>
            <option value="ADAE">ADAE</option>
            <option value="AGG">AGG</option>
            <option value="AGE">AGE</option>
            <option value="AI">AI</option>
            <option value="Alog">ALog</option>
            <option value="ARH">ARH</option>
            <option value="Assessoria Doutrina">Assessoria Doutrina</option>
            <option value="Assessoria Especial">Assessoria Especial</option>
            <option value="AEHMil">AEHMil</option>
            <option value="EMP Chefia">EMP Chefia</option>
            <option value="EMP Vice-Chefia">EMP Vice-Chefia</option>
            <option value="DTI">DTI</option>
            <option value="CADESM">CADESM</option>
            <option value="Chefia do DECEx">Chefia do DECEx</option>
            <option value="Chefia de Gabinete">Chefia de Gabinete</option>
            <option value="Escr e Repr Téc DECEx/Brasília">Escr e Repr Téc DECEx/Brasília</option>
            <option value="EPjt">EPjt</option>
            <option value="FUNCEB">FUNCEB</option>
            <option value="Div Pes">Div Pes</option>
            <option value="Div Pes / Protocolo">Div Pes / Protocolo</option>
            <option value="Div Pes / SPP">Div Pes / SPP</option>
            <option value="Div Adm">Div Adm</option>
            <option value="Div Adm / Fisc Adm">Div Adm / Fisc Adm</option>
            <option value="Div Adm / Almox">Div Adm / Almox</option>
            <option value="Div Adm / Dairias e Passagens">Div Adm / Dairias e Passagens</option>
            <option value="Div Adm / Conformidade">Div Adm / Conformidade</option>
            <option value="Div Adm / SALC">Div Adm / SALC</option>
            <option value="Div Adm / Financeiro">Div Adm / Financeiro</option>
            <option value="Div Adm / OD">Div Adm / OD</option>
            <option value="Vice-Chefia do DECEx">Vice-Chefia do DECEx</option>
            <option value="SISO">SISO</option>
        </select>



        <label for="tipo_afastamento">Tipo de Afastamento:</label><br>
        <div id="tipo_afastamento" style="margin-top: -15px;">
            <label>
                <input type="radio" name="tipo_afastamento" value="Dispensas" onclick="verificarTipoDispensa()">
                Dispensas
            </label>
            <label>
                <input type="radio" name="tipo_afastamento" value="Férias" onclick="verificarTipoDispensa()" required>
                Férias
            </label>
            <label>
                <input type="radio" name="tipo_afastamento" value="Baixa Hospitalar" onclick="verificarTipoDispensa()">
                Baixa Hospitalar
            </label>
            <label>
                <input type="radio" name="tipo_afastamento" value="Missão" onclick="verificarTipoDispensa()"> Missão
            </label>
            <label>
                <input type="radio" name="tipo_afastamento" value="Núpcias" onclick="verificarTipoDispensa()"> Núpcias
            </label>
            <label>
                <input type="radio" name="tipo_afastamento" value="Licenças" onclick="verificarTipoDispensa()"> Licenças
            </label>
            <label>
                <input type="radio" name="tipo_afastamento" value="Luto" onclick="verificarTipoDispensa()"> Luto
            </label>
            <label>
                <input type="radio" name="tipo_afastamento" value="Instalação" onclick="verificarTipoDispensa()">
                Instalação
            </label>
        </div>

        <div style="margin-top: 15px;">
            <label for="data_inicio">Data de Início de Afastamento:</label>
            <input type="text" id="data_inicio" name="data_inicio" placeholder="DD/MM/AAAA" required>


        </div>

        <!-- Adicionado à área de Tipo de Afastamento -->
        <div id="saida_guarnicao_container" style="display: none; margin-top: 15px;">
            <label>Saída de guarnição:</label><br>
            <label style="margin-top: -15px;">
                <input type="radio" name="saida_guarnicao" value="Sim" id="saida_guarnicao_sim"> Sim
            </label>
            <label>
                <input type="radio" name="saida_guarnicao" value="Não" id="saida_guarnicao_nao"> Não
            </label>
        </div>

        <div id="destino_container" style="display: none; margin-top: 15px;">
            <label for="destino">Destino:</label>
            <input type="text" id="destino" name="destino" placeholder="Informe o destino">
        </div>
        <div id="tipo_dispensa_container" style="display: none; margin-top: 10px;">
            <label for="tipo_dispensa">Tipo de Dispensa:</label>
            <select id="tipo_dispensa" name="tipo_dispensa" onchange="verificarTipoDispensa()">
                <option value="">Selecione...</option>
                <option value="Desconto em férias">Desconto em férias</option>
                <option value="Como recompensa">Como recompensa</option>
                <option value="Medica">Médica</option>
            </select>
        </div>
        <div id="tipo_dispensa_licenca" style="display: none; margin-top: 10px;">
            <label for="tipo_licenca">Tipo de Licença:</label>
            <select id="tipo_licenca" name="tipo_licenca" onchange="verificarTipoDispensa()">
                <option value="">Selecione...</option>
                <option value="Gestante">Gestante</option>
                <option value="Paternidade">Paternidade</option>
                <option value="Lactante">Lactante</option>
                <option value="LTSP">LTSP</option>
                <option value="LTSPF">LTSPF</option>
                <option value="LAC">LAC</option>
            </select>
        </div>
        <!-- Campo para anexar Documento de Concessão -->
        <div id="documento_concessao_container" style="display: none;">
            <label for="documento_concessao">Documento de Concessão:</label>
            <input type="file" id="documento_concessao" name="documento_concessao" accept=".pdf,.jpg,.jpeg,.png">
        </div>

        <br>
        <div style="display: flex">
            <!-- Novo campo "Número de dias" que será exibido somente quando "Férias" for selecionado -->
            <div id="numero_dias_container" style="margin-top: -15px; display: none;">
                <label for="numero_dias">Número de dias:</label>
                <div id="numero_dias_wrapper">
                    <!-- Este conteúdo será alternado dinamicamente -->
                </div>
            </div>
            <div id="periodo_container" style="display: none; margin-top: -15px; margin-left: 15px;">
                <label for="parcela">Selecione a Parcela:</label>
                <select id="parcela15" name="parcela">
                    <option value="">Selecione...</option>
                    <option value="1ª Parcela">1ª Parcela</option>
                    <option value="2ª Parcela">2ª Parcela</option>
                </select>
                <select id="parcela10" name="parcela">
                    <option value="">Selecione...</option>
                    <option value="1ª Parcela">1ª Parcela</option>
                    <option value="2ª Parcela">2ª Parcela</option>
                    <option value="3ª Parcela">3ª Parcela</option>
                </select>
            </div>
        </div>
        <div id="ano_referencia_container" style="display: none; margin-top: 15px;">
            <label for="ano_referencia">Ano de Referência:</label>
            <select id="ano_referencia" name="ano_referencia">
                <option value="">Selecione o ano</option>
                <?php
                $anoAtual = date('Y');
                for ($i = $anoAtual - 5; $i <= $anoAtual + 1; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
        </div>
        <!-- Campos adicionais que serão habilitados PTTC -->
        <div id="campos_adicionais" style="display: none;">
            <label for="nomeacao_data">Nomeação a contar de (Data):</label>
            <input type="text" id="nomeacao_data" name="nomeacao_data" placeholder="DD/MM/AAAA"><br>

            <label for="periodo_total_nomeacao">Período total de nomeação (Data de/para):</label>
            <div style="display:flex">
                <input style="margin-right: 5px" type="text" id="periodo_total_nomeacao_de"
                    name="periodo_total_nomeacao_de" placeholder="DD/MM/AAAA"> até
                <input style="margin-left: 5px" type="text" id="periodo_total_nomeacao_para"
                    name="periodo_total_nomeacao_para" placeholder="DD/MM/AAAA"><br>
            </div>

            <label for="periodo_aquisitivo_ferias">Período/ano aquisitivo de férias solicitado:</label>
            <select id="periodo_aquisitivo_ferias" name="periodo_aquisitivo_ferias">
                <option value="">Selecione...</option>
                <option value="Primeiro Período">Primeiro Período</option>
                <option value="Segundo Período">Segundo Período</option>
            </select>
        </div>

        <div id="data_apresentacao_div" style="display: none;">
            <p style="color: red; font-weight: bold;">Data de Término e Apresentação são somadas automaticamente
                após inserir o número de dias.</p>
            <label for="data_fim">Data de Término:</label>
            <input type="text" id="data_fim" name="data_fim" placeholder="DD/MM/AAAA" readonly required>

            <label for="data_apresentacao">Data de Apresentação:</label>
            <input type="text" id="data_apresentacao" name="data_apresentacao" placeholder="DD/MM/AAAA" readonly required>
        </div>

        <label for="observacao">Observação:</label>
        <textarea id="observacao" name="observacao"
            placeholder="No caso de Dispensas ou Licenças, informar."></textarea>

        <!-- Botões de Envio e Download CSV -->
        <input type="submit" value="Enviar Solicitação">
        <?php if (!$user->guest): ?>

            <button type="button" onclick="gerarRelatorio()"
                style="margin-top: 10px; padding: 10px; font-size: 16px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
                Baixar Relatório CSV
            </button>

        <?php else: ?>
            <p>Caso seja usuário autorizado, faça login para baixar o relatório.</p>
        <?php endif; ?>
        <input type="hidden" name="option" value="com_ajax">
        <input type="hidden" name="module" value="solicitacao_ferias">
        <input type="hidden" name="format" value="json">
    </form>
</div>

<div id="retornotab" class="tab-content">
    <!-- Formulário de Retorno -->
    <form id="retorno_form" action="<?php echo JRoute::_('index.php'); ?>" onsubmit="registrarRetorno(event)"
        name="retorno_form">
        <label for="cpf_retorno">CPF:</label>
        <input type="text" id="cpf_retorno" name="cpf_retorno" onblur="buscarRegistrosSemRetorno()" required><br>
        <span id="cpf-retorno-error" class="error-message">CPF inválido</span><br>

        <!-- Container para exibir registros sem retorno -->
        <div id="registros-sem-retorno-container" style="margin-top: -30px;margin-bottom: 15px;"></div>

        <input type="submit" id="btn-registrar-retorno" value="Registrar Retorno" disabled>

    </form>
</div>

<div id="consultatab" class="tab-content">
    <form id="consulta_form" onsubmit="event.preventDefault(); consultarCPF();">
        <label for="cpf_consulta">CPF para Consulta:</label>
        <input type="text" id="cpf_consulta" name="cpf_consulta" required>
        <span id="cpf-error" class="error-message">CPF inválido</span><br>

        <button type="button" onclick="consultarCPF()">Consultar</button>
    </form>
    <div id="consulta_resultado">
        <!-- Aqui será renderizada a tabela -->
    </div>

    <!-- Modal para detalhes -->
    <div id="modal-detalhes2" class="modal-personalizado" style="display: none;">
        <div class="modal-conteudo">
            <div id="modal-body"></div>
            <button onclick="fecharModal()"
                style="margin-top: 10px; padding: 10px; font-size: 16px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer">Fechar</button>
        </div>
    </div>

</div>

<div id="consulta_adm" class="tab-content">
    <div>
        <form id="filtro-form">
            <div style="display: flex ;justify-content: space-between;">
                <label for="mes">Mês:</label>
                <select id="mes" name="mes" style="width: 40%;">
                    <option value="">Todos</option>
                    <option value="1">Janeiro</option>
                    <option value="2">Fevereiro</option>
                    <option value="3">Março</option>
                    <option value="4">Abril</option>
                    <option value="5">Maio</option>
                    <option value="6">Junho</option>
                    <option value="7">Julho</option>
                    <option value="8">Agosto</option>
                    <option value="9">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>
                    <!-- Adicione todos os meses -->
                </select>

                <label for="ano">Ano:</label>
                <input type="number" id="ano" name="ano" placeholder="Ano" style="width: 40%;" min="1900" max="2100">
            </div>

            <label for="nome_cpf" style="display: block;">
                Nome:
                <input type="text" id="nome" name="nome" placeholder="Digite o Nome" style="width: 100%;">
            </label>
            <label for="nome_cpf" style="display: block;">
                CPF:
                <input type="text" id="nome_cpf" name="nome_cpf" placeholder="Digite o CPF" style="width: 100%;">
            </label>
            <!-- Novo filtro: apenas registros não visualizados -->
            <label for="filtro_visualizados" style="margin-bottom: 15px; text-align: end;">
                <input type="checkbox" id="filtro_visualizados" name="filtro_visualizados" value="1">
                Apenas não visualizados
            </label>

            <button type="button" onclick="carregarRegistros()">Filtrar</button>
        </form>

        <table id="registros-tabela" style="margin-top: 15px; width: 100%;">
            <thead>
                <tr>
                    <th>Nome Completo</th>
                    <th>CPF</th>
                    <th>Posto/Grad</th>
                    <th>Tipo de Afastamento</th>
                    <th>Data de Início</th>
                    <th>Data de Término</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <!-- Registros serão preenchidos via JavaScript -->
            </tbody>
        </table>
        <div id="paginacao" style="margin-top: 10px; display: flex; justify-content: center; gap: 5px;"></div>
    </div>
</div>

<div id="modal-detalhes"
    style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background: white; border: 1px solid #ccc; padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); width: 50%;">
    <div id="registro-detalhes"></div>
    <button onclick="fecharModal()"
        style="margin-top: 10px; padding: 10px; font-size: 16px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer">Fechar</button>
</div>
<div id="modal-overlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999;"
    onclick="fecharModal()"></div>

<!-- Botão do Assistente Virtual -->
<!-- <button id="assistente-virtual" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
<img src="/images/assistente.png" alt="Assistente Virtual" style="width: 30px; height: 30px; color: white" />
</button> -->

<!-- Modal do Assistente -->
<div id="modal-assistente"
    style="display: none; position: fixed; bottom: 80px; right: 20px; width: 300px; height: 400px; background: white; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); z-index: 1000;">
    <div style="padding: 10px; overflow-y: auto; height: calc(100% - 80px);">
        <h3 style="position: fixed;
  margin-top: -50px;">Assistente Virtual</h3>
        <div id="conteudo-assistente">
            <p><strong>Bem-vindo ao Assistente Virtual do Apresentação Online!</strong></p>
            <p>Digite palavras-chave como <em>Afastamento</em>, <em>Retorno</em>, ou <em>Consulta</em> para encontrar
                perguntas frequentes relacionadas.</p>
        </div>
    </div>
    <div style="display: flex; align-items: center; padding: 10px; border-top: 1px solid #ccc;">
        <input type="text" id="entrada-usuario" placeholder="Como posso ajudar?"
            style="flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        <button onclick="enviarPergunta()" style="
    margin-top: -10px;
    margin-left: 10px;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    color: white;
    background-color: #218838;
    font-weight: bold;
    border: none;
">Enviar</button>
    </div>
</div>

<script>



    document.addEventListener('DOMContentLoaded', function () {

        document.querySelector('form[name="solicitacao_ferias_form"]').addEventListener('submit', function (event) {
            event.preventDefault(); // Evita o envio automático do formulário
            const cpfInput = document.getElementById('cpf');
            const cpfError = document.getElementById('cpf-error');

            // Exibe o overlay de carregamento
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.style.display = 'flex';

            // Se o CPF for inválido, impede o envio do formulário e exibe a mensagem de erro
            if (!atualizarStatusCPF(cpfInput, cpfError)) {
                event.preventDefault(); // Impede o envio do formulário
                alert("Por favor, corrija o CPF inválido antes de enviar.");
                return; // Sai da função para garantir que o envio seja interrompido
            }
            event.preventDefault();

            const formData = new FormData(this);
            formData.append('option', 'com_ajax');
            formData.append('module', 'solicitacao_ferias'); // Mesmo módulo para ambos os formulários
            formData.append('format', 'json');
            formData.append('task', 'register_leave'); // Define task para "Solicitação de Férias"

            // Log para ver os dados no console do navegador
            // console.log("Dados enviados:", Object.fromEntries(formData));

            fetch("<?php echo JRoute::_('index.php'); ?>", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json()) // Converte a resposta para JSON
                .then(data => {
                    // Oculta o overlay de carregamento
                    loadingOverlay.style.display = 'none';
                    // console.log(data);
                    // Exibe a resposta completa no console para depuração
                    // console.log("Resposta do servidor:", data.data); 
                    if (data && data.success && data.data.message === "Já existe uma solicitação de Férias para este CPF sem registro de retorno.") {
                        alert("CPF já possui registro de férias, porém sem registro de retorno. Favor efetuar retorno das Férias.");
                    }
                    else if(data && data.success && data.data.message === "O Documento de Concessão é obrigatório para Dispensa Médica e Baixa Hospitalar."){
                        alert(data.data.message);
                    }
                    // Verifica se a resposta JSON contém a mensagem de sucesso
                    else if (data && data.success && data.data.message) {
                        alert(data.data.message);  // Exibe a mensagem de sucesso retornada do servidor
                        document.querySelector('form[name="solicitacao_ferias_form"]').reset(); // Reseta o formulário
                        window.location.reload()
                    } else {
                        alert("Enviado com sucesso"); // Mensagem alternativa se a estrutura do JSON for inesperada
                        document.querySelector('form[name="solicitacao_ferias_form"]').reset(); // Reseta o formulário
                    }
                })
                .catch(error => {
                    console.error("Erro:", error);
                    // Oculta o overlay de carregamento em caso de erro
                    loadingOverlay.style.display = 'none';
                    alert("Houve um problema ao enviar a solicitação.");
                });
        });

        document.querySelector('form[name="retorno_form"]').addEventListener('submit', function (event) {
            const cpfRetornoInput = document.getElementById('cpf_retorno');
            const cpfRetornoError = document.getElementById('cpf-retorno-error');

            // Se o CPF for inválido, impede o envio do formulário e exibe a mensagem de erro
            if (!atualizarStatusCPF(cpfRetornoInput, cpfRetornoError)) {
                event.preventDefault(); // Impede o envio do formulário
                alert("Por favor, corrija o CPF inválido antes de enviar.");
                return; // Sai da função para garantir que o envio seja interrompido
            }
            event.preventDefault();

            const formData = new FormData(this);
            formData.append('option', 'com_ajax');
            formData.append('module', 'solicitacao_ferias');  // Mesmo módulo para ambos os formulários
            formData.append('format', 'json');
            formData.append('task', 'register_return'); // Define a tarefa para "retorno"

            fetch("<?php echo JRoute::_('index.php'); ?>", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    // console.log("Resposta do servidor:", data.data); // Exibe a resposta completa no console para depuração
                    if (data.data.success) {
                        alert("Retorno cadastrado com sucesso.");
                        document.querySelector('form[name="retorno_form"]').reset();
                    } else {
                        alert(data.data.message);
                    }
                })
                .catch(error => {
                    console.error("Erro:", error);
                    alert("Houve um problema ao enviar a solicitação.");
                });
        });



        <?php if (!$user->guest): ?>
            document.getElementById('gerar_pdf').addEventListener('click', function () {
                const formData = new FormData();
                formData.append('option', 'com_ajax');
                formData.append('module', 'solicitacao_ferias');
                formData.append('format', 'json');
                formData.append('task', 'generate_pdf');  // Adiciona um campo para identificar a tarefa

                fetch("<?php echo JRoute::_('index.php'); ?>", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.text()) // Captura como texto para depuração
                    .then(data => {
                        // console.log("Resposta do servidor (PDF):", data); // Exibe a resposta completa no console
                        try {
                            const jsonData = JSON.parse(data); // Tenta converter para JSON
                            if (jsonData && jsonData.message) {
                                alert(jsonData.message);
                            } else {
                                alert("Falha ao gerar o PDF.");
                            }
                        } catch (error) {
                            console.error("Erro ao analisar JSON:", error);
                            alert("Erro ao processar a geração do PDF.");
                        }
                    })
                    .catch(error => {
                        console.error("Erro ao gerar o PDF:", error);
                        alert("Erro ao processar a geração do PDF.");
                    });
            });
        <?php endif; ?>




    });

    // Adiciona evento para tratar "Saída de guarnição"
    document.querySelectorAll('input[name="saida_guarnicao"]').forEach(radio => {
        radio.addEventListener('change', () => {
            // console.log(radio.value);
            const destinoContainer = document.getElementById('destino_container');
            destinoContainer.style.display = radio.value === 'Sim' ? 'block' : 'none';
            // console.log(destinoContainer.style.display);
        });
    });

    // Função para aplicar máscara de data DD/MM/AAAA
    function aplicarMascaraData(event) {
        let data = event.target.value.replace(/\D/g, ""); // Remove tudo que não for dígito
        if (data.length > 8) data = data.slice(0, 8); // Limita a 8 dígitos

        // Aplica a máscara de data
        data = data.replace(/(\d{2})(\d)/, "$1/$2");
        data = data.replace(/(\d{2})(\d)/, "$1/$2");
        event.target.value = data;
    }

    document.getElementById('data_inicio').addEventListener('input', aplicarMascaraData);
    document.getElementById('data_fim').addEventListener('input', aplicarMascaraData);
    document.getElementById('nomeacao_data').addEventListener('input', aplicarMascaraData);
    document.getElementById('periodo_total_nomeacao_de').addEventListener('input', aplicarMascaraData);
    document.getElementById('periodo_total_nomeacao_para').addEventListener('input', aplicarMascaraData);
    // Função para aplicar máscara de CPF
    document.getElementById('cpf').addEventListener('input', function (e) {
        let cpf = e.target.value.replace(/\D/g, ''); // Remove qualquer caractere que não seja número
        if (cpf.length > 11) cpf = cpf.slice(0, 11); // Limita a 11 dígitos

        // Aplica a máscara ao CPF
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = cpf;
    });

    document.getElementById('cpf_retorno').addEventListener('input', function (e) {
        let cpf_retorno = e.target.value.replace(/\D/g, ''); // Remove qualquer caractere que não seja número
        if (cpf_retorno.length > 11) cpf_retorno = cpf_retorno.slice(0, 11); // Limita a 11 dígitos

        // Aplica a máscara ao CPF
        cpf_retorno = cpf_retorno.replace(/(\d{3})(\d)/, '$1.$2');
        cpf_retorno = cpf_retorno.replace(/(\d{3})(\d)/, '$1.$2');
        cpf_retorno = cpf_retorno.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = cpf_retorno;
    });

    document.getElementById('cpf_consulta').addEventListener('input', function (e) {
        let cpf_consulta = e.target.value.replace(/\D/g, ''); // Remove qualquer caractere que não seja número
        if (cpf_consulta.length > 11) cpf_consulta = cpf_consulta.slice(0, 11); // Limita a 11 dígitos

        // Aplica a máscara ao CPF
        cpf_consulta = cpf_consulta.replace(/(\d{3})(\d)/, '$1.$2');
        cpf_consulta = cpf_consulta.replace(/(\d{3})(\d)/, '$1.$2');
        cpf_consulta = cpf_consulta.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = cpf_consulta;
    });

    // Adiciona eventos para detectar mudanças nos campos de Situação e Tipo de Afastamento
    document.querySelectorAll('input[name="situacao"]').forEach(radio => {
        radio.addEventListener('change', verificarCamposAdicionais);
    });
    document.querySelectorAll('input[name="tipo_afastamento"]').forEach(radio => {
        radio.addEventListener('change', verificarCamposAdicionais);
    });

    // Adiciona evento ao campo de CPF para validar após o preenchimento
    document.getElementById('cpf').addEventListener('blur', function () {
        const cpf = this.value;
        if (validarCPF(cpf)) {
            this.style.borderColor = 'green';  // CPF válido - destaque verde
        } else {
            this.style.borderColor = 'red';    // CPF inválido - destaque vermelho
        }
    });

    function verificarTipoDispensa() {
        // console.log('teste');
        const tipoAfastamento = document.querySelector('input[name="tipo_afastamento"]:checked')?.value;
        const tipoDispensaContainer = document.getElementById('tipo_dispensa_container');
        const tipoDispensaLicenca = document.getElementById('tipo_dispensa_licenca');
        const tipoDispensa = document.getElementById('tipo_dispensa').value;
        const documentoConcessaoContainer = document.getElementById('documento_concessao_container');
        const documentoConcessaoInput = document.getElementById('documento_concessao');


        // Exibe o seletor de tipo de dispensa somente se o tipo de afastamento for "Dispensa"
        if (tipoAfastamento === "Dispensas") {
            tipoDispensaContainer.style.display = 'block';
            tipoDispensaLicenca.style.display = 'none';
            // console.log('dispensas')
            // Exibe o campo de upload se o tipo de dispensa for "Médica"
            if (tipoAfastamento === "Dispensas" && tipoDispensa === "Medica") {
                documentoConcessaoContainer.style.display = 'block';
                documentoConcessaoInput.classList.add("required-field"); // Adiciona obrigatoriedade
            } else {
                documentoConcessaoContainer.style.display = 'none';
                documentoConcessaoInput.classList.remove("required-field"); // Remove obrigatoriedade

            }
        }
        // Exibe o campo de upload se o tipo de afastamento for "Baixa Hospitalar"
        else if (tipoAfastamento === "Baixa Hospitalar") {
            tipoDispensaContainer.style.display = 'none';
            tipoDispensaLicenca.style.display = 'none';
            documentoConcessaoContainer.style.display = 'block';
            documentoConcessaoInput.classList.add("required-field"); // Adiciona obrigatoriedade
        }
        // Exibe o campo de upload se o tipo de afastamento for "Baixa Hospitalar"
        else if (tipoAfastamento === "Licenças") {
            tipoDispensaContainer.style.display = 'none';
            tipoDispensaLicenca.style.display = 'block';
            documentoConcessaoInput.classList.remove("required-field");

        }
        // Oculta os campos em outros casos
        else {
            tipoDispensaLicenca.style.display = 'none';
            tipoDispensaContainer.style.display = 'none';
            documentoConcessaoContainer.style.display = 'none';
            documentoConcessaoInput.classList.remove("required-field");

        }

    }

    function verificaPeriodoFerias() {
        const tipoAfastamento = document.querySelector('input[name="tipo_afastamento"]:checked')?.value;
        const numeroDiasInput = document.getElementById('numero_dias').value;
        const periodoContainer = document.getElementById('periodo_container');
        const periodoSelect15 = document.getElementById('parcela15');
        const periodoSelect10 = document.getElementById('parcela10');

        // console.log('0');
        if (tipoAfastamento === "Férias") {
            if (tipoAfastamento === "Férias" && numeroDiasInput === "15") {
                periodoSelect15.style.display = 'block';
                periodoSelect10.style.display = 'none';
                periodoContainer.style.display = 'block'; // Exibe o contêiner
            } else if (tipoAfastamento === "Férias" && numeroDiasInput === "10") {
                // console.log('2');
                // Adiciona opções para 10 dias
                periodoSelect10.style.display = 'block';
                periodoSelect15.style.display = 'none';
                periodoContainer.style.display = 'block'; // Exibe o contêiner
            } else {
                // console.log('3');
                // Oculta o contêiner se não for 10 ou 15 dias
                periodoContainer.style.display = 'none';
                periodoSelect.innerHTML += `
            `;
            }
        }
    }

    // Função para habilitar/desabilitar campos adicionais
    function verificarCamposAdicionais() {
        // console.log('verificarCamposAdicionais');
        const situacao = document.querySelector('input[name="situacao"]:checked')?.value;
        const tipoAfastamento = document.querySelector('input[name="tipo_afastamento"]:checked')?.value;
        const tipoDispensa = document.getElementById('tipo_dispensa')?.value;
        const saidaGuarnicao = document.querySelector('input[name="saida_guarnicao"]:checked')?.value;
        const camposAdicionais = document.getElementById('campos_adicionais');
        const numeroDiasContainer = document.getElementById('numero_dias_container');
        const saidaGuarnicaoContainer = document.getElementById('saida_guarnicao_container');
        const destinoContainer = document.getElementById('destino_container');
        const anoReferenciaContainer = document.getElementById('ano_referencia_container');
        destinoContainer.style.display = 'none';

        // Exibe campos adicionais para PTTC e Férias
        if (situacao === 'PTTC' && tipoAfastamento === 'Férias') {
            camposAdicionais.style.display = 'block';
            anoReferenciaContainer.style.display = 'none';
        } else {
            camposAdicionais.style.display = 'none';
        }

        // Exibe o campo "Saída de guarnição" para tipos de afastamento "Férias" e "Missão"
        if (tipoAfastamento === 'Férias' || tipoAfastamento === 'Missão') {
            saidaGuarnicaoContainer.style.display = 'block';
        } else {
            saidaGuarnicaoContainer.style.display = 'none';
        }

        // Exibe o campo "Número de dias" para tipos relevantes
        if (["Férias", "Dispensas", "Baixa Hospitalar", "Missão", "Licenças", "Núpcias", "Luto", "Instalação"].includes(tipoAfastamento)) {
            numeroDiasContainer.style.display = 'block';

            // Ajusta o campo de entrada de "Número de Dias" dinamicamente
            const numeroDiasWrapper = document.getElementById('numero_dias_wrapper');
            if (!numeroDiasWrapper.getAttribute('data-initialized')) {
                numeroDiasWrapper.setAttribute('data-initialized', 'true');
                if (["Dispensas", "Baixa Hospitalar", "Missão", "Licenças", "Núpcias", "Luto", "Instalação"].includes(tipoAfastamento)) {
                    numeroDiasWrapper.innerHTML = `
                    <input type="number" id="numero_dias" name="numero_dias" placeholder="Insira o número de dias" min="1" required />
                `;
                } if (tipoAfastamento === "Férias" ||
                    (tipoAfastamento === 'Dispensas' && tipoDispensa === 'Desconto em férias')) {
                    numeroDiasWrapper.innerHTML = `
                    <input type="number" id="numero_dias" name="numero_dias" onkeyup="verificaPeriodoFerias()" placeholder="Insira o número de dias" min="1" required />
                
                `; if (situacao === 'ATIVA'){
                    anoReferenciaContainer.style.display = 'block';
                }
                } else {
                    anoReferenciaContainer.style.display = 'none';
                    document.getElementById('ano_referencia').value = ''; // Limpa o valor se não estiver visível
                }
            }

            // Reaplica o evento de cálculo de datas para o novo campo
            const numeroDiasInput = document.getElementById('numero_dias');
            document.getElementById('data_inicio').addEventListener('keyup', calcularDatas);
            if (numeroDiasInput) {
                numeroDiasInput.addEventListener('keyup', calcularDatas);
            }
        } else {
            numeroDiasContainer.style.display = 'none';
        }
    }

    const TourHelper = {
        init: function () {
            // Verifica se o tour já foi executado
            const jaExecutouTour = localStorage.getItem('tourExecutado');

            // Se não executou, inicia o tour e marca como executado
            if (!jaExecutouTour) {
                TourHelper.iniciarTour();
                localStorage.setItem('tourExecutado', 'true');
            }

            // Adiciona evento ao botão para reiniciar o tour
            document.getElementById('iniciar-tour').addEventListener('click', TourHelper.iniciarTour);
        },

        iniciarTour: function () {
            introJs().setOptions({
                steps: [
                    { intro: "Bem-vindo ao novo sistema de Apresentação Online! Vamos começar o tour para conhecer o sistema e suas funcionalidades." },
                    { element: "#afastamento", intro: "Essa é a aba de afastamento. Aqui vocé pode realizar o registro do seu afastamento." },
                    { element: "#retorno", intro: "Essa é a aba de retorno. Aqui vocé pode realizar o retorno de afastamentos informando o CPF e o tipo de afastamento." },
                    { element: "#consulta", intro: "Essa é a aba de consulta. Aqui vocé pode realizar a consulta de afastamentos que já registrou, consultando pelo CPF. Também é possível consultar o saldo de férias restantes." },
                    { element: "#cpf", intro: "Esse é o campo de CPF. Ele será validado automaticamente após a digitação. Caso o CPF seja inválido, o campo será destacado em vermelho indicando o erro." },
                    { element: "#nome_completo", intro: "Se o CPF inserido já tiver registro de afastamento no sistema, o nome será preenchido automaticamente." },
                    { element: "#nome_guerra", intro: "Se o CPF inserido já tiver registro de afastamento no sistema, o nome de guerra sera preenchido automaticamente." },
                    { element: "#situacao", intro: "Campo para selecionar a situação do militar. Caso o CPF inserido já tiver registro de afastamento no sistema, a situação é preenchida automaticamente." },
                    { element: "#tipo_afastamento", intro: "Campo para selecionar o Tipo de Afastamento. Campos adicionais serão exibidos automaticamente. Por favor, atentar a ordem de preenchimento dos campos." },
                    { element: "#data_inicio", intro: "Informe a data de Início de Afastamento e posteriormente o Número de dias. As Datas de Término e Apresentação serão calculadas e preenchidas automaticamente." },
                    { element: "#observacao", intro: "Para maiores informações sobre o seu afastamento, adicione uma observação." }

                ],
                showProgress: true,
                exitOnOverlayClick: true,
                prevLabel: 'Anterior',
                nextLabel: 'Próximo',
                doneLabel: 'Concluir',
            }).start();
        }
    };

    // Inicializa o tour na primeira visita ou através do botão
    document.addEventListener('DOMContentLoaded', TourHelper.init);

    // Adiciona o evento aos campos para ajustar o texto ao sair do campo
    document.getElementById('nome_completo').addEventListener('blur', aplicarMascaraNome);
    document.getElementById('nome_guerra').addEventListener('blur', aplicarMascaraNome);

    function aplicarMascaraNome(event) {
        const palavrasIgnoradas = ['de', 'da', 'do', 'dos', 'das', 'e', 'a', 'o']; // Palavras que ficam minúsculas
        let valor = event.target.value;

        // Verifica se há texto no campo antes de processar
        if (valor.trim() === '') {
            return; // Não faz nada se o campo estiver vazio
        }

        // Divide o texto em palavras, aplica as regras de capitalização e junta novamente
        valor = valor
            .toLowerCase()
            .split(' ')
            .map(palavra => {
                if (palavrasIgnoradas.includes(palavra)) {
                    return palavra; // Mantém as palavras ignoradas em minúsculas
                }
                return palavra.charAt(0).toUpperCase() + palavra.slice(1); // Torna a primeira letra maiúscula
            })
            .join(' ');

        // Atualiza o valor do campo
        event.target.value = valor;
    }

    function openTab(tabId) {
        // console.log('tab')
        // Remove a classe ativa de todas as abas e conteúdos
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

        // Ativa a aba e o conteúdo selecionados
        document.querySelector(`.tab[onclick="openTab('${tabId}')"]`).classList.add('active');
        document.getElementById(tabId).classList.add('active');

        // // Carrega os registros ADM ao abrir a aba correspondente
        // if (tabId === 'consulta_adm') {
        //     carregarRegistrosAdm();
        // }
    }


    document.getElementById('assistente-virtual').addEventListener('click', () => {
        const modal = document.getElementById('modal-assistente');
        modal.style.display = modal.style.display === 'none' ? 'block' : 'none';
    });

    function enviarPergunta() {
        const pergunta = document.getElementById('entrada-usuario').value;

        if (!pergunta.trim()) {
            alert("Digite uma pergunta.");
            return;
        }

        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'assistente_virtual');
        formData.append('pergunta', pergunta);

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                // console.log("Resposta do servidor:", data);
                const conteudo = document.getElementById('conteudo-assistente');

                if (data.data.success) {
                    conteudo.innerHTML += `<p>Usuário: ${pergunta}</p>`;
                    conteudo.innerHTML += `<p>Assistente: ${data.data.resposta || 'Desculpe, não entendi.'}</p>`;
                    conteudo.innerHTML += `
                <div>
                    <button onclick="avaliarResposta('${pergunta}', 'Útil')">👍 Útil</button>
                    <button onclick="avaliarResposta('${pergunta}', 'Não Útil')">👎 Não Útil</button>
                </div>
            `;

                    // Exibe sugestões, se houver
                    if (data.data.sugestoes && data.data.sugestoes.length > 0) {
                        conteudo.innerHTML += '<p>Você quis dizer:</p>';
                        data.data.sugestoes.forEach(sugestao => {
                            conteudo.innerHTML += `<p><a href="#" onclick="refazerPergunta('${sugestao}')">${sugestao}</a></p>`;
                        });
                    }
                } else {
                    conteudo.innerHTML += `<p>Erro ao processar a pergunta: ${data.data.message || 'Erro desconhecido.'}</p>`;
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                alert("Houve um problema ao processar sua pergunta.");
            });
        document.getElementById('entrada-usuario').value = '';
    }

    // Evento para detectar a tecla Enter
    document.getElementById('entrada-usuario').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Evita o comportamento padrão de envio do formulário
            enviarPergunta(); // Chama a função para enviar a pergunta
        }
    });

    function avaliarResposta(pergunta, feedback) {
        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'avaliar_resposta');
        formData.append('pergunta', pergunta);
        formData.append('feedback', feedback);

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Obrigado pelo seu feedback!");
                } else {
                    alert("Erro ao salvar o feedback: " + (data.message || "Erro desconhecido."));
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                alert("Houve um problema ao enviar o feedback.");
            });
    }

    // Refazer pergunta com a sugestão
    function refazerPergunta(sugestao) {
        document.getElementById('entrada-usuario').value = sugestao;
        enviarPergunta(); // Chama a função novamente
    }





    function gerarRelatorio() {
        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'gerar_relatorio');

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.data.success && data.data.file_url) {
                    // Redireciona para baixar o arquivo
                    window.location.href = data.data.file_url;
                } else {
                    alert(`Erro ao gerar o relatório: ${data.message || 'Erro desconhecido.'}`);
                }
            })
            .catch(error => {
                console.error('Erro ao gerar o relatório:', error);
                alert('Houve um problema ao gerar o relatório.');
            });
    }




    function carregarRegistros(pagina = 1) {
        const mes = document.getElementById('mes').value;
        const ano = document.getElementById('ano').value;
        const nome = document.getElementById('nome').value;
        const nomeCpf = document.getElementById('nome_cpf').value.trim(); // Novo filtro por Nome ou CPF
        const filtroVisualizados = document.getElementById('filtro_visualizados').checked ? 0 : 1;


        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'consultar_registros');
        formData.append('nome_cpf', nomeCpf); // Adiciona o filtro de Nome ou CPF
        formData.append('nome', nome);
        formData.append('mes', mes);
        formData.append('ano', ano);
        formData.append('pagina', pagina);
        formData.append('limite', 10); // Número de registros por página
        formData.append('filtro_visualizados', filtroVisualizados); // Adiciona o novo filtro
        console.log("Dados enviados:", Object.fromEntries(formData)); // Adicione este log

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Adicione para depuração
                console.log("Dados recebidos:", data.data.data.data);
                console.log(data.data.data);
                console.log(data);
                const tbody = document.querySelector('#registros-tabela tbody');
                tbody.innerHTML = '';
                // console.log(data.data.data)
                if (data.success && Array.isArray(data.data.data.data)) {
                    data.data.data.data.forEach(registro => {
                        const row = document.createElement('tr');

                        // Adiciona destaque para registros não visualizados
                        if (registro.visualizado == 0) {
                            row.classList.add('nao-visualizado'); // Classe CSS para destaque
                        }

                        row.innerHTML = `
                    <td class="visualizar" onclick="visualizarRegistro(${registro.id})">${registro.nome_completo || '---'}</td>
                    <td class="visualizar" onclick="visualizarRegistro(${registro.id})">${registro.cpf || '---'}</td>
                    <td class="visualizar" onclick="visualizarRegistro(${registro.id})">${registro.posto_grad || '---'}</td>
                    <td class="visualizar" onclick="visualizarRegistro(${registro.id})">${registro.tipo_afastamento}</td>
                    <td class="visualizar" onclick="visualizarRegistro(${registro.id})">${formatarData(registro.data_inicio) || '---'}</td>
                    <td class="visualizar" onclick="visualizarRegistro(${registro.id})">${formatarData(registro.data_fim) || '---'}</td>
                    
                    <td>
                        
                            
                            ${registro.visualizado == 0
                                ? `<button style="border: none; background-color: transparent;" 
                            onclick="marcarVisualizado(${registro.id})" 
                            class="marcar-lido" title="Marcar como visualizado">
                            📩
                            </button>`
                                : '<span style="color: green;">✔️</span>'
                            }
                        
                        <button title="Visualizar registro" style="border: none; background-color: transparent;" onclick="visualizarRegistro(${registro.id})">👁️</button>
                    </td>
                `;
                        tbody.appendChild(row);
                    });
                    renderizarPaginacao(data.data.data.total, data.data.data.pagina, data.data.data.limite);
                } else {
                    tbody.innerHTML = '<tr><td colspan="6">Nenhum registro encontrado.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Erro ao carregar registros:', error);
                alert('Houve um problema ao carregar os registros.');
            });
    }

    function renderizarPaginacao(total, paginaAtual, limite, botaoVisiveis = 5) {
        const paginacao = document.getElementById('paginacao');
        const totalPaginas = Math.ceil(total / limite);
        paginacao.innerHTML = ''; // Limpa a paginação anterior

        // Calcula o intervalo de páginas visíveis
        const inicio = Math.max(1, paginaAtual - Math.floor(botaoVisiveis / 2));
        const fim = Math.min(totalPaginas, inicio + botaoVisiveis - 1);

        // Botão "Primeira"
        if (paginaAtual > 1) {
            const primeiroBotao = document.createElement('button');
            primeiroBotao.textContent = '«';
            primeiroBotao.onclick = () => carregarRegistros(1);
            paginacao.appendChild(primeiroBotao);
        }

        // Botão "Anterior"
        if (paginaAtual > 1) {
            const anteriorBotao = document.createElement('button');
            anteriorBotao.textContent = '‹';
            anteriorBotao.onclick = () => carregarRegistros(paginaAtual - 1);
            paginacao.appendChild(anteriorBotao);
        }

        // Botões de páginas
        for (let i = inicio; i <= fim; i++) {
            const botao = document.createElement('button');
            botao.textContent = i;
            botao.disabled = i === paginaAtual; // Desativa o botão da página atual
            botao.className = i === paginaAtual ? 'pagina-ativa' : '';
            botao.onclick = () => carregarRegistros(i);
            paginacao.appendChild(botao);
        }

        // Botão "Próxima"
        if (paginaAtual < totalPaginas) {
            const proximoBotao = document.createElement('button');
            proximoBotao.textContent = '›';
            proximoBotao.onclick = () => carregarRegistros(paginaAtual + 1);
            paginacao.appendChild(proximoBotao);
        }

        // Botão "Última"
        if (paginaAtual < totalPaginas) {
            const ultimoBotao = document.createElement('button');
            ultimoBotao.textContent = '»';
            ultimoBotao.onclick = () => carregarRegistros(totalPaginas);
            paginacao.appendChild(ultimoBotao);
        }
    }


    function marcarVisualizado(id) {
        // Exibe a caixa de confirmação antes de prosseguir
        const confirmacao = confirm('Tem certeza de que deseja marcar este afastamento como visualizado? Se sim, clique em "OK"');
        if (!confirmacao) {
            return; // Cancela a ação se o usuário clicar em "Cancelar"
        }

        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'marcar_visualizado');
        formData.append('id', id);

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Registro marcado como visualizado.');
                    carregarRegistros(); // Recarrega os registros após marcar como visualizado
                } else {
                    alert('Erro ao marcar como visualizado.');
                }
            })
            .catch(error => {
                console.error('Erro ao marcar visualizado:', error);
                alert('Houve um problema ao marcar como visualizado.');
            });
    }

    function visualizarRegistro(id) {
        // console.log("Visualizando ID:", id); // Log para verificar o ID enviado
        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'visualizar_registro'); // Tarefa correta
        formData.append('id', id); // Envia o ID

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                // console.log("Resposta do servidor:", data); // Log para depuração
                if (data.success && data.data) {
                    let documentoURL = data.data.data.documento_concessao_url || '';
                    // Remove o prefixo do URL, se presente
                    const prefixo = 'http://intranet.decex.eb.mil.br/';
                    if (documentoURL.startsWith(prefixo)) {
                        documentoURL = documentoURL.replace(prefixo, '');
                    }
                    // Preenche o modal com os detalhes do registro
                    document.getElementById('registro-detalhes').innerHTML = `
                <p><strong>Nome Completo:</strong> ${data.data.data.nome_completo || '---'}</p>
                <p><strong>CPF:</strong> ${data.data.data.cpf || '---'}</p>
                <p><strong>Posto/Grad:</strong> ${data.data.data.posto_grad || '---'}</p>
                <p><strong>Situação:</strong> ${data.data.data.situacao || '---'}</p>
                <p><strong>Seção:</strong> ${data.data.data.secao || '---'}</p>
                <p><strong>Data de Início:</strong> ${formatarData(data.data.data.data_inicio) || '---'}</p>
                <p><strong>Data de Término:</strong> ${formatarData(data.data.data.data_fim) || '---'}</p>
                <p><strong>Data de Apresentação:</strong> ${formatarData(data.data.data.data_apresentacao) || '---'}</p>
                <p style="background-color: cadetblue; border-radius: 5px; width: fit-content;"><strong>Tipo de Afastamento:</strong> ${data.data.data.tipo_afastamento || '---'}</p>
                ${data.data.data.tipo_dispensa ? `<p><strong>Tipo de Dispensa:</strong> ${data.data.data.tipo_dispensa}</p>` : ''}
                ${data.data.data.tipo_licenca ? `<p><strong>Tipo de Licença:</strong> ${data.data.data.tipo_licenca}</p>` : ''}
                ${data.data.data.ano_referencia ? `<p><strong>Ano de Referência:</strong> ${data.data.data.ano_referencia}</p>` : ''}
                <p><strong>Número de Dias:</strong> ${data.data.data.numero_dias || '---'}</p>
                ${data.data.data.nomeacao_data ? `<p><strong>Data de Nomeação:</strong> ${formatarData(data.data.data.nomeacao_data)}</p>` : ''}</p>
                ${data.data.data.periodo_total_nomeacao_de ? `<p><strong>Periodo total de nomeação:</strong> De: ${formatarData(data.data.data.periodo_total_nomeacao_de)} para: ${formatarData(data.data.data.periodo_total_nomeacao_para)}</p>` : ''}</p>
                ${data.data.data.periodo_aquisitivo_ferias ? `<p><strong>Período aquisitivo de Férias:</strong> ${data.data.data.periodo_aquisitivo_ferias}</p>` : ''}</p>
                ${documentoURL ? `<p><strong>Documento de Concessão:</strong> <a href="${documentoURL}" target="_blank">Visualizar Documento</a></p>` : ''}
                ${data.data.data.saida_guarnicao ? `<p><strong>Saída de Guarnição?</strong> ${data.data.data.saida_guarnicao}</p>` : ''}
                ${data.data.data.destino ? `<p><strong>Destino:</strong> ${data.data.data.destino}</p>` : ''}
                ${data.data.data.observacao ? `<p><strong>Observação:</strong> ${data.data.data.observacao}</p>` : ''}
                ${data.data.data.parcela_selecionado ? `<p><strong>Parcela das Férias:</strong>${data.data.data.parcela_selecionado}</p>` : ''}
                ${data.data.data.retorno ? `<p style="background-color: crimson; border-radius: 5px; width: fit-content;"><strong>Retorno:</strong>${formatarData(data.data.data.retorno)}</p>` : ''}
                ${data.data.data.obs_retorno ? `<p><strong>Obeservação sobre o retorno:</strong>${data.data.data.obs_retorno}</p>` : ''}
            `;
                    document.getElementById('modal-detalhes').style.display = 'block';
                } else {
                    alert(`Erro: ${data.message || 'Não foi possível obter os detalhes do registro.'}`);
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                alert("Houve um problema ao visualizar o registro.");
            });
    }




    function fecharModal() {
        document.getElementById('modal-detalhes').style.display = 'none';
        document.getElementById('modal-detalhes2').style.display = 'none';
    }


    function consultarCPF() {
    const cpfInput = document.getElementById('cpf_consulta');
    let cpf = cpfInput.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos

    // Adiciona formatação ao CPF (XXX.XXX.XXX-XX)
    cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');

    // Verifica se o CPF é válido antes de enviar
    if (!validarCPF(cpf)) {
        alert("Por favor, insira um CPF válido para consulta.");
        return;
    }

    const formData = new FormData();
    formData.append('option', 'com_ajax');
    formData.append('module', 'solicitacao_ferias');
    formData.append('format', 'json');
    formData.append('task', 'consulta');
    formData.append('consulta_cpf', cpf);

    fetch("<?php echo JRoute::_('index.php'); ?>", {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.data && data.data.success && Array.isArray(data.data.data)) {
            const resultado = data.data.data;

            if (resultado.length > 0) {
                let feriasPorAno = {}; // Objeto para armazenar os dias de férias por ano
                let possuiAnoReferencia = false;
                let anoAtual = new Date().getFullYear();

                resultado.forEach(entry => {
                    if (entry['tipo_afastamento'] === 'Férias') {
                        let anoReferencia = entry['ano_referencia'] || null;

                        if (anoReferencia !== null) {
                            possuiAnoReferencia = true;
                            if (!feriasPorAno[anoReferencia]) {
                                feriasPorAno[anoReferencia] = { diasTotais: 30, diasTirados: 0 };
                            }
                            feriasPorAno[anoReferencia].diasTirados += parseInt(entry['numero_dias']) || 0;
                        } else {
                            // Se não há ano_referencia, assume o ano de início do afastamento
                            let dataInicio = entry['data_inicio'] ? new Date(entry['data_inicio']) : null;
                            if (dataInicio) {
                                let anoDoRegistro = dataInicio.getFullYear();
                                if (!feriasPorAno[anoDoRegistro]) {
                                    feriasPorAno[anoDoRegistro] = { diasTotais: 30, diasTirados: 0 };
                                }
                                feriasPorAno[anoDoRegistro].diasTirados += parseInt(entry['numero_dias']) || 0;
                            }
                        }
                    }
                });

                // Se o usuário não tem ano_referencia registrado, adiciona os 30 dias do ano atual
                if (!possuiAnoReferencia) {
                    if (!feriasPorAno[anoAtual]) {
                        feriasPorAno[anoAtual] = { diasTotais: 30, diasTirados: 0 };
                    }
                }

                let html = `<div style="padding: 20px;
    background-color: whitesmoke;
    border-radius: 30px; margin-top: 20px;">
    <div style="margin-top: -20px;">
    <h2 style="margin-bottom: -20px; text-align: center;">Férias Restantes</h2>`;

                if (Object.keys(feriasPorAno).length === 0) {
                    html += `<p><strong>O usuário não possui registro de ano de referência.</strong></p>`;
                } else {
                    html += `<table>
                                <thead>
                                    <tr>
                                        <th>Ano de Referência</th>
                                        <th>Dias Totais</th>
                                        <th>Dias Tirados</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    let totalDiasTirados = 0;
                    let totalDiasDisponiveis = 0;

                    for (const [ano, dados] of Object.entries(feriasPorAno)) {
                        let saldo = Math.max(dados.diasTotais - dados.diasTirados, 0);
                        if (saldo > 0) { // Só exibe anos com saldo positivo
        totalDiasTirados += dados.diasTirados;
        totalDiasDisponiveis += dados.diasTotais;

        html += `<tr>
                    <td>${ano}</td>
                    <td>${dados.diasTotais}</td>
                    <td>${dados.diasTirados}</td>
                    <td>${saldo}</td>
                </tr>`;
    }
                    }

                    let saldoTotal = Math.max(totalDiasDisponiveis - totalDiasTirados, 0);

                    html += `</tbody></table>`;
                }

                html += `
                </div>
                </div>
                <p>Os registros com o ícone ⚠️ de alerta indicam que já passaram da data de apresentação e não tiveram registros de retorno.</p>
                <table>
                <thead>
                <tr>
                <th>Nome Completo</th>
                <th>Data de Apresentação</th>
                <th>Tipo de Afastamento</th>
                <th>Aviso</th>
                <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                
                `;
                document.getElementById('consulta_resultado').innerHTML = html;

                resultado.forEach(entry => {
                    const retorno = entry['retorno'] || '---';
                    const dataApresentacao = entry['data_apresentacao'] || '---';
                    const visualizado = entry['visualizado'] || 0;

                    const hoje = new Date();
                    const partesApresentacao = dataApresentacao.split('-');
                    const dataApresentacaoObj = new Date(partesApresentacao[0], partesApresentacao[1] - 1, partesApresentacao[2]);
                    const passouDataApresentacao = dataApresentacaoObj < hoje && retorno === '---';

                    let aviso = '';
                    if (passouDataApresentacao) {
                        aviso = '<span style="color: red;" title="Data de apresentação ultrapassada e sem retorno registrado">⚠️</span>';
                    } else if (visualizado == 1) {
                        aviso = '<span style="color: green; font-weight: bold;">✅ Visualizado pela Div Pes</span>';
                    } else {
                        aviso = '<span style="color: blue;">📅 Afastamento registrado</span>';
                    }

                    html += `
                    <tr>
                        <td>${entry['nome_completo'] || '---'}</td>
                        <td>${formatarData(dataApresentacao)}</td>
                        <td>${entry['tipo_afastamento'] || '---'}</td>
                        <td>${aviso}</td>
                        <td>
                            <button onclick="abrirModal(${JSON.stringify(entry).replace(/"/g, '&quot;')})">Detalhes</button>
                        </td>
                    </tr>
                    `;
                });

                html += `</tbody></table>`;
                document.getElementById('consulta_resultado').innerHTML = html;
            } else {
                document.getElementById('consulta_resultado').innerHTML = '<p>Nenhum registro encontrado para este CPF.</p>';
            }
        } else {
            alert("Erro na consulta do CPF.");
        }
    })
    .catch(error => {
        console.error("Erro:", error);
        alert("Houve um problema ao realizar a consulta.");
    });
}



    // Função para abrir o modal e exibir os detalhes do registro
    function abrirModal(entry) {
        const modal = document.getElementById('modal-detalhes2');
        const modalBody = document.getElementById('modal-body');

        let html = `
                <h3>Detalhes do Registro</h3>
                <p><strong>Nome Completo:</strong> ${entry.nome_completo || '---'}</p>
                <p><strong>CPF:</strong> ${entry.cpf || '---'}</p>
                <p><strong>Posto/Grad:</strong> ${entry.posto_grad || '---'}</p>
                <p><strong>Situação:</strong> ${entry.situacao || '---'}</p>
                <p><strong>Seção:</strong> ${entry.secao || '---'}</p>
                <p><strong>Data de Início:</strong> ${formatarData(entry.data_inicio) || '---'}</p>
                <p><strong>Data de Término:</strong> ${formatarData(entry.data_fim) || '---'}</p>
                <p><strong>Data de Apresentação:</strong> ${formatarData(entry.data_apresentacao) || '---'}</p>
                <p style="background-color: cadetblue; border-radius: 5px; width: fit-content;"><strong>Tipo de Afastamento:</strong> ${entry.tipo_afastamento || '---'}</p>
                ${entry.tipo_dispensa ? `<p><strong>Tipo de Dispensa:</strong> ${entry.tipo_dispensa}</p>` : ''}
                ${entry.tipo_licenca ? `<p><strong>Tipo de Licença:</strong> ${entry.tipo_licenca}</p>` : ''}
                <p><strong>Número de Dias:</strong> ${entry.numero_dias || '---'}</p>
                ${entry.ano_referencia ? `<p><strong>Ano de Referência:</strong> ${entry.ano_referencia}</p>` : ''}
                ${entry.nomeacao_data ? `<p><strong>Data de Nomeação:</strong> ${formatarData(entry.nomeacao_data)}</p>` : ''}</p>
                ${entry.periodo_total_nomeacao_de ? `<p><strong>Periodo total de nomeação:</strong> De: ${formatarData(entry.periodo_total_nomeacao_de)} para: ${formatarData(entry.periodo_total_nomeacao_para)}</p>` : ''}</p>
                ${entry.periodo_aquisitivo_ferias ? `<p><strong>Período aquisitivo de Férias:</strong> ${entry.periodo_aquisitivo_ferias}</p>` : ''}</p>
                ${entry.saida_guarnicao ? `<p><strong>Saída de Guarnição?</strong> ${entry.saida_guarnicao}</p>` : ''}
                ${entry.destino ? `<p><strong>Destino:</strong> ${entry.destino}</p>` : ''}
                ${entry.observacao ? `<p><strong>Observação:</strong> ${entry.observacao}</p>` : ''}
                ${entry.parcela_selecionado ? `<p><strong>Parcela das Férias:</strong>${entry.parcela_selecionado}</p>` : ''}
                ${entry.retorno ? `<p style="background-color: crimson; border-radius: 5px; width: fit-content;"><strong>Retorno:</strong>${formatarData(entry.retorno)}</p>` : ''}
                ${entry.obs_retorno ? `<p><strong>Observação sobre o retorno:</strong>${entry.obs_retorno}</p>` : ''}
    `;

        modalBody.innerHTML = html;
        modal.style.display = 'block';
    }




    // Função para converter data no formato DD/MM/AAAA para objeto Date
    function converterDataParaObjeto(data) {
        const [dia, mes, ano] = data.split('/');
        return new Date(`${ano}-${mes}-${dia}`);
    }




    // Função para calcular as datas de término e apresentação
    function calcularDatas() {
        const dataInicioInput = document.getElementById('data_inicio').value;
        const numeroDiasInput = document.getElementById('numero_dias');

        // Verifica se os campos necessários estão preenchidos
        if (!dataInicioInput || !numeroDiasInput) {
            return;
        }

        const dataInicio = converterDataParaObjeto(dataInicioInput);
        const numeroDias = numeroDiasInput.tagName === 'SELECT'
            ? parseInt(numeroDiasInput.value.match(/\d+/)) // Extrai o número de dias do select
            : parseInt(numeroDiasInput.value); // Usa o valor do input diretamente

        if (isNaN(numeroDias) || !dataInicio) {
            return;
        }

        // Calcula a data de término
        const dataTermino = new Date(dataInicio);
        dataTermino.setDate(dataInicio.getDate() + numeroDias);

        // Calcula a data de apresentação
        const dataApresentacao = new Date(dataTermino);
        dataApresentacao.setDate(dataTermino.getDate() + 1); // D+1

        // Ajusta a data de apresentação para o próximo dia útil, se cair no fim de semana
        while (dataApresentacao.getDay() === 0 || dataApresentacao.getDay() === 6) {
            dataApresentacao.setDate(dataApresentacao.getDate() + 1);
        }

        // Atualiza os campos com as datas formatadas
        const dataFimInput = document.getElementById('data_fim');
        const dataApresentacaoInput = document.getElementById('data_apresentacao');
        const dataApresentacaoDiv = document.getElementById('data_apresentacao_div');

        dataFimInput.value = formatarDataParaBrasileiro(dataTermino);
        dataApresentacaoInput.value = formatarDataParaBrasileiro(dataApresentacao);

        // Torna visível a data de apresentação
        if (dataApresentacaoDiv) {
            dataApresentacaoDiv.style.display = 'block';
        }
    }

    // Função para converter data no formato DD/MM/AAAA para objeto Date
    function converterDataParaObjeto(data) {
        const [dia, mes, ano] = data.split('/');
        return new Date(`${ano}-${mes}-${dia}`);
    }

    // Função para formatar data para o padrão brasileiro (DD/MM/AAAA)
    function formatarDataParaBrasileiro(data) {
        const dia = String(data.getDate()).padStart(2, '0');
        const mes = String(data.getMonth() + 1).padStart(2, '0');
        const ano = data.getFullYear();
        return `${dia}/${mes}/${ano}`;
    }

    // Adiciona eventos aos elementos necessários
    document.getElementById('data_inicio').addEventListener('change', calcularDatas);
    document.querySelectorAll('input[name="tipo_afastamento"]').forEach(radio => {
        radio.addEventListener('change', () => {
            // Zera as datas ao mudar o tipo de afastamento
            document.getElementById('data_fim').value = '';
            document.getElementById('data_apresentacao').value = '';
            document.getElementById('data_apresentacao_div').style.display = 'none';

            // Verifica novamente os campos adicionais
            verificarCamposAdicionais();
        });
    });




    // Adiciona evento ao campo de CPF de retorno para validar após o preenchimento
    document.getElementById('cpf_retorno').addEventListener('blur', function () {
        const cpf = this.value;
        if (validarCPF(cpf)) {
            this.style.borderColor = 'green';  // CPF válido - destaque verde
        } else {
            this.style.borderColor = 'red';    // CPF inválido - destaque vermelho
        }
    });

    // Adiciona evento ao campo de CPF de retorno para validar após o preenchimento
    document.getElementById('cpf_consulta').addEventListener('blur', function () {
        const cpf = this.value;
        if (validarCPF(cpf)) {
            this.style.borderColor = 'green';  // CPF válido - destaque verde
        } else {
            this.style.borderColor = 'red';    // CPF inválido - destaque vermelho
        }
    });

    // Função para exibir a mensagem de erro e alterar a cor do campo
    function atualizarStatusCPF(inputCPF, errorMessage) {
        const cpf = inputCPF.value;
        if (validarCPF(cpf)) {
            inputCPF.style.borderColor = 'green';  // CPF válido - destaque verde
            errorMessage.style.display = 'none';   // Oculta a mensagem de erro
            return true;
        } else {
            inputCPF.style.borderColor = 'red';    // CPF inválido - destaque vermelho
            errorMessage.style.display = 'block';  // Exibe a mensagem de erro
            return false;
        }
    }


    function validarCPF(cpf) {
        // Remove caracteres não numéricos
        cpf = cpf.replace(/\D/g, '');

        // Verifica se o CPF tem 11 dígitos
        if (cpf.length !== 11) return false;

        // Verifica se todos os dígitos são iguais (ex.: 111.111.111-11)
        if (/^(\d)\1+$/.test(cpf)) return false;

        // Calcula os dígitos verificadores
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf.charAt(9))) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf.charAt(10))) return false;

        return true;
    }






    function formatarData(data) {
        if (!data || data === '0000-00-00') return '---'; // Trata valores nulos ou padrão SQL para datas não preenchidas
        const partes = data.split('-');
        return `${partes[2]}/${partes[1]}/${partes[0]}`; // Rearranja a data
    }


    document.getElementById('tipo_afastamento').addEventListener('change', calcularDatas);
    document.getElementById('numero_dias').addEventListener('change', calcularDatas);

    function calcularDatas() {
        const dataInicioInput = document.getElementById('data_inicio').value;
        const numeroDiasInput = document.getElementById('numero_dias').value;

        // Verifica se os campos necessários estão preenchidos
        if (!dataInicioInput || !numeroDiasInput) {
            return;
        }

        const dataInicio = converterDataParaObjeto(dataInicioInput);
        const numeroDias = parseInt(numeroDiasInput.match(/\d+/)); // Extrai o número de dias da string ou valor

        if (isNaN(numeroDias) || !dataInicio) {
            return;
        }

        // Calcula a data de término
        const dataTermino = new Date(dataInicio);
        dataTermino.setDate(dataInicio.getDate() + numeroDias); // Inclui o dia inicial no cálculo

        // Calcula a data de apresentação
        const dataApresentacao = new Date(dataTermino);
        dataApresentacao.setDate(dataTermino.getDate() + 1); // D+1

        // Ajusta a data de apresentação para o próximo dia útil, se cair no fim de semana
        while (dataApresentacao.getDay() === 0 || dataApresentacao.getDay() === 6) {
            dataApresentacao.setDate(dataApresentacao.getDate() + 1);
        }

        // Atualiza os campos com as datas formatadas
        const dataFimInput = document.getElementById('data_fim');
        const dataApresentacaoInput = document.getElementById('data_apresentacao');
        const dataApresentacaoDiv = document.getElementById('data_apresentacao_div');

        dataFimInput.value = formatarDataParaBrasileiro(dataTermino);
        dataApresentacaoInput.value = formatarDataParaBrasileiro(dataApresentacao);

        // Torna visível a data de apresentação
        if (dataApresentacaoDiv) {
            dataApresentacaoDiv.style.display = 'block';
        }
    }

    // Função para converter data no formato DD/MM/AAAA para objeto Date
    function converterDataParaObjeto(data) {
        const [dia, mes, ano] = data.split('/');
        return new Date(`${ano}-${mes}-${dia}`);
    }

    // Função para formatar data para o padrão brasileiro (DD/MM/AAAA)
    function formatarDataParaBrasileiro(data) {
        const dia = String(data.getDate()).padStart(2, '0');
        const mes = String(data.getMonth() + 1).padStart(2, '0');
        const ano = data.getFullYear();
        return `${dia}/${mes}/${ano}`;
    }



    function buscarDadosPorCPF() {
        const cpfInput = document.getElementById('cpf');
        const cpf = cpfInput.value.replace(/\D/g, ''); // Remove formatação

        if (!cpf || !validarCPF(cpf)) {
            alert("Por favor, insira um CPF válido.");
            return;
        }

        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'buscar_dados_por_cpf');
        formData.append('cpf', cpf);

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // console.log(data.data.data);
                    const { nome_completo, nome_guerra, posto_grad, secao, situacao } = data.data.data;
                    // Preenche os campos
                    const nomeCompletoInput = document.getElementById('nome_completo');
                    const nomeGuerraInput = document.getElementById('nome_guerra');
                    const postoGradInput = document.getElementById('posto_grad');
                    const secaoInput = document.getElementById('secao');

                    nomeCompletoInput.value = nome_completo || '';
                    nomeGuerraInput.value = nome_guerra || '';
                    postoGradInput.value = posto_grad || '';
                    secaoInput.value = secao || '';

                    // Torna os campos somente leitura
                    nomeCompletoInput.readOnly = true;
                    nomeGuerraInput.readOnly = true;
                    // Marca o botão de rádio correspondente à situação
                    if (situacao) {
                        document.querySelectorAll('input[name="situacao"]').forEach(radio => {
                            if (radio.value === situacao) {
                                radio.checked = true;
                            }
                        });
                    }
                } else {
                    alert(data.message || "Erro ao buscar dados.");
                }
            })
            .catch(error => {
                console.error("Erro ao buscar dados por CPF:", error);

            });
    }

    function buscarRegistrosSemRetorno() {
        const cpfInput = document.getElementById('cpf_retorno');
        const cpf = cpfInput.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        console.log(cpf)

        // Exibe o overlay de carregamento
        const loadingOverlay = document.getElementById('loading-overlay');
        loadingOverlay.style.display = 'flex';


        // Verifica se o CPF tem o formato válido
        if (!validarCPF(cpf)) {
            loadingOverlay.style.display = 'none';
            document.getElementById('cpf-retorno-error').style.display = 'block';
            document.getElementById('btn-registrar-retorno').disabled = true; // Desabilita o botão se CPF for inválido
            return;
        }
        document.getElementById('cpf-retorno-error').style.display = 'none';

        // Cria o FormData para enviar ao servidor
        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'buscar_sem_retorno');
        formData.append('cpf', cpf);

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                loadingOverlay.style.display = 'flex';
                console.log(data.data.data)
                const container = document.getElementById('registros-sem-retorno-container');
                container.innerHTML = '';

                if (data.success && Array.isArray(data.data.data)) {
                    loadingOverlay.style.display = 'none';
                    if (data.data.data.length > 0) {
                        const primeiroRegistro = data.data.data[0]; // Pega o primeiro registro para exibir o nome completo
                        const infoDiv = document.createElement('div');
                        infoDiv.style.marginBottom = "10px";
                        infoDiv.innerHTML = `
            <p><strong>CPF Consultado:</strong> ${document.getElementById('cpf_retorno').value}</p>
            <p><strong>Militar:</strong> ${primeiroRegistro.posto_grad} - ${primeiroRegistro.nome_guerra}</p>
            <p><strong>Situação:</strong> ${primeiroRegistro.situacao}</p>
            <p><strong>Total de Registros Encontrados:</strong> ${data.data.data.length}</p>
            <p><strong>⬇️ Selecione abaixo o afastamento que deseja realizar o retorno. ⬇️</strong></p>
        `;
                        container.appendChild(infoDiv); // Adiciona as informações acima da tabela

                        const table = document.createElement('table');
                        table.border = "1";
                        table.style.width = "100%";
                        table.style.borderCollapse = "collapse";

                        let html = `
                        <thead>
                        <tr>
                            <th>Tipo de Afastamento</th>
                            <th>Data de Apresentação</th>
                            <th>Observação do Retorno</th>
                            <th>Selecionar</th>
                        </tr>
                    </thead>
                    <tbody>
                    `;

                        data.data.data.forEach(entry => {
                            // Converte as datas para objetos Date
                            const dataApresentacao = entry.data_apresentacao;
                            const hoje = formatarParaYYYYMMDD(new Date()); // Transforma hoje para o mesmo formato
                            console.log(dataApresentacao, hoje, entry.data_apresentacao)

                            // Verifica se o campo de observação deve ser exibido
                            const mostrarObservacao = hoje > dataApresentacao;


                            html += `
                            <tr>
                            <td>${entry.tipo_afastamento || '---'}</td>
                            <td>${formatarData(entry.data_apresentacao)}</td>
                            <td>
                                    ${mostrarObservacao
                                    ? `<textarea name="obs_retorno_${entry.id}" placeholder="Caso o retorno seja realizado após a data de apresentação, justificar."></textarea>`
                                    : `<span style="color: green;">✅ Dentro do prazo</span>`}
                                </td>
                            <td>
                                <input type="radio" name="registro_id" value="${entry.id}">
                            </td>
                        </tr>
                        `;
                        });
                        html += '</tbody>';
                        table.innerHTML = html;
                        container.appendChild(table);
                        document.getElementById('btn-registrar-retorno').disabled = false; // Habilita o botão se houver registros
                    } else {
                        loadingOverlay.style.display = 'none';
                        container.innerHTML = '<p>Nenhum registro encontrado para o CPF informado.</p>';
                        document.getElementById('btn-registrar-retorno').disabled = true; // Desabilita o botão se não houver registros
                    }
                } else {
                    loadingOverlay.style.display = 'none';
                    container.innerHTML = '<p>Erro ao buscar registros.</p>';
                    document.getElementById('btn-registrar-retorno').disabled = true; // Desabilita o botão se não houver registros
                }
            })
            .catch(error => {
                loadingOverlay.style.display = 'none';
                console.error('Erro ao buscar registros:', error);
                alert('Houve um problema ao buscar os registros.');
                document.getElementById('btn-registrar-retorno').disabled = true; // Desabilita o botão se não houver registros
            });
    }

    function formatarParaYYYYMMDD(date) {
        const ano = date.getFullYear();
        const mes = String(date.getMonth() + 1).padStart(2, '0'); // Garante dois dígitos no mês
        const dia = String(date.getDate()).padStart(2, '0'); // Garante dois dígitos no dia
        return `${ano}-${mes}-${dia}`;
    }


    window.buscarRegistrosSemRetorno = buscarRegistrosSemRetorno;

    function registrarRetorno(event) {
        event.preventDefault();

        const registroId = document.querySelector('input[name="registro_id"]:checked');

        // if (!registroId) {
        //     alert("Por favor, selecione um afastamento para registrar o retorno.");
        //     return;
        // }

        const obsRetorno = document.querySelector(`textarea[name="obs_retorno_${registroId.value}"]`).value;


        const formData = new FormData();
        formData.append('option', 'com_ajax');
        formData.append('module', 'solicitacao_ferias');
        formData.append('format', 'json');
        formData.append('task', 'register_return');
        formData.append('registro_id', registroId.value);
        formData.append('obs_retorno', obsRetorno);

        fetch("<?php echo JRoute::_('index.php'); ?>", {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.data.success) {
                    console.log(data.data)
                    alert("Retorno registrado com sucesso!");
                    window.location.reload()
                } else {
                    alert(data.message || "Erro ao registrar retorno.");
                }
            })
            .catch(error => {
                console.error("Erro ao registrar retorno:", error);
                alert("Houve um problema ao registrar o retorno.");
            });
    }



</script>
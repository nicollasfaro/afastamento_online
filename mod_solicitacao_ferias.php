<?php
// Proibir acesso direto
defined('_JEXEC') or die;

// Carregar arquivo de ajuda
require_once __DIR__ . '/helper.php';

// Processar requisição AJAX
$input = JFactory::getApplication()->input;
$ajaxRequest = $input->get('module', null);
$task = $input->get('task', '');

if ($ajaxRequest === 'solicitacao_ferias') {
    if ($task === 'generate_pdf') {
        // Gera o PDF consolidado
        $response = ModSolicitacaoFeriasHelper::generatePDF();
    } else {
        // Processa a submissão do formulário
        $response = ModSolicitacaoFeriasHelper::getAjax();
    }
    
    echo json_encode($response);
    JFactory::getApplication()->close();
}

// Exibir layout do módulo
require JModuleHelper::getLayoutPath('mod_solicitacao_ferias');

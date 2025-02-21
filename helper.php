<?php
defined('_JEXEC') or die;
require_once JPATH_LIBRARIES . '/tcpdf/tcpdf.php';



// Função para formatar o CPF
            function formatarCPF($cpf) {
                return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf);
            }

            // Função para formatar data no formato DD/MM/AAAA
            function formatarData($data) {
                $date = DateTime::createFromFormat('Y-m-d', $data);
                return $date ? $date->format('d/m/Y') : $data;
            }

            JHtml::_('stylesheet', 'https://cdn.jsdelivr.net/npm/intro.js/minified/introjs.min.css', array(), true);
            JHtml::_('script', 'https://cdn.jsdelivr.net/npm/intro.js/minified/intro.min.js', false, true);

class ModSolicitacaoFeriasHelper
{

    private static function conectarBanco() {
        $host = 'localhost'; // Host do banco de dados
        $usuario = 'root'; // Usuário do banco
        $senha = '170414'; // Senha do banco
        $banco = 'solicitacoes'; // Nome do banco
    
        try {
            $conexao = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexao;
        } catch (PDOException $e) {
            die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
        }
    }
    
    
	
	public static function getAjax()
{
    $input = JFactory::getApplication()->input;
    $task = $input->get('task', '', 'STRING');

    switch ($task) {
        case 'register_leave': // Adicionado para registrar afastamento
            return self::registerLeave();
        case 'consulta_adm':
            return self::consultaAdm();
        case 'register_return':
            return self::registerReturn();
        case 'consulta':
            return self::consulta();
        case 'consultar_registros': // Nova tarefa para consultar registros
            $mes = $input->get('mes', null, 'INT');
            $ano = $input->get('ano', null, 'INT');
            $nomeCpf = $input->get('nome_cpf', '', 'STRING'); // Captura o parâmetro nome_cpf
            return self::consultarRegistros($mes, $ano, $nomeCpf);
        case 'marcar_visualizado': // Nova tarefa para marcar como visualizado
            $id = $input->get('id', null, 'INT');
            return self::marcarComoVisualizado($id);
        case 'visualizar_registro': // Tarefa para visualizar registro
            return self::visualizarRegistro();
        case 'buscar_sem_retorno': // Nova tarefa para buscar registros sem retorno
            return self::buscarSemRetorno();
        case 'gerar_relatorio': // Nova tarefa para gerar relatório
            return self::gerarRelatorio();
        case 'assistente_virtual':
            $pergunta = $input->get('pergunta', '', 'STRING');
            error_log("Pergunta recebida no PHP: '{$pergunta}'");
            return self::processarPergunta($pergunta); // Método que lida com a pergunta do assistente virtual
        case 'avaliar_resposta':
            return self::avaliarResposta();
        case 'calcular_dias_restantes':
            $cpf = $input->get('cpf', '', 'STRING');
            return self::calcularDiasRestantes($cpf);
        case 'buscar_dados_por_cpf':
            $cpf = $input->get('cpf', '', 'STRING');
            return self::buscarDadosPorCPF($cpf);
        default:
            return ['success' => false, 'message' => 'Tarefa não reconhecida.'];
    }
}

// Método para registrar a solicitação de afastamento
public static function registerLeave() {
    try {
        $input = JFactory::getApplication()->input;

        // Captura os dados do formulário
        $dados = [
            'nome_completo' => $input->get('nome', '', 'STRING'),
            'nome_guerra' => $input->get('nome_guerra', '', 'STRING'),
            'cpf' => $input->get('cpf', '', 'STRING'),
            'posto_grad' => $input->get('pg', '', 'STRING'),
            'secao' => $input->get('sec', '', 'STRING'),
            'situacao' => $input->get('situacao', '', 'STRING'),
            'data_inicio' => $input->get('data_inicio', '', 'STRING'),
            'data_fim' => $input->get('data_fim', '', 'STRING'),
            'data_apresentacao' => $input->get('data_apresentacao', '', 'STRING'),
            'tipo_afastamento' => $input->get('tipo_afastamento', '', 'STRING'),
            'numero_dias' => $input->get('numero_dias', '', 'STRING'),
            'observacao' => $input->get('observacao', '', 'STRING'),
            'parcela_selecionado' => $input->get('parcela', '', 'STRING'),
            'periodo_aquisitivo_ferias' => $input->get('periodo_aquisitivo_ferias', '', 'STRING'),
            'nomeacao_data' => $input->get('nomeacao_data', '', 'STRING'),
            'periodo_total_nomeacao_de' => $input->get('periodo_total_nomeacao_de', '', 'STRING'),
            'periodo_total_nomeacao_para' => $input->get('periodo_total_nomeacao_para', '', 'STRING'),
            'saida_guarnicao' => $input->get('saida_guarnicao', '', 'STRING'),
            'destino' => $input->get('destino', '', 'STRING'),
            'tipo_dispensa' => $input->get('tipo_dispensa', '', 'STRING'),
            'tipo_licenca' => $input->get('tipo_licenca', '', 'STRING'),
            'documento_concessao' => null, // Inicializa como null
            'ano_referencia' => $input->get('ano_referencia', '', 'STRING') // Corrigido para capturar corretamente
        ];

        // **💡 Validação: Documento obrigatório para Dispensa Médica e Baixa Hospitalar**
        if (($dados['tipo_afastamento'] === "Dispensas" && $dados['tipo_dispensa'] === "Medica") || 
            ($dados['tipo_afastamento'] === "Baixa Hospitalar")) {

            if (empty($_FILES['documento_concessao']['name'])) {
                return ['success' => false, 'message' => 'O Documento de Concessão é obrigatório para Dispensa Médica e Baixa Hospitalar.'];
            }
        }

        // Verifica se o arquivo foi enviado
        if (!empty($_FILES['documento_concessao']['name'])) {
            $arquivo = $_FILES['documento_concessao'];
            $uploadDir = JPATH_SITE . '/uploads/documentos_concessao/'; // Diretório de upload

            // Certifica-se de que o diretório existe
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $nomeArquivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $arquivo['name']); // Nome único
            $caminhoArquivo = $uploadDir . $nomeArquivo;

            if (move_uploaded_file($arquivo['tmp_name'], $caminhoArquivo)) {
                $dados['documento_concessao'] = '/uploads/documentos_concessao/' . $nomeArquivo; // Caminho relativo
            } else {
                return ['success' => false, 'message' => 'Erro ao fazer upload do documento de concessão.'];
            }
        }

        // Conexão com o banco
        $pdo = self::conectarBanco();

        // Query de inserção
        $stmt = $pdo->prepare("INSERT INTO solicitacoes_afastamento 
            (nome_completo, nome_guerra, cpf, posto_grad, secao, situacao, data_inicio, data_fim, data_apresentacao, tipo_afastamento, numero_dias, observacao, parcela_selecionado, periodo_aquisitivo_ferias, nomeacao_data, periodo_total_nomeacao_de, periodo_total_nomeacao_para, retorno, validacao, visualizado, saida_guarnicao, destino, tipo_dispensa, tipo_licenca, documento_concessao, ano_referencia)
            VALUES (:nome_completo, :nome_guerra, :cpf, :posto_grad, :secao, :situacao, :data_inicio, :data_fim, :data_apresentacao, :tipo_afastamento, :numero_dias, :observacao, :parcela_selecionado, :periodo_aquisitivo_ferias, :nomeacao_data, :periodo_total_nomeacao_de, :periodo_total_nomeacao_para, NULL, 'Não Validado', 0, :saida_guarnicao, :destino, :tipo_dispensa, :tipo_licenca, :documento_concessao, :ano_referencia)");

        // Conversão das datas para o formato do banco de dados (YYYY-MM-DD)
        $dados['data_inicio'] = self::converterDataParaBanco($dados['data_inicio']);
        $dados['data_fim'] = self::converterDataParaBanco($dados['data_fim']);
        $dados['data_apresentacao'] = self::converterDataParaBanco($dados['data_apresentacao']);
        $dados['nomeacao_data'] = self::converterDataParaBanco($dados['nomeacao_data']);
        $dados['periodo_total_nomeacao_de'] = self::converterDataParaBanco($dados['periodo_total_nomeacao_de']);
        $dados['periodo_total_nomeacao_para'] = self::converterDataParaBanco($dados['periodo_total_nomeacao_para']);

        // Executa a query
        $stmt->execute($dados);

        return ['success' => true, 'message' => 'Solicitação registrada com sucesso.'];
    } catch (PDOException $e) {
        // Log do erro no banco
        error_log("Erro no registerLeave: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro ao registrar solicitação: ' . $e->getMessage()];
    }
}


public static function consultaAdm() {
    try {
        $conexao = self::conectarBanco();
        $query = $conexao->query("SELECT * FROM solicitacoes_afastamento");
        $registros = $query->fetchAll(PDO::FETCH_ASSOC);

        return array('success' => true, 'data' => $registros);
    } catch (PDOException $e) {
        return array('success' => false, 'message' => 'Erro ao buscar registros: ' . $e->getMessage());
    }
}





public static function salvarValidacoes() {
    try {
        $input = JFactory::getApplication()->input;
        $validacoes = json_decode($input->get('validacoes', '', 'RAW'), true);

        if (empty($validacoes)) {
            return ['success' => false, 'message' => 'Nenhuma validação enviada.'];
        }

        $pdo = self::conectarBanco();

        foreach ($validacoes as $validacao) {
            $cpf = preg_replace('/\D/', '', $validacao['cpf']); // Remove formatação do CPF
            $validado = $validacao['validado'] ? 'Validado' : 'Não Validado';

            if ($cpf) {
                $stmt = $pdo->prepare("
                    UPDATE solicitacoes_afastamento
                    SET validacao = :validacao
                    WHERE cpf = :cpf
                ");
                $stmt->execute([
                    ':validacao' => $validado,
                    ':cpf' => $cpf
                ]);
            }
        }

        return ['success' => true, 'data' => ['success' => true, 'message' => 'Validações salvas com sucesso.']];
    } catch (PDOException $e) {
        return ['success' => false, 'data' => ['success' => false, 'message' => 'Erro ao salvar validações: ' . $e->getMessage()]];
    }
}






	
    
    
    // Função para converter data para o formato do banco de dados
    private static function converterDataParaBanco($data) {
        $date = DateTime::createFromFormat('d/m/Y', $data);
        return $date ? $date->format('Y-m-d') : null;
    }
    
    
    
    
	
	// Método para registrar o retorno
    public static function registerReturn() {
        try {
            $input = JFactory::getApplication()->input;
            $id = $input->get('registro_id', null, 'INT'); // ID do registro
            $obsRetorno = $input->get('obs_retorno', '', 'STRING'); // Observação do retorno
    
            // Log para verificar os dados recebidos
            error_log("ID recebido: " . $id);
            error_log("Observação recebida: " . $obsRetorno);
    
            if (empty($id)) {
                return ['success' => false, 'message' => 'Por favor, selecione um afastamento para registrar o retorno.'];
            }
    
            $pdo = self::conectarBanco();
    
            // Busca a data de apresentação do afastamento
            $stmt = $pdo->prepare("
                SELECT id, retorno, data_apresentacao 
                FROM solicitacoes_afastamento 
                WHERE id = :id
            ");
            $stmt->execute([':id' => $id]);
            $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Log para depuração
            error_log("Resultado da consulta: " . print_r($solicitacao, true));
    
            if (!$solicitacao) {
                return ['success' => false, 'message' => 'Registro não encontrado para o ID especificado.'];
            }
    
            if (!empty($solicitacao['retorno'])) {
                return ['success' => false, 'message' => 'Retorno já registrado para este registro.'];
            }
    
            // Converte a data de apresentação para comparar com a data atual
            $dataApresentacao = (new DateTime($solicitacao['data_apresentacao']))->format('Y-m-d');
            $dataHoje = (new DateTime())->format('Y-m-d');
    
            // Verifica se o retorno está sendo registrado APÓS a data de apresentação
            if ($dataHoje > $dataApresentacao && empty($obsRetorno)) {
                return ['success' => false, 'message' => 'A observação do retorno é obrigatória para registros feitos após a data de apresentação.'];
            }
    
            // Atualiza a solicitação com a data de retorno e a observação (se fornecida)
            $stmt = $pdo->prepare("
                UPDATE solicitacoes_afastamento 
                SET retorno = :retorno, obs_retorno = :obs_retorno, visualizado = 0 
                WHERE id = :id
            ");
            $stmt->execute([
                ':retorno' => date('Y-m-d'),
                ':obs_retorno' => $obsRetorno,
                ':id' => $id,
            ]);
    
            return ['success' => true, 'message' => 'Retorno registrado com sucesso.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erro ao registrar retorno: ' . $e->getMessage()];
        }
    }
    
    

    public static function consulta()
{
    try {
        $input = JFactory::getApplication()->input;
        $cpf = $input->get('consulta_cpf', '', 'STRING');

        // Formata o CPF com pontos e traço, se necessário
        $cpfFormatado = preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', preg_replace('/\D/', '', $cpf));

        $conexao = self::conectarBanco();
        $stmt = $conexao->prepare("SELECT * FROM solicitacoes_afastamento WHERE cpf = :cpf");
        $stmt->execute([':cpf' => $cpfFormatado]);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array('success' => true, 'data' => $registros);
    } catch (PDOException $e) {
        return array('success' => false, 'message' => 'Erro ao buscar registros: ' . $e->getMessage());
    }
}

    

public static function consultarRegistros($mes = null, $ano = null, $filtroVisualizados = null, $nomeCpf = null, $nome = null, $pagina = 1, $limite = 10)
{
    try {
        // Obter os parâmetros do request
        $input = JFactory::getApplication()->input;
        $filtroVisualizados = $input->get('filtro_visualizados', null, 'INT');
        $nomeCpf = $input->get('nome_cpf', '', 'STRING');
        $nome = $input->get('nome', '', 'STRING');
        $pagina = $input->get('pagina', 1, 'INT');
        $limite = $input->get('limite', 10, 'INT');

        // Calcular o offset para paginação
        $offset = ($pagina - 1) * $limite;

        // Log para depuração
        file_put_contents('php://stderr', print_r([
            'mes' => $mes,
            'ano' => $ano,
            'filtroVisualizados' => $filtroVisualizados,
            'nomeCpf' => $nomeCpf,
            'nome' => $nome,
            'pagina' => $pagina,
            'limite' => $limite,
            'offset' => $offset,
        ], true));

        // Conexão com o banco
        $pdo = self::conectarBanco();

        // Construção da query básica
        $query = "SELECT SQL_CALC_FOUND_ROWS * FROM solicitacoes_afastamento WHERE 1=1";
        $params = [];

        // Filtros opcionais
        // Filtro por nome ou CPF
        if ($nomeCpf) {
            $query .= " AND (REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), '/', '') LIKE :cpfLimpo)";
            $params[':cpfLimpo'] = '%' . preg_replace('/\D/', '', $nomeCpf) . '%'; // Remove formatação do CPF
        }

        if ($nome) {
            $query .= " AND LOWER(nome_completo) LIKE :nome";
            $params[':nome'] = '%' . strtolower($nome) . '%'; // Converte para minúsculo
        }

        if ($mes) {
            $query .= " AND MONTH(data_inicio) = :mes";
            $params[':mes'] = $mes;
        }

        if ($ano) {
            $query .= " AND YEAR(data_inicio) = :ano";
            $params[':ano'] = $ano;
        }

        if ($filtroVisualizados === 0 || $filtroVisualizados === '0') {
            $query .= " AND visualizado = :visualizado";
            $params[':visualizado'] = $filtroVisualizados;
        }

        // Ordenação e paginação
        $query .= " ORDER BY data_inicio DESC LIMIT :limite OFFSET :offset";

        // Log da query para depuração
        error_log("Query Montada: $query");
        error_log("Parâmetros: " . print_r($params, true));

        // Executa a query
        $stmt = $pdo->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Busca os registros
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Busca o total de registros sem limite
        $totalStmt = $pdo->query("SELECT FOUND_ROWS()");
        $totalRegistros = $totalStmt->fetchColumn();

        // Retorna os dados como JSON
        return [
            'success' => true,
            'data' => [
                'total' => $totalRegistros,
                'pagina' => $pagina,
                'limite' => $limite,
                'data' => $registros,
            ],
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Erro ao consultar registros: ' . $e->getMessage(),
        ];
    }
}


public static function marcarComoVisualizado($id) {
    try {
        $pdo = self::conectarBanco();
        $stmt = $pdo->prepare("UPDATE solicitacoes_afastamento SET visualizado = TRUE WHERE id = :id");
        $stmt->execute([':id' => $id]);

        return ['success' => true, 'message' => 'Registro marcado como visualizado.'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro ao marcar como visualizado: ' . $e->getMessage()];
    }
}

public static function visualizarRegistro()
{
    try {
        $input = JFactory::getApplication()->input;
        $id = $input->getInt('id'); // Obtém o ID do registro

        if (!$id) {
            return [
                'success' => false,
                'message' => 'ID inválido fornecido.'
            ];
        }

        $pdo = self::conectarBanco();
        $stmt = $pdo->prepare("SELECT * FROM solicitacoes_afastamento WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $registro = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$registro) {
            return [
                'success' => false,
                'message' => "Registro não encontrado para o ID: $id."
            ];
        }

        if (!empty($registro['documento_concessao'])) {
            $documento = $registro['documento_concessao'];
        
            // Verifica se o caminho já é completo
            if (filter_var($documento, FILTER_VALIDATE_URL)) {
                $registro['documento_concessao_url'] = $documento; // Já é um URL completo
            } else {
                $registro['documento_concessao_url'] = JUri::root() . ltrim($documento, '/'); // Concatena se for relativo
            }
        }

        // Certifica-se de que os dados do registro estão sendo retornados
        return [
            'success' => true,
            'data' => $registro
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Erro ao buscar registro: ' . $e->getMessage()
        ];
    }
}

public static function gerarRelatorio()
{
    try {
        // Conexão com o banco
        $pdo = self::conectarBanco();

        // Log para verificar conexão
        error_log("Conexão com o banco estabelecida.");

        // Query para obter todos os dados
        $stmt = $pdo->query("SELECT * FROM solicitacoes_afastamento");
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verifica se existem dados
        if (empty($dados)) {
            error_log("Nenhum dado encontrado na tabela.");
            return ['success' => false, 'message' => 'Nenhum dado encontrado para gerar o relatório.'];
        }

        // Caminho do arquivo CSV
        $filePath = JPATH_SITE . '/reports/solicitacoes_afastamento_completo.csv';
        error_log("Caminho do arquivo: " . $filePath);

        // Abre o arquivo para escrita
        $file = fopen($filePath, 'w');
        if ($file === false) {
            error_log("Erro ao abrir o arquivo CSV para escrita.");
            return ['success' => false, 'message' => 'Erro ao criar o arquivo CSV. Verifique permissões.'];
        }

        // Adiciona o cabeçalho
        fputcsv($file, array_keys($dados[0]));

        // Adiciona os dados
        foreach ($dados as $linha) {
            fputcsv($file, $linha);
        }
        fclose($file);

        error_log("Arquivo CSV gerado com sucesso.");

        // Retorna a URL do arquivo gerado
        return [
            'success' => true,
            'file_url' => JUri::root() . 'reports/solicitacoes_afastamento_completo.csv'
        ];
    } catch (PDOException $e) {
        error_log("Erro de PDO: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro ao gerar o relatório: ' . $e->getMessage()];
    } catch (Exception $e) {
        error_log("Erro geral: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro ao gerar o relatório: ' . $e->getMessage()];
    }
}

public static function processarPergunta($pergunta) {
    try {
        $pdo = self::conectarBanco();
        $stmt = $pdo->query("SELECT pergunta, resposta FROM perguntas_frequentes");
        $todasPerguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $melhorPontuacao = 0;
        $melhorResposta = null;
        $sugestoes = [];

        foreach ($todasPerguntas as $registro) {
            similar_text(strtolower($pergunta), strtolower($registro['pergunta']), $pontuacao);
            error_log("Comparando '{$pergunta}' com '{$registro['pergunta']}': {$pontuacao}%");

            if ($pontuacao > $melhorPontuacao) {
                $melhorPontuacao = $pontuacao;
                $melhorResposta = $registro['resposta'];
            }

            // Adiciona perguntas relacionadas com pontuação acima de 50%
            if ($pontuacao >= 30) {
                $sugestoes[] = $registro['pergunta'];
            }
        }

        // Salva a interação
        $stmt = $pdo->prepare("INSERT INTO interacoes_assistente (usuario, pergunta, resposta) VALUES (:usuario, :pergunta, :resposta)");
        $stmt->execute([
            ':usuario' => JFactory::getUser()->username,
            ':pergunta' => $pergunta,
            ':resposta' => $melhorResposta ?: 'Não encontrada'
        ]);

        return [
            'success' => true,
            'resposta' => $melhorResposta ?: 'Não entendi sua pergunta. Por favor, reformule.',
            'sugestoes' => $sugestoes // Inclui as sugestões no retorno
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Erro ao buscar resposta: ' . $e->getMessage(),
        ];
    }
}

public static function avaliarResposta() {
    try {
        $input = JFactory::getApplication()->input;
        $pergunta = $input->get('pergunta', '', 'STRING');
        $feedback = $input->get('feedback', '', 'STRING');

        if (!in_array($feedback, ['Útil', 'Não Útil'])) {
            return ['success' => false, 'message' => 'Feedback inválido.'];
        }

        $pdo = self::conectarBanco();
        $stmt = $pdo->prepare("UPDATE interacoes_assistente SET feedback = :feedback WHERE pergunta = :pergunta ORDER BY data_hora DESC LIMIT 1");
        $stmt->execute([
            ':feedback' => $feedback,
            ':pergunta' => $pergunta,
        ]);

        return ['success' => true, 'message' => 'Feedback salvo com sucesso.'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Erro ao salvar feedback: ' . $e->getMessage()];
    }
}

public static function calcularDiasRestantes($cpf)
{
    try {
        $pdo = self::conectarBanco();

        // Remove a formatação do CPF
        $cpfSemFormatacao = preg_replace('/\D/', '', $cpf);

        // Calcula o total de dias de férias tirados no ano atual
        $stmt = $pdo->prepare("
            SELECT SUM(numero_dias) AS dias_tirados
            FROM solicitacoes_afastamento
            WHERE tipo_afastamento = 'Férias'
              AND REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), '/', '') = :cpf
              AND YEAR(data_inicio) = YEAR(CURDATE())
        ");
        $stmt->execute([':cpf' => $cpfSemFormatacao]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        $diasTirados = $resultado['dias_tirados'] ? (int)$resultado['dias_tirados'] : 0;

        // Dias totais de férias por ano
        $diasTotais = 30;

        // Calcula os dias restantes
        $diasRestantes = max($diasTotais - $diasTirados, 0);

        return [
            'success' => true,
            'dias_tirados' => $diasTirados,
            'dias_restantes' => $diasRestantes,
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Erro ao calcular dias restantes: ' . $e->getMessage(),
        ];
    }
}

public static function buscarDadosPorCPF($cpf) {
    try {
        $pdo = self::conectarBanco();

        // Remove formatação do CPF
        $cpfSemFormatacao = preg_replace('/\D/', '', $cpf);

        // Busca os dados pelo CPF
        $stmt = $pdo->prepare("
            SELECT nome_completo, nome_guerra, posto_grad, secao, situacao
            FROM solicitacoes_afastamento
            WHERE REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), '/', '') = :cpf
            LIMIT 1
        ");
        $stmt->execute([':cpf' => $cpfSemFormatacao]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            return ['success' => true, 'data' => $dados];
        } else {
            return ['success' => false, 'message' => 'Nenhum registro encontrado para o CPF informado.'];
        }
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Erro ao buscar dados: ' . $e->getMessage(),
        ];
    }
}

public static function buscarSemRetorno()
{
    try {
        $input = JFactory::getApplication()->input;
        $cpf = $input->get('cpf', '', 'STRING'); // CPF recebido

        // Formata o CPF
        $cpf = formatarCPF($cpf);

        // Conexão com o banco
        $pdo = self::conectarBanco();

        // Query para buscar registros sem retorno
        $query = "
            SELECT * 
            FROM solicitacoes_afastamento 
            WHERE cpf = :cpf 
              AND (retorno IS NULL OR retorno = '')
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR); // Certifique-se do tipo do CPF
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'success' => true,
            'data' => $resultados,
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Erro ao buscar registros sem retorno: ' . $e->getMessage(),
        ];
    }
}






}

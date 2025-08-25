<?php
// includes/funcoes.php
require_once __DIR__ . '/config.php';

function estaLogado() {
    return isset($_SESSION['usuario_id']);
}

function obterUsuario() {
    if (estaLogado()) {
        $db = conectarDB();
        $stmt = $db->prepare('SELECT id, nome, email FROM usuarios WHERE id = :id');
        $stmt->bindValue(':id', $_SESSION['usuario_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function adicionarMedicamento($usuario_id, $nome, $dose, $horario, $anotacao = null, $frequencia = 'diario', $dias_intervalo = 1) {
    $db = conectarDB();
    $stmt = $db->prepare('INSERT INTO medicamentos (usuario_id, nome, dose, horario, anotacao, frequencia, dias_intervalo) VALUES (:uid, :nome, :dose, :horario, :anotacao, :freq, :dias)');
    $stmt->execute([
        ':uid' => $usuario_id,
        ':nome' => $nome,
        ':dose' => $dose,
        ':horario' => $horario,
        ':anotacao' => $anotacao,
        ':freq' => $frequencia,
        ':dias' => (int)$dias_intervalo
    ]);
    return $db->lastInsertId();
}

function excluirMedicamento($usuario_id, $medicamento_id) {
    $db = conectarDB();
    $stmt = $db->prepare('DELETE FROM medicamentos WHERE id = :id AND usuario_id = :uid');
    return $stmt->execute([':id' => $medicamento_id, ':uid' => $usuario_id]);
}

function marcarTomado($usuario_id, $medicamento_id) {
    $db = conectarDB();
    // Verifica se o medicamento pertence ao usuário
    $ver = $db->prepare('SELECT id FROM medicamentos WHERE id = :id AND usuario_id = :uid');
    $ver->execute([':id' => $medicamento_id, ':uid' => $usuario_id]);
    if (!$ver->fetch()) {
        return false;
    }
    $stmt = $db->prepare('INSERT INTO historico (medicamento_id) VALUES (:mid)');
    $stmt->execute([':mid' => $medicamento_id]);
    return $db->lastInsertId();
}

function obterMedicamentos($usuario_id) {
    $db = conectarDB();
    $stmt = $db->prepare('SELECT * FROM medicamentos WHERE usuario_id = :usuario_id ORDER BY horario');
    $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obterHistorico($usuario_id, $limite = 10) {
    $db = conectarDB();
    $stmt = $db->prepare('SELECT h.id, 
                                 DATETIME(h.data_hora, "localtime") as data_hora_corrigida, 
                                 m.nome, 
                                 m.dose 
                         FROM historico h
                         JOIN medicamentos m ON h.medicamento_id = m.id
                         WHERE m.usuario_id = :usuario_id
                         ORDER BY h.data_hora DESC
                         LIMIT :limite');
    $stmt->bindValue(':usuario_id', $usuario_id);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatar datas para exibição amigável
    foreach($resultados as &$item) {
        $item['data_hora'] = date('d/m/Y H:i', strtotime($item['data_hora_corrigida']));
    }
    
    return $resultados;
}

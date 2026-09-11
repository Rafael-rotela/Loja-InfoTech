<?php


header('Content-Type: application/json');
$resposta = true;
$array = [];
if ($resposta) {
    $array = [
        "status" => 200,
        "msg" => "Ação realizada com sucesso"
    ];
} else {
    $array = [
        "status" => 400,
        "msg" => "Erro de solicitacao"
    ];
}


echo json_encode($array);

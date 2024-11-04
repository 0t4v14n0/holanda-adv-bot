<?php

$servidor = 'mysqlASW';
$usuario  = 'root';
$senha    = 'root';
$banco    = 'bot';
$conn     = new mysqli($servidor, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$escolha = 0;
$status = 0;
$id = 0;

$opcoes = ["1", "2", "3", "4", "5", "6", "7"];

function atualizar($coluna, $valor, $telefone, $conn) {
    $sql = "UPDATE usuario SET $coluna = ? WHERE telefone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $valor, $telefone);
    return $stmt->execute(); // Retorna true se a atualização for bem-sucedida
}

function busca($bu, $telefone, $conn) {
    // Prepara a consulta para evitar SQL Injection
    $sql = "SELECT $bu FROM usuario WHERE telefone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $telefone);
    $stmt->execute();
    
    // Obtém o resultado
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Retorna o valor do campo solicitado
        return $row[$bu];
    }
    
    // Retorna null se não houver resultado
    return null;
}

function primeiraMSG(){
    echo("Ola, tudo bem ? sou um assistente virtual do escritorio Holanda Advogados Associados
    Digite o numero da opcao que deseja ?
    1- Direito de Família (Divórcio, Pensão, etc)
    2- Servidores Públicos
    3- Direito Trabalhista 
    4- Direito Previdenciário (Pensão, aposentadoria, benefícios, etc)
    5- Registro de Marca
    6- Direito Empresarial (Assessoria Jurídica, Execuções, etc)
    7- Outros Segmentos");
}

function segundaMSG(){
    echo("Qual e o seu nome ?");
}

function criacaoMSG($msg,$nome){

    static $telefoneCelio = '558191199486';
    static $telefoneRaissa = '558198485543';

    switch ($msg) {
        case "1":
            $mensagem = 'Olá, Celio Holanda! Meu nome é ' . $nome . ' e queria tratar com você sobre "Direito de Família (Divórcio, Pensão, etc)".';
            $linkWhatsApp = 'https://wa.me/' . $telefoneCelio . '?text=' . urlencode($mensagem);
            echo('Gerei um link para que você entre em contato com um especialista: ' . $linkWhatsApp);
            break;
        case "2":
            $resposta = 'Olá! Meu nome é ' . $nome . ' e queria discutir sobre "Servidores Públicos".';
            $linkWhatsApp = 'https://wa.me/' . $telefoneCelio . '?text=' . urlencode($resposta);
            echo('Gerei um link para que você entre em contato com um especialista: ' . $linkWhatsApp);
            break;
        case "3":
            $resposta = 'Olá! Meu nome é ' . $nome . ' e queria tratar sobre "Direito Trabalhista".';
            $linkWhatsApp = 'https://wa.me/' . $telefoneCelio . '?text=' . urlencode($resposta);
            echo('Gerei um link para que você entre em contato com um especialista: ' . $linkWhatsApp);
            break;
        case "4":
            $resposta = 'Olá! Meu nome é ' . $nome . ' e gostaria de discutir "Direito Previdenciário (Pensão, aposentadoria, benefícios, etc)".';
            $linkWhatsApp = 'https://wa.me/' . $telefoneCelio . '?text=' . urlencode($resposta);
            echo('Gerei um link para que você entre em contato com um especialista: ' . $linkWhatsApp);
            break;
        case "5":
            $resposta = 'Olá! Meu nome é ' . $nome . ' e gostaria de falar sobre "Registro de Marca".';
            $linkWhatsApp = 'https://wa.me/' . $telefoneCelio . '?text=' . urlencode($resposta);
            echo('Gerei um link para que você entre em contato com um especialista: ' . $linkWhatsApp);
            break;
        case "6":
            $resposta = 'Olá! Meu nome é ' . $nome . ' e gostaria de discutir "Direito Empresarial (Assessoria Jurídica, Execuções, etc)".';
            $linkWhatsApp = 'https://wa.me/' . $telefoneCelio . '?text=' . urlencode($resposta);
            echo('Gerei um link para que você entre em contato com um especialista: ' . $linkWhatsApp);
            break;
        case "7":
            $resposta = 'Olá! Meu nome é ' . $nome . ' e gostaria de falar sobre "Outros Segmentos".';
            $linkWhatsApp = 'https://wa.me/' . $telefoneCelio . '?text=' . urlencode($resposta);
            echo('Gerei um link para que você entre em contato com um especialista: ' . $linkWhatsApp);
            break;
        default:
            echo("Informe uma escolha válida...");
            break;
    }

}

if (!$conn) {

    die("Erro na conexão do BD: " . mysqli_connect_error());

} else {

    $telefone = $_GET['telefone'];
    $msg      = $_GET['msg'];

    if (busca("telefone", $telefone, $conn) == null) {
        // Certifique-se de que $nome e $status têm valores válidos antes de inserir
        $sql = "INSERT INTO usuario (telefone, nome, status) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $telefone, $nome, $status); // 'ssi' indica dois strings e um inteiro
        $stmt->execute(); // Execute a instrução preparada
    }

    $status = busca("status",$telefone,$conn);

    if ($status == 0) {
        primeiraMSG();
        atualizar("status",2,$telefone,$conn);
    } elseif ($status == 2) {
        if (in_array($msg, $opcoes)) {
            atualizar("msg",$msg,$telefone,$conn);
            segundaMSG();
            atualizar("status",3,$telefone,$conn);
        }else{
            echo("Digite uma opçao valida...");
        }
    } elseif ($status == 3) {
        atualizar("nome",$msg,$telefone,$conn);
        criacaoMSG(busca("msg",$telefone,$conn),busca("nome",$telefone,$conn));
        atualizar("status",0,$telefone,$conn);
        atualizar("status",0,$telefone,$conn);
    }

}

$conn->close();

?>
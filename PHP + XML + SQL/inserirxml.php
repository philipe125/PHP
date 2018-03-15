<!doctype html>
<html>
<head>
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
	
	<form method="POST" action="inserirxml.php">
	Por favor, preencha as informações do produto que deseja cadastrar:<br><br>
	<b>Codigo</b>:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type = "text" name= "Reduzido"><br><br>
	<b>Descrição</b>:&nbsp&nbsp&nbsp <input type = "text" name= "Descricao"><br><br>
	<b>Fornecedor</b>: <input type = "text" name= "Fornecedor"><br><br>
	<b>Preço</b>:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <input type = "text" name= "PrecoPor"><br><br>
	
	<input type="submit" name="inserir" value="Cadastrar">
	</form><br><br>
	
	<a href="index.php">Clique aqui para retornar ao menu principal</a>
	
</body>
</html>

<?php header("Content-Type: text/html; charset=ISO-8859-1", true);

if(isset($_POST['inserir']))
{   //variavel recebe o objeto DOM
    $xml = new DOMDocument();
    $xml->load('archive/0303.xml');
    //cada variavel captura o que o usuario digitou nos campos atraves do $_POST
    $codigo = $_POST['Reduzido'];
    $descricao = $_POST['Descricao'];
    $fornecedor = $_POST['Fornecedor'];
    $preco = $_POST['PrecoPor'];
    $datahoraatualizacao = date('Y-m-d- H:i');
    
    //captura novas 'tags raiz' produtos do xml
    $rootTag = $xml->getElementsByTagName("produtos")->item(0);
    
    //cria as novas 'sub tags' produto do xml
    $infoTag = $xml->createElement("produto");
    $codigoTag = $xml->createElement("Reduzido", $codigo);
    $descricaoTag = $xml->createElement("Descricao", $descricao);
    $fornecedorTag = $xml->createElement("Fornecedor", $fornecedor);
    $precoTag = $xml->createElement("PrecoPor", $preco);
    $datahoraatualizacaoTag = $xml->createElement("datahoraatualizacao", $datahoraatualizacao);
        
        //insere os valores nas 'sub tags'
        $infoTag->appendChild($codigoTag);
        $infoTag->appendChild($descricaoTag);
        $infoTag->appendChild($fornecedorTag);
        $infoTag->appendChild($precoTag);
        $infoTag->appendChild($datahoraatualizacaoTag);
    
    $rootTag->appendChild($infoTag);
    //grava no arquivo 0303.xml
    $xml->save('archive/0303.xml');
        
}
?>

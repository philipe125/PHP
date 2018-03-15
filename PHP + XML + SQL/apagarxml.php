<!doctype html>
<html>
<head>
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
	
	<form method="POST" action="apagarxml.php">
	Por favor, digite o codigo do produto que deseja excluir:<br><br>
	<b>Codigo</b>:&nbsp&nbsp <input type = "text" name= "Reduzido"><br><br>
	<input type="submit" name="apagar" value="Confirmar"><br><br>
	
	<a href="index.php">Clique aqui para retornar ao menu principal</a>
	
</body>
</html>

<?php header("Content-Type: text/html; charset=ISO-8859-1", true);

//Caso o usuário clique no botão para confirmar o código do produto que deseja excluir
if(isset($_POST['apagar']))
{   //variavel recebe o objeto DOM
    $xml = new DOMDocument();
    $xml->load('archive/0303.xml');
    //CAPTURA DO CODIGO DIGITADO NO INPUT
    $codigo = $_POST['Reduzido'];
    
    $xpath = new DOMXPath($xml);
    //ATRAVES DO XPATH REALIZEI A PESQUISA DO PRODUTO RELACIONADO AO CODIGO
    //DIGITADO E O REMOVIDO DIRETAMENTE PELO CAMINHO ESPECIFICADO
    foreach ($xpath->query("/produtos/produto[Reduzido = '$codigo']") as $node)
    {
        //remove o nó-filho pesquisado
        $node->parentNode->removeChild($node);
    }
    
    $xml->formatoutput = true;
    //SALVAR ARQUIVO XML APÓS MODIFICAÇÃO
    $xml->save('archive/0303.xml');
        
}
?>

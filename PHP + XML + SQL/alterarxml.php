<!doctype html>
<html>
<head>
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" /> 
</head>
<body>
	<form method="POST" action="alterarxml.php">
	Por favor, digite o código do produto que deseja pesquisar:<br><br>
	<b>Codigo</b>:&nbsp <input type = "text" name= "pesquisarCodigo"><br><br>
	<input type="submit" name="pesquisar" value="Pesquisar"><br><br>
	</form>
	<?php header("Content-Type: text/html; charset=ISO-8859-1", true);
	
	//FUNÇÃO PARA REALIZAR PESQUISA DO PRODUTO PELO CÓDIGO
	if(isset($_POST['pesquisar']))
	{
	    //variavel codigo recebe o codigo digitado pelo usuario no input
	    $codigo = $_POST['pesquisarCodigo'];
	    //variavel recebe o objeto DOM
	    $DOMDocument = new DOMDocument('1.0','utf-8');
	    $DOMDocument->preserveWhiteSpace = false;
	    $DOMDocument->load('archive/0303.xml');
	    $produtos = $DOMDocument->getElementsByTagName('produto');
	    //VARRE O XML
	    foreach($produtos as $produto){
	       //CAPTURA O CÓDIGO DIGITADO NA TAG 'REDUZIDO'
	        $label = $produto->getElementsByTagName('Reduzido')->item(0)->nodeValue;
	        //VERIFICA SE O VALOR CONTIDO DENTRO DA TAG <REDUZIDO> É IGUAL AO CODIGO
	        //PASSADO COMO PARAMETRO
	        if($label == $codigo)
	        {
	                   //RETORNA OS REGISTROS RELACIONADOS AO PRODUTO DAQUELE CODIGO  
        	         echo $produto->getElementsByTagName("Reduzido")->item(0)->nodeValue;
        	         echo '<br>';
        	         echo $produto->getElementsByTagName("Descricao")->item(0)->nodeValue;
        	         echo '<br>';
        	         echo $produto->getElementsByTagName("Fornecedor")->item(0)->nodeValue;
        	         echo '<br>';
        	         echo $produto->getElementsByTagName("PrecoPor")->item(0)->nodeValue;
        	         echo '<br><br>';
        	         echo 'Digite agora os novos valores:<br><br>';       	     
	        }
	    }
	    
	    $DOMDocument->save('archive/0303.xml');

	}
	//NESSA LINHA DE CÓDIGO EM PHP, O USUÁRIO IRA DIGITAR OS VALORES QUE ELE DESEJA
	//MODIFICAR DO PRODUTO
	?>
	<form method="POST" action="alterarxml.php">
	<!-- CONSIDERANDO QUE O CÓDIGO É UNICO POR PRODUTO, MANTIVE O INPUT COMO NÃO-EDITAVEL 
	RECEBENDO O VALOR DO CODIGO PREENCHIDO ANTERIORMENTE NA PESQUISA -->
	<br><b>Codigo</b>:&nbsp<input type = "text" name= "Reduzido" value="<?php echo $codigo;?>" readonly><br>
	<br><b>Descricao</b>:&nbsp<input type = "text" name= "Descricao"><br><br>
    <b>Fornecedor</b>:&nbsp<input type = "text" name= "Fornecedor"><br><br>
    <b>Preço</b>:&nbsp<input type = "text" name= "Preco"><br><br>
    <input type="submit" name="alterar" value="Confirmar"><br><br>
	</form><br>
	<?php 
	if(isset($_POST['alterar']))
	{
	    //cada variavel captura o que o usuario digitou nos campos atraves do $_POST
	    $codigo = $_POST['Reduzido'];
	    $descricao = $_POST['Descricao'];
	    $fornecedor = $_POST['Fornecedor'];
	    $preco = $_POST['Preco'];
	    //variavel recebe o objeto DOM
	    $DOMDocument = new DOMDocument('1.0','utf-8');
	    //true para preservar espaços em branco, e false para não
	    $DOMDocument->preserveWhiteSpace = false;
	    $DOMDocument->load('archive/0303.xml');
	    //variavel aponta para a tag produto do arquivo xml 0303.xml
	    $produtos = $DOMDocument->getElementsByTagName('produto');
	    
	    foreach($produtos as $produto){
	    
	        $label = $produto->getElementsByTagName('Reduzido')->item(0)->nodeValue;
	        // coleta todo conteúdo digitado e armazena dentro dos registros
	        // daquele produto
	        if($label == $codigo)
	        {
	               //atribui o valor a aquele registro de acordo com o que foi preenchido
	               //nos inputs
	               $produto->getElementsByTagName("Reduzido")->item(0)->nodeValue = $codigo;
	               echo '<br>';
        	       $produto->getElementsByTagName("Descricao")->item(0)->nodeValue = $descricao;
        	       echo '<br>';
        	       $produto->getElementsByTagName("Fornecedor")->item(0)->nodeValue = $fornecedor;
        	       echo '<br>';
        	       $produto->getElementsByTagName("PrecoPor")->item(0)->nodeValue = $preco;
        	       echo 'Alteração realizada com êxito';
        	       echo '<br>';
	        }
	    }
	    //salvar as alterações feitas
	    $DOMDocument->save('archive/0303.xml');

	}
	?>
	
	<a href="index.php"><br><br>Clique aqui para retornar ao menu principal</a>
	
</body>
</html>
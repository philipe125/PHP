<!doctype html>
<html>
<head> 
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" /> 
</head>
<body>
	<form method="get" action="http://testedev.baixou.com.br/processo/zip">
	<h3>1� Fa�a o download do arquivo zip:</h3>
	<button type="submit">Download</button>
	</form>
	<h3>2� Importe-o para o projeto clicando no bot�o abaixo:</h3>
	<form action="index.php" method="POST" enctype="multipart/form-data">
	<input type="file" name="file"><input type="submit" name="submit" value="Extrair">
	</form>
	<h3>3� Atualizar os dados da lista:</h3>
	<a href="inserirxml.php">Inclus�o de um novo produto</a><br><br>
	<a href="apagarxml.php">Exclus�o de um produto</a><br><br>
	<a href="alterarxml.php">Altera��o de dados cadastrais de um produto</a>
	<br>
	<h3>4� Gravar seu xml no banco de dados:</h3>
	<form action="" method="post">
	<input type="submit" name="inserirBanco"></input>
	</form>
	<h3>5� Atualizar banco de dados:</h3>
	<form action="" method="POST" enctype="multipart/form-data">
	<input type="submit" name="atualizarBanco" value="Atualizar">
	</form>
	<h3>Visualizar lista:</h3>
	<form action="" method="post">
	<input type="submit" name="gerarLista"></input>
	</form>
	</body>
</html>
	
	

<?php header("Content-Type: text/html; charset=ISO-8859-1", true); 

//Caso o usu�rio clique no bot�o para extrair o xml
if(isset($_POST['submit']))
{
	$array = explode(".",$_FILES["file"]["name"]);
	$arquivo = $array[0];
	$extensao = strtolower(end($array));
	
	//Verifica se o projeto j� foi importado
	if($extensao == "zip" && $arquivo == "0303")
	{
	    //caso a pasta archive do projeto esteja vazia...
		if(is_dir("archive/") == false)
		{
		    //m�todo para mover os arquivos importados para o projeto
			move_uploaded_file($_FILES["file"]["tmp_name"],"tmp/".$_FILES["file"]["name"]);
			$zip = new ZipArchive();
			$zip -> open("tmp/".$_FILES["file"]["name"]);
			//extrai os arquivos do zip para a pasta "archive"
			for($num = 0; $num < $zip->numFiles; $num++)
			{
				$fileInfo = $zip->statIndex($num);
				echo "Extraindo para: ".$fileInfo["name"];
				$zip->extractTo("archive/");
				echo "<br />";
			}
			
			$zip->close();
			unlink("tmp/".$_FILES["file"]["name"]);
			//o objetivo do unlink foi de mover o arquivo xml v�lido salvo na pasta 
			//principal para o projeto
			//o original continha registros invalidos que n�o eram importados
			//para o php ***solu��o a melhorar
			unlink("archive/0303.xml");
			$origem = '0303.xml';
			$destino = 'archive/0303.xml';
			copy($origem, $destino);
			
		 }
		else
		{
		    //CASO O USU�RIO J� TENHA IMPORTADO, O SISTEMA N�O AUTORIZA
			echo "Arquivo j� existente no projeto.'<br>";
		}
	}
	else
	{
	    //CASO O USU�RIO TENTE IMPORTAR UM ARQUIVO INVALIDO PARA O PROJETO
		echo "Somente o arquivo 0303 (em formato zip) ser� permitido.";
	}
}

//Caso o usu�rio clique no bot�o para gerar a lista
if(isset($_POST['gerarLista']))
{
    //SE O DIRET�RIO ARCHIVE ESTIVER VAZIO...
    if(is_dir("archive/") == false)
    {
        //O SISTEMA IR� SOLICITAR QUE FA�A O DOWNLOAD E IMPORTE O ARQUIVO
        echo "Favor realizar o download e a importa��o do arquivo antes de gerar a lista";
    }else{
    $xml = simplexml_load_file('archive/0303.xml');
    
    //LISTAR TODOS OS REGISTROS QUE EST�O DENTRO DO ARQUIVO XML NA TELA
    foreach ($xml->produto as $produto){
        echo '<b>C�digo:</b>&nbsp'.$produto->Reduzido.'<br>';
        echo '<b>Descri��o:</b>&nbsp'.$produto->Descricao.'<br>';
        echo '<b>Fornecedor:</b>&nbsp'.$produto->Fornecedor.'<br>';
        echo '<b>Pre�o: R$</b>&nbsp'.$produto->PrecoPor.'<br><br><br>';
    }
    }
}

//Caso o usu�rio clique no bot�o para importar o xml para o banco
if(isset($_POST['inserirBanco']))
{
    $xml = simplexml_load_file('archive/0303.xml');
    //Diretivas de autentica��o do banco de dados, s�o apenas 'facilitadores'
    //que ser�o passados por parametro no mysqli_connect
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS','');
    define('DBNAME', 'sys');
    //Conecta passando como parametro as informa��es definidas
    $con=mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
        IF($con)
        {
            echo "Conex�o com o banco efetuada com �xito!";
        }
    
    //cria variavel que armazenas as informacoes do xml
    foreach ($xml->produto as $row){
        $reduzido = $row->Reduzido;
        $descricao = $row->Descricao;
        $fornecedor = $row->Fornecedor;
        $precoPor = $row->PrecoPor;
        $datahoraatualizacao = date('Y-m-d- H:i');
    
    
    //e as insere dentro do banco de dados...
    mysqli_query($con, "INSERT INTO produtos (codigo, descricao, fornecedor, preco, datahoraatualizacao) VALUES (".$reduzido.",'".$descricao."','".$fornecedor."','".$precoPor."','".$datahoraatualizacao."')");
    
    }
}

    //Caso o usu�rio clique no bot�o para atualizar o banco
    //**em desenvolvimento**
    if(isset($_POST['atualizarBanco']))
    {
        $xml = simplexml_load_file('archive/0303.xml');
        define('DBHOST', 'localhost');
        define('DBUSER', 'root');
        define('DBPASS','');
        define('DBNAME', 'sys');
    
    $con=mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
    mysqli_query($con, "truncate produtos");
    
        IF($con)
        {
            echo "Conex�o com o banco efetuada com �xito!";
        }
    
    foreach ($xml->produto as $row){
        $reduzido = $row->Reduzido;
        $descricao = $row->Descricao;
        $fornecedor = $row->Fornecedor;
        $precoPor = $row->PrecoPor;
        $datahoraatualizacao = date('Y-m-d- H:i');
        
        mysqli_query($con, "INSERT INTO produtos (codigo, descricao, fornecedor, preco, datahoraatualizacao) VALUES (".$reduzido.",'".$descricao."','".$fornecedor."','".$precoPor."','".$datahoraatualizacao."')");
        
    }
}
    ?>
<!doctype html>
<html>
<head> 
	<meta http-equiv="content-Type" content="text/html; charset=iso-8859-1" /> 
</head>
<body>
	<form method="get" action="http://testedev.baixou.com.br/processo/zip">
	<h3>1º Faça o download do arquivo zip:</h3>
	<button type="submit">Download</button>
	</form>
	<h3>2º Importe-o para o projeto clicando no botão abaixo:</h3>
	<form action="index.php" method="POST" enctype="multipart/form-data">
	<input type="file" name="file"><input type="submit" name="submit" value="Extrair">
	</form>
	<h3>3º Atualizar os dados da lista:</h3>
	<a href="inserirxml.php">Inclusão de um novo produto</a><br><br>
	<a href="apagarxml.php">Exclusão de um produto</a><br><br>
	<a href="alterarxml.php">Alteração de dados cadastrais de um produto</a>
	<br>
	<h3>4º Gravar seu xml no banco de dados:</h3>
	<form action="" method="post">
	<input type="submit" name="inserirBanco"></input>
	</form>
	<h3>5º Atualizar banco de dados:</h3>
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

//Caso o usuário clique no botão para extrair o xml
if(isset($_POST['submit']))
{
	$array = explode(".",$_FILES["file"]["name"]);
	$arquivo = $array[0];
	$extensao = strtolower(end($array));
	
	//Verifica se o projeto já foi importado
	if($extensao == "zip" && $arquivo == "0303")
	{
	    //caso a pasta archive do projeto esteja vazia...
		if(is_dir("archive/") == false)
		{
		    //método para mover os arquivos importados para o projeto
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
			//o objetivo do unlink foi de mover o arquivo xml válido salvo na pasta 
			//principal para o projeto
			//o original continha registros invalidos que não eram importados
			//para o php ***solução a melhorar
			unlink("archive/0303.xml");
			$origem = '0303.xml';
			$destino = 'archive/0303.xml';
			copy($origem, $destino);
			
		 }
		else
		{
		    //CASO O USUÁRIO JÁ TENHA IMPORTADO, O SISTEMA NÃO AUTORIZA
			echo "Arquivo já existente no projeto.'<br>";
		}
	}
	else
	{
	    //CASO O USUÁRIO TENTE IMPORTAR UM ARQUIVO INVALIDO PARA O PROJETO
		echo "Somente o arquivo 0303 (em formato zip) será permitido.";
	}
}

//Caso o usuário clique no botão para gerar a lista
if(isset($_POST['gerarLista']))
{
    //SE O DIRETÓRIO ARCHIVE ESTIVER VAZIO...
    if(is_dir("archive/") == false)
    {
        //O SISTEMA IRÁ SOLICITAR QUE FAÇA O DOWNLOAD E IMPORTE O ARQUIVO
        echo "Favor realizar o download e a importação do arquivo antes de gerar a lista";
    }else{
    $xml = simplexml_load_file('archive/0303.xml');
    
    //LISTAR TODOS OS REGISTROS QUE ESTÃO DENTRO DO ARQUIVO XML NA TELA
    foreach ($xml->produto as $produto){
        echo '<b>Código:</b>&nbsp'.$produto->Reduzido.'<br>';
        echo '<b>Descrição:</b>&nbsp'.$produto->Descricao.'<br>';
        echo '<b>Fornecedor:</b>&nbsp'.$produto->Fornecedor.'<br>';
        echo '<b>Preço: R$</b>&nbsp'.$produto->PrecoPor.'<br><br><br>';
    }
    }
}

//Caso o usuário clique no botão para importar o xml para o banco
if(isset($_POST['inserirBanco']))
{
    $xml = simplexml_load_file('archive/0303.xml');
    //Diretivas de autenticação do banco de dados, são apenas 'facilitadores'
    //que serão passados por parametro no mysqli_connect
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS','');
    define('DBNAME', 'sys');
    //Conecta passando como parametro as informações definidas
    $con=mysqli_connect(DBHOST,DBUSER,DBPASS,DBNAME);
        IF($con)
        {
            echo "Conexão com o banco efetuada com êxito!";
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

    //Caso o usuário clique no botão para atualizar o banco
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
            echo "Conexão com o banco efetuada com êxito!";
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
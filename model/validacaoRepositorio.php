<meta charset="UTF-8">
<?php 
    session_start();
    if(!isset($_SESSION["numLogin"])){
        header("location: ../erro/401.php");
        exit;
    }

    if(isset($_POST['nome']) && isset($_POST['prof_orientador']) && isset($_POST['membros_banca']) && isset($_POST['membros_grupo']) 
    && isset($_POST['curso']) && isset($_POST['ano']) && isset($_POST['mencao']) && isset($_POST['resumo']) && isset($_POST['abstract'])
    && isset($_POST['pa_ch']) && isset($_POST['key_words']) && isset($_POST['data_ap']) && isset($_POST['instituicao'])){
        $dados = [];
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prof_orientador = filter_input(INPUT_POST, 'prof_orientador', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $prof_corientador = filter_input(INPUT_POST, 'prof_corientador', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $membros_grupo = filter_input(INPUT_POST, 'membros_grupo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $membros_banca = filter_input(INPUT_POST, 'membros_banca', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $curso = filter_input(INPUT_POST, 'curso', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mencao = filter_input(INPUT_POST, 'mencao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $resumo = filter_input(INPUT_POST, 'resumo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $abstract = filter_input(INPUT_POST, 'abstract', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pa_ch = filter_input(INPUT_POST, 'pa_ch', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $key_words = filter_input(INPUT_POST, 'key_words', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data_ap = filter_input(INPUT_POST, 'data_ap', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $instituicao = filter_input(INPUT_POST, 'instituicao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        array_push($dados, $nome, $prof_orientador, $membros_grupo, $membros_banca, $curso, $ano, $mencao, $resumo, $abstract, $pa_ch, $key_words, $data_ap, $instituicao);

        if($prof_corientador == ""){
            $prof_corientador = NULL;
        }

        foreach($dados as $dado){
            if($dado == ""){
                $impedimento = TRUE;
                if($_SESSION['tipo'] == 1){
                    header("Location: ../dashboard/adicionar/?dadoFaltante");
                }else{
                    header("Location: ../instituicao/adicionar/?dadoFaltante");
                }
            }else{
                $impedimento = FALSE;
            }
        }

        #FOTO DE PERFIL
        if($imagem = $_FILES['foto']['tmp_name'] != NULL){
            $imagem = $_FILES['foto']['tmp_name'];
            $foto_tamanho = $_FILES['foto']['size'];
            $foto_tipo = $_FILES['foto']['type'];
            $foto_nome_img = $_FILES['foto']['name'];

            $fp = fopen($imagem, "rb");
            $foto_conteudo = fread($fp, $foto_tamanho);
            $foto_conteudo = addslashes($foto_conteudo);
            fclose($fp);
        }elseif(!isset($_POST['foto'])){
            $imagem = '../assets/img/image.bin';
            $foto_tipo = 'image/png';
            $foto_nome_img = 'padrão';
            $foto_tamanho = filesize('../assets/img/image.bin');

            $fp = fopen($imagem, "rb");
            $foto_conteudo = fread($fp, $foto_tamanho);
            $foto_conteudo = addslashes($foto_conteudo);
            fclose($fp);        
        }

        #PDF
        $pdf = $_FILES['pdf']['tmp_name'];
        $pdf_tamanho = $_FILES['pdf']['size'];
        $pdf_tipo = $_FILES['pdf']['type'];
        $pdf_arquivos = $_FILES['pdf']['name'];

        $fp = fopen($pdf, "rb");
        $pdf_conteudo = fread($fp, $pdf_tamanho);
        $pdf_conteudo = addslashes($pdf_conteudo);
        fclose($fp);

        #ZIP
        if($zip = $_FILES['zip']['tmp_name'] != NULL){
            $zip = $_FILES['zip']['tmp_name'];
            $zip_tamanho = $_FILES['zip']['size'];
            $zip_tipo = $_FILES['zip']['type'];
            $zip_arquivos = $_FILES['zip']['name'];

            $fp = fopen($zip, "rb");
            $zip_conteudo = fread($fp, $zip_tamanho);
            $zip_conteudo = addslashes($zip_conteudo);
            fclose($fp);
            $zipTrue = TRUE;
        }elseif($zip = $_FILES['zip']['tmp_name'] == NULL){
            $zipTrue = FALSE;
        }

        include "../conexao/conexao.inc";
        $data_add = date("Y-m-d H:i:s");

        if($zipTrue == TRUE){
            if($_SESSION['tipo'] == 1){
                $id_prof = $_SESSION['codigo_u'];
                $query = "INSERT INTO repositorio VALUES(NULL, '$nome', '$prof_orientador', '$prof_corientador', '$membros_grupo', '$membros_banca', '$curso', '$ano', '$mencao', '$resumo', '$abstract',
                '$pa_ch', '$key_words', '$data_ap', '$instituicao', '$id_prof' , '$data_add', NULL, '$foto_conteudo', '$pdf_conteudo', '$zip_conteudo')";
            }else{
                $query = "INSERT INTO repositorio VALUES(NULL, '$nome', '$prof_orientador', '$prof_corientador', '$membros_grupo', '$membros_banca', '$curso', '$ano', '$mencao', '$resumo', '$abstract',
                '$pa_ch', '$key_words', '$data_ap', '$instituicao', 0, '$data_add', NULL, '$foto_conteudo', '$pdf_conteudo', '$zip_conteudo')";
            }    
        }else{
            if($_SESSION['tipo'] == 1){
                $id_prof = $_SESSION['codigo_u'];
                $query = "INSERT INTO repositorio VALUES(NULL, '$nome', '$prof_orientador', '$prof_corientador', '$membros_grupo', '$membros_banca', '$curso', '$ano', '$mencao', '$resumo', '$abstract',
                '$pa_ch', '$key_words', '$data_ap', '$instituicao', '$id_prof' , '$data_add', NULL, '$foto_conteudo', '$pdf_conteudo', NULL)";
            }else{
                $query = "INSERT INTO repositorio VALUES(NULL, '$nome', '$prof_orientador', '$prof_corientador', '$membros_grupo', '$membros_banca', '$curso', '$ano', '$mencao', '$resumo', '$abstract',
                '$pa_ch', '$key_words', '$data_ap', '$instituicao', 0, '$data_add', NULL, '$foto_conteudo', '$pdf_conteudo', NULL)";
            }    
        }

        if($impedimento == FALSE){
            if (mysqli_query($conexao, $query)) {
                $last_id = mysqli_insert_id($conexao);
            }
            mysqli_close($conexao);
            header("Location: ../projeto/?tcc=$last_id&criado=TRUE");
        }
    }
?>
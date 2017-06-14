<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once 'config.php';
        // put your code here
        
        while(!set_time_limit(0)){
            //garantindo que a execução do código não irá parar após executar por um longo período de tempo.
        }
        /*
        try{
        $conexao = new PDO('pgsql:host='.host.';dbname='.database,user,password);
        
        }catch(PDOException $e){
            
            exit( "Error!: ".$e->getMessage()."<br/>");
            
        }
        */
        $conexao = pg_connect("host = ".host." dbname = ".database." user = ".user);
        /*
        $result = $conexao->query("SELECT value "
                                             ." FROM ".tabela_valor
                                             ." WHERE cast(data as date) = cast('".intervalo_inicio."' as date)");
            */
        $result = pg_query("SELECT value "
                                             ." FROM ".tabela_valor
                                             ." WHERE cast(data as date) = cast('".intervalo_inicio."' as date)");
   //     define("valor_inicial",  $result->fetch(SQLITE3_NUM)[0]);
            define("valor_inicial", pg_fetch_row($result)[0]);
           /* 
            $result = $conexao->query("SELECT value "
                                           ." FROM ".tabela_valor
                                           ." WHERE cast(data as date) = cast( '".intervalo_fim."' as date)");
                                   
        define("valor_final" , $result->fetch(SQLITE3_NUM)[0]);
        */
        $result = pg_query("SELECT value "
                                           ." FROM ".tabela_valor
                                           ." WHERE cast(data as date) = cast( '".intervalo_fim."' as date)");
        define("valor_final", pg_fetch_row($result)[0]);
        
            define("aumentou" , valor_inicial-valor_final>0);
        /*
        $result = $conexao->query("SELECT P.id_palavra, ISNULL(SUM(qtd),0),count(R.id_palavra) "
                        . ""
                . " FROM ".tabela_regex." R , ".tabela_crawler." C , ".tabela_palavras." P "
                . "     "
                . " WHERE R.id_tabela_crawler = C.id and ( C.date_created >= '".intervalo_inicio."' "
                . " AND '".intervalo_fim."' >= C.date_created) "
                . " AND P.id > 0 "
                . " LEFT OUTER JOIN ".tabela_palavras." P ON P.id = R.id_palavra"
				. "GROUP BY P.id "
				. "ORDER BY P.id ");
        
        $result2 = $conexao->query("SELECT RE.id_palavra, count(RE.id_palavra) as total "
                        . "FROM ".tabela_regex." RE "
                        . "GROUP BY id_palavra "
                . "ORDER BY id_palavra");
        while($palavra = $result->fetch(SQLITE3_NUM)){
            
		//0 = qtd de ocorrencias no intervalo, 1 = qtd de noticias em que aparece;(em $matriz_total_palavras);
            $matriz_total_palavras[$palavra[0]] = array($palavra[1],$result2->fetch(SQLITE3_NUM)[1]);
            
        }
        */
            $result = pg_query("SELECT P.id_palavra, ISNULL(SUM(qtd),0),count(R.id_palavra) "
                        . ""
                . " FROM ".tabela_regex." R , ".tabela_crawler." C , ".tabela_palavras." P "
                . "     "
                . " WHERE R.id_tabela_crawler = C.id and ( C.date_created >= '".intervalo_inicio."' "
                . " AND '".intervalo_fim."' >= C.date_created) "
                . " AND P.id > 0 "
                . " LEFT OUTER JOIN ".tabela_palavras." P ON P.id = R.id_palavra"
				. "GROUP BY P.id "
				. "ORDER BY P.id ");
//            
//            $result2 = pg_query("SELECT RE.id_palavra, count(RE.id_palavra) as total "
//                        . "FROM ".tabela_regex." RE "
//                        . "GROUP BY id_palavra "
//                . "ORDER BY id_palavra");
            
            while($palavra = pg_fetch_row($result)){
                $matriz_total_palavras[$palavra[0]] = array($palavra[1] , $palavra[2]);
            }
            
            
        $num_dimensoes = count($matriz_total_palavras);
        
        unset($palavra);
        
        
		//                                                                                                                                     data_inicio AND data_fim
        
        /*
        $result = $conexao->query("SELECT R.id, R.id_palavra, R.qtd, a.total "
                . " FROM ".tabela_regex." R, ".tabela_crawler." C, "
                . "         (SELECT RE.id_tabela_crawler, SUM(RE.qtd) as total 
                            FROM ".tabela_regex." RE  
                            GROUP BY id_tabela_crawler"
                . "         ) as a "
                . " WHERE "
                . " R.id_tabela_crawler = C.id AND "
                . " ( C.date_created >= '".intervalo_inicio."' AND "
                . " '".intervalo_fim."' >= C.date_created) AND "
                . " R.id_tabela_crawler = a.id_tabela_crawler "
                . " ORDER BY id_palavra");
                        
                        
        while($noticia = $result->fetch(SQLITE3_NUM)){// 0 = id_noticia ; 1 = id_palavra; 2 = qtd de ocorrencias na noticia; 3 = total de palavras na noticia
            $matriz_noticias[] = array($noticia[0],$noticia[1],$noticia[2],$noticia[3]);
        }
        */
        pg_query("SELECT R.id, R.id_palavra, R.qtd, a.total "
                . " FROM ".tabela_regex." R, ".tabela_crawler." C, "
                . "         (SELECT RE.id_tabela_crawler, SUM(RE.qtd) as total 
                            FROM ".tabela_regex." RE  
                            GROUP BY id_tabela_crawler"
                . "         ) as a "
                . " WHERE "
                . " R.id_tabela_crawler = C.id AND "
                . " ( C.date_created >= '".intervalo_inicio."' AND "
                . " '".intervalo_fim."' >= C.date_created) AND "
                . " R.id_tabela_crawler = a.id_tabela_crawler "
                . " ORDER BY id_palavra");
        while($noticia = pg_fetch_row($result)){
            $matriz_noticias[] = array($noticia[0],$noticia[1],$noticia[2],$noticia[3]);
        }
        unset($noticia);
        
//        
//		$result = $conexao->query(""
//                        . " SELECT P.id, P.palavra "
//                        . " FROM ".tabela_regex." R , ".tabela_crawler." C , ".tabela_palavras." P "
//                        . " WHERE R.id_tabela_crawler = C.id "
//                        . " AND P.id = R.id "
//                        . " AND ( C.date_created >= '".intervalo_inicio."' "
//                        . " AND '".intervalo_fim."' >= C.date_Created)"
//                        . " ORDER BY id_palavra ");
//		
                
                $result = pg_query(" SELECT P.id, P.palavra "
                        . " FROM ".tabela_regex." R , ".tabela_crawler." C , ".tabela_palavras." P "
                        . " WHERE R.id_tabela_crawler = C.id "
                        . " AND P.id = R.id "
                        . " AND ( C.date_created >= '".intervalo_inicio."' "
                        . " AND '".intervalo_fim."' >= C.date_Created)"
                        . " ORDER BY id_palavra ");
                
		?>
		
		<div background="000">
			
			<table>
				<tr>
					<th colspan="<?php echo ($num_dimensoes+2) ;?>" offset= > <b>Pesos/Velocidades</b></th>
				</tr>
				<tr>
					<th>particula</th>
					<?php 
					
						while($row = pg_fetch_row($result)[1]){
							
							echo "<th>".$row[1]."</th>";
							
						}
						
						
						unset($result);
						
						
						$melhor_nota_banco = $conexao->query("SELECT peso from ".tabela_palavras." WHERE id_palavra = 0");
						
						
						
					?>
					<th>Nota</th>
					
					
					
				</tr>
				
			
		
		
		
		<?php
			
			
			
			for($i = 1; $i <= $num_particulas; $i++){
				
				$particulas = cria_particulas();
				
			}
			
			for($iteracao_atual = 1; $iteracao_atual <= num_iteracoes; $iteracao_atual++){
				
				mover_particulas();
				
			}
                        
		echo ("</table>");
                    
			$query = "SELECT peso FROM ".tabela_palavras." "; 
			
			$update = "UPDATE ".tabela_palavras." set peso = ";
			
			$insert = "INSERT into ".tabela_palavras."(palavra,id_palavra,peso) values ('',";
			
			$where = " WHERE id_palavra = ";
			
			if(!$melhor_nota_banco || ($melhor_nota_banco >= 0 && $melhor_nota_banco < $melhor_global[particula_melhor_local])){
			
                            echo "<div background=".'"FFFFFF"'." color=".'"000000"'." > |";
                            $conexao->beginTransaction();
                            
				for($i = 1; $i<=num_dimensoes; $i++){
					
					if($conexao->query($query.$where.$i)->rowCount() > 0){
						
                                            if($conexao->exec($update.$melhor_global[particula_melhor_local][$i-1]." ".$where.$i)>1){
                                                    $conexao->rollBack(); 
                                                    exit("Erro ao atualizar melhor nota no banco");
                                            }
                                            
					}else{
						
                                            if($conexao->exec($conexao,$insert.$i." , ".$melhor_global[particula_melhor_local][$i-1].")") != 1){
                                                $conexao->rollBack();
                                                exit();
                                            }
						
					}
					
                                    echo $melhor_global[particula_melhor_local][$i-1]."|";
					
					
				}
                                
                                $conexao->exec($update.$melhor_global[particula_melhor_local]." ".$where."0") != 1?$conexao->rollBack():$conexao->commit();
				
                                echo $melhor_global[particula_nota]."|</div>";
			
			}else{
				
				if(!melhor_nota_banco){
					
                                    $conexao->exec($insert."0 , ".$melhor_global[particula_melhor_local].")")>1? $conexao->rollBack():null;
                                    
					
				}
				
			}
			
        ?>
        
			
			
		</div>
		
            
    </body>
</html>

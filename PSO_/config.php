<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


##########################################################
define("host","localhost");
														 #
define("user","jhonathanaguiar");
														 #
define("port","");
														 #
define("password","");									 
														 #
define("database","topesi");
														 ######### Banco de dados
define("tabela_crawler","crawler");
														 #
define("tabela_regex","regex");
														 #
define("tabela_valor","bitcoin");
														 #
define("tabela_palavras","palavras");
##########################################################
define("intervalo_inicio","2017-06-13 19:40:53.234057-03");

define("intervalo_fim","2017-06-13 19:42:10.199958-03");

define("num_particulas",5);

define("num_iteracoes",5);
##########################################################
define("particula_posicao",0);
														 #
define("particula_velocidade",1);
														 ######### Constantes Particula
define("particula_melhor_local",2);
														 #
define("particula_nota",3);
##########################################################

##########################################################
define("matriz_total_palavras_qtd_intervalo",0);
														 ######### Constantes matriz_total_palavras
define("matriz_total_palavras_num_noticias_em_que_aparece",1);
##########################################################

##########################################################
define("matriz_noticias_id_noticia",0);
														 #
define("matriz_noticias_id_palavra",1);
														 ######### Constantes matriz_noticias
define("matriz_noticias_qtd_na_noticia",3);
														 #
define("matriz_noticias_total_palavras_noticia",4);
##########################################################

define("w",0.5); //constante de inercia
    




/**
 * cria particulas e as salva em um array
 * @return array(array(posicao,velocidade,melhor_local,nota),...);
 */
function cria_particulas(){
    for($j = 1; $j<= num_particulas; $j++){
	$html = "<tr><td>".$j."</td>";
        for($i = 1; $i<= $num_dimensoes; $i++){

            $posicao = ((mt_rand()/mt_getrandmax())*2) -1;

            $velocidade = mt_rand(-1-$posicao, 1-$posicao);

            $particula[] = array($posicao,$velocidade, $posicao,        0);
                                                     // melhor_local , nota
			
			$html .= "<td>".$posicao."/".$velocidade."</td>";
			
			
			
			
        }
        $particulas[] = $particula;
		$particula = avaliar_particula($particula[$i-1]);
		
		$html .= "<td>".$particula[particula_nota]."</td></tr>";
		
		echo $html;
		
    }
    return $particulas;
}

/**
 * 
 * atualiiza a velocidade e posicao das partículas
 */
function mover_particulas(){
    
	echo "<tr background=". dechex(floor(pow(pow(16,6)-1),$iteracao_atual/num_iteracoes)).">";
    for($i = 0; $i< $num_particulas;$i++){
        
        for($j = 0 ; $j < $num_dimensoes; $j++){
            $posicao = $particulas[$i][particula_posicao][$j];
        
            $velocidade = $particulas[$i][particula_velocidade][$j];
            
            $velocidade = $velocidade * w + 
                pow(sin(pi()*($i+1)*($iteracao_atual/$num_iteracoes)),2)*(mt_rand()/mt_getrandmax())*($particulas[$i][particula_melhor_local][$j]-$posicao) +  //c1*r1* melhor-atual
                pow(cos(pi()*($i+1)*($iteracao_atual/$num_iteracoes)),2)*(mt_rand()/mt_getrandmax())*($melhor_global[0][$j]-$posicao);   //c2*r2* melhor_global-atual
            
            // impedindo as partículas de saírem do espaço[-1.1]
            
            if(abs($posicao + $velocidade) > 1){
                $posicao = $velocidade > 0? 1:-1;
                $velocidade = 0;
            }
            
            $posicao = $posicao + $velocidade;
			
			echo "<td>".$posicao."/".$velocidade."</td>";
			
        }
        
		$particula[$i] = avaliar_particula($particula[$i]);
		
		echo "<td>".$particula[particula_nota]."</td></tr>";
		
    }
    
}

function avaliar_particula($particula){
    $n = count($matriz_noticias);
	$acertos = 0;
	
		
			
	for($noticia = 0; $noticia<$n;$noticia++){
	
		$p = count($matriz_noticias[$noticia]);
		$rise = 0;
											//id_palavra,[qtd_na_noticia,qtd_palavras na noticia];
		foreach($matriz_noticias[$noticia] as $key => $value){
			
			$rise += $particula[$key]*($value[1]/$value[2])*(($key!=$n?(1-($matriz_total_palavras[$key][matriz_total_palavras_num_noticias_em_que_aparece])/$n):(1-$value[0]/$value[1])/2));
			######+= peso*(qtd_na_noticia/qtd_palavras na noticia)*(num(noticias em que aparece != total_noticias)? 1- num_noticias_em_que_aparece/total_noticias : (1- qtd_na_noticia/qtd_palavras_na_noticia)/2)
			
		}
		
		if(($rise > 0 && aumentou) || ($rise < 0 && !aumentou)){
			
			$acertos++;
			
		}
		
	}
    
	$nota = $acertos/$n;
	
	if(!isset($melhor_global)) $melhor_global = $particula;
	else{
		
		if($nota > $particula[particula_nota]){
			
			$particula[particula_melhor_local] = $particula[particula_posicao];
			
			if($nota > $melhor_global[particula_melhor_local]){
				
				$melhor_global = $particula;
				
			}
			
			
		}
		
		
		
	}
	
	return $particula;
	
}




?>

<table id="preguntas" name="preguntas" border>
	<?php 
		if(file_exists("xml/preguntas.xml")){
			if(!($ficheroPreguntas=simplexml_load_file("xml/preguntas.xml"))){
				echo('Ha habido un error al cargar el fichero con las preguntas');
			}else{
				if(isset($_GET['email'])){
					$email = $_GET['email'];
					echo "<tr>";
					echo "<th>Autor</th>";
					echo "<th>Enunciado</th>";
					echo "<th>Respuesta correcta</th>";
					echo "</tr>";
					if($ficheroPreguntas->assessmentItem){
						foreach($ficheroPreguntas->assessmentItem as $assessmentItem){
							if($assessmentItem['author'] == $email){
								echo('<tr>');
								echo('<td>'.$assessmentItem['author'].'</td>');
								echo('<td>'.$assessmentItem->itemBody->p.'</td>');
								echo('<td>'.$assessmentItem->correctResponse->value.'</td');
								echo('</tr>');
							}
						}
					}else{
						echo "No hay preguntas";	
					}	
				}
			}
		}
	?>
</table>
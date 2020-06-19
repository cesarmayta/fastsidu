<?php
/******************************** FASTFORMSPHP  *******************************/
/* PROGRAMA QUE PERMITE CREAR FORMULARIOS Y GESTIONA EL ENVIO DE LA INFORMACION A UNA BASE DE DATOS/
/* EN UN TABLA - PUEDE USARES EN CUALQUIER PROYECTO WEB			      /
/*  AUTOR : CESAR MAYTA AVALOS-CUELA - www.clubprog.com	- 		/
     correo : cesarmayta@gmail.com														
/* *************************************************************************/
//////////////////////////////////
DEFINE('host',''); // AQUI COLOCA EL NOMBRE DEL SERVIDOR 
DEFINE('bd',''); // AQUI COLOCA EN NOMBRE DE LA BASE DE DATOS
DEFINE('user',''); // AQUI COLOCA EL USUARIO DE LA BASE DE DATOS
DEFINE('pwd',''); // AQUI COLOCA EL PASSWORD DEL USUARIO SELECCIONADO

function fq_connect()
{
	//FUNCION QUE SE CONECTA A LA BASE DE DATOS
	$Cn = mysql_connect(host,user,pwd);
	mysql_select_db(bd,$Cn);
	if(!$Cn)
		echo("Error : No se puede conectar a la base de datos");
	return $Cn;
}

function Mostrar_Consulta($sql,$borde,$Ancho,$ColorCab,$ColorBody,$EstiloCab,$EstiloBody,$Pagina="",$mostrar_id,$parametros="")
{
	//EJECUTA LA SENTENCIA INGRESADA
	$Cn = fq_connect();
	$result = mysql_query($sql,$Cn);
	if ($result)
		{
			$html = "<TABLE BORDER=".$borde." width='".$Ancho."'>";
			$nRows = mysql_num_rows($result);
			$nCols = mysql_num_fields($result);
			//CABECERA DE LA TABLA
			$html = $html . "<TR bgcolor = '";
			if($ColorCab == "0")
				$html = $html . "#006699' ";
			else
				$html = $html . $ColorCab . "' ";
			
			if($EstiloCab == "0")
				$html = $html . "class='Cab'>";
			else
				$html = $html . "class='".$EstiloCab."'>";
				
			for($c=0;$c < $nCols;$c++)
				{
					if($mostrar_id == 1)
						$html = $html . "<TD>".mysql_field_name($result, $c)."</TD>";
					else
					{
						if($c > 0)
							$html = $html . "<TD>".mysql_field_name($result, $c)."</TD>";	
					}
						
				}
			if($Pagina != "")
			{
				$html = $html . "<td colspan='2'>Opciones</td>";
			}
			$html = $html . "</TR>";
			//CUERPO DE LA TABLA
			
			for($f=0;$f < $nRows;$f++)
				{
					$html = $html . "<TR bgcolor = '";
					if($ColorBody == "0")
						$html = $html . "#CCCCCC' ";
					else
						$html = $html . $ColorBody . "' ";
					if($EstiloBody == "0")
						$html = $html . "class='Cuerpo'>";
					else
						$html = $html . "class='".$EstiloBody."'>";
					for($c=0;$c < $nCols;$c++)
					{
						if($mostrar_id == 1)
							$html = $html . "<TD>".mysql_result($result,$f,$c)."</TD>";
						else
						{
							if($c > 0)
								$html = $html . "<TD>".mysql_result($result,$f,$c)."</TD>";
						}
					}
					if($Pagina != "")
						{
							//$Pagina = substr($Pagina,strlen($Pagina) - 3,strlen($Pagina));
							
							$html = $html . "<td><a href='".$Pagina."?act=edit&rid=".mysql_result($result,$f,0)."&".$parametros."'>Editar</a></td><td><a href='".$Pagina."?act=del&rid=".mysql_result($result,$f,0)."&".$parametros."'>Borrar</a></td>";
						}
					$html = $html . "</TR>";
				}
			$html = $html . "</TABLE>";
		}
	else
		echo("Error : No se pudo ejecutar la consulta <br>" .mysql_error());
	
	return $html ;

}

function CrearFormulario($sql,$tabla,$boton,$pagina,$edit,$campo_id)
{
	//EJECUTA LA SENTENCIA INGRESADA
	$Cn = fq_connect();
	if($edit != "")
		$sql = $sql . " where ".$campo_id."='".$edit."'";
	$result = mysql_query($sql,$Cn);
	if ($result)
	{
		$nRows = mysql_num_rows($result);
		$nCols = mysql_num_fields($result);
		if($edit != "")
		{
			$html = "<FORM action='".$pagina."?act=upd&rid=".$edit."' method='post' name='form'>\n";
			$boton = "Actualizar Registro";
		}
		else
		{
			$html = "<FORM action='".$pagina."?act=ins' method='post' name='form'>\n";
			$boton = "Insertar Nuevo Registro";
		}
		$html = $html . "<INPUT TYPE='hidden' name=t value='".$tabla."'>\n";
		$html = $html . "<INPUT TYPE='hidden' name=nc value='".$nCols."'>\n";
		$html = $html . "<INPUT TYPE='hidden' name=pf value='".$pagina."'>\n";
		$html = $html . "<TABLE border=0>\n";
		for($c=0;$c < $nCols;$c++)
		{
			$Campo = mysql_field_name($result, $c);
			if($edit != "")
				$edit_field = mysql_result($result,0,$c);
			else
				$edit_field = "";
			$html = $html . "<TR><TD><INPUT TYPE='hidden' name=c".$c." value='".$Campo."'>".$Campo.":</TD><TD><INPUT TYPE='text' name=v".$c." value='".$edit_field."'></TD></TR>\n";
		}
		$html = $html . "<TR><TD colspan=2><INPUT type='submit' name=enviar value='".$boton."'></TD></TR>"; 
		$html = $html . "</TABLE></FORM>\n";

		return $html;
	}
	else
		echo("Error : No se pudo ejecutar la consulta <br>" .mysql_error());
}
?>



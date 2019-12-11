<?php
libxml_disable_entity_loader(false);
global $user_ID; 
if( !$user_ID  || !current_user_can('level_10') ) : 
	 echo "Acceso no autorizado para NO ADMINISTRADORES";
	 return;
endif;

function imageResize($html="",$newWidth="") {

	$needle = '<img width="';
	$lastPos = 0;
	$positions = array();
	if(!$newWidth) $newWidth=500;
	
	while (($lastPos = strpos($html, $needle, $lastPos))!== false) {
		$positions[] = $lastPos;
		$lastPos = $lastPos + strlen($needle);
	}
	
	// Displays 3 and 10
	foreach ($positions as $value) {
		$posWidth=$value+12;
		if(substr($html,$posWidth-1,1)!='"') $posWidth--;

		//$posHeight=$value+25;

$pos=$posWidth;
$width="";
$height="";
do{
	$a=substr($html,$pos,1);
	if($a!='"')$width.=$a;
	$pos++;
}while($a!='"');

$pos+=9;

do{
	$a=substr($html,$pos,1);
	if($a!='"')$height.=$a;
	$pos++;
}while($a!='"');

		
		//Calculamos digitos del tamaño de imagen, habitualmente 3 pero verificar 4 (más de 1000 px)
		//if(substr($html,$posWidth+4,1)=='"') {$lenWidth=4;$posHeight++;} else $lenWidth=3;
		//if(substr($html,$posHeight+4,1)=='"') $lenHeight=4; else $lenHeight=3;
		
		//$width=substr($html,$posWidth,$lenWidth);
		//$height=substr($html,$posHeight,$lenHeight);
		
		if(is_numeric($width)) {
			$newHeight = round($newWidth * $height / $width);
			$html= str_replace('width="'.$width.'" height="'.$height.'"','width="'.$newWidth.'" height="'.$newHeight.'"',$html);
			//$html= str_replace('height="'.$height.'"','height="'.$newHeight.'"',$html);
		}
				
		echo $width;
		echo "-";
		echo $height;
		echo "-";
		echo $newWidth;
		echo "-";
		echo $newHeight;
		echo "-";
		echo "<br>";

	}
	return $html;

}

function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	// Eliminar estas dos líneas si no pasamos por proxy
	if($_SERVER['SERVER_NAME'] != 'ibermutuamur.local' && $_SERVER['SERVER_NAME'] != 'ibermutua.local' && $_SERVER['SERVER_NAME'] != 'ib.arabial.com') {
		//curl_setopt($ch, CURLOPT_PROXY, 'proxy3.ibmutua.inet:8080');
		//curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'app-wordpress:Wordpr3ss;2015');
		curl_setopt($ch, CURLOPT_PROXY, 'http://10.0.2.49:8080');
	}
		
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
// Generamos la URL para WEB EN LA WEB
$urlweb=site_url().'/newsletters/?';
foreach($_GET as $key=>$value)
	if(!empty($value))
		$urlweb.=$key.'='.$value.'&amp;';
	else
		$urlweb.=$key.'&amp;';



$html_head = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Newsletters Ibermutua</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">';

$html_footer.="</body></html>";

$html = '
<style>
#news_admin {
	font-family: "Open Sans", Arial, sans-serif !important;
	width:100%;
	margin:auto;	
}
#news_admin .news-item {
	clear:both;
	background:none;
	padding:5px 0px 10px 0px !important;
	font-family: "Open Sans", Arial, sans-serif !important;
}

#news_admin a:link, a:visited, a:active {
    text-decoration: none;
    color: #005195;
}	

#news_admin .news-item img{
	width:500px;
	/*height:340px !important;
	max-height:340px !important;*/
	margin:auto;
}

#news_admin h2 {
	font-size:22px;
	line-height:24px;
	margin:8px 0px 4px 0px;
}

#news_admin div {
	margin:3px 0px;
}

#news_admin small {
	color:#999;
}

#news_admin .logo {
	text-align:center;
	margin:0px;	
}

#news_admin #pie_news, .pie_news {
	margin-top:0px;
	margin-bottom:20px;
	font-family:  "Open Sans", Arial, sans-serif !important;
	font-size: 12px;
    padding: 15px;
    text-align: center;
    color:#777;
}

#news_admin #pie_firma, .pie_firma {
	background-color: #0c9cd6;
	padding:20px 5px;
	text-align:center;
	color:white;
	font-size: 14px;
	line-height:15px;
	height:50px;
	vertical-align:middle;
	font-family:  Arial, sans-serif !important;
}

#news_admin .ver_web {
	text-align:center;
	margin-bottom:20px;
}


</style>'."\n".'
<!--[if mso]>
<style>
#news_admin {
	font-family: Arial, "Open Sans", sans-serif !important;
}	
</style>
<![endif]-->


</head>
<body>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td valign="top"><div align="center">
      <table border="0" cellspacing="0" cellpadding="0" width="500">
        <tr>
          <td valign="top"><table border="0" cellspacing="0" cellpadding="0">
<div id="news_admin">
<div class="logo"><img src="'.site_url().'/wp-ibmutua/news/lib/cabecera.jpg" title="Noticias Ibermutua" width="500" height="127"></div>'."\n".'
<div align="center"><a href="http://x.info.ibermutuamur.es/ats/msg.aspx?sg1={(URLSignature1)}">[Ver versión online]</a></div>
'."\n";

?>

<style>
#contenido {
	width:810px !important;
}

#news_admin textarea {
	width:100%;
	height:500px;
}

#news_admin {
	font-family: "Open Sans", sans-serif, Arial !important;
	width:100%; /*500px;*/
	margin:auto;	
}
#news_admin .news-item {
	clear:both;
	background:none;
	padding:5px 0px 10px 0px !important;
	font-family: 'Open Sans', sans-serif, Arial !important;
}

#news_admin a:link, a:visited, a:active {
    text-decoration: none;
    color: #005195;
}	

#news_admin .news-item img{
	width:500px;
/*	height:340px !important;
	max-height:340px !important;*/
	margin:auto;
}

#news_admin h2 {
	font-size:19px;
	line-height:20px
	margin:8px 0px 4px 0px;
}
#news_admin div {
	margin:3px 0px;
}

#news_admin small {
	color:#999;
}

#news_admin .logo {
	text-align:center;
	margin:0px 0px 0px 0px;
		
}

#news_admin #pie_news, .pie_news {
	margin-top:0px;
	margin-bottom:20px;
	font-family:  "Open Sans", Arial, sans-serif !important;
	font-size: 12px;
    padding: 15px;
    text-align: center;
    color:#777;
}

#news_admin #pie_firma, .pie_firma {
	background-color: #0c9cd6;
	padding:20px 5px;
	text-align:center;
	color:white;
	font-size: 14px;
	line-height:15px;
	height:50px;
	vertical-align:middle;
	font-family:  Arial, sans-serif !important;
}

#news_admin .ver_web {
	text-align:center;
}

#pie, #sidebar {
	display:none !important;
}

</style>

<?php
function getImagen($content, $site) {
	$imagen="";
	$posfinal = strpos($content, '.jpg');
	if(!$posfinal) return;
	$posfinal+=4;
	$str1 = substr($content,0,$posfinal);
	$posini = strrpos($str1,'/wp-content/uploads/');
	$str2 = substr($str1,$posini,strlen($str1));
	$imagen =  $site . $str2;
	return site_url().'/wp-ibmutua/news/inc-cropimagen.php?imagen='.$imagen;
}

function traducirMes($fecha) {
	$fecha = str_replace(
	  array('January', 'February','March','April','May','June','July','August','September','October','November','December'), 
	  array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'), 
	  $fecha
	);
	return $fecha;
}

?>


<div id="news_admin">
<?php // Get RSS Feed(s)
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);
$limit=15;
$contador=1;
$feed = array();
include_once( ABSPATH . WPINC . '/feed.php' );
$aRSS = array(

	array('https://www.ibermutua.es/feed/','https://www.ibermutua.es','Ibermutua.es'),
	array('http://blog.ibermutua.es/feed/','http://blog.ibermutua.es','ColectivoSS Ibermutua'),
	array('http://revista.ibermutua.es/feed/','http://revista.ibermutua.es','Revista ON MUTUA Ibermutua')
	);
foreach ($aRSS as $myRSS) :
	//$response = file_get_contents($myRSS[0], false, stream_context_create($arrContextOptions));
	$response = getSslPage($myRSS[0]);	
	$rss= simplexml_load_string($response);
	$conta=1;
	if($rss->channel->item) foreach ($rss->channel->item as $node) {
		if($conta>$limit) break;
		$item = array ( 
			'contador' => $contador,
			'site' => $myRSS[0],
			'site_url' => $myRSS[1],
			'site_name' => $myRSS[2],
			'title' => $node->title,
			'desc' => $node->description,
			'link' => $node->link,
			'date' => ($node->pubDate),
			'content' => $node->children("content", true)
			);		
		array_push($feed, $item);
		$conta++;
		$contador++;
	}
?>
<?php endforeach; ?>

<?php
$datos=$_GET;
echo "<hr>";
$conta=0;
for($x=0;$x<sizeof($feed);$x++) {
	$id=$feed[$x]['contador'];
	if($datos['reg-'.$id]) {
		$articulos[$conta]=$feed[$x];
		$articulos[$conta]['contador']=$datos['reg-'.$id];
		$articulos[$conta]['date'] = traducirMes(date('j F Y', strtotime($articulos[$conta]['date'])));		
		$conta++;

	}
}
if($articulos) asort($articulos);
if($articulos) foreach ($articulos as $articulo) {
	//$imagen = '<img src="'.$articulo['imagen'].'">';
	$html.= '<div class="news-item">
		<h2><a href="'.$articulo['link'].'" target="_blank">'.$articulo['title'].'</a></h2>
		<div><small>Publicado: '.$articulo['date'].' en '.$articulo['site_name'].'</small></div>
		<div>'.$articulo['desc'].'</div>
		<div><strong><a href="'.$articulo['link'].'" target="_blank">Ver la noticia completa ></a></strong></div>
		</div>
		'."\n";
}
$html.="</div>";
$html.='
</td>
</tr>
<tr><td>
<img src="'.site_url().'/wp-ibmutua/news/lib/pie_news.png" title="Noticias Ibermutua" width="680" height="150">
</td></tr>
<tr><td bgcolor="#0c9cd6" height="100">
<div id="pie_firma" class="pie_firma">
<p>© Ibermutua, Mutua Colaboradora con la Seguridad Social n° 274.<br />Todos los derechos reservados.</p>
</div>
</td></tr>
<tr><td>
<div id="pie_news" class="pie_news">
<p>No responda a este correo, si desea contactar con Ibermutua, puede hacerlo en el mail <a href="mailto:infocolaboradores@ibermutua.es">infocolaboradores@ibermutua.es</a>.</p>
<p>Si desea cancelar o modificar el tipo de comunicaciones que recibirá por vía electrónica de Ibermutua, <a href="http://x.info.ibermutuamur.es/ats/show.aspx?cr=1058&amp;fm=29&amp;tp={(TrackingParams)}">Pulse Aquí</a>.</p>
{[Blanco Registros CCMP|1666]}
</div>
</td></tr>
</table></td></tr></table>
';
echo $html;

// FIX PARA EL CORREO
$html= str_replace('&amp;',htmlentities('&amp;'),$html);
$html= str_replace('"Open Sans",','',$html);
$html= str_replace('<div class="news-item">','<br /><br /><div class="news-item">',$html);
$html= str_replace('font-size:22px;','font-size:26px;',$html);
$html= str_replace('font-size:24px;','font-size:28px;',$html);
/*$html= str_replace('width="822"','width="500"',$html);
$html= str_replace('height="466"','height="283"',$html);
$html= str_replace('height="419"','height="254"',$html);*/

$html=imageResize($html)

?>
<?php if(sizeof($articulos)>1) { ?>
<h1 style="margin-top:40px">HTML Generado</h1>
<textarea name="htmlgenerado"><?php echo $html_head.$html.$html_footer;?></textarea>
<?php } else {
echo $articulos[0]['content'];
 ?>
<h1 style="margin-top:40px">HTML Generado Noticia Unica</h1>
<textarea name="htmlgenerado"><?php echo $html_head.$html.$html_footer;?></textarea>
<?php  } ?>
</div>

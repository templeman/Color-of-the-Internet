<?php
	include('cssparser.php');
	
	$doc = new DOMDocument();
	$doc->loadHTMLFile('http://www.sixrevisions.com');
	$tags = $doc->getElementsByTagName('link');

	if(!is_null($tags))
	{
        foreach($tags as $tag)
        {
			$links[] = $tag->getAttribute('href');
			
			foreach($links as $link)
			{
				if (strstr($link, '.css') == true)
				{
					echo '<PRE>';
					print_r($link);	
					echo '</PRE>';
					
			
				}
				$css = new cssparser();
					
				$css->Parse($link);
				
				$css = array(get_object_vars($css));
				
				/*echo '<PRE>';	
				print_r($css);
				echo '</PRE>';
				 
				 */
				foreach($css as $cssString)
				{
				$cssString1 = implode(',', $css);
				
				echo $cssString1;
				}
				//$cssString->Get($cssString,'color');
				
				//echo '<PRE>';	
				//print_r($cssString);
				//echo '</PRE>';
				
				}
			
				
			}
		}
	
?>
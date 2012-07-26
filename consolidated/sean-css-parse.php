<?php
       
        $doc = new DOMDocument();
        $doc->loadHTMLFile('http://www.nytimes.com'); // Any URL can go here
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
                       
                        }
                       
                }
        }
 
?>

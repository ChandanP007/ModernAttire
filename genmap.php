
<?php
    $currentDateTime = date("Y-m-d\TH:i:sP");
    //Getting all products
    $response = file_get_contents('https://sheets.googleapis.com/v4/spreadsheets/1PhDr3cH-4gn4G1Gcz8H9EPQw5uCf2Dxd90Ay_nDgEbE/values/AllProducts?key=AIzaSyDNBeKsUnqKFzJ54MNJKn-H82fuSTtXApI');
    $response = json_decode($response);
    $response = $response->{'values'}; 
    $totalProd = count($response);

    //Sitemap file loaded
    $sitexml = new DOMDocument();
    $sitexml->load("sitemap.xml");
    $tagname = 'url';
    
    function countTagOccurences($sitexml,$tag){
        $count = $sitexml->getElementsByTagName($tag)->length;
        return $count;
    }
    $urlcount = countTagOccurences($sitexml,$tagname);
    $urlcount -= 4;

    // Generating url childs inside the urlset 
    $urlcount++;
    for($i=$urlcount;$i<$totalProd;$i++){
        $prodId = str_replace("/","-",$response[$i][0]);
        $prodName = str_replace(" ", "-", $response[$i][1]);

        //Creating new set inside urlset.If new product added.
        $url = $sitexml->createElement("url");
        $loc = $sitexml->createElement("loc");
        $lastmod = $sitexml->createElement("lastmod");
        $priority = $sitexml->createElement("priority");
        //Appending inside urlset
        $root = $sitexml->documentElement;
        $root->appendChild($url);
        $url->appendChild($loc);
        $url->appendChild($lastmod);
        $url->appendChild($priority);
        //Content of url
        $loc->appendChild($sitexml->CreateTextNode("https://themodernattire.com/Shop/Collections/".$prodId."/".$prodName));
        $lastmod->appendChild($sitexml->CreateTextNode($currentDateTime));
        $priority->appendChild($sitexml->CreateTextNode("0.64"));

    }

    $sitexml->save("sitemap.xml");
?>
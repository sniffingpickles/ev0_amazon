<?php

#### Author : ev0 ####
####Change History#####

// ---- 2013/7/19 ----//
// getNodeIdFromUrl : added  
  
class AmazonCrawler extends HttpCrawler{
  protected $_tag = "AmazonCrawler";
  protected $_debug = false;
  
  public function __construct()
  {
    $this->initialize();
  }
  
  public function getDigits($arg) { // replace <,> with <.>
    if ( preg_match("/([0-9]+)/i", $arg, $matches)) {
      if ( preg_match("/([0-9,\.]+)/i", $arg, $matches)) {
        $ret = $matches[1];
        $ret = str_replace(",", ".", $ret);
        return $ret;
      }
    }
    
    return 0;
  } 
  
  public function getDigits_1($arg) { // replace <,> with empty
    if ( preg_match("/([0-9]+)/i", $arg, $matches)) {
      if ( preg_match("/([0-9,\.]+)/i", $arg, $matches)) {
        $ret = $matches[1];
        $ret = str_replace(",", "", $ret);
        return $ret;
      }
    }
    
    return 0;
  } 
  
  public function getDigits_2($arg) { // replace <.> with empty
    if ( preg_match("/([0-9]+)/i", $arg, $matches)) {
      if ( preg_match("/([0-9,\.]+)/i", $arg, $matches)) {
        $ret = $matches[1];
        $ret = str_replace(".", "", $ret);
        return $ret;
      }
    }
    
    return 0;
  }      
  
  public function getAsinFromUrl($url) {
    if ( preg_match("/dp\/([^\/]*)/i",$url, $matches)) {
      return $matches[1];
    }    
    return "";
  }
  
  public function getSellIdFromUrl($url) {
    
    if (preg_match("/seller=(.*)/i", $url, $matches)) {
        return $matches[1];
    }
    return "";
  }  
  
  public function getImageUrl($str) {
    
    if (preg_match("/background-image:url\(([^\(]*)\)/i", $str, $matches)) {
        return $matches[1];
    }
    return "";
  }
  
 public function getCountryFromUrl($url)
  {
    if ( preg_match("/www\.amazon\.co\.uk/i", $url, $matches)) {
      return "uk";
    }
    
    if ( preg_match("/www\.amazon\.com\.br/i", $url, $matches)) {
      return "br";
    }
    
    
    if ( preg_match("/www\.amazon\.com/i", $url, $matches)) {
      return "us";
    }
    
    if ( preg_match("/www\.amazon\.ca/i", $url, $matches)) {
      return "ca";
    }
    
    if ( preg_match("/www\.amazon\.it/i", $url, $matches)) {
      return "it";
    }
    
    if ( preg_match("/www\.amazon\.es/i", $url, $matches)) {
      return "es";
    }
    
    if ( preg_match("/www\.amazon\.fr/i", $url, $matches)) {
      return "fr";
    }
    
    if ( preg_match("/www\.amazon\.co\.jp/i", $url, $matches)) {
      return "jp";
    }
    
    if ( preg_match("/www\.amazon\.de/i", $url, $matches)) {
      return "de";
    }
  }

  function amazonKeywordSearchUrlCorrect($url) {
    if ( preg_match_all("/ref=[^\/]*/", $url, $matches1 )) {
      
      if ( is_array($matches1[0]) && count($matches1[0]) > 1) {
        
        $base = "";
        if ( preg_match("/([^=]*\/)ref=/i", $url, $matches2)) {
          $base = $matches2[1];
        }
        
        $ref = $matches1[0][count($matches1[0]) - 1];
        return $base . $ref;
      }
    } 
    return $url; 
  }
  
  function trimBlankLines($str) {
    $lines = explode("\n", $str);
    
    $temp = array();
    if ( is_array($lines)) {
      foreach($lines as $line) {
        if ( trim($line) == "") {
        } else {
          $temp[] = trim($line);
        }
      }
    }
    return implode("\n", $temp);
  }            
  
  public function getUrlStatus($url) {
    $html= $this->getUrl($url);
    if ( $html == 404) {
      $status = 404;
    } else {
      $status = 200;
    }
    
    return $status;
  } 
  
  public function getNodeIdFromUrl($url) {
    if ( preg_match("/\/([0-9]+)\/ref=/i",$url, $matches)) {
      return $matches[1];
    }    
    return "";
  }  
  

}

class AmazonCrawler_US extends AmazonCrawler{
  protected $_tag = "AmazonCrawler_US";
  protected $_html = "";
  
  protected $_debug = false;  
  protected $_logview = false;  
  
  // pattern  
  protected $_pattern_detail = "Product Details";
  protected $_pattern_technical_detail = "Technical Details";
  protected $_pattern_description = "Product Description";
  protected $_pattern_asin_text = "ASIN:";
  protected $_pattern_asin_soldby = "Sold by";
  
  protected $_pattern_isbn10_text = "ISBN-10:";
  protected $_pattern_isbn13_text = "ISBN-13:";
  
  protected $_pattern_frequently_bought = "bxgyBundle";
  protected $_pattern_other_items = "bxgyBundle";
    
  protected $_pattern_offerlisting_total_rating = "total ratings";
  protected $_pattern_offerlisting_ship_from = "Ships from";
  protected $_pattern_offerlisting_in_stock = "In Stock";
  
  protected $_pattern_offerlisting_new = "New";
  
  protected $_pattern_sales_rank_in = "in"; 
  protected $_pattern_sales_rank_num = "#";  
  
  protected $_pattern_search_by_keyword_total_asin_num = "of";  
  
  protected $_pattern_dimention_1 = "Product Dimensions";  // table
  protected $_pattern_weight_1 = "Item Weight";  

  protected $_pattern_dimention_2 = "Product Dimensions:";  // list
  protected $_pattern_weight_2 = "Item Weight:";  
  
  protected $_pattern_upc_1 = "UPC";  
  protected $_pattern_upc_2 = "UPC:";  
  
  protected $_pattern_important_information = "Important Information";
  
  // url  
  protected $_url_site = "http://www.amazon.com/";
  protected $_url_asin_detail = "dp/";
  protected $_url_offer_listing = "gp/offer-listing/";
  
  protected $_url_customer_image = "gp/customer-media/product-gallery/";
  
  public function __construct()
  {
    $this->initialize();
  }
  
  public function setDebugMode($mode=true) {
    if ( $mode) {
      $this->_logview = true;
      $this->_debug = true;
    } else {
      $this->_logview = false;
      $this->_debug = false;
    }
  }
  
  public function setHtml($html) {
    $this->_html = $html;
  }
  
  public function getUrl($url, $posts=array(), $cookies=array()) {
    
    if ( $this->_html != "") {
      return $this->_html;
    } else {
      
      //return HttpCrawler::getUrl($url);
      
      $ch = curl_init();
      
      $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
      $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
      $header[] = "Cache-Control: max-age=0";
      $header[] = "Connection: keep-alive";
      $header[] = "Keep-Alive: 300";
      $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
      $header[] = "Accept-Language: en-us,en;q=0.5";
      $header[] = "Pragma: "; // browsers keep this blank.
      
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      //curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies_asin.txt");
      //curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies_asin.txt");
      curl_setopt($ch, CURLOPT_AUTOREFERER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      
      curl_setopt($ch, CURLOPT_URL, $url);
      
      ob_start();
      $ret = curl_exec($ch);
      unset($ret);
      $html = ob_get_contents();
      ob_end_clean();    
      curl_close($ch);      
      
      return $html;
    }
  }  
  
  public function getOfferListingBaseURL() {
    
    $url = $this->_url_site . $this->_url_offer_listing;
    return $url;
  }
  
  public function getAsinDetailBaseURL() {
    
    $url = $this->_url_site . $this->_url_asin_detail;
    return $url;
  }  
  
  public function getSiteBaseURL() {
    
    $url = $this->_url_site;
    return $url;
  }  
  
  # added 2013/4/24
  public function getCustomerImageBaseURL() {
    
    $url = $this->_url_site . $this->_url_customer_image;
    return $url;
  }      
######################################################################################  
// Product List
######################################################################################  

######### get asin list from store url ###########
  public function getAsins($url) {
  
  list($TotalAsins, $RowsPerPage) = $this->getTotalAsinNums($url);
  
  if ( $RowsPerPage == 0) {
    if ( $this->_logview) {
      echo "Invalid store url\n";
    }
    return;
  }
  
  $total_asins = array();
                    
  for($page=0; $page< $TotalAsins / $RowsPerPage; $page++) {
    
    if ( $page == 0) {
      $nextPageUrl = $url;
    } else {
      $nextPageUrl = $url . "&page={$page}"; 
    }
    
    if ( $this->_logview) {
      echo "\n-Page " . ($page + 1) . "\n";
    }
    
    $asins = $this->getAsinList($nextPageUrl);
    
    if ( $this->_logview) {
      print_r($asins);
    }
    
    if ( is_array($asins)) {    
      $total_asins = array_merge($total_asins, $asins);
    }
  }  
  
  return $total_asins;    
    
  }   // not tested
  
  public function getTotalAsinNums($url) {
    
    $html = $this->getUrl($url);
    $dom = str_get_html($html);
    
    $total_asins = 0;  
    $rows_per_page = 0;
    
    //total asins
    $text = "";
    if ( $dom->find('div[id="topBar"] div[id="resultCount"]', 0)) {
      $text = $dom->find('div[id="topBar"] div[id="resultCount"]', 0)->plaintext;
    }
              
    if ( $text != "" && preg_match("/1-([0-9]+) von ([0-9,]+)/i", $text, $matches)) {
      $rows_per_page = $matches[1];
      $total_asins = $this->getDigits($matches[2]);
    }
    
    $dom->clear();
    unset($dom);
    
    return array($total_asins,$rows_per_page);
  } // not tested
  
  public function getAsinList($url) {
    
    $html = $this->getUrl($url);
    $dom = str_get_html($html);
    
    $asins = array();
     
        foreach($dom->find('div[class="productTitle"] a') as $e)
        {
          $href = $e->href;
          
          //echo $href . "\n";
          if ( preg_match("/dp\/([0-9A-Z]+)\//i", $href, $matches)) {
            //echo $matches[1] . "\n";      
            $asins[] = $matches[1];
          }
        } 
        
        $asins = array_unique($asins);
    
    $dom->clear();
    unset($dom);
    
    return $asins;
      
  }  // not tested
  
  
  ######### get asin list from search page by keyword ###########
  /*
  public function getAsinsFromSearchByKeyword($url, $callback=null) {
  
  list($TotalAsins, $RowsPerPage) = $this->getTotalAsinNumsFromSearchByKeyword($url);
  
  if ( $RowsPerPage == 0) {
    if ( $this->_logview) {
      echo "No search result.\n";
    }
    return;
  }
  
  $total_asins = array();
                    
  for($page=1; $page< $TotalAsins / $RowsPerPage; $page++) {
    
    if ( $page == 1) {
      $nextPageUrl = $url;
    } else {
      $nextPageUrl = $url . "&page={$page}"; 
    }
    
    if ( $this->_logview) {
      echo "\n-Page " . ($page) . "\n";
    }
    
    $asins = $this->getAsinListFromSearchByKeyword($nextPageUrl);
    
    if ( count($asins) == 0) {
      if ( $this->_logview) {
        echo "No search results.\n";
      }
      break;
    }
    
    if ( $this->_logview) {
      print_r($asins);
    }
    
    if ( is_array($asins)) {    
      $total_asins = array_merge($total_asins, $asins);
    }
    
    if ($callback) {
      foreach($asins as $asin) {
        call_user_func($callback, $asin);
      }
    } 
  }  
  
  return $total_asins;    
    
  }    // get All asins from all pages by search keyword
  */
  
  public function getAsinsFromSearchByKeyword($url, $callback=null, $continue_check_callback=null) {
  
  list($TotalAsins, $RowsPerPage) = $this->getTotalAsinNumsFromSearchByKeyword($url);
  
  if ( $RowsPerPage == 0) {
    
    $asins = $this->getAsinListFromSearchByKeyword($url);
    if ( is_array($asins) && count($asins) > 0) {
      
      if ($callback) {
        foreach($asins as $asin) {
          call_user_func($callback, $asin);
        }
      }      
      return $asins;
       
    } else {
      if ( $this->_logview) {
        echo "No search result.\n";
      }      
      return;
    }
  }         
  
  $total_asins = array();
  
  $nextPageUrl_base = $this->getNextUrlFromSearchByKeyword($url);
                    
  for($page=1; $page< $TotalAsins / $RowsPerPage; $page++) {
    
    if ( $nextPageUrl_base) {
      if ( $page == 1) {
        $nextPageUrl = $url;
      } else {
        $nextPageUrl = preg_replace("/page=([0-9]*)/i", "page=" . $page, $nextPageUrl_base);
        $nextPageUrl = str_replace("sr_pg_2", "sr_pg_" . $page, $nextPageUrl);
        $nextPageUrl = str_replace("&amp;", "&", $nextPageUrl);
      }
      
    } else {
      if ( $page == 1) {
        $nextPageUrl = $url;
      } else {
        $nextPageUrl = $url . "&page={$page}"; 
      }
    }
    
    if ( $this->_logview) {
      echo "\n-Page " . ($page) . "\n";
      echo "pageUrl = " . $nextPageUrl . "\n";
    }
    
    $asins = $this->getAsinListFromSearchByKeyword($nextPageUrl);
    
    if ( count($asins) == 0) {
      if ( $this->_logview) {
        echo "No search results.\n";
      }
      break;
    }
    
    if ( $this->_logview) {
      print_r($asins);
    }
    
    if ( is_array($asins)) {    
      $total_asins = array_merge($total_asins, $asins);
    }
    
    if ($callback) {
      foreach($asins as $asin) {
        call_user_func($callback, $asin);
      }
    } 
    
    if ($continue_check_callback) {
      if ( !$continue_check_callback($page)) { // check if go to next page or not
        break;
      }
    }      
    
  }  
  
  return $total_asins;    
    
  }    
  
  # get All asins from all pages by search keyword  
  public function getAsinListFromSearchByKeyword($url) {
    
    $html = $this->getUrl($url);
    $dom = str_get_html($html);
    
    $asins = array();
     
    // top
    foreach($dom->find('div[id="atfResults"] h3[class="newaps"] a') as $a)
    {
      if ( $this->_logview) {
        echo "<pre>";
        echo $a->href;
        echo "</pre>";
      }

      $asin = $this->getAsinFromUrl($a->href);
      if ( $asin) {
        $asins[] = $asin;
      }
    } 
    
    // bottom
    foreach($dom->find('div[id="btfResults"] h3[class="newaps"] a') as $a)
    {
      if ( $this->_logview) {
        echo "<pre>";
        echo $a->href;
        echo "</pre>";
      }

      $asin = $this->getAsinFromUrl($a->href);
      if ( $asin) {
        $asins[] = $asin;
      }
    }     
    
    if (count($asins) == 0) {
      foreach($dom->find('div[id="rightResultsATF"] a') as $e)
      {
        $asin = $this->getAsinFromUrl($e->href);
        if ( $asin) {
          $asins[] = $asin;
        }
      }       
    }
              
    $asins = array_unique($asins);
    $new_asins = array();
    
    if ( is_array($asins)) {
      foreach($asins as $a) {
        $new_asins[] = $a;
      }
    }
    
    $dom->clear();
    unset($dom);
    
    return $new_asins;
      
  }   
  
  public function getTotalAsinNumsFromSearchByKeyword($url) {
    
    $html = $this->getUrl($url);
    $dom = str_get_html($html);
    
    $total_asins = 0;  
    $rows_per_page = 0;
    
    //total asins
    $text = "";
    if ( $dom->find('div[id="topBar"] h2[id="resultCount"]', 0)) {
      $text = $dom->find('div[id="topBar"] h2[id="resultCount"]', 0)->plaintext;
    }
    
    //var_dump($text);
    
    $pattern = "/1 - ([0-9,\.]+) " . $this->_pattern_search_by_keyword_total_asin_num . " ([0-9,\.]+)/i";
              
    if ( $text != "" && preg_match($pattern, $text, $matches)) {
      $rows_per_page = $matches[1];
      $total_asins = $this->getDigits($matches[2]);
    }
    
    $dom->clear();
    unset($dom);
    
    return array($total_asins,$rows_per_page);
  }  
  
  public function getNextUrlFromSearchByKeyword($url) {
    
    $html = $this->getUrl($url);
    $dom = str_get_html($html);
    
    $ret = null;
    
    $asins = array();
    foreach($dom->find('div[id="pagn"] span[class="pagnLink"] a') as $e)
    {
        $asins[] = $e->href;
    }
    
    
    if ( isset($asins[0])) {
      $ret = $this->getSiteBaseURL() . str_replace("/s/", "s/", $asins[0]);
    }

    
    $dom->clear();
    unset($dom);
    
    return $ret;
      
  }   
  
  ######### get asin list from search page by category ###########
  
  public function getSearchResultByCategoryUrl($url) {
    
    $html = $this->getUrl($url);
    $dom = str_get_html($html);
    
    $total_asins = 0;  
    $rows_per_page = 0;
    
    //total asins
    $text = "";
    if ( $dom->find('div[id="topBar"] h2[id="resultCount"]', 0)) {
      $text = $dom->find('div[id="topBar"] h2[id="resultCount"]', 0)->plaintext;
    }
    
    //var_dump($text);
    
    $pattern = "/1 - ([0-9,\.]+) " . $this->_pattern_search_by_keyword_total_asin_num . " ([0-9,\.]+)/i";
              
    if ( $text != "" && preg_match($pattern, $text, $matches)) {
      $rows_per_page = $matches[1];
      $total_asins = $this->getDigits($matches[2]);
    }
    
    $dom->clear();
    unset($dom);
    
    return array($total_asins,$rows_per_page);
  }
  
  public function getAsinListFromSearchByCategory($url, $rank=0) {
    
    $html = $this->getUrl($url);
    $dom = str_get_html($html);
    
    $rets = array();
     
    if ($dom->find('div[class="data"]')) {
      foreach($dom->find('div[class="data"]') as $e)
      {
        if ( $e->find('h3[class="title"] a', 0)) {
          $ret['title'] = $e->find('h3[class="title"] a', 0)->plaintext;
          $ret['url'] = $e->find('h3[class="title"] a', 0)->href;
          $ret['asin'] = $this->getAsinFromUrl($ret['url']);
          $ret['rank'] = ++$rank;
          
          if ( $e->find('td[class="toeOurPrice"] a', 0)) {
            $ret['price'] = $this->getDigits($e->find('td[class="toeOurPrice"] a', 0)->plaintext);
          } else {
            $ret['price'] = 0;
          }
          
          if ( $e->find('td[class="toeRentPrice"] a', 0)) {
            $ret['rent_price'] = $this->getDigits($e->find('td[class="toeRentPrice"] a', 0)->plaintext);
          } else {
            $ret['rent_price'] = 0;
          }          
          
          if ( $e->find('td[class="toeOurPriceWithRent"] a', 0)) {
            $ret['buy_price'] = $this->getDigits($e->find('td[class="toeOurPriceWithRent"] a', 0)->plaintext);
          } else {
            $ret['buy_price'] = 0;
          }              
          
          if ( $e->find('td[class="toeNewPrice"] a', 0)) {
            $ret['new_price'] = $this->getDigits($e->find('td[class="toeNewPrice"] a', 0)->plaintext);
          } else {
            $ret['new_price'] = 0;
          }
          
          if ( $e->find('td[class="toeUsedPrice"] a', 0)) {
            $ret['used_price'] = $this->getDigits($e->find('td[class="toeUsedPrice"] a', 0)->plaintext);
          } else {
            $ret['used_price'] = 0;
          }
          
          $rets[] = $ret;
        }
        
      }
    }
    
    $dom->clear();
    unset($dom);
    
    return $rets;
      
  }   
  
  public function getAsinsFromSearchByCategory($url, $callback=null) {
  
  list($TotalAsins, $RowsPerPage) = $this->getSearchResultByCategoryUrl($url);
  
  if ( $RowsPerPage == 0) {
    if ( $this->_logview) {
      echo "No search result.\n";
    }
    return;
  }
  
  $total_asins = array();
                    
  for($page=1; $page< $TotalAsins / $RowsPerPage; $page++) {
    
    if ( $page == 1) {
      $nextPageUrl = $url;
    } else {
      $nextPageUrl = $url . "&page={$page}"; 
    }
    
    if ( $this->_logview) {
      echo "\n-Page " . ($page) . "\n";
    }
    
    $asins = $this->getAsinListFromSearchByCategory($nextPageUrl);
    
    if ( count($asins) == 0) {
      if ( $this->_logview) {
        echo "No search results.\n";
      }
      break;
    }
    
    
    if ( $this->_logview) {
      print_r($asins);
    }
    
    if ( is_array($asins)) {    
      $total_asins = array_merge($total_asins, $asins);
    }
    
    if ($callback) {
      foreach($asins as $asin) {
        call_user_func($callback, $asin);
      }
    } 
  }  
  
  return $total_asins;    
    
  }    // get All asins from all pages by search keyword
  
######################################################################################  
// Product Detail
######################################################################################    

  // get product info for specifi url with asin
  public function getAsinDetail($url, $needCustomerImage=false) {
    
    $AmazonProductInfo = array();
    
    $plain_html = $this->getUrl($url);
    
    $html = str_get_html($plain_html);
    
    // asin     
    $AmazonProductInfo['asin'] = $this->parseAsin($html);
   
    // ISBN     
    $AmazonProductInfo['isbn'] = $this->parseISBN($html);
    
    // title
    $AmazonProductInfo['title'] = $this->parseTitle($html);
    
    // price & shipping price
    $AmazonProductInfo['price'] = 0;
    $AmazonProductInfo['shipping'] = 0;
    $AmazonProductInfo['currency'] = "";
    list($AmazonProductInfo['price'], $AmazonProductInfo['shipping'], $AmazonProductInfo['currency']) = $this->parsePriceAndShipPrice($html);
    
    // sellerId
    $AmazonProductInfo['sellerId'] = $this->parseSellerId($html);
    
    // product by
    $AmazonProductInfo['product_by'] = $this->parseProductBy($html);       
    
    // In stock
    $AmazonProductInfo['inventory'] = $this->parseInventory($html);
    
    // Availability
    $AmazonProductInfo['availability'] = $this->parseAvailability($html);
    
    // Sold by
    $AmazonProductInfo['sold_by'] = $this->parseSoldBy($html);
    
    // frequently bought toegether 
    $AmazonProductInfo['frequently_bought'] = $this->parseFrequentlyBought($html);
    
    // What other items do customers buy after viewing this item
    $AmazonProductInfo['other_items'] = $this->parseOtherItems($html);
    
    // more buying choices list
    $AmazonProductInfo['more_buying_choices'] = $this->parseMoreChoiceList($html);
    
    // buying choice #
    $AmazonProductInfo['buyingChoice#'] = $this->parseMoreChoice($html);       
    
    // sales rank
    $AmazonProductInfo['sales_rank'] = $this->parseSalesRank($html);       
    
    // images
    $AmazonProductInfo['images'] = $this->parseLargeImage($plain_html);       
    
    // customer image
    if ($needCustomerImage) {
      $customer_images = $this->getCustomerImages(trim($AmazonProductInfo['asin']));
      foreach($customer_images as $i) {
        $AmazonProductInfo['images'][] = $i;
      }
    }   

    // technical detail
    $AmazonProductInfo['technical_details'] = $this->parseProductTechnicalDetail($html);       

    // description
    $AmazonProductInfo['description'] = $this->parseProductDescription($html);       
    
    // html description
    $AmazonProductInfo['html_description'] = $this->parseProductDescription($html, true);       

    // product detail
    $AmazonProductInfo['details'] = $this->parseProductDetails($html);       
    
    // product item detail
    $AmazonProductInfo['item_details'] = $this->parseProductItemDetails($html);       
    
    # customers who bouht this item also bought
    $AmazonProductInfo['customers_also_bought_items'] = $this->parseCustomerAlsoBoughtItems($html);       
    
    #offers
    $AmazonProductInfo['offers'] = $this->parseProductOffers($plain_html);
    
    #dimention
    list($AmazonProductInfo['dimension'], $AmazonProductInfo['dimension_unit']) = $this->parseProductDimention($html);
    
    #weight
    list($AmazonProductInfo['weight'], $AmazonProductInfo['weight_unit']) = $this->parseProductWeight($html);

    #prime
    $AmazonProductInfo['prime'] = $this->parseProductPrime($html);
    
    #upc
    $AmazonProductInfo['upc'] = $this->parseProductUPC($html);

    #important information
    $AmazonProductInfo['important'] = $this->parseProductImportantInformation($html);
    
    $html->clear();
    unset($html);    
    
    return $AmazonProductInfo;
  }
 
  public function getNumberOfMoreChoices($url) {
    
    $html = $this->getUrl($url);
    
    return $this->parseMoreChoice($html);
  }
  
  public function getLargeImages($url) {
    
    $html = $this->getUrl($url);
    return $this->parseLargeImage($html);
  
  }  

  public function getCustomerImages($asin) {
    
    $url = $this->getCustomerImageBaseURL() . $asin;
    
    $html = $this->getUrl($url);
    
    return $this->parseCustomerImage($html);
  
  }  
  
######################################################################################  
// Product Offer List
######################################################################################    

  // get all offer listings for specific asin
  public function getOfferListing($asin) {
    $url = $this->getOfferListingBaseURL() . $asin;
    
    return $this->getOfferListingByUrl($url);
  }
  
  public function getOfferNextUrl($url) {
    
    $html = $this->getUrl($url);
    
    $next_url = $this->parseOfferNextUrl_2($html);
    
    if ( !$next_url) {
      $next_url = $this->parseOfferNextUrl_1($html);
    } 
    
    return $next_url;       
    
  }

  public function getOfferListingByUrl($url) {
    $html = $this->getUrl($url);
    
    $lists = $this->parseOfferListingByUrl_2($html);
    
    if ( count($lists) == 0) {
      $lists = $this->parseOfferListingByUrl_1($html);
    } 
    
    return $lists;
    
  }
  
  public function getOfferListingByUrlForNewOffers($url) {
    
    $html = $this->getUrl($url);
    
    $lists = $this->parseOfferListingByUrlForNewOffers_2($html);
    
    if ( count($lists) == 0) {
      $lists = $this->parseOfferListingByUrlForNewOffers_1($html);
    } 
    
    return $lists;    
  }  
  
  public function getAllOfferListingByUrl($url, $callback=null, $continue_check_callback=null) {
    
    if ( $this->_logview) {
      echo "<pre>";
      echo "URL=" . $url . "\n";
      echo "</pre>";
    }        
    
    $total_offers = array();
    
    $page = 1;  
    $urlTemplate = "";  
    do {
      
      $offers = array();
      
      $offers = $this->getOfferListingByUrl($url);
      
      if ( $this->_logview) {
        echo "<pre>";
        echo  "HTTP STATUS : " . $this->_http_status . "\n";
        echo "</pre>";
      }
      
      if ( $this->_http_status  != 200) {
        return $this->_http_status;
        break;
      }
      
      if ( $this->_logview) {
        echo "<pre>";
        print_r($offers);
        echo "</pre>";
      }
      
      if ( count($offers) == 0) {
        if ( $this->_logview) {
          echo "<pre>";
          echo "No offers.\n";
          echo "</pre>";
        }
        break;
      }
      
      if ( is_array($offers)) {    
        $total_offers = array_merge($total_offers, $offers);
      }
      
      if ( $callback) {
        $callback($offers);
      }
      
      if ($continue_check_callback) {
        if ( !$continue_check_callback($page)) { // check if go to next page or not
          break;
        }
      }      
      
      $page ++;      
      
      if ( $urlTemplate == "") {
        $urlTemplate = $this->getOfferNextUrl($url);
        $urlTemplate = str_replace("&amp;", "&", $urlTemplate);
      }
      
      $url = $this->getSiteBaseURL() . str_replace("/gp", "gp", $urlTemplate);
      $url = preg_replace("/olp_page_(next[^\?]*)\?ie/i", "olp_page_" .$page . "?ie" , $url);
      $url = preg_replace("/startIndex=[0-9]*/i", "startIndex=" .($page - 1) * 10 , $url);
      
      if ( $this->_logview) {
        echo "<pre>";
        echo "nextURL=" . $url . "\n";
        echo "</pre>";
      }
        
    } while(true);
    
    
    return $total_offers;
    
  }  
  
######################################################################################  
// Parse
######################################################################################  

  ######### Offer Listing ###########
  
  // version before 2013/7/20
  public function parseOfferNextUrl_1($plain_html) {
    
    $dom = str_get_html($plain_html);
    
    $rets = array();
    if ( $dom->find('div[class="pagination"] div[class="pages"] a[id="olp_page_next"]')) {
      $ret = $dom->find('div[class="pagination"] div[class="pages"] a[id="olp_page_next"]', 0)->href;
    } else {
      $ret = null;
    }

    $dom->clear();
    unset($dom);   
    
    return $ret;     
  }
  
  public function parseOfferNextUrl_2($plain_html) {
    
    $dom = str_get_html($plain_html);
    
    $rets = array();
    if ( $dom->find('div[class="a-pagination"] ul[class="a-pagination"] li a')) {
      $ret = $dom->find('div[class="a-pagination"] ul[class="a-pagination"] li a', 1)->href;
    } else {
      $ret = null;
    }

    $dom->clear();
    unset($dom);   
    
    return $ret;     
  }
    
  public function parseOfferListingByUrl_1($plain_html) { //  before ~ 2013/08 
    
    $dom = str_get_html($plain_html);
    
    $rets = array();
    if ( $dom->find('div[class="resultsset"] tbody[class="result"] tr')) {
      foreach($dom->find('div[class="resultsset"] tbody[class="result"] tr') as $e) {
        
        $ret = array();
        
        if ( $e->find('span[class="price"]', 0)) {
          $text = $e->find('span[class="price"]', 0)->plaintext;
          $ret['price'] = $this->getDigits($text);
        } else {
          
        }
        
        $ret['shipping'] = 0;
        if ( $e->find('div[class="shipping_block"]  span[class="price_shipping"]', 0)) {
          $text = $e->find('div[class="shipping_block"]  span[class="price_shipping"]', 0)->plaintext;
          $ret['shipping'] = $this->getDigits($text);
        } else {

        }
        
        if ( $e->find('div[class="condition"]', 0)) {
          $text = $e->find('div[class="condition"]', 0)->plaintext;
          
          $text = str_replace("\n", "", $text);
          $text = str_replace("\r", "", $text);
          $text = trim($text);

          $ret['condition'] = $text;
        } else {
          $ret['condition'] = "";
        }    
        
        $ret['sellerName'] = "";
        $ret['sellerId'] = "";
        $ret['sellerImage'] = "";
        if ( $e->find('ul[class="sellerInformation"] a img', 0)) {
          $text = $e->find('ul[class="sellerInformation"] a img', 0)->title;
          $ret['sellerName'] = $text;
          
          $ret['sellerImage'] = $e->find('ul[class="sellerInformation"] a img', 0)->src; 

          if ( $e->find('ul[class="sellerInformation"] a', 0)) {
            $text = $e->find('ul[class="sellerInformation"] a', 0)->href;
            if ( preg_match("/shops\/([^\/]+)/i", $text, $matches)) {
              $ret['sellerId'] = $matches[1];
            }
            
          }
          
        } elseif ( $e->find('ul[class="sellerInformation"] div[class="seller"] a', 0)) {
          $text = $e->find('ul[class="sellerInformation"] div[class="seller"] a', 0)->plaintext;
          $ret['sellerName'] = $text;
          
          $text = $e->find('ul[class="sellerInformation"] div[class="seller"] a', 0)->href;
          if ( preg_match("/seller=(.*)/i", $text, $matches)) {
            $text = $matches[1];
            $ret['sellerId'] = $text;
          }
        } elseif ( $e->find('ul[class="sellerInformation"] img', 0)) {
          $img = $e->find('ul[class="sellerInformation"] img', 0);
          $alt = $img->alt;
          
          $ret['sellerImage'] = $img->src; 
          
          if ( $alt == "Amazon.co.jp") {
            $ret['sellerName'] = $img->title;
            $ret['sellerId'] = $img->title;
          }
        }

        if ( $e->find('ul[class="sellerInformation"] div[class="fba_link"]', 0)) {
          $ret['fba'] = 1;
        } else {
          $ret['fba'] = 0;
        } 
        
        $ret['prime'] = 0;
        if ( $e->find('span[class="supersaver"] img', 0)) {
          $img_alt = $e->find('span[class="supersaver"] img', 0)->alt;
          if ( $img_alt == "Amazon Prime (TM)") {
            $ret['prime'] = 1;
          }
        }         
        
        $ret['stock'] = 0;
        if ( $e->find('ul[class="sellerInformation"] div[class="availability"]', 0)) {
          $stock_text = $e->find('ul[class="sellerInformation"] div[class="availability"]', 0)->plaintext;
          if ( strpos($stock_text, $this->_pattern_offerlisting_in_stock) !== false) {
            $ret['stock'] = 1;  
          }
        }          
        
        $ret['rating_percent'] = $this->parseOfferListingRatingPercent($e);
        
        $ret['rating_count'] = $this->parseOfferListingRatingCount($e);
        
        $ret['ship_from'] = $this->parseOfferListingShipFrom($e);
        
        if ( isset($ret['price']) || (isset($ret['sellerId']) && $ret['sellerId'] != "") ) {
          $rets[] = $ret;  
        }

      } 
    } 

    $dom->clear();
    unset($dom);  
    
    return $rets;     
  }
  
  public function parseOfferListingByUrl_2($plain_html) { // 2013/08 ~ 
      
      $dom = str_get_html($plain_html);
      
      $rets = array();
      if ( $dom->find('div[class="a-box-inner"] div[class="olpOffer"]')) {
        foreach($dom->find('div[class="a-box-inner"] div[class="olpOffer"]') as $e) {
          
          //echo $e->plaintext . "<br/><br/>";          continue;
          
          $ret = array();
          
          if ( $e->find('span[class="olpOfferPrice"]', 0)) {
            $text = $e->find('span[class="olpOfferPrice"]', 0)->plaintext;
            $ret['price'] = $this->getDigits($text);
          } else {
            
          }
          
          $ret['shipping'] = 0;
          if ( $e->find('span[class="olpShippingPrice"]', 0)) {
            $text = $e->find('span[class="olpShippingPrice"]', 0)->plaintext;
            $ret['shipping'] = $this->getDigits($text);
          } else {

          }
          
          if ( $e->find('h3[class="olpCondition"]', 0)) {
            $text = $e->find('h3[class="olpCondition"]', 0)->plaintext;
            
            $text = str_replace("\n", "", $text);
            $text = str_replace("\r", "", $text);
            $text = trim($text);

            $ret['condition'] = $text;
          } else {
            $ret['condition'] = "";
          }    
          
          $ret['sellerName'] = "";
          $ret['sellerId'] = "";
          $ret['sellerImage'] = "";
          if ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a img', 0)) {
            $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a img', 0)->title;
            $ret['sellerName'] = $text;
            
            $ret['sellerImage'] = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a img', 0)->src; 

            if ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)) {
              $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)->href;
              if ( preg_match("/shops\/([^\/]+)/i", $text, $matches)) {
                $ret['sellerId'] = $matches[1];
              }
              
            }
            
          } elseif ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a b', 0)) {
            $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a b', 0)->plaintext;
            $ret['sellerName'] = $text;
            
            $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)->href;
            if ( preg_match("/seller=(.*)/i", $text, $matches)) {
              $text = $matches[1];
              $ret['sellerId'] = $text;
            }
          } elseif ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)) {
            $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)->plaintext;
            $ret['sellerName'] = $text;
            
            $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)->href;
            if ( preg_match("/seller=(.*)/i", $text, $matches)) {
              $text = $matches[1];
              $ret['sellerId'] = $text;
            }

          } elseif ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] img', 0)) {
            $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] img', 0)->alt;
            $ret['sellerName'] = $text;
            $ret['sellerId'] = $text;
          }

          if ( $e->find('div[class="olpBadge"]', 0)) {
            $ret['fba'] = 1;
          } else {
            $ret['fba'] = 0;
          } 
          
          $ret['prime'] = 0;
          if ( $e->find('span[class="supersaver"] img', 0)) {
            $img_alt = $e->find('span[class="supersaver"] img', 0)->alt;
            if ( $img_alt == "Amazon Prime (TM)") {
              $ret['prime'] = 1;
            }
          }         
          
          $ret['stock'] = 0;
          if ( $e->find('ul[class="sellerInformation"] div[class="availability"]', 0)) {
            $stock_text = $e->find('ul[class="sellerInformation"] div[class="availability"]', 0)->plaintext;
            if ( strpos($stock_text, $this->_pattern_offerlisting_in_stock) !== false) {
              $ret['stock'] = 1;  
            }
          }          
          
          $ret['rating_percent'] = $this->parseOfferListingRatingPercent($e);
          
          $ret['rating_count'] = $this->parseOfferListingRatingCount($e);
          
          $ret['ship_from'] = $this->parseOfferListingShipFrom($e);
          
          if ( isset($ret['price']) || (isset($ret['sellerId']) && $ret['sellerId'] != "") ) {
            $rets[] = $ret;  
          }

        } 
      } 

      $dom->clear();
      unset($dom);   
      
      return $rets;     
    }  
    
  public function parseOfferListingByUrlForNewOffers_1($plain_html) { // before ~ 2013/08
    
    $dom = str_get_html($plain_html);
    
    
    $rets = array();
    if ( $dom->find('div[class="resultsset"] table', 3)) {
      $table = $dom->find('div[class="resultsset"] table', 3);
      
      if ( $table->find('tbody[class="result"] tr')) {
        foreach($table->find('tbody[class="result"] tr') as $e) {
        
        $ret = array();
        
        if ( $e->find('span[class="price"]', 0)) {
          $text = $e->find('span[class="price"]', 0)->plaintext;
          $ret['price'] = $this->getDigits($text);
        } else {
          
        }
        
        $ret['shipping'] = 0;
        if ( $e->find('div[class="shipping_block"]  span[class="price_shipping"]', 0)) {
          $text = $e->find('div[class="shipping_block"]  span[class="price_shipping"]', 0)->plaintext;
          $ret['shipping'] = $this->getDigits($text);
        } else {

        }
        
        if ( $e->find('div[class="condition"]', 0)) {
          $text = $e->find('div[class="condition"]', 0)->plaintext;
          
          $text = str_replace("\n", "", $text);
          $text = str_replace("\r", "", $text);
          $text = trim($text);

          $ret['condition'] = $text;
        } else {
          $ret['condition'] = "";
        }    
        
        $ret['sellerName'] = "";
        $ret['sellerId'] = "";
        $ret['sellerImage'] = "";
        if ( $e->find('ul[class="sellerInformation"] a img', 0)) {
          $text = $e->find('ul[class="sellerInformation"] a img', 0)->title;
          $ret['sellerName'] = $text;
          
          $ret['sellerImage'] = $e->find('ul[class="sellerInformation"] a img', 0)->src; 

          if ( $e->find('ul[class="sellerInformation"] a', 0)) {
            $text = $e->find('ul[class="sellerInformation"] a', 0)->href;
            if ( preg_match("/shops\/([^\/]+)/i", $text, $matches)) {
              $ret['sellerId'] = $matches[1];
            }
            
          }
          
        } elseif ( $e->find('ul[class="sellerInformation"] div[class="seller"] a', 0)) {
          $text = $e->find('ul[class="sellerInformation"] div[class="seller"] a', 0)->plaintext;
          $ret['sellerName'] = $text;
          
          $text = $e->find('ul[class="sellerInformation"] div[class="seller"] a', 0)->href;
          if ( preg_match("/seller=(.*)/i", $text, $matches)) {
            $text = $matches[1];
            $ret['sellerId'] = $text;
          }
        } elseif ( $e->find('ul[class="sellerInformation"] img', 0)) {
          $img = $e->find('ul[class="sellerInformation"] img', 0);
          $alt = $img->alt;
          
          $ret['sellerImage'] = $img->src; 
          
          if ( $alt == "Amazon.co.jp") {
            $ret['sellerName'] = $img->title;
            $ret['sellerId'] = $img->title;
          }
        }

        if ( $e->find('ul[class="sellerInformation"] div[class="fba_link"]', 0)) {
          $ret['fba'] = 1;
        } else {
          $ret['fba'] = 0;
        } 
        
        $ret['prime'] = 0;
        if ( $e->find('span[class="supersaver"] img', 0)) {
          $img_alt = $e->find('span[class="supersaver"] img', 0)->alt;
          if ( $img_alt == "Amazon Prime (TM)") {
            $ret['prime'] = 1;
          }
        }         
        
        $ret['stock'] = 0;
        if ( $e->find('ul[class="sellerInformation"] div[class="availability"]', 0)) {
          $stock_text = $e->find('ul[class="sellerInformation"] div[class="availability"]', 0)->plaintext;
          if ( strpos($stock_text, $this->_pattern_offerlisting_in_stock) !== false) {
            $ret['stock'] = 1;  
          }
        }          
        
        $ret['rating_percent'] = $this->parseOfferListingRatingPercent($e);
        
        $ret['rating_count'] = $this->parseOfferListingRatingCount($e);
        
        $ret['ship_from'] = $this->parseOfferListingShipFrom($e);
        
        if ( isset($ret['price']) || (isset($ret['sellerId']) && $ret['sellerId'] != "") ) {
          $rets[] = $ret;  
        }

      }         
      }
    }

    $dom->clear();
    unset($dom);   
    
    return $rets;     
  }  

  public function parseOfferListingByUrlForNewOffers_2($plain_html) { // 2013/08 ~ 
      
      $dom = str_get_html($plain_html);
      
      $rets = array();
      
      
      if ( $dom->find('div[class="a-box-inner"] div[class="a-spacing-double-large"]')) {
        foreach($dom->find('div[class="a-box-inner"] div[class="a-spacing-double-large"]') as $section) {
        
          if ( $section->find('div[class="a-spacing-medium"] h2')) {
            $section_title = $section->find('div[class="a-spacing-medium"] h2',0)->plaintext;
            
            if ( strstr($section_title, $this->_pattern_offerlisting_new )) {
              
              if ( $section->find('div[class="olpOffer"]')) {
                foreach($section->find('div[class="olpOffer"]') as $e) {
                  
                  $ret = array();
                  
                  if ( $e->find('span[class="olpOfferPrice"]', 0)) {
                    $text = $e->find('span[class="olpOfferPrice"]', 0)->plaintext;
                    $ret['price'] = $this->getDigits($text);
                  } else {
                    
                  }
                  
                  $ret['shipping'] = 0;
                  if ( $e->find('span[class="olpShippingPrice"]', 0)) {
                    $text = $e->find('span[class="olpShippingPrice"]', 0)->plaintext;
                    $ret['shipping'] = $this->getDigits($text);
                  } else {

                  }
                  
                  if ( $e->find('h3[class="olpCondition"]', 0)) {
                    $text = $e->find('h3[class="olpCondition"]', 0)->plaintext;
                    
                    $text = str_replace("\n", "", $text);
                    $text = str_replace("\r", "", $text);
                    $text = trim($text);

                    $ret['condition'] = $text;
                  } else {
                    $ret['condition'] = "";
                  }    
                  
                  $ret['sellerName'] = "";
                  $ret['sellerId'] = "";
                  $ret['sellerImage'] = "";
                  if ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a img', 0)) {
                    $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a img', 0)->title;
                    $ret['sellerName'] = $text;
                    
                    $ret['sellerImage'] = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a img', 0)->src; 

                    if ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)) {
                      $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)->href;
                      if ( preg_match("/shops\/([^\/]+)/i", $text, $matches)) {
                        $ret['sellerId'] = $matches[1];
                      }
                      
                    }
                    
                  } elseif ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a b', 0)) {
                    $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a b', 0)->plaintext;
                    $ret['sellerName'] = $text;
                    
                    $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] a', 0)->href;
                    if ( preg_match("/seller=(.*)/i", $text, $matches)) {
                      $text = $matches[1];
                      $ret['sellerId'] = $text;
                    }
                  } elseif ( $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] img', 0)) {
                    $text = $e->find('div[class="olpSellerColumn"] p[class="olpSellerName"] img', 0)->alt;
                    $ret['sellerName'] = $text;
                    $ret['sellerId'] = $text;
                  }

                  if ( $e->find('div[class="olpBadge"]', 0)) {
                    $ret['fba'] = 1;
                  } else {
                    $ret['fba'] = 0;
                  } 
                  
                  $ret['prime'] = 0;
                  if ( $e->find('span[class="supersaver"] img', 0)) {
                    $img_alt = $e->find('span[class="supersaver"] img', 0)->alt;
                    if ( $img_alt == "Amazon Prime (TM)") {
                      $ret['prime'] = 1;
                    }
                  }         
                  
                  $ret['stock'] = 0;
                  if ( $e->find('ul[class="sellerInformation"] div[class="availability"]', 0)) {
                    $stock_text = $e->find('ul[class="sellerInformation"] div[class="availability"]', 0)->plaintext;
                    if ( strpos($stock_text, $this->_pattern_offerlisting_in_stock) !== false) {
                      $ret['stock'] = 1;  
                    }
                  }          
                  
                  $ret['rating_percent'] = $this->parseOfferListingRatingPercent($e);
                  
                  $ret['rating_count'] = $this->parseOfferListingRatingCount($e);
                  
                  $ret['ship_from'] = $this->parseOfferListingShipFrom($e);
                  
                  if ( isset($ret['price']) || (isset($ret['sellerId']) && $ret['sellerId'] != "") ) {
                    $rets[] = $ret;  
                  }

                } 
              }               
            }
          }  
        }
      }
      
      $dom->clear();
      unset($dom);   
      
      return $rets;     
    }  
  
  public function parseOfferListingRatingPercent($html) {
    //$html = str_get_html($html);

    if ( $html->find('ul[class="sellerInformation"] div[class="rating"] a b', 0)) {
        $str = $html->find('ul[class="sellerInformation"] div[class="rating"] a b', 0)->plaintext;
        $ret = $this->getDigits($str);
    } else {
      $ret = 0;
    }                
 
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }
  
  public function parseOfferListingRatingCount($html) {
    //$html = str_get_html($html);

    if ( $html->find('ul[class="sellerInformation"] div[class="rating"]', 0)) {
        $str = $html->find('ul[class="sellerInformation"] div[class="rating"]', 0)->plaintext;
        
        $pattern = "/\((.*)" . $this->_pattern_offerlisting_total_rating . "\)/i"; 
        
        if ( preg_match($pattern, $str, $matches)) {
          $ret = $this->getDigits($matches[1]);  
        } else {
          $ret = 0;
        }
        
    } else {
      $ret = 0;
    }                 
 
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }  
    
  public function parseOfferListingShipFrom($html) {
    //$html = str_get_html($html);

    if ( $html->find('ul[class="sellerInformation"] div[class="availability"]', 0)) {
        $str = $html->find('ul[class="sellerInformation"] div[class="availability"]', 0)->plaintext;
        
        $pattern = "/" . $this->_pattern_offerlisting_ship_from . "([^\.]*)\./i";
        
        if ( preg_match($pattern, $str, $matches)) {
          $ret = $matches[1];  
        } else {
          $ret = '';
        }
                    
    } else {
      $ret = '';
    }              
        
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }  
  
  ######### Asin Detail ###########
  
  public function parseMoreChoice(&$html) {
    //$html = str_get_html($html);

    if ( $html->find('div[class="mbcOlpLink"] a[class="buyAction"]', 0)) {
      $str = trim($html->find('div[class="mbcOlpLink"] a[class="buyAction"]', 0)->plaintext);
      $ret = $this->getDigits($str);
    } else {
      $ret = 0;
    }     

    //$html->clear();
    //unset($html);    
    
    return $ret;    
  }
  
  public function parseMoreChoiceList(&$html) {
    //$html = str_get_html($html);

    $ret = array();
    if ( $html->find('table[class="mbcOffers"] tbody tr td[class="mbcOfferRowTD"]')) 
    {
        foreach($html->find('table[class="mbcOffers"] tbody tr td[class="mbcOfferRowTD"]') as $e)
        {
          $temp = array();
          if ( $e->find('table[class="mbcOfferRow"] tbody tr[class="mbcMerch"] td span', 0)->id) {
          $str = trim($e->find('table[class="mbcOfferRow"] tbody tr[class="mbcMerch"] td span', 0)->id);
          $str_arr = explode("_", $str);
          $temp['sellerId'] = isset($str_arr[2]) ? $str_arr[2] : "";
          } else {
              $temp['sellerId'] = "";
          }

          if ( $e->find('table[class="mbcOfferRow"] tbody tr[class="mbcMerch"] td', 0)) {
            $str = trim($e->find('table[class="mbcOfferRow"] tbody tr[class="mbcMerch"] td', 0)->plaintext);
            $temp['name'] = $str;
          } else {
            $temp['name'] = "";
          }
          
          if ( $e->find('table[class="mbcOfferRow"] tbody tr td[class="mbcPriceCell"]', 0)) { 
            $_t = explode('+', trim(str_replace('&nbsp;', '', $e->find('table[class="mbcOfferRow"] tbody tr td[class="mbcPriceCell"]', 0)->plaintext)));
            $temp['price'] = $this->getDigits($_t[0]);
          } else {
              $temp['price'] = 0;
          }
          
          if ( $e->find('table[class="mbcOfferRow"] tbody tr td[class="mbcPriceCell"] span[class="plusShippingText"]', 0)) {
            $temp['shipping'] = $this->getDigits($e->find('table[class="mbcOfferRow"] tbody tr td[class="mbcPriceCell"] span[class="plusShippingText"]', 0)->plaintext);
          } else {
              $temp['shipping'] = 0;
          }

          $ret[] = $temp;
        }
    }

    //$html->clear();
    //unset($html);    
    
    return $ret;    
  }  

  public function parseProductTechnicalDetail(&$html) {
    //$html = str_get_html($html);

    $ret = array();
    foreach($html->find('div[class="bucket"]') as $e)
    {
      if ( !$e->find('h2', 0)) {
        continue;
      }
      if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_technical_detail) {
        continue;
      }
      
      if($e->find('ul'))
      {
        foreach($e->find('ul li') as $li) {
          
          $ret[] = $li->plaintext;
        }
        //$ret = trim($e->find('ul', 0)->outertext);
      }
    } 
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }  

  public function parseProductDescription(&$html, $need_html=false) {
    //$html = str_get_html($html);

    $ret = "";
    if ( $html->find('div[class="bucket"]')) {
      foreach($html->find('div[class="bucket"]') as $e)
      {
        if ( !$e->find('h2', 0)) {
          continue;
        }
        if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_description) {
          continue;
        }
        
        if($e->find('div[class="content"]'))
        {
          if ($need_html) {
            $ret = trim($e->find('div[class="content"]', 0)->outertext);
          } else {
            $ret = trim($e->find('div[class="content"]', 0)->plaintext);
          }
          
        }
      }
    }
    
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }  
  
  public function parseProductDetails(&$html) {
    //$html = str_get_html($html);

    $ret = array();
    foreach($html->find('table tbody tr td[class="bucket"]') as $e)
    {
      if ( !$e->find('h2', 0)) {
        continue;
      }
      if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) {
        continue;
      }
      
      if ($e->find('div[class="content"] ul li')) {
        foreach($e->find('div[class="content"] ul li') as $t)
        {
          $ret[] = $this->trimBlankLines($t->plaintext);
        }
      }
    }
    
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }  
  
  public function parseProductItemDetails(&$html) {
    //$html = str_get_html($html);

    $ret = array();
    foreach($html->find('table tbody tr td[class="bucket"]') as $e)
    {
      if ( !$e->find('h2', 0)) {
        continue;
      }
      if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) {
        continue;
      }
      
      if ($e->find('div[class="content"] ul li')) {
        foreach($e->find('div[class="content"] ul li') as $li)
        {
          if ($li->find('b',0)) {
              $ret[$this->trimBlankLines(str_replace(":", "", $li->find('b',0)->plaintext))] = $this->trimBlankLines(str_replace($li->find('b',0)->plaintext, "", $li->plaintext));
            }
        }
      }
    }
    
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }
  
  public function parseCustomerAlsoBoughtItems(&$html) {
    //$html = str_get_html($html);

    $ret = array();
    if ($html->find('div [id="purchaseSimsData"]', 0)) {
      $ret = $html->find('div [id="purchaseSimsData"]', 0)->plaintext;
      $ret = explode(",", $ret);
    }
    
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }  
  
  public function parseProductBy(&$html) {
    //$html = str_get_html($html);

    $ret = "";     
    if($html->find('div[class="buying"] h1[class="parseasinTitle"]'))
    {
      
      if ( $html->find('div[class="buying"] h1[class="parseasinTitle"]', 0)->parent()->find('a', 0)) {
        $ret = $html->find('div[class="buying"] h1[class="parseasinTitle"]', 0)->parent()->find('a', 0)->plaintext;
      }
      
      if ( $ret == "") {
        $temp = $html->find('div[class="buying"] h1[class="parseasinTitle"]', 0)->parent()->innertext;
        
        $temp = str_replace("\n", "", $temp);
        
        if ( preg_match("/<a[^<]*>(.*)<\/a>/i", $temp, $matches)) {
          $ret = $matches[1];
        }
      }      
    }
    
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }    
    
  public function parseSalesRank(&$html) {
    //$html = str_get_html($html);

    $infos = array();    
    
    foreach($html->find('table tbody tr td[class="bucket"]') as $e)
    {
      if($e->find('h2')) {
        if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) continue;
        foreach($e->find('div[class="content"] ul li[id="SalesRank"]') as $t)
        {
          $temp = array();
          $_t = array();
          //if($t->class == "zg_hrsr") continue;
          if(trim($t->plaintext) != '')
          {
            $SalesRank = $t->plaintext;
            
            if($t->find('ul[class="zg_hrsr"]'))
            {
              $zg_hrsr = $t->find('ul[class="zg_hrsr"]', 0)->plaintext;
              $SalesRank = str_replace($zg_hrsr, '', $SalesRank);
            }

            $b = trim($t->find('b', 0)->plaintext);
            $a = trim($t->find('a', 0)->plaintext);
            $li = trim($SalesRank);
            
            $li = str_replace($a, '', str_replace($b, '', $li));
            
            $pattern_in = $this->_pattern_sales_rank_in; 
            $pattern_num = $this->_pattern_sales_rank_num;

            //var_dump($li);
            $_t = explode($pattern_in, trim($li));
            
            if ( isset($_t[0])) {
              $temp['rank'] = $this->getDigits(trim(str_replace($pattern_num, '', $_t[0])));
            } else {
              $temp['rank'] = 0;
            }
            
            if ( isset($_t[1])) {
              $temp['categorytext'] = trim(str_replace('()', '', trim($_t[1])));
            } else {
              $temp['categorytext'] = '';
            }

            $infos[] = $temp;
          }
        }
        
        foreach($e->find('div[class="content"] ul[class="zg_hrsr"] li[class="zg_hrsr_item"]') as $t)
        {
          $temp = array();

          $temp['rank'] = trim(str_replace($pattern_num, '', $t->find('span[class="zg_hrsr_rank"]', 0)->plaintext));
          $temp['categorytext'] = trim(str_replace($pattern_in, '', $t->find('span[class="zg_hrsr_ladder"]', 0)->plaintext));
          
          $infos[] = $temp;
        }
      }
    }
    
    //$html->clear();
    //unset($html);    
    
    return $infos;
  } 
  
  public function parseFrequentlyBought(&$html) {
    //$html = str_get_html($html);

    $ret = array();
    
    $pattern = 'table[class="' . $this->_pattern_frequently_bought .'"] tr td a';
    if ($html->find($pattern)) {
      foreach($html->find($pattern) as $e)
      {
        $asin = $this->getAsinFromUrl($e->href);
        if ( $asin != "") {
          $ret[] = $asin;
        }
        
      }
    }

    //$html->clear();
    //unset($html);    
    
    return $ret;
  }   
  
  public function parseOtherItems(&$html) {
    //$html = str_get_html($html);
 
    $ret = array();
    
    $pattern = 'table[class="' . $this->_pattern_other_items .'"] tr td a'; 
    if ($html->find($pattern)) {
      foreach($html->find('ul[class="content small"] li') as $e)
      {
        $asin = $this->getAsinFromUrl($e->find('a', 0)->href);
        if ( $asin != "") {
          $ret[] = $asin;
        }
      }
    }    
    
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }         
      
  public function parseSellerId(&$html) {
    //$html = str_get_html($html);
 
    $ret = "";
    if($html->find('div[class="buying"]'))
    {
        foreach( $html->find('div[class="buying"] b a') as $e) {
            if ( isset($e->href)) {
                if (preg_match("/seller=(.*)/i", $e->href, $matches)) {
                    $ret = $matches[1];
                    break;
                }
            }
        }
    }
    
    if ( $ret == "") {
      if($html->find('a'))
      {
          foreach( $html->find('a') as $e) {
              if ( isset($e->href)) {
                $id = $this->getSellIdFromUrl($e->href);
                if ( $id != "") {
                  $ret = $id;
                  break;
                }
              }
          }
      }      
    }
        
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }        
  
  public function parseTitle(&$html) {
    //$html = str_get_html($html);
 
    $ret = "";
    if($html->find('div[class="buying"] span[id="btAsinTitle"]', 0)) {
      $ret = $html->find('div[class="buying"] span[id="btAsinTitle"]', 0)->plaintext;
    }          
    
    if  ( $ret == "") {
      if($html->find('h1[class="parseasinTitle "] span[id="btAsinTitle"]', 0)) {
        $ret = $html->find('h1[class="parseasinTitle"] span[id="btAsinTitle"]', 0)->plaintext;
      }     
    }
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }       
      
  public function parseInventory(&$html) {
    //$html = str_get_html($html);
 
    $ret = 0;
    if($html->find('div[class="buying"] span[class="availGreen"]')) {
      $ret = 1;
    }        
    //$html->clear();
    //unset($html);    
    
    return $ret;
  } 
  
  public function parseAvailability(&$html) {
    //$html = str_get_html($html);
 
    $ret = '';
    if($html->find('div[class="buying"] span[class="availGreen"]')) {
      $ret = $html->find('div[class="buying"] span[class="availGreen"]',0)->plaintext;
    } else if($html->find('div[class="buying"] span[class="availOrange"]')) {
      $ret = $html->find('div[class="buying"] span[class="availOrange"]',0)->plaintext;
    } else if($html->find('div[id="availability"] span')) {
      $ret = $this->trimBlankLines($html->find('div[id="availability"] span"]',0)->plaintext);
    }         
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }   
  
  public function parseSoldBy(&$html) {
    //$html = str_get_html($html);
 
    $ret = '';
    if( $html->find('div[class="buying"]')) {
      
      foreach($html->find('div[class="buying"]') as $buying) {
        
        $text = $buying->plaintext;
        $list = explode(".", $text);
        
        if ( is_array($list)) {
          foreach($list as $line) {
            
            $pattern = "/(" . $this->_pattern_asin_soldby . ".*)/i";
            if ( preg_match($pattern, $line, $matches)) {
              $ret = $matches[1];
              break;
            }
            
          }
        }
        
        /*
        if ( $buying->find('span[class="availGreen"]')) {
          $str = $buying->plaintext;
          $availability = $buying->find('span[class="availGreen"]',0)->plaintext;
          
          $str = str_replace($availability, "", $str);
          
          if ( preg_match("/([^\.]*\.) /i", $str, $matches)) {
            $ret = $matches[1];
          } else {
            $ret = $str;
          }
          
          unset($str);
          
          break;
          
        } 
        elseif ( $buying->find('span[class="availOrange"]')) {
          $str = $buying->plaintext;
          $availability = $buying->find('span[class="availOrange"]',0)->plaintext;
          
          $str = str_replace($availability, "", $str);
          
          if ( preg_match("/([^\.]*\.) /i", $str, $matches)) {
            $ret = $matches[1];
          } else {
            $ret = $str;
          }
          
          unset($str);
          
          break;
          

        }        
        */
        
      }
    } 
    
    if ( $ret == "" && $html->find('div[id="merchant-info"]')) {

          $ret = $html->find('div[id="merchant-info"]',0)->plaintext;
    }       
    //$html->clear();
    //unset($html);    
    
    return $this->trimBlankLines($ret);
  }   
  
  public function parsePriceAndShipPrice(&$html) {
    //$html = str_get_html($html);
    
    $AmazonProductInfo['price'] = 0;
    $AmazonProductInfo['shipping'] = 0;
    $AmazonProductInfo['currency'] = '';
 
    if($html->find('div[id="buyBoxContent"] div[id="BBPricePlusShipID"] span[id="pricePlusShippingQty"] b[class="price"]'))
    {
      $str = $html->find('div[id="buyBoxContent"] div[id="BBPricePlusShipID"] span[id="pricePlusShippingQty"] b[class="price"]', 0)->plaintext;
      if ( preg_match("/([0-9,\.]+)/i", $str, $matches)) {
        $price = $matches[1];
      } else {
        $price = $str;
      }
      
      $currency = str_replace($price, "", $str);
      
      //$price = str_replace(",", ".", $price);
      $AmazonProductInfo['price'] = $this->getDigits($price);
      $AmazonProductInfo['currency'] = $currency;
      
      if($html->find('div[id="buyBoxContent"] div[id="BBPricePlusShipID"] span[id="pricePlusShippingQty"] span[class="plusShippingText"]'))
      {
        $AmazonProductInfo['shipping'] = $html->find('div[id="buyBoxContent"] div[id="BBPricePlusShipID"] span[id="pricePlusShippingQty"] span[class="plusShippingText"]', 0)->plaintext;
        if ( preg_match("/([0-9,\.]+)/i", $AmazonProductInfo['shipping'], $matches)) {
          //$AmazonProductInfo['shipping'] = str_replace(",", ".", $matches[1]);
          $AmazonProductInfo['shipping'] = $matches[1];
        }          
        $AmazonProductInfo['shipping'] = $this->getDigits($AmazonProductInfo['shipping']);
      }
    } elseif ( $html->find('div[class="pa_price"]')) {
      
      $str_all = $html->find('div[class="pa_price"]', 0)->plaintext;
      
      if ( $html->find('span[class="pa_ship"]')) {
        $str_shipping = $html->find('span[class="pa_ship"]', 0)->plaintext;
      } else {
        $str_shipping = "";
      }
      
      $str_price = str_replace($str_shipping, "", $str_all);
      $price = $this->getDigits($str_price);
      $currency = str_replace($price, "", $str_price);
      
      $AmazonProductInfo['price'] = $price;
      $AmazonProductInfo['currency'] = $this->trimBlankLines($currency);      
      $AmazonProductInfo['shipping'] = $this->getDigits($str_shipping);
      
      
      
    } elseif ( $html->find('table[class="product"] tbody tr td'))  {         
        foreach($html->find('table[class="product"] tbody tr td') as $e)
        {
          if ( $e->find('b[class="priceLarge"]', 0)) {
            $str = $e->find('b[class="priceLarge"]', 0)->plaintext;
            if ( preg_match("/([0-9,\.]+)/i", $str, $matches)) {
              $AmazonProductInfo['price'] = $matches[1];
            } else {
              $AmazonProductInfo['price'] = 0;
            }             
            
            $AmazonProductInfo['currency'] = str_replace($AmazonProductInfo['price'], "", $str);
            //$AmazonProductInfo['price'] = str_replace(",", ".", $AmazonProductInfo['price']);
            $AmazonProductInfo['price'] = $this->getDigits($AmazonProductInfo['price']);
          } 
          
          if ( $e->find('b', 1)) {
            $AmazonProductInfo['shipping'] = $e->find('b', 1)->plaintext;
            if ( preg_match("/([0-9,\.]+)/i", $AmazonProductInfo['shipping'], $matches)) {
              //$AmazonProductInfo['shipping'] = str_replace(",", ".", $matches[1]);
            } else {
              $AmazonProductInfo['shipping'] = 0;
            }                
            $AmazonProductInfo['shipping'] = $this->getDigits($AmazonProductInfo['shipping']);
          }
        }
    } elseif ( $html->find('div[id="price"] tbody tr td'))  {         
        foreach($html->find('div[id="price"] tbody tr td') as $e)
        {
          if ( $e->find('span', 0)) {
            $str = $e->find('span', 0)->plaintext;
            
            if ( preg_match("/([0-9,\.]+)/i", $str, $matches)) {
              $AmazonProductInfo['price'] = $matches[1];
            } else {
              $AmazonProductInfo['price'] = 0;
            }             
            
            $AmazonProductInfo['currency'] = str_replace($AmazonProductInfo['price'], "", $str);
            //$AmazonProductInfo['price'] = str_replace(",", ".", $AmazonProductInfo['price']);
            $AmazonProductInfo['price'] = $this->getDigits($AmazonProductInfo['price']);
          } 
          
          if ( $e->find('span', 1)) {
            $str = $e->find('span', 1)->plaintext;
            if ( preg_match("/([0-9,\.]+)/i",  $str, $matches)) {
              //$AmazonProductInfo['shipping'] = str_replace(",", ".", $matches[1]);
              $shipping = $matches[1];
            } else {
              $shipping = 0;
            }   
            
            $AmazonProductInfo['shipping'] = $this->getDigits($shipping);
          }
        }                                                           

    }

    //$html->clear();
    //unset($html);    
    
    return array($AmazonProductInfo['price'], $AmazonProductInfo['shipping'], $AmazonProductInfo['currency']);
  }      

  public function parseAsin(&$html) {
    //$html = str_get_html($html);

    $ret = "";
    
    if ($html->find('table tbody tr td[class="bucket"]')) {

      foreach($html->find('table tbody tr td[class="bucket"]') as $e) {
        if ( !$e->find('h2', 0)) { 
          continue;
        }                                   
        
        if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) {
          continue;
        }
        
        if ($e->find('div[class="content"] ul li')) {
          foreach($e->find('div[class="content"] ul li') as $t)
          {
            if($t->find('b'))
            {
              if(trim($t->find('b', 0)->plaintext) == $this->_pattern_asin_text)
              {
                $ret = str_replace(trim($t->find('b', 0)->plaintext), '', trim($t->plaintext));
                break;
              } else {
                
              }
              
            }
          }  
        }
        
        if ( $ret != "") {
          break;
        }  
      }    
    }
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }  
  
  public function parseISBN(&$html) {
    //$html = str_get_html($html);

    $ret = "";
    
    if ($html->find('table tbody tr td[class="bucket"]')) {

      foreach($html->find('table tbody tr td[class="bucket"]') as $e) {
        if ( !$e->find('h2', 0)) { 
          continue;
        }                                   
        
        if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) {
          continue;
        }
        
        if ($e->find('div[class="content"] ul li')) {
          foreach($e->find('div[class="content"] ul li') as $t)
          {
            if($t->find('b'))
            {
                if(trim($t->find('b', 0)->plaintext) == $this->_pattern_isbn10_text)
                {
                  $ret = str_replace(trim($t->find('b', 0)->plaintext), '', trim($t->plaintext));
                  break;
                }
                
                if(trim($t->find('b', 0)->plaintext) == $this->_pattern_isbn13_text)
                {
                  $ret = str_replace(trim($t->find('b', 0)->plaintext), '', trim($t->plaintext));
                  break;
                }
            }
          }  
        }
        
        if ( $ret != "") {
          break;
        }  
      }    
    }
    //$html->clear();
    //unset($html);    
    
    return $ret;
  }   
  
  public function parseLargeImage($html) {
    //$dom = str_get_html($html);

    $imgurls_1 = $this->parseLargeImage_1($html);
    $imgurls_2 = $this->parseLargeImage_2($html);
    $imgurls_3 = $this->parseLargeImage_3($html);
    
    if ( count($imgurls_1) > count($imgurls_2)) {
      $imgurls = $imgurls_1;
    } else {
      $imgurls = $imgurls_2;
    }
    
    if ( count($imgurls) > count($imgurls_3)) {
      
    } else {
      $imgurls = $imgurls_3;
    }    
    
    /*
    $imgurls = $this->parseLargeImage_1($html);
    if ( is_array($imgurls) && count($imgurls) > 0) {
    } else {
      $imgurls = $this->parseLargeImage_2($html);
      if ( is_array($imgurls) && count($imgurls) == 0) {
        $imgurls = $this->parseLargeImage_3($html);
      }
    }
    */

    return $imgurls;    
  }    
  
  public function parseLargeImage_1($html) {  // CA, UK
    //$dom = str_get_html($html);

    // image urls
    $imgurls = array();
    preg_match_all("/registerImage\(\"\w+\"\,[\s]{1}\"(http:\/\/[^\"]+)\"/", $html, $matches);
    if(count($matches[1]) > 0) $imgurls = $matches[1];
    
    if($imgurls == null)
    {
      $html = str_get_html($html);
      
      if ($html->find('div[class="thumbs left"] img')) {
        foreach($html->find('div[class="thumbs left"] img') as $e)
        {
          $temp = trim($e->src);
          $temp = str_replace('SS40', 'SS450', $temp);
          $temp = str_replace('SS30', 'SS450', $temp);
          $temp = preg_replace("/_S.*/i", "jpg", $temp);
          $imgurls[] = $temp;
        }
      }
      
      $html->clear();
      unset($html);         
    }  
    
    return $imgurls;    
  }  
  
  public function parseLargeImage_2($html) {  // US
    $html = str_get_html($html);
    
    $imgurls = array();
    
    if ($html->find('div[id="thumb-strip"] div[class="thumb"] img')) {
      foreach($html->find('div[id="thumb-strip"] div[class="thumb"] img') as $e)
      {
        $temp = trim($e->src);
        $temp = str_replace('SL75_AA32', 'SL500_AA300', $temp);
        $temp = str_replace('SL75_AA45', 'SL500_AA300', $temp);
        
        $temp = preg_replace("/_SL75.*/i", "SL500_AA300.jpg", $temp);
        $temp = preg_replace("/_S.*/i", "jpg", $temp);
        $imgurls[] = $temp;
      }
    } elseif ( $html->find('div[id="thumbs-image"] img')) {
        $e = $html->find('div[id="thumbs-image"] img', 0);
        
        $temp = trim($e->src);
        $temp = str_replace('SL75_AA32', 'SL500_AA300', $temp);
        $temp = str_replace('SL75_AA45', 'SL500_AA300', $temp);
        
        $temp = preg_replace("/_SL75.*/i", "SL500_AA300.jpg", $temp);
        $temp = preg_replace("/_S.*/i", "jpg", $temp);
        $imgurls[] = $temp;      
    }

    $html->clear();
    unset($html);    
    
    return $imgurls;    
  }     

  public function parseLargeImage_3($html) {  // US
    $html = str_get_html($html);
    
    $imgurls = array();
    
    if ($html->find('div[class="alt_images_thumb"] div[class="alt_image_selector"]')) {
      foreach($html->find('div[class="alt_images_thumb"]  div[class="alt_image_selector"]') as $e)
      {
        $temp = $this->getImageUrl($e->style);
        $temp = str_replace('AA40', 'AA450', $temp);
        $temp = str_replace('AA30', 'AA450', $temp);
        
        $temp = preg_replace("/_S.*/i", "jpg", $temp);
        $imgurls[] = $temp;
      }
    }

    $html->clear();
    unset($html);    
    
    return $imgurls;    
  }
  
  public function parseCustomerImage($html) {  // US
    $html = str_get_html($html);
    
    $imgurls = array();
    
    if ($html->find('div[class="cmuImageSliderBlock"] div a img')) {
      foreach($html->find('div[class="cmuImageSliderBlock"] div a img') as $e)
      {
        $temp = preg_replace("/_.*_\./i", "", $e->src);
        
        if ( preg_match("/ecx\.images-amazon\.com\/images\/I/i", $temp, $matches)) {
          $imgurls[] = $temp;  
        }
        
      }
    }

    $html->clear();
    unset($html);    
    
    return $imgurls;    
  }  
  
  public function parseProductOffers($html) {  // US
    $html = str_get_html($html);
    
    $offers = array();
    
    if ($html->find('div[id="olpDivId"] span[class="olpCondLink"]')) {
      foreach($html->find('div[id="olpDivId"] span[class="olpCondLink"]') as $span)
      {

        if ( $span->find('a')) {
          $text = $span->find('a',0)->plaintext;
          $size = $this->getDigits($text);
          $type = trim(str_replace(array($size, "&nbsp;"), array("", ""), $text));
        } else {
          $size = 0;
          $type = "";
        }
        
        if ( $span->find('span[class="price"]')) {
          $text = $span->find('span[class="price"]',0)->plaintext;
          $price = $this->getDigits($text);
        } else {
          $price = 0;
        }
        
        if ($type != "") {
          $offers[$type] = array('offer'=>$size, 'price'=>$price);
        }
        
      }
    }

    $html->clear();
    unset($html); 
    
    return $offers;    
  }   
  
  public function getDigits($arg) { // replace <,> with empty
  
    if ( preg_match("/([0-9]+)/i", $arg, $matches)) {
      if ( preg_match("/([0-9,\.]+)/i", $arg, $matches)) {
        $ret = $matches[1];
        $ret = str_replace(",", "", $ret);
        return $ret;
      }
    }
    
    return 0;
  }   
  
# categories  
  public function getCategories($url, $level, $callback=null, $limit_level=3) {
    
    if ( $this->_logview) {
      echo "Level = " . $level . "\n";
    }
    
    $html = $this->getUrl($url);
    
    if ( $html) {
      $cats = $this->parseCategories($html);
      
      if ($callback) {
        call_user_func($callback, $url, $cats);
      }     
      
      if ( $level >= $limit_level || count($cats) == 0) {
        return;
      }
      if ( count($cats) > 0) {
        foreach($cats as $cat) {
          $this->getCategories($cat[0], $level+1, $callback, $limit_level);
        }
      }
    }
  }

  public function parseCategories($html) { 
    
    $cats = array();
    
      $dom = str_get_html($html);
      
      //total asins
      $text = "";
      if ( $dom->find('div[id="zg_left_col2"] ul[id="zg_browseRoot"] ul li', 0)) {
        $lis = $dom->find('div[id="zg_left_col2"] ul[id="zg_browseRoot"] ul li');
        
        foreach($lis as $li) {
          if ( $li->find("a", 0)) {
            $a = $li->find("a", 0);
            
            $cats[] = array($a->href, $a->plaintext);
          } 
        }
      }
      
      $dom->clear();
      unset($dom);
      
      return $cats;
  }
  
  # best seller
  
 public function getBesetTopSellers($url, $callback=null) {
   
   $rets = array(); 
   
   for($pg=1; $pg<=5; $pg++) {
     
     if ( $pg == 1) {
       $pg_url = $url;
     } else {
       $pg_url = str_replace("pg_1/", "pg_{$pg}/", $next_page_url);
       $pg_url = str_replace("&pg=1", "&pg={$pg}", $pg_url);
     }
     
      if ( $this->_logview) {
        echo $pg_url . "\n";
      }     
     
      $html = $this->getUrl($pg_url);
      
      if ( $html) {
        $items = $this->parseBestSellers($html);
        $next_page_url = isset($items[0]['page_url']) ? $items[0]['page_url'] : null;
        
        if ( $this->_logview) {
          print_r($items);
        }
          
        foreach($items as $item) {
          $rets[] = $item;
        }
      }
   }
   
   return $rets;  
     
   
  }
    
  public function parseBestSellers($html) { 
    
      $rets = array();
    
      $dom = str_get_html($html);
      
      //if ( $dom->find('div[class="zg_itemImmersion"] div[class="zg_itemWrapper"] ul[id="zg_browseRoot"] ul li', 0)) {
      if ( $dom->find('div[class="zg_itemImmersion"]')) {

        $items = $dom->find('div[class="zg_itemImmersion"]');
        foreach($items as $item) {
          //echo $item->plaintext;
          
          # days in top 100
          if ( $item->find('td[class="zg_daysInList"]')) {
            $ret['days_in_list'] = $item->find('td[class="zg_daysInList"]', 0)->plaintext;
            
            if ( preg_match("/([0-9,\.]+) /i", $ret['days_in_list'], $matches)) {
              $ret['days_in_list'] = $this->getDigits($matches[1]);  
            }
             
          } else {
            $ret['days_in_list'] = '';
          }
          
          # title
          if ( $item->find('div[class="zg_title"] a')) {
            $ret['title'] = $item->find('div[class="zg_title"] a', 0)->plaintext;
            $ret['item_url'] = $this->trimBlankLines($item->find('div[class="zg_title"] a', 0)->href);
            $ret['asin'] = $this->getAsinFromUrl($ret['item_url']);
            $ret['country'] = $this->getCountryFromUrl($ret['item_url']);
          }        
          
          # rating
          if ( $item->find('div[class="zg_reviews"] span[class="crAvgStars"] span[class="swSprite"]')) {
            $ret['rating'] = $item->find('div[class="zg_reviews"] span[class="crAvgStars"] span[class="swSprite"]', 0)->title;
            
            if ( preg_match("/([0-9,\.]+)/i", $ret['rating'], $matches)) {
              $ret['rating'] = $matches[1]; 
            }          
          }           
          
          # number of rating
          if ( $item->find('div[class="zg_reviews"] span[class="crAvgStars"] a', 2)) {
            $ret['number_of_rating'] = $item->find('div[class="zg_reviews"] span[class="crAvgStars"] a', 2)->plaintext;
            $ret['number_of_rating'] = str_replace(",", "", $ret['number_of_rating']);
            
          }     
          
          # price
          if ( $item->find('div[class="zg_price"] strong[class="price"]')) {
            $text = $item->find('div[class="zg_price"] strong[class="price"]', 0)->plaintext;

            if ( preg_match("/([^0-9]+)([0-9,\.]+) -.*/i", $text, $matches)) { // $x - $y
              $price = str_replace(",", ".", $matches[2]); 
              $currency = trim($matches[1]);
            } elseif ( preg_match("/([^0-9]+)([0-9,\.]+)/i", $text, $matches)) { // $x - $y              
              $price = str_replace(",", ".", $matches[2]); 
              $currency = trim($matches[1]);
            } 
            
            $ret['price'] = $price;
            $ret['currency'] = $currency;
          }      
          
          # offers
          if ( $item->find('div[class="zg_usedPrice"] a')) { 
            $ret['offers_count'] = str_replace("&nbsp;", " ", $item->find('div[class="zg_usedPrice"] a', 0)->plaintext);
            $ret['offer_url'] = str_replace("&nbsp;", " ", $item->find('div[class="zg_usedPrice"] a', 0)->href);
          }             

          # offer price
          if ( $item->find('div[class="zg_usedPrice"] span[class="price"]')) {
            $ret['offer_price'] = $item->find('div[class="zg_usedPrice"] span[class="price"]', 0)->plaintext;
            $ret['offer_price'] = $this->getDigits($ret['offer_price']);
            $ret['offer_price'] = str_replace(",", ".", $ret['offer_price']);
          }     
          
          #rank  
          if ( $item->find('span[class="zg_rankNumber"]')) {
            $ret['rank'] = str_replace(".", "", $item->find('span[class="zg_rankNumber"]', 0)->plaintext);
          }     
          
          #image
          if ( $item->find('div[class="zg_itemImageImmersion"] img')) {
            $ret['image_url'] = $item->find('div[class="zg_itemImageImmersion"] img', 0)->src;
          }          
          
          #next page url
          if ( $dom->find('ol[class="zg_pagination"] a')) {
            $ret['page_url'] = $dom->find('ol[class="zg_pagination"] a', 0)->href;
          }           
          
          $rets[] = $ret;
        }
      }
      
      $dom->clear();
      unset($dom);
      
      return $rets;
  }  
  
  public function parseProductDimention(&$html) {
    list($value, $value_unit) = $this->parseProductDimention_1($html);
    if ( $value == "") {
      list($value, $value_unit) = $this->parseProductDimention_2($html);
    }
    return array($value, $value_unit); 
  }
  
  public function parseProductWeight(&$html) {
    list($value, $value_unit) = $this->parseProductWeight_1($html);
    if ( $value == "") {
      list($value, $value_unit) = $this->parseProductWeight_2($html);
    }
    return array($value, $value_unit);     
  }
  
  
  public function parseProductDimention_1(&$html) {
    //$html = str_get_html($html);

    $text = "";
    $dim = "";
    $dim_unit = "";
    
    if ($html->find('div[class="pdTab"] table tbody tr')) {

      foreach($html->find('div[class="pdTab"] table tbody tr') as $e) {
        
        if ( $e->find('td[class="label"]')) {
          if(trim($e->find('td[class="label"]', 0)->plaintext) == $this->_pattern_dimention_1) {
            $text = $e->find('td[class="value"]', 0)->plaintext;
          }          
        }
      }    
    }
    
    if ( $text != "") {
      if ( preg_match("/([0-9,\.x ]+)/i", $text, $matches)) {
        $dim = $matches[1]; 
      } 
      
      $dim_unit = str_replace($dim, "", $text);
    }    
    //$html->clear();
    //unset($html);    
    
    return array($dim, $dim_unit);
  }      
  
  public function parseProductWeight_1(&$html) {
    //$html = str_get_html($html);

    $weight = "";
    $weight_unit = "";
    $text = "";
    
    if ($html->find('div[class="pdTab"] table tbody tr')) {

      foreach($html->find('div[class="pdTab"] table tbody tr') as $e) {
        if ( $e->find('td[class="label"]')) {
          if(trim($e->find('td[class="label"]', 0)->plaintext) == $this->_pattern_weight_1) {
            $text = $e->find('td[class="value"]', 0)->plaintext;
          }
        }
      }    
    }
    //$html->clear();
    //unset($html);    
    
    //var_dump($text);
    
    if ( $text != "") {
      if ( preg_match("/([0-9,\.]+)/i", $text, $matches)) {
        $weight = $matches[1]; 
      } 
      
      $weight_unit = str_replace($weight, "", $text);
    }
    
    return array($weight, $weight_unit);
  }   
  
  public function parseProductDimention_2(&$html) {

    $value = "";
    $value_unit = "";
    $text = "";
    
    if ($html->find('table tbody tr td[class="bucket"]')) {

      foreach($html->find('table tbody tr td[class="bucket"]') as $e) {
        if ( !$e->find('h2', 0)) { 
          continue;
        }                                   
        
        if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) {
          continue;
        }
        
        if ($e->find('div[class="content"] ul li')) {
          foreach($e->find('div[class="content"] ul li') as $t)
          {
            if($t->find('b'))
            {
              if(trim($t->find('b', 0)->plaintext) == $this->_pattern_dimention_2)
              {
                $text = str_replace(trim($t->find('b', 0)->plaintext), '', trim($t->plaintext));
                break;
              } else {
                
              }
              
            }
          }  
        }
        
        if ( $text != "") {
          break;
        }  
      }    
    }
    
    if ( $text != "") {
      if ( preg_match("/([0-9,\.]+ x [0-9,\.]+ x [0-9,\.]+) ([A-Za-z]+)[ ;]*/i", $text, $matches)) {
        $value = $matches[1]; 
        $value_unit = $matches[2];
      } 
      
    }
    
    return array($value, $value_unit);
  }  

  public function parseProductWeight_2(&$html) {

    $value = "";
    $value_unit = "";
    $text = "";
    
    if ($html->find('table tbody tr td[class="bucket"]')) {

      foreach($html->find('table tbody tr td[class="bucket"]') as $e) {
        if ( !$e->find('h2', 0)) { 
          continue;
        }                                   
        
        if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) {
          continue;
        }
        
        if ($e->find('div[class="content"] ul li')) {
          foreach($e->find('div[class="content"] ul li') as $t)
          {
            if($t->find('b'))
            {
              if(trim($t->find('b', 0)->plaintext) == $this->_pattern_weight_2)
              {
                $text = str_replace(trim($t->find('b', 0)->plaintext), '', trim($t->plaintext));
                break;
              }
              
            }
          }  
        }
        
        if ( $text != "") {
          break;
        }  
      }    
    }
    
    if ( $text != "") {

      if ( preg_match("/[ ;]*([0-9,]+) ([A-Za-z]+)/i", $text, $matches)) {
        $value = $matches[1]; 
        $value_unit = $matches[2];
      } 
    } else {
      
      if ($html->find('table tbody tr td[class="bucket"]')) {

        foreach($html->find('table tbody tr td[class="bucket"]') as $e) {
          if ( !$e->find('h2', 0)) { 
            continue;
          }                                   
          
          if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) {
            continue;
          }
          
          if ($e->find('div[class="content"] ul li')) {
            foreach($e->find('div[class="content"] ul li') as $t)
            {
              if($t->find('b'))
              {
                if(trim($t->find('b', 0)->plaintext) == $this->_pattern_weight_1)
                {
                  $text = str_replace(trim($t->find('b', 0)->plaintext), '', trim($t->plaintext));
                  break;
                }
                
              }
            }  
          }
          
          if ( $text != "") {
            break;
          }  
        }    
      }
      
      if ( $text != "") {
        if ( preg_match("/([0-9,]+) ([A-Za-z]+)/i", $text, $matches)) {
          $value = $matches[1]; 
          $value_unit = $matches[2];
        } 
        
      }            
    }
    
    return array($value, $value_unit);
  }    
  
  public function parseProductPrime(&$html) {
    //$html = str_get_html($html);

    $ret = 0;
    
    if ( $html->find('span[id="actualPriceExtraMessaging"]', 0)) {
      $text = $html->find('span[id="actualPriceExtraMessaging"]', 0)->plaintext;
      if ( trim($text) != "") {
        $ret = 1;
      }
    }   
    
    return $ret;
            

  }  
  
  public function parseProductImportantInformation(&$html) {
    
    $ret = array();
    if ($html->find('div[class="bucket"]')) {

        foreach($html->find('div[class="bucket"]') as $e) {
          if ( !$e->find('h2', 0)) { 
            continue;
          }                                   
          
          if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_important_information) {
            continue;
          }
          
          $content = "";
          if ( $e->find('div[class="content"]')) {
            $content = $e->find('div[class="content"]',0)->innertext;  
          }
          
          if ( $content != "") {
            $content = str_replace("\n", "", $content);
            
            $list = explode("<br />", $content);
            
            foreach($list as $line) {
              if ( trim($line) != "") {
                
                if ( preg_match("/<b>([^<>]*)<\/b>/i", $line, $matches)) {
                  $key = $matches[1];
                } else {
                  $val = $line;
                  
                  $ret[$key] = $val;
                }
              }
            }
            

          }
        }    
      }
    
    return $ret;
        
  }
  
  public function parseProductUPC(&$html) {

    $upc = $this->parseProductUPC_1($html);
    if ( $upc == "") {
      $upc = $this->parseProductUPC_2($html);
    }
    return $upc;

  }     
  
  public function parseProductUPC_1(&$html) {
 
    $text = "";
    if ($html->find('table tbody tr td[class="bucket"]')) {

      foreach($html->find('table tbody tr td[class="bucket"]') as $e) {
        if ( !$e->find('h2', 0)) { 
          continue;
        }                                   
        
        if(trim($e->find('h2', 0)->plaintext) != $this->_pattern_detail) {
          continue;
        }
        
        if ($e->find('div[class="content"] ul li')) {
          foreach($e->find('div[class="content"] ul li') as $t)
          {
            if($t->find('b'))
            {
              if(trim($t->find('b', 0)->plaintext) == $this->_pattern_upc_2)
              {
                $text = str_replace(trim($t->find('b', 0)->plaintext), '', trim($t->plaintext));
                break;
              }
              
            }
          }  
        }
        
        if ( $text != "") {
          break;
        }  
      }    
    }
    
    return $text;
  }
  
  public function parseProductUPC_2(&$html) {
 
    $text = "";
    
    if ($html->find('div[class="pdTab"] table tbody tr')) {

      foreach($html->find('div[class="pdTab"] table tbody tr') as $e) {
        if ( $e->find('td[class="label"]')) {
          if(trim($e->find('td[class="label"]', 0)->plaintext) == $this->_pattern_upc_1) {
            $text = $e->find('td[class="value"]', 0)->plaintext;
          }
        }
      }    
    }
    
    return $text;
  
  }  
 
  
     
}
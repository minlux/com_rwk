<?php
class C_RwkWebQuery
{
   var $cvar_Cookie;


   // constructor
   public function __construct($var_Cookie)
   {
      //set cookie data
      $this->cvar_Cookie = $var_Cookie;

      /* This tells libxml2 not to send errors and warnings through to PHP. Then, to check for errors and handle them yourself.
         You can consult libxml_get_last_error() and/or libxml_get_errors() when you're ready. */
      libxml_use_internal_errors(true);
   }



   public function getHTMLString($var_Url, $var_Post = NULL, $var_DebugLevel = 0)
   {
      //usage of post
      $var_UsePOST = !empty($var_Post);

      //include html header in debug level 3
      $var_IncludeHeader = (($var_DebugLevel >= 3) ? 1 : 0);

      $var_Curl = curl_init();
      curl_setopt($var_Curl, CURLOPT_HEADER, $var_IncludeHeader); //include header or not
      curl_setopt($var_Curl, CURLOPT_POST, var_UsePOST); //use http post if var_Post is not empty
      if ($var_UsePOST)
      {
         curl_setopt($var_Curl, CURLOPT_POSTFIELDS, $var_Post);
      }
      curl_setopt($var_Curl, CURLOPT_RETURNTRANSFER, 1); //redirect output into string
      $var_Cookie = $this->cvar_Cookie;
      if ($var_Cookie) //set cookie data (if any)
      {
         curl_setopt ($var_Curl, CURLOPT_COOKIE, $var_Cookie);
      }

      //load page and store output into string
      curl_setopt($var_Curl, CURLOPT_URL, $var_Url);
      $var_Html = curl_exec($var_Curl);
      curl_close($var_Curl);


      //print url and cookie in debug level >=1
      if ($var_DebugLevel >= 1)
      {
         echo "\n\n<!-- DEBUG-LEVEL >= 1: Request (URL and Cookie) -->\n";
         echo "<!-- <![CDATA[\n";
         echo "Url: ".$var_Url."\n";
         echo "Cookie: ".$var_Cookie."\n";
         echo "]] -->\n\n";
      }
      //echo html pate in debug level >=2
      if ($var_DebugLevel >= 2)
      {
         echo "<!-- DEBUG-LEVEL >= 3: Response (Header and HTML) -->\n";
         echo "<!-- DEBUG-LEVEL >= 2: Response (only HTML) -->\n";
         echo "<!-- <![CDATA[\n";
         echo $var_Html;
         echo "\n]] -->\n\n";
      }

      //return received html
      return $var_Html;
   }
}
?>

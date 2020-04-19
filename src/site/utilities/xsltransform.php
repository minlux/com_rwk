<?php
class C_XslTransform
{
   var $cvar_XsltProc;
   var $cvar_XslDom;


   // constructor
   public function __construct()
   {
      $this->cvar_XsltProc = new XsltProcessor(); //create xslt processor
      $this->cvar_XslDom = new DomDocument();  //create dom doc (used to load XSL file)
   }


   public function returnString($var_String) //just a test function
   {
      return $var_String;
   }


   public function transformToDOC($var_DOMDocument, $var_XslFile)
   {
      $this->cvar_XslDom->load($var_XslFile);    //load the XSL stylesheet
      $this->cvar_XsltProc->importStylesheet($this->cvar_XslDom);  //import the XSL styelsheet into the XSLT process

      //do transformation
      $var_NewDomDOC = $this->cvar_XsltProc->transformToDoc($var_DOMDocument);
      $var_NewDomDOC->formatOutput = true;
      return $var_NewDomDOC;
   }


   public function transformToXML($var_DOMDocument, $var_XslFile)
   {
      $var_NewDomDOC = $this->transformToDOC($var_DOMDocument, $var_XslFile);
      return $var_NewDomDOC->saveXML();
   }
}
?>

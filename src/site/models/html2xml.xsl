<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" encoding="UTF-8" indent="yes"/>

   <!-- overwrite build-in template -->
   <xsl:template match="text()">
   </xsl:template>


   <!--   **************************************** RWK ***************************************-->
   <xsl:template match="/html">
      <xsl:element name="rwk">
         <xsl:apply-templates select="/descendant::td[@bgcolor='#345331'][1]" mode="INFO"/>
      </xsl:element>
   </xsl:template>



   <!--   *************************************** INFO ***************************************-->
   <xsl:template match="td[@bgcolor='#345331']" mode="INFO">
      <xsl:apply-templates select="descendant::table[not(descendant::table)]" mode="INFO" />
   </xsl:template>


   <xsl:template match="table[not(descendant::table)]" mode="INFO">
<!--
      <xsl:variable name="var_Disciplin"><xsl:value-of select="descendant::td[2]" /></xsl:variable>
      <xsl:variable name="var_Name"><xsl:value-of select="substring-before($var_Disciplin, ' (')" /></xsl:variable>
      <xsl:variable name="var_Id"><xsl:value-of select="substring-after($var_Disciplin, ' (')" /></xsl:variable>

      <xsl:element name="disziplin">
         <xsl:attribute name="name"><xsl:value-of select="$var_Name" /></xsl:attribute>
         <xsl:attribute name="id"><xsl:value-of select="substring-before($var_Id, ')')" /></xsl:attribute>

         <xsl:element name="klasse">
            <xsl:attribute name="name"><xsl:value-of select="descendant::td[4]" /></xsl:attribute> -->

            <xsl:element name="wettkampf">
               <xsl:variable name="var_Durchgang"><xsl:value-of select="substring-before(descendant::td[6], ' vom')" /></xsl:variable>
               <xsl:attribute name="durchgang"><xsl:value-of select="substring-after($var_Durchgang, 'Nr. ')" /></xsl:attribute>
               <xsl:attribute name="datum"><xsl:value-of select="substring-after(descendant::td[6], 'vom ')" /></xsl:attribute>

               <xsl:apply-templates select="/descendant::td[@bgcolor='#345331'][2]" mode="MANNSCHAFT">
                  <xsl:with-param name="venue">Heim</xsl:with-param>
               </xsl:apply-templates>
               <xsl:apply-templates select="/descendant::td[@bgcolor='#345331'][3]" mode="MANNSCHAFT">
                  <xsl:with-param name="venue">Gast</xsl:with-param>
               </xsl:apply-templates>

            </xsl:element>
<!--
         </xsl:element>

      </xsl:element> -->
   </xsl:template>




   <!--   ************************************ MANNSCHAFTT ***********************************-->
   <xsl:template match="td[@bgcolor='#345331']" mode="MANNSCHAFT">
      <xsl:param name="venue" />
      <xsl:apply-templates select="descendant::td[@bgcolor='#BFCABF'][1]" mode="MANNSCHAFT">
         <xsl:with-param name="venue" select="$venue" />
      </xsl:apply-templates>
   </xsl:template>


   <xsl:template match="td[@bgcolor='#BFCABF']" mode="MANNSCHAFT">
      <xsl:param name="venue" />
      <xsl:apply-templates select="table" mode="MANNSCHAFT">
         <xsl:with-param name="venue" select="$venue" />
      </xsl:apply-templates>
   </xsl:template>


   <xsl:template match="td[@bgcolor='#BFCABF']/table" mode="MANNSCHAFT">
      <xsl:param name="venue" />
      <xsl:element name="mannschaft">
         <xsl:variable name="var_Verein"><xsl:value-of select="tr[1]/td[1]/b[2]" /></xsl:variable>
         <xsl:variable name="var_Name"><xsl:value-of select="substring-before($var_Verein, '&#xA0; -&#xA0; ')" /></xsl:variable>
         <xsl:variable name="var_Nr"><xsl:value-of select="substring-after($var_Verein, 'VereinsNr.: ')" /></xsl:variable>

         <xsl:attribute name="venue"><xsl:value-of select="$venue" /></xsl:attribute>
         <xsl:attribute name="verein"><xsl:value-of select="$var_Nr" /></xsl:attribute>
         <xsl:attribute name="name"><xsl:value-of select="$var_Name" /></xsl:attribute>


         <xsl:apply-templates select="tr[position() &gt; 3 and position() &lt; 11]" mode="SCHUTZE" />
         <xsl:apply-templates select="tr[position() &gt; 11 and position() &lt; 17]" mode="ERSATZ" />
         <xsl:apply-templates select="tr[11]" mode="TOTAL" />

         <xsl:apply-templates select="descendant::table[not(descendant::table)]" mode="BEMERKUNG" />
      </xsl:element>
   </xsl:template>




   <!--   ************************************** SCHUTZE *************************************-->
   <xsl:template match="tr" mode="SCHUTZE">
      <xsl:variable name="var_Ringe"><xsl:value-of select="td[3]" /></xsl:variable>

      <xsl:if test="$var_Ringe != ''">
         <xsl:if test="$var_Ringe > 0">
            <xsl:element name="schutze">
               <xsl:attribute name="nummer"><xsl:value-of select="substring(td[1], 5)" /></xsl:attribute>
               <xsl:attribute name="tag"><xsl:value-of select="substring(td[1], 2, 1)" /></xsl:attribute>
               <xsl:element name="name"><xsl:value-of select="td[2]" /></xsl:element>
               <xsl:element name="ringe"><xsl:value-of select="td[3]" /></xsl:element>
            </xsl:element>
         </xsl:if>
      </xsl:if>
   </xsl:template>


   <xsl:template match="tr" mode="ERSATZ">
      <xsl:variable name="var_Ringe"><xsl:value-of select="td[3]" /></xsl:variable>

      <xsl:if test="$var_Ringe != ''">
         <xsl:if test="$var_Ringe > 0">
            <xsl:element name="ersatz">
               <xsl:attribute name="nummer"><xsl:value-of select="substring(td[1], 5)" /></xsl:attribute>
               <xsl:attribute name="tag"><xsl:value-of select="substring(td[1], 2, 1)" /></xsl:attribute>
               <xsl:element name="name"><xsl:value-of select="td[2]" /></xsl:element>
               <xsl:element name="ringe"><xsl:value-of select="td[3]" /></xsl:element>
            </xsl:element>
         </xsl:if>
      </xsl:if>
   </xsl:template>


   <xsl:template match="tr" mode="TOTAL">
      <xsl:element name="total">
         <xsl:value-of select="descendant::font[last()]" />
      </xsl:element>
   </xsl:template>




   <!--   ************************************* BEMERKUNG ***********************************-->
   <xsl:template match="table[not(descendant::table)]" mode="BEMERKUNG">
      <xsl:element name="bemerkung">
         <xsl:value-of select="descendant::font[2]" />
      </xsl:element>
   </xsl:template>



</xsl:stylesheet>


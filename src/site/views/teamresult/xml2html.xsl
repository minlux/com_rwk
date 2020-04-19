<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes"
    doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" />


   <!-- overwrite build-in template -->
   <xsl:template match="text()">
   </xsl:template>


   <!--   **************************************** RWK ***************************************-->
   <xsl:template match="/rwk">
<!--
      <xsl:element name="div">
         <xsl:attribute name="id">rwkteamresult</xsl:attribute>

         <xsl:element name="div">
            <xsl:attribute name="class">brief</xsl:attribute>

            <xsl:element name="table">
               <xsl:attribute name="class">tabelle</xsl:attribute>
               <xsl:element name="tr">
                  <xsl:element name="th">Heim-Mannschaft</xsl:element>
                  <xsl:element name="th">Ringe</xsl:element>
                  <xsl:element name="th">Ringe</xsl:element>
                  <xsl:element name="th">Gast-Mannschaft</xsl:element>
               </xsl:element>
               <xsl:for-each select="wettkampf">
                  <xsl:element name="tr">
                     <xsl:element name="td"><xsl:value-of select="mannschaft[1]/@name" /></xsl:element>
                     <xsl:element name="td"><xsl:value-of select="mannschaft[1]/total" /></xsl:element>
                     <xsl:element name="td"><xsl:value-of select="mannschaft[2]/total" /></xsl:element>
                     <xsl:element name="td"><xsl:value-of select="mannschaft[2]/@name" /></xsl:element>
                  </xsl:element>
               </xsl:for-each>
            </xsl:element>
         </xsl:element>

      </xsl:element>
-->
      <xsl:apply-templates />
   </xsl:template>


   <!--   ************************************* WETTKAMPF ************************************-->
   <xsl:template match="wettkampf">
      <xsl:element name="div">
         <xsl:attribute name="class">wettkampf</xsl:attribute>
         <p>Durchgang: <xsl:value-of select="@durchgang" />, <xsl:value-of select="@datum" /></p>
         <xsl:apply-templates />
      </xsl:element>
   </xsl:template>


   <!--   ************************************ MANNSCHAFT ************************************-->
   <xsl:template match="mannschaft">
      <xsl:element name="div">
         <xsl:attribute name="class">mannschaft</xsl:attribute>

         <h3><xsl:value-of select="@name" /></h3>
         <h4><xsl:value-of select="@venue" />-Mannschaft</h4>

         <table>
            <tr>
               <th>Name, Vorname</th>
               <th>Ringe</th>
            </tr>
            <xsl:apply-templates select="schutze"/>
            <xsl:apply-templates select="ersatz"/>
            <xsl:apply-templates select="total"/>
         </table>
      </xsl:element>
   </xsl:template>



   <!--   ************************************ SCHUTZE ***************************************-->
   <xsl:template match="schutze">
      <tr>
         <td><xsl:value-of select="name" /></td>
         <td><xsl:value-of select="ringe" /></td>
      </tr>
   </xsl:template>


   <!--   ************************************ ERSATZ ****************************************-->
   <xsl:template match="ersatz">
      <tr class="ersatz">
         <td><xsl:value-of select="name" /></td>
         <td><xsl:value-of select="ringe" /></td>
      </tr>
   </xsl:template>


   <!--   ************************************ TOTAL ****************************************-->
   <xsl:template match="total">
      <tr class="total">
         <td>Gesamt</td>
         <td><xsl:value-of select="." /></td>
      </tr>
   </xsl:template>


</xsl:stylesheet>


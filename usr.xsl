<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" >

<!-- ///////////////////////////////////////////////////// //-->

  <xsl:variable name="usr" select="/r/i[./ca='usr']" />

  <xsl:template name="ca_usr" match="i[./ca='usr']" >
	<xsl:choose>
		<xsl:when test="./xl" >
			<aside class="usr" >
				<b><xsl:value-of select="./xl" /></b> <br/>
				<small><xsl:value-of select="./fio" /></small> <br/>
				<a href="{concat($link,$vrs/url,'/logout')}" >выход</a>
			</aside>
		</xsl:when>
		<xsl:otherwise>
			<form action="" method="post" >
				<input type="hidden" name="login" value="1" />
				<div class="frm ln" ><input type="text" name="xl" placeholder="login" /></div>
				<div class="frm ln" ><input type="password" name="xp" placeholder="password" /></div>
				<div class="frm sbmt" ><input type="submit" class="sbmt" /></div>
				<div class="cl" >&#160;</div>
			</form>
		</xsl:otherwise>
	</xsl:choose>
  </xsl:template>

</xsl:stylesheet>
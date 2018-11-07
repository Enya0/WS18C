<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
<html>
	<body>
		<table border="2" align="center">
			<tr>
				<th>Autor</th>
				<th>Enunciado</th>
				<th>Respuesta correcta</th>
				<th>Respuestas incorrectas</th>
				<th>Tema</th>
			</tr>
			<xsl:for-each select="assessmentItems/assessmentItem">
			  <!-- Content -->
			  	<tr>
				  	<td><xsl:value-of select="@author"/></td>
					<td><xsl:value-of select="itemBody/p"/></td>
					<td><xsl:value-of select="correctResponse/value"/></td>
					<td>
						<ul>
						<xsl:for-each select="incorrectResponses/value">
							<li><xsl:value-of select="text()"/></li>
						</xsl:for-each>
						</ul>
					</td>
					<td><xsl:value-of select="@subject"/></td>
				</tr>
			</xsl:for-each>
		</table>
	</body>
</html>
</xsl:template>
</xsl:stylesheet>
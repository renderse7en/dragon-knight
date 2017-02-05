<?php
$template = <<<THEVERYENDOFYOU
<table width="100%">
<tr><td class="title"><img src="images/town_{{id}}.gif" alt="Welcome to {{name}}" title="Welcome to {{name}}" /></td></tr>
<tr><td>
<b>Town Options:</b><br />
<ul>
<li /><a href="index.php?do=inn">Rest at the Inn</a>
<li /><a href="index.php?do=buy">Buy Weapons/Armor</a>
<li /><a href="index.php?do=maps">Buy Maps</a>
</ul>
</td></tr>
<tr><td><center>
<table width="95%">
<tr><td class="title">Latest News</td></tr>
<tr><td>{{news}}</td></tr>
</table>
<br />
<table width="95%">
<tr><td class="title" width="50%">Who's Online</td><td class="title">Babble Box</td></tr>
<tr><td>{{whosonline}}<br /><br /></td>
<td><iframe src="index.php?do=babblebox" name="sbox" width="100%" height="250" frameborder="0" id="bbox">Your browser does not support inline frames! The Babble Box will not be available until you upgrade to a newer <a href="http://www.mozilla.org" target="_new">browser</a>.</iframe></td></tr>
</table>
</td></tr>
</table>
THEVERYENDOFYOU;
?>
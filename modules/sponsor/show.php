<?php

if (!$cfg["sponsor_picwidth"]) $cfg["sponsor_picwidth"] = 400;

$dsp->NewContent($lang["sponsor"]["caption"], $lang["sponsor"]["sub_caption"]);
$sponsoren = $db->query("SELECT * FROM {$config['tables']['sponsor']} WHERE sponsor ORDER BY pos, sponsorid");

$out = "<table>";
while ($sponsor = $db->fetch_array($sponsoren)){
	$templ['sponsor']['row']['col1'] = "";
	// If entry is HTML-Code
	if (substr($sponsor["pic_path"], 0, 12) == 'html-code://') {
		$templ['sponsor']['row']['col1'] = substr($sponsor["pic_path"], 12, strlen($sponsor["pic_path"]) - 12);

	// Else add Image-Tag
	} else if ($sponsor["pic_path"] != "" and $sponsor["pic_path"] != "http://") {
		$ImgSize = @GetImageSize($sponsor["pic_path"]);
		if (!$ImgSize[0]) $ImgSize[0] = 200;
		if ($ImgSize[0] > $cfg["sponsor_picwidth"]) $ImgSize[0] = $cfg["sponsor_picwidth"];
		$templ['sponsor']['row']['col1'] = "<img src=\"{$sponsor["pic_path"]}\" width=\"{$ImgSize[0]}\" border=\"0\" title=\"{$sponsor["name"]}\">";
		if ($sponsor["url"] != "" and $sponsor["url"] != "http://")
			$templ['sponsor']['row']['col1'] = "<a href=\"index.php?mod=sponsor&action=bannerclick&design=base&sponsorid={$sponsor["sponsorid"]}\" target=\"_blank\">". $templ['sponsor']['row']['col1'] ."</a>";
	}

	$templ['sponsor']['row']['col2'] = $sponsor["name"];
	if ($sponsor["url"] != "" && $sponsor["url"] != "http://")
		$templ['sponsor']['row']['col2'] = "<a href=\"index.php?mod=sponsor&action=bannerclick&design=base&sponsorid={$sponsor["sponsorid"]}\" target=\"_blank\">". $templ['sponsor']['row']['col2'] ."</a>";
	$templ['sponsor']['row']['col2'] .= HTML_NEWLINE. $func->db2text2html($sponsor["text"]);

	$out .= $dsp->FetchModTpl("sponsor", "liste");
}
$db->free_result($sponsoren);
$out .= "</table>";

$dsp->AddSingleRow($out);
$dsp->AddBackButton("index.php?mod=sponsor", "sponsor/show");
$dsp->AddContent();

$sponsoren = $db->query("UPDATE {$config['tables']['sponsor']} SET views=views+1");
?>
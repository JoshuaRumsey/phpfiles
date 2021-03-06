<?php
	if (isset($_POST["search_url"])) {
		$curl_Session = curl_init(); 
		$search_file = $_POST["search_url"]; //arbitrary name of page to search
	
		//open php curl to search contents of external page
		curl_setopt($curl_Session,CURLOPT_URL,$search_file);
		curl_setopt($curl_Session,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl_Session,CURLOPT_HEADER, false); 
		$contents=curl_exec($curl_Session);
		$contents = addslashes($contents); //add slashes for js
		$contents = str_replace(array("\r", "\n"), '', $contents); //remove line breaks for js
		$contents = preg_replace('/[^a-z\d ]/i', ' ', $contents);
	
		curl_close($curl_Session);
		
		//use below code instead if file is local
		//$my_file = "file_to_search.txt";
		//$file = fopen($my_file,"r");
		//$contents = addslashes($contents);
		//$contents = fread($file,filesize($my_file));
		//fclose($file);
	}
	else $contents = 0;
	//echo $contents;
?>

<!DOCTYPE html>
<html lang="en-US"> 
<head>
<style>
body {
	font-family: arial;
	background-color: #ffff00;
}
</style>

 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script>

var mytext = '<?php echo $contents; ?>';
if (mytext != 0) {
	var word_arr = [];
	var unique_arr = [];
	var word_count_arr = [];
	var current_key;
	var display_html = "";
	var word_row = [];
	var row_append;
	var i;
	var j;
	var k;
	
	//wash the data and create main word array
	mytext = mytext.replace(/\./g, ' ');
	mytext = mytext.replace(/\s+/g, '+');
	mytext  = mytext.replace(/[^a-zA-Z0-9+]/g, "");
	word_arr = mytext.split("+");
	var new_word;
	var old_word;
	
	for (var m = 0; m < word_arr.length; m++) {
		word_arr[m] = word_arr[m].toLowerCase();		
	}
	word_arr.sort();
	var max_find = 0;

	for (i = 0; i < word_arr.length; i++) {
		if (word_arr[i].length > 3) {
			new_word = word_arr[i];
			current_key = unique_arr.indexOf(new_word);
			if (current_key == -1)  {
				unique_arr.push(word_arr[i]);
				word_count_arr.push("0");				
			}
			else { //increment existing node
				word_count_arr[unique_arr.length]++;
				if (max_find < word_count_arr[unique_arr.length]) max_find = word_count_arr[unique_arr.length];
			}
			current_key = '';
		}
	}
	var font_scale = 10 / max_find; //calculate scale
	var this_font_size;
	new_word = '';

	for (j = 0; j < unique_arr.length; j++) { //create combined array with word and count
		new_word = unique_arr[j] + "|" + word_count_arr[j+1];
		new_word = new_word.toLowerCase();
		unique_arr[j] = new_word.trim();	
	}
	unique_arr.sort();
}
</script>
</head>

<body>

<!--form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'-->
<form action='site_pen_challenge1.php' method='post'>
<input type='text' size='50' name='search_url'>
<input type='submit' value='Submit'>
</form><br><br>

<table width=50%>
	<tr>
		<td>
			<div id="show_word_list"></div>
		</td>
	</tr>
</table>

<script>
if (mytext != 0) {
	for (k = 0; k < unique_arr.length; k++) {
		word_row = unique_arr[k].split("|");
		this_font_size = Math.round(word_row[1] * font_scale); //calc font size based on scale
		if ((k + 1) % 10 == 0) row_append = "<br>";
		else if (k != (unique_arr.length - 1)) row_append = "&nbsp;&nbsp;&nbsp;";
		else row_append = "";
		//display_html += "<font size=+"+this_font_size+">"+word_row[0]+"</font>"+row_append;	
		display_html += "<font size=+"+this_font_size+">"+word_row[0]+"</font>"+row_append;
	}
}
if (display_html.length > 0) document.getElementById("show_word_list").innerHTML = display_html;	


</script>

</body>
</html>

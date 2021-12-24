<html>
<header>
<title>Demo page</title>
<style>.redtext {color:red;font-size:120%;float:left;}</style>
<script>
alert('hello');
function change_color(event) {
//$('.redtext').css('color','blue');
var el= document.getElementsByClassName("redtext");
//el.setAttribute('style', 'color: blue');
document.getElementById("redtext").style.color = "blue";
	
	
}
</script>
</header>
<body>


sjgjgsjelkglewjgljewgjewlgjewlgß <span id="redtext" class="redtext">some other text</span>
<div style="clear:both">same other </div>

<form method="post">
<input type="radio" name="class" value="1">
<input type="radio" name="class" value="0">
<input type="submit" value="submit" name="submit">
</form>
<button onclick="change_color()"> Change color </button>

<?php 
if (isset($_REQUEST['submit'])) {
print_r($_REQUEST);
}
?>

</body>
</html>
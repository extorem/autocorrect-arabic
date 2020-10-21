<?php 

$hn = 'localhost';
$db ='autocorrection_arabic';
$un = 'root';
$pw = '';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

$ssql='SET CHARACTER SET utf8';
mysqli_query($conn,$ssql);
?>
<script>
function getOutput(name) {
  getRequest(
      'suggesting.php?name='+name, // URL for the PHP file
       drawOutput,  // handle successful request
       drawError    // handle error
  );
  return false;
}  
// handles drawing an error message
function drawError() {
   console.log('error in function');
}
// handles the response, adds the html
function drawOutput(responseText) {
	add_to_select(responseText);	
}
// helper function for cross-browser request object
function getRequest(url, success, error) {
    var req = false;
    try{
        // most browsers
        req = new XMLHttpRequest();
    } catch (e){
        // IE
        try{
            req = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            // try an older version
            try{
                req = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                return false;
            }
        }
    }
    if (!req) return false;
    if (typeof success != 'function') success = function () {};
    if (typeof error!= 'function') error = function () {};
    req.onreadystatechange = function(){
        if(req.readyState == 4) {
            return req.status === 200 ? 
                success(req.responseText) : error(req.status);
        }
    }
    req.open("GET", url, true);
    req.send(null);
    return req;
}

</script>
<script language="javascript">
function updateValue(value)
{
	var vv=document.getElementById('word').value;
	tvv=vv.split(" ");
	var ff="";
	var last="";
	for (var j=0; j < (tvv.length - 1);++j)
	{
	   ff = ff + tvv[j]+" ";
	}
    document.getElementById('word').value = ff+" "+value;
	document.getElementById('word').focus();
	document.getElementById("mySelect").style.display = "none";

}

function add_to_select(res)
{
var str='';
var word=document.getElementById("word").value
var ii=0;
var tt=res.split(";");
for (var i=0; i < tt.length;++i){
	var res = tt[i].split(",");
	for (var j=0; j < res.length;++j)
	{
		if(res[j]!=''){
		ii = ii +1;
		str += '<option value="'+res[j]+'" >'+res[j]+'</option>';
		}
	}
}
document.getElementById("mySelect").innerHTML = str;
if(ii != 0)
{
	document.getElementById("mySelect").style.display = "block";
	var f="\""+ii+"\"";
	document.getElementById("mySelect").size = "6";
}
else
	document.getElementById("mySelect").style.display = "none";

console.log(str)
}
</script>

<form action="#" method="post" id="ff" autocomplete="off">
<div align="center">

<input id="word" style="width:150px;center;text-align:right;" onkeyup="return getOutput(this.value);" name="name"  >


<br>
<select id="mySelect" style="display:none; width:150px;align:center;text-align:right;" size="1" onchange="updateValue(this.value)">
</select>
<br>
<input type="submit" onclick="alert(ff.word.value);" name="submit" value="submit">
</div>
</form>


<?php
$hn = 'localhost';
$db ='autocorrection_arabic';
$un = 'root';
$pw = '';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);

$ssql='SET CHARACTER SET utf8';
mysqli_query($conn,$ssql);

if(isset($_GET['name']))
{
	$text=$_GET['name'];
	$tvv=explode(" ",$text);
	$ff="";
	$last="";
	foreach($tvv as $dd)
	{
	   if($dd!=" ")
		   if($dd!="")
			   $last=$dd;
	}
	$text=$last;
	
function arquery($text)
{
    $replace = array(
    "أ",
    "ا",
    "إ",
    "آ",
    "ي",
    "ى",
    "ه",
    "ة",
    ); 
    $with = array("(أ|ا|آ|إ)",
    "(أ|ا|آ|إ)",
    "(أ|ا|آ|إ)",
    "(أ|ا|آ|إ)",
    "(ي|ى)",
    "(ي|ى)",
    "(ه|ة)",
    "(ه|ة)",
    );
    $new = array_combine($replace,$with);
    $return = "" ;
    $len = strlen(utf8_decode($text));
    for($i=0;$i<$len;$i++)
	{
        $current = mb_substr($text,$i,1,'utf-8');
        if(isset($new[$current]))
            $return.=$new[$current];
        else
            $return.=$current;
    }
    return $return;
}
function get_number($one,$conn)
{
	    $count=1;
		$len=strlen($one);
		for($i=0;$i < $len;$i+=2)
		{
			$c = substr($one, $i,2);
			
			$condition1="select * from arabic_letter where letter like '$c'";
			$ress1=mysqli_query($conn,$condition1);
			$pp=0;
		    while($line1=mysqli_fetch_array($ress1,MYSQLI_ASSOC))
	        {
				$pp=$line1['pp'];
				$count *=$pp;
	        }	
		}
		return $count;
}
function get_list_character($count,$conn)
{
	$list_character=array();
	$list_character_number=array();
	$ss="select letter,pp from arabic_letter ";
	$res=mysqli_query($conn,$ss);
	$i=0;
	while($line1=mysqli_fetch_array($res,MYSQLI_ASSOC))
	{
		$pp=$line1['pp'];
		$number=0;
		while($count%$pp==0)
		{
			$number ++;
			array_push($list_character,$line1['letter']);
			if($number==1)
				array_push($list_character_number,$number);
			else
				$list_character_number[$i]=$number;
			$count =$count/$pp;
		}
		if($count==1)
			break;
		if($number!=0)
			$i ++;
			
	}
	return $list_character;
}
$count=get_number($text,$conn);
$list_character=get_list_character($count,$conn);
//print_r($list_character);
$sugg="select * from word_all where three = $count ";
//echo "<br>";
//$st_len=strlen($text);
$min=$count - 100000;
$max=$count + 100000;
$len=strlen($text);
if($len <=2)
{
	$min=$count - 1000;
    $max=$count + 1000;
}
if($len <=4 && $len>2)
{
	$min=$count - 100000;
    $max=$count + 100000;
}
if($len>4)
{
	$min=$count - 1000000;
    $max=$count + 1000000;
}

$sugg2="select three,one from word_all where three > $min and three < $max ";
$option1="";$pou1=0;	
$option2="";$pou2=0;
$option3="";$pou3=0;
$option4="";$pou4=0;
$option5="";$pou5=0;
$option6="";$pou6=0;
$sugg_2=mysqli_query($conn,$sugg2);
			
		    while($line1_1=mysqli_fetch_array($sugg_2,MYSQLI_ASSOC))
	        {
				$count_db=$line1_1['three'];
				
				$list_character_db=get_list_character($count_db,$conn);
				$nb_character=count($list_character_db);
				//echo $nb_character;
				$nb_character_exist=0;
				foreach($list_character_db as $character)
				{
					if(in_array($character,$list_character))
					{
						$nb_character_exist ++;
					}
				}
				if($nb_character_exist !=0 && $nb_character!=0)
				{
					$pou=$nb_character_exist * 100 /$nb_character;
					if($pou1<$pou)
					{
						$pou1=$pou;
						$option1=$line1_1['one'];
					}
					else
						if($pou2<$pou)
						{
							$pou2=$pou;
							$option2=$line1_1['one'];
						}
						else
							if($pou3<$pou)
							{
								$pou3=$pou;
								$option3=$line1_1['one'];
							}
							else
							if($pou4<$pou)
							{
								$pou4=$pou;
								$option4=$line1_1['one'];
							}
							else
							if($pou5<$pou)
							{
								$pou5=$pou;
								$option5=$line1_1['one'];
							}
							else
							if($pou6<$pou)
							{
								$pou6=$pou;
								$option6=$line1_1['one'];
							}
				}
	        }
			echo $option1.";";
			echo $option2.";";
			echo $option3.";";
			echo $option4.";";
			echo $option5.";";
			echo $option6.";";
}?>
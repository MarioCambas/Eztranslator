# Eztranslator
Translate your web pages easily
# HOW TO CONFIGURE:
Create a txt file for each language you want to use and save it into languages folder under filename 'code for language.txt' using lowercase an the code for language thats it 'es' for spanish, 'en' for english, fr for french, and so on. Make a web search for languages codes...
- The name of the file will be used to show the flag image related to the language
- The fisrt line of the txt file define the language
- The second line define the langauage name
- Use a single line for each text to translate
- You can include html tags in the file
for example:
```
0 en
1 english
2 <span style="font-size:20px;color:red;" trans="15">This is a code expample used in this demo</span>
3 ...
4 ...
```
Once you done crating those languages files run the php file class_traslationfilestojs.php located in php folder.
This class will process all language files included in languages folder and will create the language.js file that have to be include in your html file.
```php
<?php header("Access-Control-Allow-Origin: *");
class C_writetranslationfilestojs{
    var $translationFile;
    var $varname='';

    function __construct()
    {
        $translatefn='
		function setCookie(cname, cvalue, exdays) {
		  var d = new Date();
		  //(exdays * 24 * 60 * 60 * 1000)
		  document.cookie = cname + "=" + cvalue + ";expires=Fri, 09 Aug 2013 04:35:50 GMT;path=/";
		  d.setTime(d.getTime() + exdays);
		  var expires = "expires="+d.toUTCString();
		  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}
        function translate(alang){
            setCookie("lang", alang, (365 * 24 * 60 * 60 * 1000));
            lang=alang;
            translatables =$("[trans]");
            if (translatables.length>0){
                for (i=0;i<translatables.length;i++){
                    if($(translatables[i]).attr("placeholder")){
                        $(translatables[i]).attr("placeholder",window[alang][$(translatables[i]).attr( "trans" )]);
                    }else if($(translatables[i]).attr("data-toggle")=="tooltip"){
                        $(translatables[i]).attr("title",window[alang][$(translatables[i]).attr( "trans" )]);
                        $(translatables[i]).attr("data-original-title",window[alang][$(translatables[i]).attr( "trans" )]);
                        if ($(translatables[i]).hasClass("fa-ban")){
                            $(translatables[i]).attr("title",window[alang][$(translatables[i]).attr( "trans" )]);
                            $(translatables[i]).attr("data-original-title",window[alang][$(translatables[i]).attr( "trans" )]);
                        }
                    }else if ($(translatables[i]).attr("data-toggle")!="tooltip"){$(translatables[i]).html(window[alang][$(translatables[i]).attr( "trans" )]);}
                }
            }
            $(\'.selectlang\').val(lang);
            $(\'.selectlang\').selectpicker(\'render\');
            $(\'[data-toggle="tooltip"]\').tooltip();
        }';
        $localPath='./languages/';
        $searchString= "$localPath"."*.txt";
        $compile='';
        $lang=array();
        foreach (glob($searchString) as $flie_name){

            $Languagefile=file(utf8_decode($flie_name), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $jscontext='';
            array_push($lang,array($Languagefile[0],$Languagefile[1]));
            foreach($Languagefile as $key => $value)
            {
                $jscontext.="'".str_replace("'","\'",$value)."',";
            }
            $jscontext=substr($jscontext,0,strlen($jscontext)-1);
            $this->varname=str_replace($localPath,"",str_replace(".txt","",$flie_name));
            echo "archivo: ".$Languagefile[0].".txt (".$Languagefile[1].") procesado <br>";
            $jscontext=$this->varname."=[".$jscontext."]";
            $compile.=$jscontext.";\n";
        }
            $selectlanguages='<a><i class="fas fa-language"></i>&nbsp;&nbsp;&nbsp;<span trans="none"><SELECT name="lang"  id="selectlang" onchange="trans(this.value)" changed.bs.select="trans(this.value)" style="background-color:#ecf0f5;text-transform:capitalize;" data-width="fit" class="selectlang"></a>';
            for ($i=0;$i<count($lang);$i++)
            {
                $selectlanguages.='<option data-thumbnail="./langImages/'.$lang[$i][0].'.png" value="'.$lang[$i][0].'" style="text-transform:capitalize;">'.$lang[$i][1].'</option>';
            }
            $selectlanguages.='</SELECT></span></a>';
            $image_size="24px";
            $flags='<div style="margin: auto;">';
            for ($i=0;$i<count($lang);$i++)
    		{
    		    $flags.= '<img type="image" src="./langImages/'.trim($lang[$i][0]).'.png" width="'.$image_size.'" height="'.$image_size.'" id="lang_'.$i.'" name="'.trim($lang[$i][0]).'" style="cursor:pointer;" onclick="trans('."\'".$lang[$i][0]."\'".')" title= "'.$lang[$i][1].'" alt= "'.$lang[$i][1].'"/>';
    		}
            $flags.= '</div>';
            $compile.="flagsSelector='$flags';\n\nlangDownLst='$selectlanguages';\n\n $translatefn";
            $this->translationFile=$localPath."languages.js";

            if (!$handle = fopen($this->translationFile, 'w'))
            {
                echo "Can not crate (".$this->translationFile.")...";
                exit;
            }
            if (fwrite($handle, $compile) === FALSE) {
                echo "Can not write (".$this->translationFile.")";
                exit;
            }
            fclose($handle);
    }
}
$writeConfig = new  C_writetranslationfilestojs();
?>
```
Javascript: Include the following code in the head of your html file
```javascript
    <head>
    ...
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/bootstrap.css">
  <link rel="stylesheet" href="./css/bootstrap-select.css">
  <!-- jQuery 2.2.3 -->
	<script src="./js/jquery-2.2.3.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="./js/bootstrap-3.1.1.min.js"></script>
  <script src="./js/bootstrap-select.js"></script>
  <script type="text/javascript" src="./languages/languages.js"></script>
  <style>
	.selectlang{ display:none; /* Prevent FOUC */}
  </style>
    ...
    </head>
```
Javascript: At the end of the html file add the folowing code...
```javascript
<script>
 $(document).ready(function () {
	$('.flagslist').html(flagsSelector);
	$('.flags').html(langDownLst);
    lang = (navigator.language || navigator.userLanguage).substr(0,2);
    cookies=(document.cookie).split(';');
    for(i=0;i<cookies.length;i++)
    {
       acookie=cookies[i].split("=");
       if (((acookie[0]).trim()).trim()=='lang'){
           if (acookie[1]!='undefined'){
                lang=acookie[1];
           }
       }
    }
	translate(lang);
});

	function trans(alang){
		translate(alang);
	}
</script>
```
# HOW TO USE:
Simply add the attr trans to every text, component, html tags you want to translate.
Syntax: trans="index" index = line number in translation file subtratting 1; example: line number 10 index =10-1=9
HTML souce code examples
```html
Input with placeholder translated <input class="form-control" trans="11" placeholder="" value="">

Input textarea with value translated <textarea trans="13"></textarea>

Button with tooltip value translated <button data-toggle="tooltip" trans="15" title="">A button</button>
```

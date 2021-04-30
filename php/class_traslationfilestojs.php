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
                $("#search-box").attr("placeholder",window[alang][5]);
                $("#pageheader").attr("placeholder",window[alang][5]);
                $("#paneldescription").attr("placeholder",window[alang][5]);
            }
            $(\'.selectlang\').val(lang);
            $(\'.selectlang\').selectpicker(\'render\');
            $(\'[data-toggle="tooltip"]\').tooltip();
        }';
        $localPath='../languages/';
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
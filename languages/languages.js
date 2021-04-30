en=['en','english'];
es=['es','español'];
flagsSelector='<div style="margin: auto;"><img type="image" src="./langImages/en.png" width="24px" height="24px" id="lang_1" name="en" style="cursor:pointer;" onclick="trans(\'en\')" title= "english" alt= "english"/><img type="image" src="./langImages/es.png" width="24px" height="24px" id="lang_2" name="es" style="cursor:pointer;" onclick="trans(\'es\')" title= "español" alt= "español"/></div>';

langDownLst='<a><i class="fas fa-language"></i>&nbsp;&nbsp;&nbsp;<span trans="none"><SELECT name="lang"  id="selectlang" onchange="trans(this.value)" changed.bs.select="trans(this.value)" style="background-color:#ecf0f5;text-transform:capitalize;" data-width="fit" class="selectlang"></a><option data-thumbnail="./langImages/en.png" value="en" style="text-transform:capitalize;">english</option><option data-thumbnail="./langImages/es.png" value="es" style="text-transform:capitalize;">español</option></SELECT></span></a>';

 
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
            $('.selectlang').val(lang);
            $('.selectlang').selectpicker('render');
            $('[data-toggle="tooltip"]').tooltip();
        }
<?php
/*
Plugin Name: WP-Антимат
Plugin URI: http://2lx.ru/2009/02/wp-antimat-filtr-mata-dlya-wordpress/
Description: Плагин отслеживает нецензурные выражения в комментариях и закрывает их надписью [censored]. Базируется на Anti Mate PHP Class от s1ayer.
Version: 0.7
Author: Le)(x
Author URI: http://2lx.ru
*/


$anti_mate = new anti_mate();

add_filter('pre_comment_content','filter_comment', 60);

function filter_comment($comment_text) {
	global $anti_mate;
	$comment_text = $anti_mate->filter($comment_text);
	return $comment_text;
}
/********************************************/
/*Welcome to Anti Mate PHP Class source-code!*/
/*The Anti Mate PHP Class and its functions, contexture are copyrighted by s1ayer [www.spg.arbse.net]*/
/*Current file: anti_mate.php*/
/*Optimized for PHP 4.3.6, Apache 1.3.27*/
/********************************************/

/*<=====================Describing anti_mate class==============================>*/
class anti_mate {
    //latin equivalents for russian letters
    var $let_matches = array (
    "a" => "а",
    "c" => "с",
    "e" => "е",
    "k" => "к",
    "m" => "м",
    "o" => "о",
    "x" => "х",
    "y" => "у",
    "ё" => "е"
                             );
    //bad words array. Regexp's symbols are readable !
    var $bad_words = array (".*ху(й|и|я|е|ли|ле).*", ".*пи(з|с)д.*", "бля.*", ".*бля(д|т|ц).*", "(с|сц)ук(а|о|и).*", "еб.*", ".*уеб.*", "заеб.*", ".*еб(а|и)(н|с|щ|ц).*", ".*ебу(ч|щ).*", ".*пид(о|е|а)р.*", ".*хер.*", "г(а|о)ндон.*", ".*залуп.*", "г(а|о)вн.*");

function rand_replace (){
        $output = " <font color=red>[censored]</font> ";
        return $output;
}
function filter ($string){
            $counter = 0;
	    $string = str_replace("\n", " {nl} ", $string); // Заменяем символя переноса строки на коды
            $elems = explode (" ", $string); //here we explode string to words
            $count_elems = count($elems);
            for ($i=0; $i<$count_elems; $i++)
            {
            $blocked = 0;
            /*formating word...*/
            //$str_rep = eregi_replace ("[^a-zA-Zа-яА-Яё]", "", strtolower($elems[$i]));
	    $str_rep = eregi_replace ("[^a-zйцукенгшщзхъфывапролджэячсмитьбюё]", "", mb_convert_case($elems[$i], MB_CASE_LOWER, "UTF-8")); // Thanks to Дима (graf, http://thexnews.com)

                for ($j=0; $j<strlen($str_rep); $j++)
                {
                    foreach ($this->let_matches as $key => $value)
                    {
                        if ($str_rep[$j] == $key)
                        $str_rep[$j] = $value;

                    }
                }
            /*done*/

            /*here we are trying to find bad word*/
            /*match in the special array*/
                for ($k=0; $k<count($this->bad_words); $k++)
                {
                    if (ereg("\*$", $this->bad_words[$k]))
                    {
                        if (ereg("^".$this->bad_words[$k], $str_rep))
                        {
                        $elems[$i] = $this->rand_replace();
                        $blocked = 1;
                        $counter++;
                        break;
                        }
                    
                    }
                    if ($str_rep == $this->bad_words[$k]){
                    $elems[$i] = $this->rand_replace();
                    $blocked = 1;
                    $counter++;
                    break;
                    }

                }
            }
            if ($counter != 0)
            $string = implode (" ", $elems); //here we implode words in the whole string
	    $string = str_replace(" {nl} ", "\n", $string);
return $string;
}
}
/*<===================================END=======================================>*/
?>
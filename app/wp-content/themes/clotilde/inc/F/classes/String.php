<?php


namespace F;


class String {
    /**
     * Renvoie une chaine racourci
     * @param string $sText: chaine à racourcir
     * @param float $iMaxLength : longueur du racourci
     * @param string $sMessage : message qu'on met à la fin du racourci, exemple 'lire la suite', '[...]' etc...
     * @return string return la chaine d'origine si celle ci est moin longue que la taille donnée
     */
    public static function wordCut($sText, $iMaxLength, $sMessage) {
        $sText = strip_tags($sText);
        if (strlen($sText) > $iMaxLength) {
            $sString = wordwrap($sText, ($iMaxLength-strlen($sMessage)), '[cut]', 1);
            $asExplodedString = explode('[cut]', $sString);

            $sCutText = $asExplodedString[0];

            return $sCutText.$sMessage;
        } else {
            return $sText;
        }
    }

    public static function slugify($str) {
        $str = strtolower($str);
        $str = str_replace(array('é', 'à', 'ê', 'è', ' ', '_', 'É', 'Ê', 'È'), array('e', 'a', 'e', 'e', '-', '-', 'e', 'e', 'e'), $str);
        return $str;
    }

    public static function checkPhone($phone) {
        return preg_match("/^0[0-9]{1}([ \.\-]?[0-9]{2}){4}$/", $phone) > 0;
    }

    public static function checkEmail($email) {
        if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)){
            return true;
        }
        return false;
    }
} 
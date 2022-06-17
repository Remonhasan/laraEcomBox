<?php
namespace App\Http;

class ENtoBN
{
    /**
     * Main function that handles the string
     *
     * @param string $str
     * @return string
     */
    public static function translate($str)
    {

        $str = self::translate_number($str);
        $str = self::translate_month($str);
        $str = self::translate_day($str);
        $str = self::translate_am($str);

        return $str;
    }

    /**
     * Translate numbers only
     *
     * @param string $str
     * @return string
     */
    public static function translate_number($str)
    {
        $en = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $bn = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

        $str = str_replace($en, $bn, $str);

        return $str;
    }

    /**
     * Translate months only
     *
     * @param string $str
     * @return string
     */
    public static function translate_month($str)
    {
        $en = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $en_short = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        $bn = array('জানুয়ারী', 'ফেব্রুয়ারী', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর');
        $bn_short = array('জানু.', 'ফেব্রু.', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টে.', 'অক্টো.', 'নভে.', 'ডিসে.');

        $str = str_replace($en, $bn, $str);
        $str = str_replace($en_short, $bn_short, $str);

        return $str;
    }

        /**
     * Translate months only
     *
     * @param string $str
     * @return string
     */
    public static function translate_day($str)
    {
        $en = array('Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

        $bn = array('শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার');

        $str = str_replace($en, $bn, $str);

        return $str;
    }

    /**
     * Translate AM and PM
     *
     * @param string $str
     * @return string
     */
    public static function translate_am($str)
    {
        $en = array('am', 'AM', 'pm', 'PM');
        $bn = array('পূর্বাহ্ন', 'পূর্বাহ্ন', 'অপরাহ্ন', 'অপরাহ্ন');

        $str = str_replace($en, $bn, $str);

        return $str;
    }


    public static function translateAlphabetToConsonent($str)
    {
        $en = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $bn = array('ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ', 'ঝ', 'ঞ', 'ট', 'ঠ', 'ড', 'ঢ', 'ণ', 'ত', 'থ', 'দ', 'ধ', 'ন', 'প', 'ফ', 'ব', 'ভ', 'ম', 'য');

        $str = str_replace($en, $bn, $str);

        return $str;
    }



    /**
     * @return string
     *
     */
    public static function translateNumberToBengaliWords($number,$lang)
    {

        $Koti = floor($number / 10000000); /* Koti */
        $number -= $Koti * 10000000;

        $lakh = floor($number / 100000); /* lakh */
        $number -= $lakh * 100000;

        $hajar = floor($number / 1000); /* Thousands (hajar) */
        $number -= $hajar * 1000;

        $Hn = floor($number / 100); /* Hundreds */
        $number -= $Hn * 100;

        $Dn = floor($number / 10); /* Tens (deca) */
        $n = $number % 10; /* Ones */

        $res = "";

        if ($Koti) {
            $res .= self::translateNumberToBengaliWords($Koti,$lang) . (($lang == 'bn') ? " কোটি " : " Crore ");
        }

        if ($lakh) {
            $res .= self::translateNumberToBengaliWords($lakh,$lang) . (($lang == 'bn') ? " লক্ষ " : " Lakh ");
        }

        if ($hajar) {
            $res .= self::translateNumberToBengaliWords($hajar,$lang) . (($lang == 'bn') ? " হাজার " : " Thousand ");
        }

        if ($Hn) {
            $res .= self::translateNumberToBengaliWords($Hn,$lang) . ( ($lang == 'bn') ? " শত " : " Hundred ");
        }

        $wordsBn = array(
            "", "এক",
            "দুই", "তিন", "চার", "পাঁচ", "ছয়", "সাত", "আট", "নয়", "দশ", "এগার", "বারো",
            "তের", "চৌদ্দ", "পনের", "ষোল", "সতের", "আঠার", "ঊনিশ", "বিশ", "একুশ", "বাইস",
            "তেইশ", "চব্বিশ", "পঁচিশ", "ছাব্বিশ", "সাতাশ", "আঠাশ", "ঊনত্রিশ", "ত্রিশ", "একত্রিশ",
            "বত্রিশ", "তেত্রিশ", "চৌত্রিশ", "পঁয়ত্রিশ", "ছত্রিশ", "সাঁইত্রিশ", "আটত্রিশ", "ঊনচল্লিশ",
            "চল্লিশ", "একচল্লিশ", "বিয়াল্লিশ ", "তেতাল্লিশ ", "চুয়াল্লিশ ", "পঁয়তাল্লিশ", "ছেচল্লিশ ",
            "সাতচল্লিশ", "আটচল্লিশ", "ঊনপঞ্চাশ ", "পঞ্চাশ ", "একান্ন ", "বায়ান্ন ",
            "তিপ্পান্ন", "চুয়ান্ন ", "পঞ্চান্ন ", "ছাপ্পান্", "সাতান্ন", "আটান্ন", "ঊনষাট ", "ষাট ",
            "একষট্টি", "বাষট্টি", "তেষট্টি", "চৌষট্টি", "পঁয়ষট্টি", "ছেষট্টি", "সাতষট্টি", "আটষট্টি ",
            "ঊনসত্তর", "সত্তর", "একাত্তর ", "বাহাত্তর ", "তিয়াত্তর ", "চুয়াত্তর ", "পঁচাত্তর", "ছিয়াত্তর ",
            "সাতাত্তর", "আটাত্তর ", "ঊনআশি ", "আশি ", "একাশি ", "বিরাশি ", "তিরাশি ", "চুরাশি ", "পঁচাশি ",
            "ছিয়াশি ", "সাতাশি ", "আটাশি ", "ঊননব্বই ", "নব্বই ", "একানব্বই ", "বিরানব্বই ", "তিরানব্বই ",
            "চুরানব্বই ", "পঁচানব্বই ", "ছিয়ানব্বই", "সাতানব্বই", "আটানব্বই", "নিরানব্বই"
        );

        $wordsEn = array(
            "", "One",
            "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve",
            "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen", "Twenty", "Twenty one", "Twenty two",
            "Twenty three", "Twenty four", "Twenty five", "Twenty six", "Twenty seven", "Twenty eight", "Twenty nine", "Thirty", "Thirty one",
            "Thirty two", "Thirty three", "Thirty four", "Thirty five", "Thirty six", "Thirty seven", "Thirty eight", "Thirty nine",
            "Forty", "Forty one", "Forty two ", "Forty three ", "Forty four ", "Forty five", "Forty six ",
            "Forty seven", "Forty eight", "Forty nine ", "Fifty ", "Fifty one ", "Fifty two ",
            "Fifty three", "Fifty four ", "Fifty five ", "Fifty six", "Fifty seven", "Fifty eight", "Fifty nine ", "Sixty ",
            "Sixty one", "Sixty two", "Sixty three", "Sixty four", "Sixty five", "Sixty six", "Sixty seven", "Sixty eight ",
            "Sixty nine", "Seventy", "Seventy one ", "Seventy two ", "Seventy three ", "Seventy four ", "Seventy five", "Seventy six ",
            "Seventy seven", "Seventy eight ", "Seventy nine ", "Eighty ", "Eighty one ", "Eighty two ", "Eighty three ", "Eighty four ", "Eighty five ",
            "Eighty six ", "Eighty seven ", "Eighty eight ", "Eighty nine ", "Ninety ", "Ninety one ", "Ninety two ", "Ninety three ",
            "Ninety four ", "Ninety five ", "Ninety six", "Ninety seven", "Ninety eight", "Ninety nine"
        );

        // dd( $res, $Dn,$n);
        if ($Dn || $n) {
            $index = $Dn * 10 + $n;
            $res .= ($lang == 'bn') ? $wordsBn[$index] : $wordsEn[$index] ;
        }

        if (empty($res)) {
            $res = ($lang == 'bn') ? "শূন্য " : "Zero ";
        }
        return $res;
    }

}

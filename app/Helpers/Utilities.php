<?php

/**
 * Utilities/Helper Functions.
 *
 * Helper functions necessary for the application
 * will be managed here.
 */

use App\Http\ENtoBN;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Auto Copyright
 *
 * @param string|integer $year A year | 'auto'.
 * @param string         $text Copyright text.
 *
 * @author Ipstenu (Mika Epstein) <[<email address>]>
 * @link   https://halfelf.org/2017/copyright-years-wordpress/
 *
 * @return void.
 * ---------------------------------------------------------------------
 */
function autoCopyright($year = 'auto', $text = '')
{
    $year = ($year == 'auto' || ctype_digit($year) == false) ? date('Y') : intval($year);
    $text = ($text == '') ? '&copy;' : $text;

    if ($year == date('Y') || $year > date('Y')) {
        $output = date('Y');
    } elseif ($year < date('Y')) {
        $output = $year . ' - ' . date('Y');
    }

    $output = ('bn' == config('app.locale')) ? ENtoBN::translate($output) : $output;

    echo '&copy; ' . $output . ' ' . $text;
}

/**
 * Translate String, when applicable.
 *
 * Translate the string only when certain locale is set.
 *
 * @param string $string String to translate.
 *
 * @return string        Translated/Non-translated string.
 * -----------------------------------------------------------------------
 */
function translateString($string)
{
    $translatedString = ('bn' == config('app.locale')) ? ENtoBN::translate($string) : $string;

    return $translatedString;
}

function translateAlphabet($string)
{
    $translatedString = ('bn' == config('app.locale')) ? ENtoBN::translateAlphabetToConsonent($string) : $string;

    return $translatedString;
}

function translateNumberInWords($number)
{
    if (is_numeric($number)) {
        if (($number < 0) || ($number > 999999999)) {
            $res = "Number is out of range";
            $translatedString = $number;
        } else {
            $translatedString = ENtoBN::translateNumberToBengaliWords($number, config('app.locale'));
        }
    } else {
        $translatedString = $number;
    }

    return $translatedString;
}

/**
 * Display DateTime.
 *
 * Empty check the date first to avoid displaying default date '01 January 1970',
 * then translate the string if applicable, and return the formatted DateTime.
 *
 * @param integer $dateTimeInput DateTime as input.
 * @param string  $format        Date Format.
 *
 * @return string                String translated, EmDash otherwise.
 * -----------------------------------------------------------------------
 */
function displayDateTime($dateTimeInput, $format = 'd F Y')
{
    if (empty($dateTimeInput)) {
        return '―';
    }

    $formattedDate = date($format, strtotime($dateTimeInput));

    if ('bn' == config('app.locale')) {
        return ENtoBN::translate($formattedDate);
    } else {
        return $formattedDate;
    }
}

function displayDayName($dateTimeInput)
{
    if (empty($dateTimeInput)) {
        return '―';
    }

    $dayName = date('l', strtotime($dateTimeInput));

    if ('bn' == config('app.locale')) {
        return ENtoBN::translate($dayName);
    } else {
        return $dayName;
    }
}

/**
 * Truncate Amount (in US format).
 *
 * @param integer $amount Amount.
 *
 * @return array Array of truncated amount and label.
 */
function truncateAmountUS($amount)
{
    // filter and format it
    if ($amount < 1000) {
        // 0 - 1000
        $amountOutput = (int) $amount;
        $truncateLabel = '';
    } elseif ($amount < 900000) {
        // 0.9k-850k
        $amountOutput = round(($amount / 1000), 2);
        $truncateLabel = __('k');
    } elseif ($amount < 900000000) {
        // 0.9m-850m
        $amountOutput = round(($amount / 1000000), 2);
        $truncateLabel = __('m');
    } elseif ($amount < 900000000000) {
        // 0.9b-850b
        $amountOutput = round(($amount / 1000000000), 2);
        $truncateLabel = __('b');
    } else {
        // 0.9t+
        $amountOutput = round(($amount / 1000000000000), 2);
        $truncateLabel = __('t');
    }

    return array(
        'amountOutput' => $amountOutput,
        'truncateLabel' => $truncateLabel,
    );
}

/**
 * Truncate Amount (in Indian format).
 *
 * @param integer $amount Amount.
 *
 * @link https://stackoverflow.com/q/42571702/1743124 (Concept)
 *
 * @return array Array of truncated amount and label.
 */
function truncateAmountIndian($amount)
{
    // Failsafe with double equals instead of tripple equal.
    if (0 == $amount) {
        return 0;
    }

    // Known issue: strlen() on string returns wrong estimation.
    $length = strlen((float) $amount);

    if ($length <= 3) {
        $amountOutput = (int) $amount;
        $label = '';
    } elseif ($length === 4 || $length === 5) {
        // 1,000 - 99,999.
        $amountOutput = round(($amount / 1000), 2);
        $label = __('k');
    } elseif ($length === 6 || $length === 7) {
        // 1,00,000 - 99,99,999.
        $amountOutput = round(($amount / 100000), 2);
        $label = __('Lac');
    } elseif ($length > 7) {
        // 1,00,00,000 - 99,99,99,999+.
        $amountOutput = round(($amount / 10000000), 2);
        $label = __('Cr');
    }

    return array(
        'amountOutput' => $amountOutput,
        'truncateLabel' => $label,
    );
}

/**
 * Display amount.
 *
 * Display amount thousand formatted, and float pointed,
 * and if applicable, with currency symbol.
 *
 * @param integer        $amount         Amount value.
 * @param boolean|string $currencySymbol Symbol Currency symbol, otherwise false.
 * @param boolean        $truncate       Whether display the short form or detail form.
 *
 * @see truncateAmountUS()     Truncate Amount in US format.
 * @see truncateAmountIndian() Truncate Amount in Indian format.
 *
 * @return string Formatted amount string.
 */
function displayAmount($amount, $currencySymbol = false, $truncate = false)
{
    if (empty($amount)) {
        return '—';
    }

    // make sure it's a number...
    if (!IS_NUMERIC($amount)) {
        return false;
    }

    if ($truncate) {
        if (config('app.is_money_format_indian')) {
            $tr = truncateAmountIndian($amount);
        } else {
            $tr = truncateAmountUS($amount);
        }

        $amountOutput = $tr['amountOutput'];
        $truncateLabel = $tr['truncateLabel'];
    } else {
        // Thousand Separated and float pointed.
        if (config('app.is_money_format_indian')) {
            // Decimal pointed value without thousand separator.
            $amount = number_format($amount, 2, '.', '');
            // Thousand separator like the Indian format.
            // @link https://stackoverflow.com/a/54603979/1743124
            $amountOutput = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $amount);
        } else {
            $amountOutput = number_format((float) $amount, 2, '.', ',');
        }
    }

    $amountOutput = ('bn' === app()->getLocale()) ? ENtoBN::translate_number($amountOutput) : $amountOutput;

    if (false !== $currencySymbol) {
        $amountOutput = $currencySymbol . ' ' . $amountOutput;
    }

    if ($truncate) {
        $amountOutput = $amountOutput . ' ' . $truncateLabel;
    }

    return $amountOutput;
}

/**
 * Elapsed time
 *
 * Calculates how much time elapsed from a time mentioned.
 *
 * @param string $time Date & Time string.
 *
 * @author arnorhs
 * @link   http://stackoverflow.com/a/2916189/1743124
 *
 * @return string       Elapsed time.
 * -----------------------------------------------------------------------
 */
function timeElapsed($time)
{
    $time = strtotime($time);
    $now = date('Y-m-d H:i:s', time());
    $time = strtotime($now) - $time; // to get the time since that moment
    $tokens = array(
        31536000 => __('year|years'),
        2592000 => __('month|months'),
        604800 => __('week|weeks'),
        86400 => __('day|days'),
        3600 => __('hour|hours'),
        60 => __('minute|minutes'),
        1 => __('second|seconds'),
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) {
            continue;
        }

        $number_of_units = floor($time / $unit);
        $localized_number_of_units = 'bn' == config('app.locale') ? ENtoBN::translate($number_of_units) : $number_of_units;
        return $localized_number_of_units . ' ' . trans_choice($text, $number_of_units);
    }
}

/**
 * Date Difference In Days
 *
 * Calculates how much time from a date mentioned.
 * PHP code to find the number of days between two given dates.
 * Function to find the difference between two dates.
 *
 * @param string $date1        From Date.
 * @param string $date2        To Date.
 * @param string $stringAppend Text.
 *
 * @link http://www.geeksforgeeks.org/program-to-find-the-number-of-days-between-two-dates-in-php/
 *
 * @return integer|string in days.
 * -----------------------------------------------------------------------
 */

function dateDiffInDays($date1, $date2, $stringAppend = '')
{
    // Calulating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);

    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    $number = abs(round($diff / 86400));

    if (!empty($stringAppend)) {
        return "{$number} {$stringAppend}";
    }

    return $number;
}

/**
 * Parse Arguments.
 *
 * Parse user defined arguments and mix them with default
 * arguments defined.
 *
 * Adopted, but modified from WordPress Core.
 *
 * @param array $args     User defined arguments.
 * @param array $defaults Default arguments.
 *
 * @return array          Merged version of arguments.
 * ---------------------------------------------------------------------
 */
function parseArguments($args, $defaults)
{
    if (!is_array($args) || !is_array($defaults)) {
        return 'Both the parameters need to be array';
    }

    $r = &$args;

    return array_merge($defaults, $r);
}

/**
 * The array_merge_recursive does indeed merge arrays, but it converts values with duplicate
 * keys to arrays rather than overwriting the value in the first array with the duplicate
 * value in the second array, as array_merge does. I.e., with array_merge_recursive,
 * this happens (documented behavior):
 *
 * The array_merge_recursive(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('org value', 'new value'));
 *
 * array_merge_recursive_distinct does not change the datatypes of the values in the arrays.
 * Matching keys' values in the second array overwrite those in the first array, as is the
 * case with array_merge, i.e.:
 *
 * array_merge_recursive_distinct(array('key' => 'org value'), array('key' => 'new value'));
 *     => array('key' => array('new value'));
 *
 * Parameters are passed by reference, though only for performance reasons. They're not
 * altered by this function.
 *
 * @param array $array1 Array 1
 * @param array $array2 Array 2
 *
 * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
 * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
 *
 * @link https://medium.com/@kcmueller/php-merging-two-multi-dimensional-arrays-overwriting-existing-values-8648d2a7ea4f
 *
 * @return array
 * ---------------------------------------------------------------------
 */
function arrayMergeRecursiveDistinct(array &$array1, array &$array2)
{
    $merged = $array1;
    foreach ($array2 as $key => &$value) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
            $merged[$key] = arrayMergeRecursiveDistinct($merged[$key], $value);
        } else {
            $merged[$key] = $value;
        }
    }
    return $merged;
}

/**
 * In Admin
 * Detect whether the current URL is of Admin Panel or not.
 *
 * @return boolean          True if in admin, false otherwise.
 * ---------------------------------------------------------------------
 */
function inAdmin()
{
    // Strip out language prefix.
    $currentRoutePrefix = str_replace('/' . config('app.locale'), '', Request()->route()->getPrefix());

    if (config('app.admin_route_prefix') === $currentRoutePrefix) {
        return true;
    } else {
        return false;
    }
}

/**
 * Items Per Page (IPP).
 *
 * Restrict items per page within the defined item limits.
 *
 * @param  integer $default Default is 10 items per page.
 * @return integer Fool-proof items per page.
 */
function itemsPerPage($default = 10)
{
    $itemLimits = config('app.items_per_pages');

    $itemsPerPage = Request::input('ipp') ?? $default;

    if (!in_array($itemsPerPage, $itemLimits)) {
        $itemsPerPage = $default;
    }

    return $itemsPerPage;
}

/**
 * Show the Footer of a Grid/List.
 *
 * @param object  $query        Query object.
 * @param integer $itemsPerPage Items per page count.
 */
function gridFooter($query, $itemsPerPage)
{
    $itemLimits = config('app.items_per_pages');
    $query_count = ('bn' === app()->getLocale()) ? ENtoBN::translate_number($query->count()) : $query->count();
    $query_total = ('bn' === app()->getLocale()) ? ENtoBN::translate_number($query->total()) : $query->total();

    ob_start();
    ?>

    <div class="row small text-muted">
        <div class="col-sm-4 pt-1">
            <?php echo __('Per page'); ?>
            <select name="ipp" id="items-per-page" class="custom-select custom-select-sm w-auto">
                <?php foreach ($itemLimits as $limit) {?>
                    <option value="<?php echo intval($limit); ?>" <?php echo $itemsPerPage == $limit ? 'selected="selected"' : ''; ?>><?php echo translateString($limit); ?></option>
                <?php }?>
            </select>
            <?php echo __('items'); ?>
            <span class="ml-1 mr-1">|</span>
            <?php echo __('Showing :count out of :total items', ['count' => $query_count, 'total' => $query_total]); ?>
        </div>
        <div class="col-sm-8 text-sm-right">
            <?php
if ($query->total() > $itemsPerPage) {
        // Pagination keeping the filter parameters
        echo $query->appends(Request::except('page'))->render();
    } else {
        echo __('Page 1');
    }
    ?>
        </div>
    </div>

<?php
return ob_get_clean();
}

/**
 * Show the Footer of a Grid/List.
 *
 * @param object  $query        Query object.
 * @param integer $itemsPerPage Items per page count.
 */
function gridFooterSimplePaginate($links, $itemsPerPage, $query_total)
{
    $itemLimits = config('app.items_per_pages');
    $query_count = 10;
    if (isset($_GET['ipp']) && !empty($_GET['ipp'])) {
        $query_count = $_GET['ipp'];
    }
    ?>

    <div class="row small text-muted">
        <div class="col-sm-4 pt-1">
            <?php echo __('Per page'); ?>
            <select name="ipp" id="items-per-page" class="custom-select custom-select-sm w-auto">
                <?php foreach ($itemLimits as $limit) {?>
                    <option value="<?php echo intval($limit); ?>" <?php echo $itemsPerPage == $limit ? 'selected="selected"' : ''; ?>><?php echo translateString($limit); ?></option>
                <?php }?>
            </select>
            <?php echo __('items'); ?>
            <span class="ml-1 mr-1">|</span>
            <?php echo __('Showing :count out of :total items', ['count' => $query_count, 'total' => $query_total]); ?>
        </div>
        <div class="col-sm-8 text-sm-right">
            <?php
/* if ($query->total() > $itemsPerPage) {
    // Pagination keeping the filter parameters
    echo $query->appends(Request::except('page'))->render();
    } else {
    echo __('Page 1');
    } */
    echo $links;
    ?>
        </div>
    </div>

<?php
return ob_get_clean();
}

/**
 * Format Bytes.
 *
 * @param integer $bytes     Bytes
 * @param integer $precision Precision
 *
 * @link https://stackoverflow.com/a/2510459/1743124
 *
 * @return integer
 * ---------------------------------------------------------------------
 */
function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Method to check mysqldump command is accessible or not.
 *
 * Used to execute database backup using mysqldump command.
 *
 * @return string|boolean false if inaccessible, path as string if accessible.
 * ---------------------------------------------------------------------
 */
function hasMySQLDump()
{
    // Check default way - the PATH.
    exec('mysqldump --version 2>&1', $output, $return_val);

    if ($return_val != 0) {
        // It's not working by default. Get user defined path, and try again.
        $path_to_mysqld = (string) getOption('_path_to_mysqld');
        if (empty($path_to_mysqld)) {
            return false;
        } else {
            $path_to_mysqld = rtrim($path_to_mysqld, '/\\'); // untrailingSlahIt

            if (!is_dir($path_to_mysqld)) {
                // There's no such directory.
                return false;
            }

            // https://stackoverflow.com/a/13539649/1743124
            $mysql_path = "{$path_to_mysqld}\mysqldump.exe";
            $mysqldump = escapeshellarg($mysql_path); // escape unwanted shell command

            exec("{$mysqldump} --version 2>&1", $output_secondary, $return_val_secondary);

            if ($return_val_secondary != 0) {
                // It's not working even with user defined path.
                return false;
            } else {
                // It's working with user defined path, return it.
                return $mysql_path;
            }
        }
    } else {
        // It's working, return the command itself.
        return 'mysqldump';
    }
}

/**
 * Method to check gzip command is accessible or not.
 *
 * Used to execute database backup using mysqldump command.
 *
 * @return boolean false if inaccessible, true otherwise.
 * ---------------------------------------------------------------------
 */
function hasGZip()
{
    // Check default way - the PATH.
    exec('gzip --version 2>&1', $output, $return_val);

    return $return_val != 0 ? false : true;
}

/**
 * Generate alt text from Image file path.
 *
 * @param string $image_file_path Image file path.
 *
 * @return string                 Alt text.
 * -----------------------------------------------------------------------
 */
function generateAltTextFromImagePath($image_file_path)
{
    $alt_text = pathinfo($image_file_path)['filename'];
    $alt_text = str_replace('_', ' ', $alt_text);
    $alt_text = str_replace('-', ' ', $alt_text);

    return $alt_text;
}

/**
 * Add Filter Parameters to Query Arguments.
 *
 * If your db Query key is different than the URL params,
 * then pass and associative array as $additions, if not,
 * you can pass a one-dimentional array of parameters.
 *
 * Usage Instructions:
 * - $additions = array(
 *      'query_key1' => 'parameter1',
 *      'query_key2' => 'parameter2'
 *   );
 * - $additions = array('parameter1', 'parameter2');
 *
 * @param array $args      Array of Arguments.
 * @param array $additions Array of Filter Keys.
 *
 * @return array           Merged Array.
 * -----------------------------------------------------------------------
 */
function filterParams($args, $additions)
{
    if (Arr::isAssoc($additions)) {
        foreach ($additions as $query_key => $param) {
            $_var = Request::input($param);

            if (!empty($_var)) {
                $args = array_merge($args, array($query_key => $_var));
            }
        }
    } else {
        foreach ($additions as $param) {
            $_var = Request::input($param);

            if (!empty($_var)) {
                $args = array_merge($args, array($param => $_var));
            }
        }
    }

    return $args;
}

/**
 * Get Status Label and Icon
 *
 * @param string $status Status key.
 * @param bool   $icon   True to return icon also, false otherwise. Default: false.
 *
 * @return array|bool|null|string If icon, array, otherwise status string. Returns false if not matched.
 * -----------------------------------------------------------------------
 */
function getStatusLabel($status, $icon = false)
{
    if ('draft' === $status) {
        $icon = 'icon-pencil7';
        $label = __('Draft');
    } elseif ('pending' === $status) {
        $icon = 'icon-shield-notice';
        $label = __('Pending Review');
    } elseif ('private' === $status) {
        $icon = 'icon-user-lock';
        $label = __('Private');
    } elseif ('internal' === $status) {
        $icon = 'icon-lock5';
        $label = __('Internal');
    } elseif ('approve' === $status) {
        $icon = 'icon-checkmark-circle';
        $label = __('Approve');
    } elseif ('publish' === $status) {
        $icon = 'icon-checkmark-circle2';
        $label = __('Publish');
    } elseif ('trash' === $status) {
        $icon = 'icon-trash-alt';
        $label = __('Trash');
    } else {
        return false;
    }

    if ($icon) {
        return array(
            'icon' => $icon,
            'label' => $label,
        );
    } else {
        return $label;
    }
}

/**
 * Site's Base URL.
 *
 * Might be similar to the Home URL, but it's made
 * because of the locale middleware.
 *
 * @return string Base URL of the site.
 * -----------------------------------------------------------------------
 */
function baseURL()
{
    if (!empty(config('app.locale'))) {
        $locale_param = '/' . config('app.locale') . '/';
    } else {
        $locale_param = '';
    }

    return action('LanguageController@baseURL') . $locale_param;
}

/**
 * Suppress Base URL from a URL.
 *
 * @param string $url User passed URL.
 *
 * @return string Suppressed URL.
 * -----------------------------------------------------------------------
 */
function suppressBaseURL($url)
{
    $baseURL = rtrim(baseURL(), '/\\'); //untrailingslashit. Thanks to WordPress.
    return str_replace($baseURL, '', $url);
}

/**
 * Convert Line Breaks to Paragraphs.
 *
 * @param string  $string      String to put paragraphs on.
 * @param boolean $line_breaks Whether to put line breaks or not.
 * @param boolean $xml         Whether there is XML or not.
 *
 * @author Shoelaced
 * @link   https://stackoverflow.com/a/52692970/1743124
 *
 * @return string              Formatted String
 * -----------------------------------------------------------------------
 */
function nl2p($string, $line_breaks = true, $xml = true)
{
    // Remove current tags to avoid double-wrapping.
    $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);

    // Default: Use <br> for single line breaks, <p> for multiple line breaks.
    if ($line_breaks == true) {
        $string = '<p>' . preg_replace(
            array("/([\n]{2,})/i", "/([\r\n]{3,})/i", "/([^>])\n([^<])/i"),
            array("</p>\n<p>", "</p>\n<p>", '$1<br' . ($xml == true ? ' /' : '') . '>$2'),
            trim($string)
        ) . '</p>';

        // Use <p> for all line breaks if $line_breaks is set to false.
    } else {
        $string = '<p>' . preg_replace(
            array("/([\n]{1,})/i", "/([\r]{1,})/i"),
            "</p>\n<p>",
            trim($string)
        ) . '</p>';
    }

    // Remove empty paragraph tags.
    $string = str_replace('<p></p>', '', $string);

    // Return string.
    return $string;
}

/**
 * Years for Select field.
 *
 * @param integer $earliestYear Earliest year to load from.
 * @param integer $limitYear    Limit where to stop.
 *
 * @author Mayeenul Islam
 * @author Chris Baker
 * @link   https://stackoverflow.com/a/7083153/1743124
 *
 * @return array                 Translated array of years.
 * -----------------------------------------------------------------------
 */
function selectYears($earliestYear = null, $limitYear = null)
{
    $earliestYear = null == $earliestYear ? 1952 : $earliestYear;
    $limitYear = null == $limitYear ? date('Y') + 1 : $limitYear;

    $years = array();

    foreach (range($limitYear, $earliestYear) as $year):
        $yearLabel = 'bn' == config('app.locale') ? ENtoBN::translate_number($year) : $year;
        $years[$year] = $yearLabel;
    endforeach;

    return $years;
}

/**
 * Show CSS Loader.
 *
 * Show the HTML markups for a CSS loader.
 *
 * @author Aaron Iker
 * @link   https://codepen.io/aaroniker/pen/ZmOMJp
 *
 * @return void
 * -----------------------------------------------------------------------
 */
function showLoader()
{
    ?>
    <div class="loader-boxes">
        <div class="loader-box">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="loader-box">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="loader-box">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="loader-box">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <?php
}

/**
 * Display View Button.
 *
 * Display view button in admin panel based on the status.
 * - Draft => Preview
 * - View (Internal) => Admin View
 * - View (Public) => Public View
 *
 * @param string $status      Status.
 * @param string $internalURL Internal Url.
 * @param string $publicURL   Public Url.
 *
 * @return void
 * -----------------------------------------------------------------------
 */
function displayViewButton($status, $internalURL, $publicURL = '')
{
    if (in_array($status, ['internal', 'draft'])) {
        ?>
        <a href="<?php echo $internalURL; ?>" class="btn btn-outline-primary btn-sm">
            <i class="icon-eye mr-1" aria-hidden="true"></i>
            <?php echo 'draft' === $status ? __('Preview') : __('View'); ?>
        </a>
    <?php
} elseif ('publish' === $status) {
        ?>
        <div class="dropdown d-inline-block">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="view-scope" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-eye mr-1" aria-hidden="true"></i>
                <?php echo __('View'); ?>
            </button>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="view-scope">
                <a href="<?php echo $internalURL; ?>" class="dropdown-item">
                    <?php echo __('Internal'); ?>
                </a>

                <a href="<?php echo $publicURL; ?>" class="dropdown-item">
                    <?php echo __('Public'); ?>
                </a>
            </div>
        </div>
<?php
}
}

/**
 * Prohibit Editing Other People's Draft.
 *
 * Not applicable for 'administrator'.
 *
 * @param string  $status     Status.
 * @param integer $authorId   Author ID.
 * @param integer $statusCode HTTP Status Code. Default: 403.
 * @param string  $message    Custom message. Default: empty.
 *
 * @return \Illuminate\Http\Response
 */
function prohibitDraftEdit($status, $authorId, $statusCode = 403, $message = 'Unauthorized Draft')
{
    if (isUserRole('administrator')) {
        return;
    }

    if ('draft' === $status && $authorId !== Auth::id()) {
        if (empty($message)) {
            return abort($statusCode);
        } else {
            return abort($statusCode, $message);
        }
    }
}

/**
 * Set Default Value.
 *
 * @param object  $object  Data Object.
 * @param string  $field   Database Field.
 * @param mixed   $default Default value. Default: Empty.
 * @param boolean $isDate  Whether the field is date or not. Default: false.
 *
 * @return mixed
 */
function setDefaultValue($field, $object, $default = '', $isDate = false)
{
    if (old($field)) {
        // Prioritize $_POST data.
        $value = old($field);
    } elseif (isset($object) && !empty($object)) {
        // Then database value (in edit mode).
        if ($isDate && !empty($object->$field)) {
            $value = date('d-m-Y', strtotime($object->$field));
        } else {
            $value = $object->$field;
        }
    } else {
        // None. Return default.
        $value = $default;
    }

    return $value;
}

/**
 * Check URL/Route is match or not.
 *
 * @param string $route User provided Route/URL.
 *
 * @return boolean      True if url is matched, false otherwise.
 */
function isCurrentRoute($route)
{
    $this_url = suppressBaseURL(url()->current());
    $edit_url = suppressBaseURL($route);

    if ($this_url === $edit_url) {
        return true;
    }

    return false;
}

/**
 * Blood Groups.
 *
 * Defined blood groups.
 *
 * @link https://www.nhs.uk/conditions/blood-groups/
 *
 * @return array Array of Blood Groups.
 */
function bloodGroups()
{
    return array(
        'A+' => __('A Positive (A+ve)'),
        'A-' => __('A Negative (A-ve)'),
        'B+' => __('B Positive (B+ve)'),
        'B-' => __('B Negative (B-ve)'),
        'O+' => __('O Positive (O+ve)'),
        'O-' => __('O Negative (O-ve)'),
        'AB+' => __('AB Positive (AB+ve)'),
        'AB-' => __('AB Negative (AB-ve)'),
    );
}

/**
 * Resolve Field Name.
 *
 * If the data is available in the column of current language, then get from there,
 * otherwise fall back to English column (by default).
 *
 * @param object $object|array  Data object or Array.
 * @param string $column_prefix Column name prefix. Default: 'name_'.
 * @param string $fallback_lang Column langugage follback. Default: 'en'.
 *
 * @return string The available string from the available column.
 */
function resolveFieldName($object, $column_prefix = 'name_', $fallback_lang = 'en')
{
    $lang = config('app.locale');

    $fallback_column = $column_prefix . $fallback_lang;
    $column = $column_prefix . $lang;

    if (is_object($object)) {
        $column_value = $object->$column ?? $object->$fallback_column;
        if (strlen($column_value) <= 0) {
            $column_value = $object->$fallback_column;
        }
    } elseif (is_array($object)) {
        $column_value = $object[$column] ?? $object[$fallback_column];
        if (strlen($column_value) <= 0) {
            $column_value = $object[$fallback_column];
        }
    }

    return $column_value ?? '-';
}

/**
 * User Levels.
 *
 * User levels defined by the LGSP-3.
 *
 * @return array User levels.
 */
function userLevels()
{
    return array(
        'admin' => __('BFD Admin'),
        'employee' => __('Employee'),
    );
}

/**
 * Genders.
 *
 * Defined genders.
 *
 * @return array Array of Genders.
 */
function genders()
{
    return array(
        'male' => __('Male'),
        'female' => __('Female'),
        'third_gender' => __('Third Gender'),
    );
}

/**
 * Operator Status.
 *
 * Defined Status.
 *
 * @return array Array of Genders.
 */
function operatorStatus()
{
    return array(
        'pending' => __('Pending'),
        'publish' => __('Publish'),
        'cancel' => __('Cancel'),
    );
}

/**
 * Add number of days to a date
 *
 * @param $date date to add days
 * @param $days
 * @param $format date format that will be return
 * @author Nazmul Hasan
 */
function addDaysToDate($date, $days, $format = 'd-m-Y')
{
    return date($format, strtotime($date . ' + ' . $days . ' days'));
}

/**
 * GPS to Number.
 *
 * @param string $coordPart Coordinate part.
 *
 * @return float            GPS Number.
 */
function gps2Num($coordPart)
{
    $parts = explode('/', $coordPart);
    if (count($parts) <= 0) {
        return 0;
    } elseif (count($parts) == 1) {
        return $parts[0];
    }

    if (floatval($parts[1] != 0)) {
        return floatval($parts[0]) / floatval($parts[1]);
    }
}

/**
 * Get Image Location (Lat-Lng)
 *
 * @param string $file File with complete path.
 *
 * @return boolean|array False if unsuccessful | Array of lat-lng otherwise.
 */
function get_image_location($file)
{
    try {
        if (!function_exists('exif_read_data')) {
            return false;
        }

        $exif = @exif_read_data($file, 0, true);

        if (!empty($exif) && isset($exif['GPS'])) {
            getExifLatLng($exif);

            return false;
        }
    } catch (\Exception $e) {
        return false;
    }

    return false;
}

/**
 * Get Latitude, Longitude from EXIF data.
 *
 * @param array $exif EXIF data.
 *
 * @return array|boolean Lat-Lng as array, false otherwise.
 */
function getExifLatLng($exif)
{
    if (!is_array($exif)) {
        return false;
    }

    if (false == $exif) {
        return false;
    }

    if (!isset($exif['GPS'])) {
        return false;
    }

    $GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
    $GPSLatitude = $exif['GPS']['GPSLatitude'];
    $GPSLongitudeRef = $exif['GPS']['GPSLongitudeRef'];
    $GPSLongitude = $exif['GPS']['GPSLongitude'];

    $lat_degrees = count($GPSLatitude) > 0 ? gps2Num($GPSLatitude[0]) : 0;
    $lat_minutes = count($GPSLatitude) > 1 ? gps2Num($GPSLatitude[1]) : 0;
    $lat_seconds = count($GPSLatitude) > 2 ? gps2Num($GPSLatitude[2]) : 0;

    $lon_degrees = count($GPSLongitude) > 0 ? gps2Num($GPSLongitude[0]) : 0;
    $lon_minutes = count($GPSLongitude) > 1 ? gps2Num($GPSLongitude[1]) : 0;
    $lon_seconds = count($GPSLongitude) > 2 ? gps2Num($GPSLongitude[2]) : 0;

    $lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
    $lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;

    $latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60 * 60)));
    $longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60 * 60)));

    if (empty($latitude) || empty($longitude)) {
        return false;
    }

    return array(
        'latitude' => $latitude,
        'longitude' => $longitude,
    );
}

/**
 * Get Image Date Time
 *
 * @param string $file File with complete path.
 *
 * @return boolean|array False if unsuccessful
 */
function get_image_datetime($file)
{
    try {
        if (!function_exists('exif_read_data')) {
            return false;
        }

        $exif = @exif_read_data($file, 0, true);

        if (!empty($exif) && (isset($exif['IFD0']) || isset($exif['EXIF']) || isset($exif['GPS']))) {
            if (!empty($exif['EXIF']['DateTimeOriginal'])) {
                $dateTime = $exif['EXIF']['DateTimeOriginal'];
            } elseif (!empty($exif['IFD0']['DateTime'])) {
                $dateTime = $exif['IFD0']['DateTime'];
            } elseif (!empty($exif['GPS']['GPSDateStamp'])) {
                $dateTime = $exif['GPS']['GPSDateStamp'];
            } else {
                $dateTime = null;
            }

            return array(
                'dateTime' => $dateTime,
            );
        }
    } catch (\Exception $e) {
        return false;
    }

    return false;
}

function getImageExifInfo($file)
{
    try {
        if (!function_exists('exif_read_data')) {
            return null;
        }

        $exif = @exif_read_data($file, 0, true);
        return serialize($exif);
    } catch (\Exception $e) {
        return null;
    }

    return null;
}

/**
 * Get all the Months.
 *
 * @return array
 */
function months()
{
    return array(
        1 => __('January'),
        2 => __('February'),
        3 => __('March'),
        4 => __('April'),
        5 => __('May'),
        6 => __('June'),
        7 => __('July'),
        8 => __('August'),
        9 => __('September'),
        10 => __('October'),
        11 => __('November'),
        12 => __('December'),
    );
}

/**
 * Sharer.
 *
 * Displaying social sharing buttons on some parameters.
 *
 * Adopted from the codebase of Gyanoshudha Gronthagar.
 *
 * @param string $url
 * @param string $title
 * @param string $hashtags
 *
 * @return mixed
 */
function sharer($url, $title = '', $hashtags = '')
{
    return '<button type="button" title="' . __('Share on Twitter') . '" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary rounded-circle btn-social" data-sharer="twitter" data-title="' . $title . '" data-hashtags="' . $hashtags . '" data-url="' . $url . '"><i class="icon-twitter" aria-hidden="true"></i> <span class="sr-only">Share on Twitter</span></button>
    <button type="button" title="' . __('Share on Facebook') . '" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary rounded-circle btn-social" data-sharer="facebook" data-hashtag="' . $hashtags . '" data-url="' . $url . '"><i class="icon-facebook" aria-hidden="true"></i> <span class="sr-only">Share on Facebook</span></button>
    <button type="button" title="' . __('Share on Linkedin') . '" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary rounded-circle btn-social" data-sharer="linkedin" data-url="' . $url . '"><i class="icon-linkedin" aria-hidden="true"></i> <span class="sr-only">Share on Linkedin</span></button>
    <button type="button" title="' . __('Share on Pinterest') . '" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary rounded-circle btn-social" data-sharer="pinterest" data-url="' . $url . '"><i class="icon-pinterest2" aria-hidden="true"></i> <span class="sr-only">Share on Pinterest</span></button>
    <button type="button" title="' . __('Share on Tumblr') . '" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary rounded-circle btn-social" data-sharer="tumblr" data-caption="' . $title . '" data-title="' . $title . '" data-tags="' . $hashtags . '" data-url="' . $url . '"><i class="icon-tumblr" aria-hidden="true"></i> <span class="sr-only">Share on Tumblr</span></button>
    <button type="button" title="' . __('Share on Reddit') . '" data-toggle="tooltip" class="btn btn-sm btn-outline-secondary rounded-circle btn-social" data-sharer="reddit" data-url="' . $url . '"><i class="icon-reddit" aria-hidden="true"></i> <span class="sr-only">Share on Reddit</span></button>';
}

/**
 * Application Type.
 *
 * Defined Application Type.
 *
 * @return array Array of Application Type.
 */
function applicationType()
{
    return array(
        'registration' => __('Registration'),
        'renewal' => __('Renewal'),
    );
}

/**
 * Vessel Type.
 *
 * Defined Vessel Type.
 *
 * @return array Array of Vessel Type.
 */
function vesselType($vesselType = null)
{
    $vesselTypeList = array(
        'main-vessel' => __('Main Vessel'),
        'associate-vessel' => __('Associate Vessel'),
    );

    if (!is_null($vesselType)) {
        return $vesselTypeList[$vesselType];
    }

    return $vesselTypeList;
}

/*
 * Translation-ready Configurations.
 * Make Configuration Label Translation-ready
 *
 * @param array $array Config array.
 *
 * @return array
 */
function translationReadyConfig($array)
{
    $ready = array();

    foreach ($array as $key => $label) {
        $ready[$key] = __($label);
    }

    return $ready;
}

/**
 * Star Rating (Input)
 *
 * @param boolean $isRequired If required, true, otherwise false.
 * @param integer $rating     Optionally pass the rating on the edit mode.
 */
function starRating($isRequired = false, $rating = null)
{
    if (null !== $rating) {
        if (in_array($rating, range(1, 5))) {
            $rating = abs(intval($rating));
        } else {
            $rating = null;
        }
    }

    echo '<div class="star-rating">';
    // Reversed. We will align them using CSS flexbox.
    for ($i = 5; $i >= 1; $i--):
        $_checked = $rating === $i ? 'checked="checked"' : '';
        $_required = $isRequired ? 'required' : '';
        echo '<input type="radio" class="star-rating-input" name="rating" value="' . $i . '" id="star-rating-' . $i . '" ' . $_required . ' ' . $_checked . '>';
        echo '<label class="star-rating-label" for="star-rating-' . $i . '">';
        echo '<span class="sr-only">' . __('Rate :rate', ['rate' => $i]) . '</span>';
        echo '</label>';
    endfor;
    echo '</div>';
}

/**
 * Display the Star Rating (view)
 *
 * @param integer $rating The rating in integer.
 */
function displayStarRating($rating)
{
    $rating = empty($rating) ? 0 : intval($rating);

    $limit = 5;
    $rating = $rating > $limit ? $limit : $rating; // Set the limit.
    $empty = $limit - $rating;

    ob_start();
    for ($i = 1; $i <= $rating; $i++) {
        echo '<i class="icon-star-full2" aria-hidden="true"></i>';
    }
    for ($i = 1; $i <= $empty; $i++) {
        echo '<i class="icon-star-empty3" aria-hidden="true"></i>';
    }
    echo ob_get_clean();
}

/**
 * Get Other Fee Type
 *
 */
function getFeeType($feeType = null)
{
    $feeTypeList = array(
        'guide' => __('Guide'),
        'guard' => __('Guard'),
        'crew' => __('Crew'),
        'others' => __('Others'),
    );

    if (!is_null($feeType)) {
        if (!empty($feeType)) {
            return $feeTypeList[$feeType];
        } else {
            return '__';
        }

    }

    return $feeTypeList;
}

/**
 * Get Visit Type
 *
 */
function getVisitType($visitType = null)
{
    $visitTypeList = array(
        '1' => __('Recreational Travel'),
        '2' => __('Pilgrim Travel'),
    );

    if (!is_null($visitType)) {
        return $visitTypeList[$visitType];
    }

    return $visitTypeList;
}

/**
 * Get Visit Type
 *
 */
function getFlightType($flightType = null)
{
    $flightTypeList = array(
        '1' => __('The conventional route to travel to the Sundarbans'),
        '2' => __('The conventional route to Rasmela'),
    );

    if (!is_null($flightType)) {
        return $flightTypeList[$flightType];
    }

    return $flightTypeList;
}

/**
 * Get Visit Type
 *
 */
function getCommonLabelNameById($flightType = null)
{
    $flightTypeList = array(
        '1' => __('The conventional route to travel to the Sundarbans'),
        '2' => __('The conventional route to Rasmeela'),
    );

    if (!is_null($flightType)) {
        return $flightTypeList[$flightType];
    }

    return $flightTypeList;
}

/**
 * Feature Type.
 *
 * Defined Feature Type.
 *
 * @return array Array of Feature Type.
 */
function featureType($featureType = null)
{
    $featureTypes = array(
        'letter' => __('Letter'),
        'sms' => __('SMS'),
        'email' => __('Email'),
    );

    if (!is_null($featureType)) {
        return $featureTypes[$featureType];
    }

    return $featureTypes;
}

function concateValueToEachItemOfArray(array $arrayData, $concatItem)
{
    $message = "";
    foreach ($arrayData as $key => $value) {
        $message .= $concatItem . " " . $value . "\r\n"; //concatinate your existing array with new one
    }

    return $message;
}

<?php
/**
 * @package Pagination
 * @version 3.0
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace Rundiz\Pagination;

/**
 * Set the total number of records, number of displaying items per page, then render the page numbers with links.<br>
 * This class support to render pages in these styles.<br>
 * First Previous Next Last<br>
 * Previous Next<br>
 * First Previous 1 2 3 4 [5] 6 7 8 9 Next Last<br>
 * Previous 1 2 3 4 [5] 6 7 8 9 Next<br>
 * 1 2 3 4 [5] 6 7 8 9<br>
 * First Previous 1 2 ... 4 [5] 6 ... 8 9 Next Last<br>
 * Previous 1 2 ... 4 [5] 6 ... 8 9 Next<br>
 * 1 2 ... 4 [5] 6 ... 8 9<br>
 * First Previous 4 [5] 6 ... 8 9 Next Last<br>
 * Previous 4 [5] 6 ... 8 9 Next<br>
 * 4 [5] 6 ... 8 9<br>
 * First Previous 1 2 ... 4 [5] 6 Next Last<br>
 * Previous 1 2 ... 4 [5] 6 Next<br>
 * 1 2 ... 4 [5] 6<br>
 * Or any custom styles that you can build on your own.
 * 
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT MIT
 */
class Pagination
{


    // Options properties. The properties that are the options of this class. -------------------------------------------
    // These options are use for override or change default values, some options are required to set before run.
    /**
     * @var string (Required property.) The URL for use when generate page numbers with links. Set the position where page numbers will be appears as URI segment or query string with %PAGENUMBER% placeholder.<br>
     * Example 1: `http://domain.tld/my-category/page/%PAGENUMBER%` This URL use page number as URI segment<br>
     * Example 2: `http://domain.tld/my-category?page=%PAGENUMBER%` This URL use page number as query string<br>
     * Example 3: `http://domain.tld/my-category?filter=some_filter_values&amp;search=foobar&amp;page=%PAGENUMBER%` This URL use page number as query string with other query strings in it, seperate each query string with `&amp;` not just `&`.<br>
     * Example 4: `http://domain.tld/my-category?start=%PAGENUMBER%` This URL use page number as query string but use start as the name.<br>
     * You have to get the page number value and set its value to this class by call the "page_number_value" property.
     */
    public $base_url;

    /**
     * @var integer (Required property.) The total number of records. This means "all" records by conditions with out the "LIMIT" or slices commands.
     */
    public $total_records;

    /**
     * @var integer The number of items that will be displaying per page. Such as number of articles to display in each page.
     */
    public $items_per_page = 10;

    /**
     * @var string The page number type. The value can be start_num or page_num. Start number or start_num. (eg. page number value will be 0, 10, 20, 30, ...) Page number or page_num. (eg. page number value will be 1, 2, 3, 4, ...)<br>
     * This class cannot detect page number value automatically because of dynamic styles of URL. So, you have to manually set its value to the "page_number_value" property.
     */
    public $page_number_type = 'start_num';

    /**
     * @var integer (Required property.) The current page number value. This class cannot detect current page number automatically because of dynamic styles of URL. So, you have to manually set its value to this property.
     */
    public $page_number_value;

    /**
     * @var string The overall tag open. It will be place at the very beginning of displaying page numbers.
     */
    public $overall_tag_open = '';
    /**
     * @var string The overall tag close. It will be place at the very end of displaying page numbers.
     */
    public $overall_tag_close = '';

    /**
     * @var mixed The link text of the paginate that will go to the first page. Set to false to not displaying first page link.
     */
    public $first_page_text = '&laquo; First';
    /**
     * @var boolean If you are at first page the first page link will not show if you set this value to false, if you set to true it will be always show the first page link.
     */
    public $first_page_always_show = false;
    /**
     * @var string The first page tag open. If you set to display first page, this will be placed before link to the first page.
     */
    public $first_tag_open = ' ';
    /**
     * @var string The first page tag close. If you set to display first page, this will be placed after link to the first page.
     */
    public $first_tag_close = ' ';

    /**
     * @var mixed The link text of the paginate that will go to the last page. Set to false to not displaying last page link.
     */
    public $last_page_text = 'Last &raquo;';
    /**
     * @var boolean If you are at last page the last page link will not show if you set this value to false, if you set to true it will be always show the last page link.
     */
    public $last_page_always_show = false;
    /**
     * @var string The last page tag open. If you set to display last page, this will be placed before link to the last page.
     */
    public $last_tag_open = ' ';
    /**
     * @var string The last page tag close. If you set to display last page, this will be placed after link to the last page.
     */
    public $last_tag_close = ' ';

    /**
     * @var mixed The link text of the paginate that will go to the next page. Set to false to not displaying next page link.
     */
    public $next_page_text = 'Next &rsaquo;';
    /**
     * @var boolean If you are at last page the next page link will not show if you set this value to false, if you set to true it will be always show the next page link.
     */
    public $next_page_always_show = false;
    /**
     * @var string The next page tag open. If you set to display next page, this will be placed before link to the next page.
     */
    public $next_tag_open = ' ';
    /**
     * @var string The next page tag close. If you set to display next page, this will be placed after link to the next page.
     */
    public $next_tag_close = ' ';

    /**
     * @var mixed The link text of the paginate that will go to the previous page. Set to false to not displaying previous page link.
     */
    public $previous_page_text = '&lsaquo; Previous';
    /**
     * @var boolean If you are at first page the previous page link will not show if you set this value to false, if you set to true it will be always show the previous page link.
     */
    public $previous_page_always_show = false;
    /**
     * @var string The previous page tag open. If you set to display previous page, this will be placed before link to the previous page.
     */
    public $previous_tag_open = ' ';
    /**
     * @var string The previous page tag close. If you set to display previous page, this will be placed after link to the previous page.
     */
    public $previous_tag_close = ' ';

    /**
     * @var boolean Display current link at current page. Set to true to display, false not to display.
     */
    public $current_page_link = false;
    /**
     * @var string The current page tag open. If you set to display current page, this will be placed before link to the current page.
     */
    public $current_tag_open = ' <strong>';
    /**
     * @var string The current page tag close. If you set to display current page, this will be placed after link to the current page.
     */
    public $current_tag_close = '</strong> ';

    /**
     * @var boolean Display the page numbers or not. Set to true to display, false not to display.
     */
    public $number_display = true;
    /**
     * @var string The page number tag open. If you set to display page number, this will be placed before link to the page number.
     */
    public $number_tag_open = ' ';
    /**
     * @var string The page number tag close. If you set to display page number, this will be placed after link to the page number.
     */
    public $number_tag_close = ' ';
    /**
     * @var integer The number of adjacent pages before and after the current page.
     */
    public $number_adjacent_pages = 5;

    /**
     * @var boolean Display unavailable page (...) or not. Set to true to display, false to not display.
     */
    public $unavailable_display = false;
    /**
     * @var string The unavailable page text. Basically it is something to show that there are pages between these range such as 3 dots text. (...)
     */
    public $unavailable_text = '&hellip;';
    /**
     * @var string The unavailable page tag open. If you set to display unavailable page, this will be placed before unavailable page (...).
     */
    public $unavailable_tag_open = ' ';
    /**
     * @var string The unavailable page tag close. If you set to display unavailable page, this will be placed after unavailable page (...).
     */
    public $unavailable_tag_close = ' ';
    /**
     * @var mixed Number of pages to display before first unavailable page. Set number as integer or set to false to not display the pages before unavailable.
     */
    public $unavailable_before = 2;
    /**
     * @var mixed Number of pages to display after last unavailable page. Set number as integer or set to false to not display the pages after unavailable.
     */
    public $unavailable_after = 2;
    // End options properties -----------------------------------------------------------------------------------------------

    /**
     * @var array Temporary property for store the original properties.
     */
    protected $temp_properties;

    /**
     * @var integer Total pages that was calculated from total records and items per page. This is for use in programatic only.
     */
    protected $total_pages;


    public function __construct()
    {
        $class_properties = get_class_vars(__CLASS__);
        $this->temp_properties = $class_properties;
        unset($class_properties);
    }// __construct


    public function __destruct()
    {
        $this->clear();
    }// __destruct


    /**
     * Clear and reset properties to the default values.
     */
    public function clear()
    {
        if (is_array($this->temp_properties)) {
            foreach ($this->temp_properties as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
            unset($key, $value);

            $this->temp_properties = null;
        }
    }// clear


    /**
     * Create pagination links and return the html data.
     * 
     * @param string $result_string The pagination results. Add these place holders for replace with content while generating pages. (:total_pages, :page_number_type, :current_page_number_displaying, :pagination).
     * @return string Return created pagination links in HTML format.
     */
    public function createLinks($result_string = 'Displaying page :current_page_number_displaying of :total_pages<br>:pagination')
    {
        $pagination_data = $this->getPaginationData();

        if (!is_array($pagination_data)) {
            return null;
        }

        if ($result_string == null) {
            $result_string = 'Displaying page :current_page_number_displaying of :total_pages<br>:pagination';
        }

        // render the pagination. --------------------------------------------------------------------------------------------
        $pagination_rendered = "\n";
        if (array_key_exists('overall_tag_open', $pagination_data)) {
            $pagination_rendered .= $pagination_data['overall_tag_open'];
        }
        if (array_key_exists('generated_pages', $pagination_data) && is_array($pagination_data['generated_pages'])) {
            foreach ($pagination_data['generated_pages'] as $page_key => $page_item) {
                if (is_array($page_item)) {
                    if (array_key_exists('tag_open', $page_item)) {
                        $pagination_rendered .= $page_item['tag_open'];
                    }
                    if (array_key_exists('link', $page_item) && $page_item['link'] != null) {
                        $pagination_rendered .= '<a href="' . $page_item['link'] . '">';
                    }
                    if (array_key_exists('text', $page_item)) {
                        $pagination_rendered .= $page_item['text'];
                    }
                    if (array_key_exists('link', $page_item) && $page_item['link'] != null) {
                        $pagination_rendered .= '</a>';
                    }
                    if (array_key_exists('tag_close', $page_item)) {
                        $pagination_rendered .= $page_item['tag_close'];
                    }
                }
            }
            unset($page_item, $page_key);
        }
        if (array_key_exists('overall_tag_close', $pagination_data)) {
            $pagination_rendered .= $pagination_data['overall_tag_close'];
        }
        $pagination_rendered .= "\n";
        // end render the pagination ----------------------------------------------------------------------------------------

        $output = $result_string;
        if (array_key_exists('total_pages', $pagination_data)) {
            $output = str_replace(':total_pages', $pagination_data['total_pages'], $output);
        }
        if (array_key_exists('page_number_type', $pagination_data)) {
            $output = str_replace(':page_number_type', $pagination_data['total_pages'], $output);
        }
        if (array_key_exists('current_page_number_displaying', $pagination_data)) {
            $output = str_replace(':current_page_number_displaying', $pagination_data['current_page_number_displaying'], $output);
        }
        if (isset($pagination_rendered)) {
            $output = str_replace(':pagination', $pagination_rendered, $output);
        }

        unset($pagination_data, $pagination_rendered);
        return $output;
    }// createLinks


    /**
     * Generate pagination URL.
     * 
     * @param integer $page_value Page number value.
     * @param string $direction The direction can be first, previous, next, last, number.
     * @param boolean $return_value_ony Set to true to return the page value only. Set to false to return as URL.
     * @return string Return generated URL.
     */
    private function generateUrl($page_value, $direction = '', $return_value_only = false)
    {
        switch ($direction) {
            case 'first':
                if ($this->page_number_type == 'start_num') {
                    $page_value = 0;
                } elseif ($this->page_number_type == 'page_num') {
                    $page_value = 1;
                }
                break;
            case 'previous':
                if ($this->page_number_type == 'start_num') {
                    $page_value = ($page_value - $this->items_per_page);
                    if ($page_value < 0) {
                        $page_value = 0;
                    }
                } elseif ($this->page_number_type == 'page_num') {
                    $page_value = ($page_value - 1);
                    if ($page_value <= 0) {
                        $page_value = 1;
                    }
                }
                break;
            case 'next':
                if ($this->page_number_type == 'start_num') {
                    $page_value = ($page_value + $this->items_per_page);
                    if ($page_value > (($this->total_pages*$this->items_per_page) - $this->items_per_page)) {
                        $page_value = (($this->total_pages*$this->items_per_page) - $this->items_per_page);
                    }
                } elseif ($this->page_number_type == 'page_num') {
                    $page_value = ($page_value + 1);
                    if ($page_value > (($this->total_pages*$this->items_per_page) / $this->items_per_page)) {
                        $page_value = (($this->total_pages*$this->items_per_page) / $this->items_per_page);
                    }
                }
                break;
            case 'last':
                if ($this->page_number_type == 'start_num') {
                    $page_value = ($page_value - $this->items_per_page);
                } elseif ($this->page_number_type == 'page_num') {
                    $page_value = ($page_value / $this->items_per_page);
                }
                break;
            default:
                // calculate page querystring number from generated before and after current pages. example 1 2 [3] 4 5 current is 3, generated is 1 2 and 4 5
                if ($this->page_number_type == 'start_num') {
                    $page_value = (($page_value * $this->items_per_page) - $this->items_per_page);
                }
                break;
        }

        if ($return_value_only === true) {
            return $page_value;
        } else {
            return str_replace('%PAGENUMBER%', $page_value, $this->base_url);
        }
    }// generateUrl


    /**
     * Generate the pagination data for render in HTML.<br>
     * You can call to this method directly if you want to render pagination on your own styles.
     * 
     * @return array Return generated pagination data as array.
     * @throws \Exception Throw the errors on failure such as some required property is missing.
     */
    public function getPaginationData()
    {
        // Validate correct properties.
        $this->validateCorrectProperties();
        // Validate required properties. If something is missing, it throw the errors.
        $this->validateRequiredProperties();

        // Prepare output
        $output = array();

        $this->total_pages = ceil($this->total_records/$this->items_per_page);
        $output['total_pages'] = $this->total_pages;

        $output['page_number_type'] = $this->page_number_type;
        $output['current_page_number_displaying'] = 1;
        // Set current page number "displaying". The current page number displaying will be always start from 1 even its type is start_num.
        // The page number "displaying" is not the same with page number value. The page number value is up to page_number_type settings while page number "displaying" is always like this. 1, 2, 3, ...
        if ($this->page_number_type == 'start_num') {
            // Page number type display page query string or URI as 0, 10, 20, 30, ...
            $output['current_page_number_displaying'] = ceil(($this->page_number_value/$this->items_per_page)+1);
        } elseif ($this->page_number_type == 'page_num') {
            // Page number type display page query string or URI as 1, 2, 3, 4, ...
            $output['current_page_number_displaying'] = $this->page_number_value;
        }
        if ($output['current_page_number_displaying'] > $this->total_pages) {
            $output['current_page_number_displaying'] = $this->total_pages;
        } elseif ($output['current_page_number_displaying'] < 1) {
            $output['current_page_number_displaying'] = 1;
        }

        $output['overall_tag_open'] = $this->overall_tag_open;
        // Prepare for generate all pages by settings. -------------------------------
        $generated_pages = array();

        // First page link
        $generated_pages['first_page'] = array();
        $generated_pages['first_page']['tag_open'] = null;
        $generated_pages['first_page']['link'] = null;
        $generated_pages['first_page']['page_value'] = null;
        $generated_pages['first_page']['text'] = null;
        $generated_pages['first_page']['tag_close'] = null;
        if ($this->first_page_text !== false) {
            if ($this->first_page_always_show === true || ($this->first_page_always_show === false && !$this->isCurrentlyFirstPage())) {
                $generated_pages['first_page']['tag_open'] = $this->first_tag_open;
                $generated_pages['first_page']['link'] = $this->generateUrl($this->page_number_value, 'first');
                $generated_pages['first_page']['page_value'] = $this->generateUrl($this->page_number_value, 'first', true);
                $generated_pages['first_page']['text'] = $this->first_page_text;
                $generated_pages['first_page']['tag_close'] = $this->first_tag_close;
            }
        }

        // Previous page link
        $generated_pages['previous_page'] = array();
        $generated_pages['previous_page']['tag_open'] = null;
        $generated_pages['previous_page']['link'] = null;
        $generated_pages['previous_page']['page_value'] = null;
        $generated_pages['previous_page']['text'] = null;
        $generated_pages['previous_page']['tag_close'] = null;
        if ($this->previous_page_text !== false) {
            if ($this->previous_page_always_show === true || ($this->previous_page_always_show === false && !$this->isCurrentlyFirstPage())) {
                $generated_pages['previous_page']['tag_open'] = $this->previous_tag_open;
                $generated_pages['previous_page']['link'] = $this->generateUrl($this->page_number_value, 'previous');
                $generated_pages['previous_page']['page_value'] = $this->generateUrl($this->page_number_value, 'previous', true);
                $generated_pages['previous_page']['text'] = $this->previous_page_text;
                $generated_pages['previous_page']['tag_close'] = $this->previous_tag_close;
            }
        }

        // Numbering pages.
        if ($this->number_display === true) {
            // Pages before unavailable
            if ($this->unavailable_display === true && $this->unavailable_before !== false && ($output['current_page_number_displaying'] - $this->number_adjacent_pages) > 1) {
                $number_adjacent_before_current = ($output['current_page_number_displaying'] - $this->number_adjacent_pages);
                $number_before_unavailable =  $this->unavailable_before;
                if ($number_adjacent_before_current <= $number_before_unavailable) {
                    $number_before_unavailable = ($number_adjacent_before_current - 1);
                }
                for ($i = 1; $i <= $number_before_unavailable; $i++) {
                    $generated_pages[$i] = array();
                    $generated_pages[$i]['tag_open'] = $this->number_tag_open;
                    $generated_pages[$i]['link'] = $this->generateUrl($i);
                    $generated_pages[$i]['page_value'] = $this->generateUrl($i, '', true);
                    $generated_pages[$i]['text'] = $i;
                    $generated_pages[$i]['tag_close'] = $this->number_tag_close;
                }
                if ($number_adjacent_before_current > ($number_before_unavailable + 1)) {
                    $generated_pages['unavailable_before'] = array();
                    $generated_pages['unavailable_before']['tag_open'] = $this->unavailable_tag_open;
                    $generated_pages['unavailable_before']['link'] = null;
                    $generated_pages['unavailable_before']['page_value'] = null;
                    $generated_pages['unavailable_before']['text'] = $this->unavailable_text;
                    $generated_pages['unavailable_before']['tag_close'] = $this->unavailable_tag_close;
                }
                unset($i, $number_adjacent_before_current, $number_before_unavailable);
            }// endif; unavailable before.

            // Adjacent pages before current
            if ($this->number_adjacent_pages > 0) {
                for ($i = ($output['current_page_number_displaying'] - $this->number_adjacent_pages); $i < $output['current_page_number_displaying']; $i++) {
                    if ($i > 0) {
                        $generated_pages[$i] = array();
                        $generated_pages[$i]['tag_open'] = $this->number_tag_open;
                        $generated_pages[$i]['link'] = $this->generateUrl($i);
                        $generated_pages[$i]['page_value'] = $this->generateUrl($i, '', true);
                        $generated_pages[$i]['text'] = $i;
                        $generated_pages[$i]['tag_close'] = $this->number_tag_close;
                    }
                }
            }

            // The current page
            $generated_pages[$i] = array();
            $generated_pages[$i]['tag_open'] = $this->current_tag_open;
            $generated_pages[$i]['link'] = ($this->current_page_link === true ? $this->generateUrl($i) : null);
            $generated_pages[$i]['page_value'] = $this->generateUrl($i, '', true);
            $generated_pages[$i]['text'] = $output['current_page_number_displaying'];
            $generated_pages[$i]['tag_close'] = $this->current_tag_close;

            // Adjacent pages after current
            if ($this->number_adjacent_pages > 0) {
                for ($i = ($output['current_page_number_displaying'] + 1); $i <= ($output['current_page_number_displaying'] + $this->number_adjacent_pages); $i++) {
                    if ($i <= $this->total_pages) {
                        $generated_pages[$i] = array();
                        $generated_pages[$i]['tag_open'] = $this->number_tag_open;
                        $generated_pages[$i]['link'] = $this->generateUrl($i);
                        $generated_pages[$i]['page_value'] = $this->generateUrl($i, '', true);
                        $generated_pages[$i]['text'] = $i;
                        $generated_pages[$i]['tag_close'] = $this->number_tag_close;
                    }
                }
            }

            // Pages after unavailable
            if ($this->unavailable_display === true && $this->unavailable_after !== false && ($output['current_page_number_displaying'] + $this->number_adjacent_pages) < $this->total_pages) {
                $number_adjacent_after_current = ($output['current_page_number_displaying'] + $this->number_adjacent_pages);
                $number_after_unavailable = ($this->total_pages - ($this->unavailable_after - 1));
                if ($number_adjacent_after_current >= $number_after_unavailable) {
                    $number_after_unavailable = ($number_adjacent_after_current + 1);
                }
                if (($number_adjacent_after_current + 1) < $number_after_unavailable) {
                    $generated_pages['unavailable_after'] = array();
                    $generated_pages['unavailable_after']['tag_open'] = $this->unavailable_tag_open;
                    $generated_pages['unavailable_after']['link'] = null;
                    $generated_pages['unavailable_after']['page_value'] = null;
                    $generated_pages['unavailable_after']['text'] = $this->unavailable_text;
                    $generated_pages['unavailable_after']['tag_close'] = $this->unavailable_tag_close;
                }
                for ($i = $number_after_unavailable; $i <= $this->total_pages; $i++) {
                    $generated_pages[$i] = array();
                    $generated_pages[$i]['tag_open'] = $this->number_tag_open;
                    $generated_pages[$i]['link'] = $this->generateUrl($i);
                    $generated_pages[$i]['page_value'] = $this->generateUrl($i, '', true);
                    $generated_pages[$i]['text'] = $i;
                    $generated_pages[$i]['tag_close'] = $this->number_tag_close;
                }
                unset($i, $number_adjacent_after_current, $number_after_unavailable);
            }// endif; unavailable after.
        }// endif; display numbers.

        // Next page link
        $generated_pages['next_page'] = array();
        $generated_pages['next_page']['tag_open'] = null;
        $generated_pages['next_page']['link'] = null;
        $generated_pages['next_page']['page_value'] = null;
        $generated_pages['next_page']['text'] = null;
        $generated_pages['next_page']['tag_close'] = null;
        if ($this->next_page_text !== false) {
            if ($this->next_page_always_show === true || ($this->next_page_always_show === false && !$this->isCurrentlyLastPage())) {
                $generated_pages['next_page']['tag_open'] = $this->next_tag_open;
                $generated_pages['next_page']['link'] = $this->generateUrl($this->page_number_value, 'next');
                $generated_pages['next_page']['page_value'] = $this->generateUrl($this->page_number_value, 'next', true);
                $generated_pages['next_page']['text'] = $this->next_page_text;
                $generated_pages['next_page']['tag_close'] = $this->next_tag_close;
            }
        }

        // Last page link
        $generated_pages['last_page'] = array();
        $generated_pages['last_page']['tag_open'] = null;
        $generated_pages['last_page']['link'] = null;
        $generated_pages['last_page']['page_value'] = null;
        $generated_pages['last_page']['text'] = null;
        $generated_pages['last_page']['tag_close'] = null;
        if ($this->last_page_text !== false) {
            if ($this->last_page_always_show === true || ($this->last_page_always_show === false && !$this->isCurrentlyLastPage())) {
                $generated_pages['last_page']['tag_open'] = $this->last_tag_open;
                $generated_pages['last_page']['link'] = $this->generateUrl(($this->total_pages*$this->items_per_page), 'last');
                $generated_pages['last_page']['page_value'] = $this->generateUrl(($this->total_pages*$this->items_per_page), 'last', true);
                $generated_pages['last_page']['text'] = $this->last_page_text;
                $generated_pages['last_page']['tag_close'] = $this->last_tag_close;
            }
        }

        $output['generated_pages'] = $generated_pages;
        unset($generated_pages);
        // End prepare for generate all pages by settings. ---------------------------
        $output['overall_tag_close'] = $this->overall_tag_close;

        return $output;
    }// getPaginationData


    /**
     * Is it currently in the first page?
     * 
     * @return boolean Return true if the page that is displaying is first page, return false if otherwise.
     */
    private function isCurrentlyFirstPage()
    {
        if ($this->page_number_type === 'start_num') {
            if ($this->page_number_value === 0) {
                return true;
            }
            return false;
        } elseif ($this->page_number_type === 'page_num') {
            if ($this->page_number_value === 1) {
                return true;
            }
            return false;
        }
        return false;
    }// isCurrentlyFirstPage


    /**
     * Is it currently in the last page?
     * 
     * @return boolean Return true if the page that is displaying is last page, return false if otherwise.
     */
    private function isCurrentlyLastPage()
    {
        if ($this->page_number_type === 'start_num') {
            if ($this->page_number_value >= ($this->total_pages*$this->items_per_page)-$this->items_per_page) {
                return true;
            }
            return false;
        } elseif ($this->page_number_type === 'page_num') {
            if ($this->page_number_value >= $this->total_pages) {
                return true;
            }
            return false;
        }
        return false;
    }// isCurrentlyLastPage


    /**
     * Validate that properties values have correct data and type.
     * It will be set to default if the values are incorrect.
     */
    private function validateCorrectProperties()
    {
        if (!is_bool($this->current_page_link)) {
            $this->current_page_link = false;
        }

        if (!is_bool($this->first_page_always_show)) {
            $this->first_page_always_show = false;
        }

        if (is_numeric($this->items_per_page)) {
            $this->items_per_page = intval($this->items_per_page);
            if ($this->items_per_page <= 0) {
                $this->items_per_page = 10;
            }
        } elseif (!is_numeric($this->items_per_page)) {
            $this->items_per_page = 10;
        }

        if (!is_bool($this->last_page_always_show)) {
            $this->last_page_always_show = false;
        }

        if (!is_bool($this->next_page_always_show)) {
            $this->next_page_always_show = false;
        }

        if (is_numeric($this->number_adjacent_pages)) {
            $this->number_adjacent_pages = intval($this->number_adjacent_pages);
            if ($this->number_adjacent_pages < 0) {
                $this->number_adjacent_pages = 5;
            }
        } else {
            $this->number_adjacent_pages = 5;
        }

        if (!is_bool($this->number_display)) {
            $this->number_display = false;
        }

        if ($this->page_number_type != 'start_num' && $this->page_number_type != 'page_num') {
            $this->page_number_type = 'start_num';
        }

        if (!is_bool($this->previous_page_always_show)) {
            $this->previous_page_always_show = false;
        }

        if (is_numeric($this->unavailable_after)) {
            $this->unavailable_after = intval($this->unavailable_after);
            if ($this->unavailable_after <= 0) {
                $this->unavailable_after = 2;
            }
        } else {
            $this->unavailable_after = false;
        }

        if (is_numeric($this->unavailable_before)) {
            $this->unavailable_before = intval($this->unavailable_before);
            if ($this->unavailable_before <= 0) {
                $this->unavailable_before = 2;
            }
        } else {
            $this->unavailable_before = false;
        }

        if (!is_bool($this->unavailable_display)) {
            $this->unavailable_display = false;
        }
    }// validateCorrectProperties


    /**
     * Validate required properties values.
     * 
     * @throws \Exception Throw the errors on failure such as some required property is missing.
     */
    private function validateRequiredProperties()
    {
        if ($this->base_url == null) {
            throw new \Exception('The base_url property was not set.');
        }

        if (is_numeric($this->total_records)) {
            $this->total_records = intval($this->total_records);
        } else {
            throw new \Exception('The total_records property was not set.');
        }

        if (!is_numeric($this->page_number_value)) {
            throw new \Exception('The page_number_value property was not set.');
        }
    }// validateRequiredProperties


}

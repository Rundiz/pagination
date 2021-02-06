<?php

// You must require the class file if you did not install this class via Composer.
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Rundiz' . DIRECTORY_SEPARATOR . 'Pagination' . DIRECTORY_SEPARATOR . 'Pagination.php';


// This test will not use database, it uses dummy data.
require __DIR__ . DIRECTORY_SEPARATOR . 'includes-dummy-data.php';


$page_number_type = 'start_num';
$total_records = count($data);
$start = (isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0);
if ($start < 0) {
    $start = 0;
}
$limit = (isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 10);
if ($limit > 50) {
    $limit = 50;
} elseif ($limit < 1) {
    $limit = 1;
}
// Warning! The offset value between start_num style and page_num style are different, please use carefully.
$offset = $start;


// Slice data array to limited per page and start by number of $start
// In most real project this will be replaced with SQL query and its OFFSET, LIMIT commands.
$newdata = array_slice($data, $offset, $limit);
unset($data);


// Setup base URL and generate dynamic query string. In your real project, replace these code with your code if you have different way to setup base URL.
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?start=%PAGENUMBER%';
// generate dynamic query string except the one that has name "start" which is pagination query string name.
$query_string_array = array();
if (isset($_GET) && is_array($_GET)) {
    foreach ($_GET as $key => $value) {
        if ($key != 'start') {
            $query_string_array[$key] = $value;
        }
    }// endforeach;
    unset($key, $value);
}
if (!empty($query_string_array)) {
    $base_url .= '&amp;' . http_build_query($query_string_array, null, '&amp;');
}
unset($query_string_array);
// end generate dynamic query string. ----------------------------------------------------------------------------------


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Pagination test</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Pagination test</h1>
        <h2>Full options test, Page number type as start number</h2>
        <p>The page value will be start at 0 and next start is total items that were displayed. Example: 0, 10, 20, 30 for display 10 items per page.</p>
        <p>
            Total records = <?php echo $total_records; ?><br />
            Display items per page = <?php echo $limit; ?><br />
            Use page number type as <strong>start number</strong> (0, 10, 20, 30, ...)
        </p>
        <div class="filters text-left">
            Limit items per page:
            <select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                <?php for ($i = 10; $i <= 50; $i += 10) { ?>
                <option<?php if (isset($limit) && $i == $limit) {echo ' selected="selected"';} ?> value="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?limit=' . $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
            </select>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>IP Address</th>
                </tr>
            </tfoot>
            <tbody>
                <?php
                $i = 1;
                foreach ($newdata as $key => $item) {
                    ?> 
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td><?php echo $item['first_name'].' '.$item['last_name']; ?></td>
                        <td><?php echo $item['gender']; ?></td>
                        <td><?php echo $item['email']; ?></td>
                        <td><?php echo $item['ip_address']; ?></td>
                    </tr>
                    <?php
                    $i++;
                    if ($i > 500) {
                        // Prevent damage to the system because list too many items per page.
                        // This condition can be removed in real project's source code.
                        break;
                    }
                } // endforeach;
                unset($item, $key, $newdata);
                ?> 
            </tbody>
        </table>
        <div class="example-paginations-block">
            <h3>Pagination styles</h3>
            <?php 
            if ($i > 500) {
                echo '<div class="alert">You are displaying too many items per page! This is for prevent your system use too much resources so it limits the items per page at 500.</div>'."\n";
            }
            ?> 
            <h4>Basic style</h4>
            <p>This example override <strong>all</strong> class&#039;s properties.</p>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                // Set options to the pagination class.
                $Pagination->base_url = $base_url;// *This property must be set.
                $Pagination->total_records = $total_records;// *This property must be set.
                $Pagination->page_number_value = $start;// *This property must be set.
                $Pagination->current_page_link = true;
                $Pagination->current_tag_close = '</strong>'."\n";
                $Pagination->current_tag_open = '<strong>';
                $Pagination->first_page_always_show = true;
                $Pagination->first_page_text = '|&lt;';
                $Pagination->first_tag_close = '</span>'."\n";
                $Pagination->first_tag_open = '<span class="first">';
                $Pagination->items_per_page = $limit;
                $Pagination->last_page_always_show = true;
                $Pagination->last_page_text = '&gt;|';
                $Pagination->last_tag_close = '</span>'."\n";
                $Pagination->last_tag_open = '<span class="last">';
                $Pagination->next_page_always_show = true;
                $Pagination->next_page_text = '&gt;';
                $Pagination->next_tag_close = '</span>'."\n";
                $Pagination->next_tag_open = '<span class="next">';
                $Pagination->number_adjacent_pages = 3;
                $Pagination->number_display = true;
                $Pagination->number_tag_close = '</span>'."\n";
                $Pagination->number_tag_open = '<span class="number">';
                $Pagination->overall_tag_close = '</div>';
                $Pagination->overall_tag_open = '<div class="basic-style-pagination">'."\n";
                $Pagination->page_number_type = $page_number_type;
                $Pagination->previous_page_always_show = true;
                $Pagination->previous_page_text = '&lt;';
                $Pagination->previous_tag_close = '</span>'."\n";
                $Pagination->previous_tag_open = '<span class="previous">';
                $Pagination->unavailable_after = 3;
                $Pagination->unavailable_before = 1;
                $Pagination->unavailable_display = true;
                $Pagination->unavailable_tag_close = '</span>'."\n";
                $Pagination->unavailable_tag_open = '<span class="unavailable">';
                $Pagination->unavailable_text = '..';
                // create pagination links.
                echo $Pagination->createLinks();
                // clear style.
                $Pagination->clear();
                ?> 
            </div>

            <h4>No first, last style</h4>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                // Set options to the pagination class.
                $Pagination->base_url = $base_url;// *This property must be set.
                $Pagination->total_records = $total_records;// *This property must be set.
                $Pagination->page_number_value = $start;// *This property must be set.
                $Pagination->current_page_link = true;
                $Pagination->current_tag_close = '</strong>'."\n";
                $Pagination->current_tag_open = '<strong>';
                $Pagination->first_page_always_show = false;
                $Pagination->first_page_text = false;
                //$Pagination->first_tag_close = '</span>'."\n";// while first_page_text is set to false this option is no need.
                //$Pagination->first_tag_open = '<span class="first">';// while first_page_text is set to false this option is no need.
                $Pagination->items_per_page = $limit;
                $Pagination->last_page_always_show = false;
                $Pagination->last_page_text = false;
                //$Pagination->last_tag_close = '</span>'."\n";// while last_page_text is set to false this option is no need.
                //$Pagination->last_tag_open = '<span class="last">';// while last_page_text is set to false this option is no need.
                $Pagination->next_page_always_show = true;
                $Pagination->next_page_text = '&gt;';
                $Pagination->next_tag_close = '</span>'."\n";
                $Pagination->next_tag_open = '<span class="next">';
                $Pagination->number_adjacent_pages = 3;
                $Pagination->number_display = true;
                $Pagination->number_tag_close = '</span>'."\n";
                $Pagination->number_tag_open = '<span class="number">';
                $Pagination->overall_tag_close = '</div>';
                $Pagination->overall_tag_open = '<div class="basic-style-pagination">'."\n";
                $Pagination->page_number_type = $page_number_type;
                $Pagination->previous_page_always_show = true;
                $Pagination->previous_page_text = '&lt;';
                $Pagination->previous_tag_close = '</span>'."\n";
                $Pagination->previous_tag_open = '<span class="previous">';
                $Pagination->unavailable_after = 3;
                $Pagination->unavailable_before = 1;
                $Pagination->unavailable_display = true;
                $Pagination->unavailable_tag_close = '</span>'."\n";
                $Pagination->unavailable_tag_open = '<span class="unavailable">';
                $Pagination->unavailable_text = '..';
                // create pagination links.
                echo $Pagination->createLinks(':pagination');
                // clear style.
                $Pagination->clear();
                ?> 
            </div>

            <h4>No first, last, previous, next style</h4>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                // Set options to the pagination class.
                $Pagination->base_url = $base_url;// *This property must be set.
                $Pagination->total_records = $total_records;// *This property must be set.
                $Pagination->page_number_value = $start;// *This property must be set.
                $Pagination->current_page_link = true;
                $Pagination->current_tag_close = '</strong>'."\n";
                $Pagination->current_tag_open = '<strong>';
                $Pagination->first_page_always_show = false;
                $Pagination->first_page_text = false;
                //$Pagination->first_tag_close = '</span>'."\n";// while first_page_text is set to false this option is no need.
                //$Pagination->first_tag_open = '<span class="first">';// while first_page_text is set to false this option is no need.
                $Pagination->items_per_page = $limit;
                $Pagination->last_page_always_show = false;
                $Pagination->last_page_text = false;
                //$Pagination->last_tag_close = '</span>'."\n";// while last_page_text is set to false this option is no need.
                //$Pagination->last_tag_open = '<span class="last">';// while last_page_text is set to false this option is no need.
                $Pagination->next_page_always_show = false;
                $Pagination->next_page_text = false;
                //$Pagination->next_tag_close = '</span>'."\n";// while next_page_text is set to false this option is no need.
                //$Pagination->next_tag_open = '<span class="next">';// while next_page_text is set to false this option is no need.
                $Pagination->number_adjacent_pages = 3;
                $Pagination->number_display = true;
                $Pagination->number_tag_close = '</span>'."\n";
                $Pagination->number_tag_open = '<span class="number">';
                $Pagination->overall_tag_close = '</div>';
                $Pagination->overall_tag_open = '<div class="basic-style-pagination">'."\n";
                $Pagination->page_number_type = $page_number_type;
                $Pagination->previous_page_always_show = false;
                $Pagination->previous_page_text = false;
                //$Pagination->previous_tag_close = '</span>'."\n";// while previous_page_text is set to false this option is no need.
                //$Pagination->previous_tag_open = '<span class="previous">';// while previous_page_text is set to false this option is no need.
                $Pagination->unavailable_after = 3;
                $Pagination->unavailable_before = 1;
                $Pagination->unavailable_display = true;
                $Pagination->unavailable_tag_close = '</span>'."\n";
                $Pagination->unavailable_tag_open = '<span class="unavailable">';
                $Pagination->unavailable_text = '..';
                // create pagination links.
                echo $Pagination->createLinks(':pagination');
                // clear style.
                $Pagination->clear();
                ?> 
            </div>

            <h4>No first, last, previous, next, unavailable before style</h4>
            <p>To view this style perfectly, please go to between page 6 - 96.</p>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                // Set options to the pagination class.
                $Pagination->base_url = $base_url;// *This property must be set.
                $Pagination->total_records = $total_records;// *This property must be set.
                $Pagination->page_number_value = $start;// *This property must be set.
                $Pagination->current_page_link = true;
                $Pagination->current_tag_close = '</strong>'."\n";
                $Pagination->current_tag_open = '<strong>';
                $Pagination->first_page_always_show = false;
                $Pagination->first_page_text = false;
                //$Pagination->first_tag_close = '</span>'."\n";// while first_page_text is set to false this option is no need.
                //$Pagination->first_tag_open = '<span class="first">';// while first_page_text is set to false this option is no need.
                $Pagination->items_per_page = $limit;
                $Pagination->last_page_always_show = false;
                $Pagination->last_page_text = false;
                //$Pagination->last_tag_close = '</span>'."\n";// while last_page_text is set to false this option is no need.
                //$Pagination->last_tag_open = '<span class="last">';// while last_page_text is set to false this option is no need.
                $Pagination->next_page_always_show = false;
                $Pagination->next_page_text = false;
                //$Pagination->next_tag_close = '</span>'."\n";// while next_page_text is set to false this option is no need.
                //$Pagination->next_tag_open = '<span class="next">';// while next_page_text is set to false this option is no need.
                $Pagination->number_adjacent_pages = 3;
                $Pagination->number_display = true;
                $Pagination->number_tag_close = '</span>'."\n";
                $Pagination->number_tag_open = '<span class="number">';
                $Pagination->overall_tag_close = '</div>';
                $Pagination->overall_tag_open = '<div class="basic-style-pagination">'."\n";
                $Pagination->page_number_type = $page_number_type;
                $Pagination->previous_page_always_show = false;
                $Pagination->previous_page_text = false;
                //$Pagination->previous_tag_close = '</span>'."\n";// while previous_page_text is set to false this option is no need.
                //$Pagination->previous_tag_open = '<span class="previous">';// while previous_page_text is set to false this option is no need.
                $Pagination->unavailable_after = 3;
                $Pagination->unavailable_before = false;
                $Pagination->unavailable_display = true;
                $Pagination->unavailable_tag_close = '</span>'."\n";
                $Pagination->unavailable_tag_open = '<span class="unavailable">';
                $Pagination->unavailable_text = '..';
                // create pagination links.
                echo $Pagination->createLinks(':pagination');
                // clear style.
                $Pagination->clear();
                ?> 
            </div>

            <h4>No first, last, previous, next, unavailable before, unavailable after style</h4>
            <p>To view this style perfectly, please go to between page 6 - 96.</p>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                // Set options to the pagination class.
                $Pagination->base_url = $base_url;// *This property must be set.
                $Pagination->total_records = $total_records;// *This property must be set.
                $Pagination->page_number_value = $start;// *This property must be set.
                $Pagination->current_page_link = true;
                $Pagination->current_tag_close = '</strong>'."\n";
                $Pagination->current_tag_open = '<strong>';
                $Pagination->first_page_always_show = false;
                $Pagination->first_page_text = false;
                //$Pagination->first_tag_close = '</span>'."\n";// while first_page_text is set to false this option is no need.
                //$Pagination->first_tag_open = '<span class="first">';// while first_page_text is set to false this option is no need.
                $Pagination->items_per_page = $limit;
                $Pagination->last_page_always_show = false;
                $Pagination->last_page_text = false;
                //$Pagination->last_tag_close = '</span>'."\n";// while last_page_text is set to false this option is no need.
                //$Pagination->last_tag_open = '<span class="last">';// while last_page_text is set to false this option is no need.
                $Pagination->next_page_always_show = false;
                $Pagination->next_page_text = false;
                //$Pagination->next_tag_close = '</span>'."\n";// while next_page_text is set to false this option is no need.
                //$Pagination->next_tag_open = '<span class="next">';// while next_page_text is set to false this option is no need.
                $Pagination->number_adjacent_pages = 3;
                $Pagination->number_display = true;
                $Pagination->number_tag_close = '</span>'."\n";
                $Pagination->number_tag_open = '<span class="number">';
                $Pagination->overall_tag_close = '</div>';
                $Pagination->overall_tag_open = '<div class="basic-style-pagination">'."\n";
                $Pagination->page_number_type = $page_number_type;
                $Pagination->previous_page_always_show = false;
                $Pagination->previous_page_text = false;
                //$Pagination->previous_tag_close = '</span>'."\n";// while previous_page_text is set to false this option is no need.
                //$Pagination->previous_tag_open = '<span class="previous">';// while previous_page_text is set to false this option is no need.
                $Pagination->unavailable_after = false;
                $Pagination->unavailable_before = false;
                //$Pagination->unavailable_display = true;// while both unavailable_before & unavailable_after is set to false this option is set to false by default.
                //$Pagination->unavailable_tag_close = '</span>'."\n";// while [both unavailable_before & unavailable_after] or unavailable_display is set to false this option is no need.
                //$Pagination->unavailable_tag_open = '<span class="unavailable">';// while [both unavailable_before & unavailable_after] or unavailable_display is set to false this option is no need.
                $Pagination->unavailable_text = '..';
                // create pagination links.
                echo $Pagination->createLinks(':pagination');
                // clear style.
                $Pagination->clear();
                ?> 
            </div>

            <h4>Just first, previous, next, last style</h4>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                // Set options to the pagination class.
                $Pagination->base_url = $base_url;// *This property must be set.
                $Pagination->total_records = $total_records;// *This property must be set.
                $Pagination->page_number_value = $start;// *This property must be set.
                $Pagination->current_page_link = true;
                $Pagination->current_tag_close = '</strong>'."\n";
                $Pagination->current_tag_open = '<strong>';
                $Pagination->first_page_always_show = true;
                $Pagination->first_page_text = '|&lt;';
                $Pagination->first_tag_close = '</span>'."\n";
                $Pagination->first_tag_open = '<span class="first">';
                $Pagination->items_per_page = $limit;
                $Pagination->last_page_always_show = true;
                $Pagination->last_page_text = '&gt;|';
                $Pagination->last_tag_close = '</span>'."\n";
                $Pagination->last_tag_open = '<span class="last">';
                $Pagination->next_page_always_show = true;
                $Pagination->next_page_text = '&gt;';
                $Pagination->next_tag_close = '</span>'."\n";
                $Pagination->next_tag_open = '<span class="next">';
                $Pagination->number_adjacent_pages = 3;
                $Pagination->number_display = false;
                //$Pagination->number_tag_close = '</span>'."\n";// while number_display is set to false this option is no need.
                //$Pagination->number_tag_open = '<span class="number">';// while number_display is set to false this option is no need.
                $Pagination->overall_tag_close = '</div>';
                $Pagination->overall_tag_open = '<div class="basic-style-pagination">'."\n";
                $Pagination->page_number_type = $page_number_type;
                $Pagination->previous_page_always_show = true;
                $Pagination->previous_page_text = '&lt;';
                $Pagination->previous_tag_close = '</span>'."\n";
                $Pagination->previous_tag_open = '<span class="previous">';
                //$Pagination->unavailable_after = 3;// while number_display is set to false this option is set to false by default.
                //$Pagination->unavailable_before = 1;// while number_display is set to false this option is set to false by default.
                //$Pagination->unavailable_display = true;// while number_display is set to false this option is set to false by default.
                //$Pagination->unavailable_tag_close = '</span>'."\n";// while number_display is set to false this option is no need.
                //$Pagination->unavailable_tag_open = '<span class="unavailable">';// while number_display is set to false this option is no need.
                //$Pagination->unavailable_text = '..';
                // create pagination links.
                echo $Pagination->createLinks(':pagination');
                // clear style.
                $Pagination->clear();
                ?> 
            </div>

            <h4>Just previous, next style</h4>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                // Set options to the pagination class.
                $Pagination->base_url = $base_url;// *This property must be set.
                $Pagination->total_records = $total_records;// *This property must be set.
                $Pagination->page_number_value = $start;// *This property must be set.
                $Pagination->current_page_link = true;
                $Pagination->current_tag_close = '</strong>'."\n";
                $Pagination->current_tag_open = '<strong>';
                $Pagination->first_page_always_show = false;
                $Pagination->first_page_text = false;
                //$Pagination->first_tag_close = '</span>'."\n";// while first_page_text is set to false this option is no need.
                //$Pagination->first_tag_open = '<span class="first">';// while first_page_text is set to false this option is no need.
                $Pagination->items_per_page = $limit;
                $Pagination->last_page_always_show = false;
                $Pagination->last_page_text = false;
                //$Pagination->last_tag_close = '</span>'."\n";// while last_page_text is set to false this option is no need.
                //$Pagination->last_tag_open = '<span class="last">';// while last_page_text is set to false this option is no need.
                $Pagination->next_page_always_show = true;
                $Pagination->next_page_text = '&gt;';
                $Pagination->next_tag_close = '</span>'."\n";
                $Pagination->next_tag_open = '<span class="next">';
                $Pagination->number_adjacent_pages = 3;
                $Pagination->number_display = false;
                //$Pagination->number_tag_close = '</span>'."\n";// while number_display is set to false this option is no need.
                //$Pagination->number_tag_open = '<span class="number">';// while number_display is set to false this option is no need.
                $Pagination->overall_tag_close = '</div>';
                $Pagination->overall_tag_open = '<div class="basic-style-pagination">'."\n";
                $Pagination->page_number_type = $page_number_type;
                $Pagination->previous_page_always_show = true;
                $Pagination->previous_page_text = '&lt;';
                $Pagination->previous_tag_close = '</span>'."\n";
                $Pagination->previous_tag_open = '<span class="previous">';
                //$Pagination->unavailable_after = 3;// while number_display is set to false this option is set to false by default.
                //$Pagination->unavailable_before = 1;// while number_display is set to false this option is set to false by default.
                //$Pagination->unavailable_display = true;// while number_display is set to false this option is set to false by default.
                //$Pagination->unavailable_tag_close = '</span>'."\n";// while number_display is set to false this option is no need.
                //$Pagination->unavailable_tag_open = '<span class="unavailable">';// while number_display is set to false this option is no need.
                //$Pagination->unavailable_text = '..';
                // create pagination links.
                echo $Pagination->createLinks(':pagination');
                // clear style.
                $Pagination->clear();
                ?> 
            </div>

            <h4>Bootstrap 4 style</h4>
            <p>This example override <strong>all</strong> class&#039;s properties, uses Bootstrap element style and css.</p>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                // Set options to the pagination class.
                $Pagination->base_url = $base_url;// *This property must be set.
                $Pagination->total_records = $total_records;// *This property must be set.
                $Pagination->page_number_value = $start;// *This property must be set.
                $Pagination->current_page_link = true;
                $Pagination->current_page_link_attributes = ['class' => 'page-link is-current-page active'];
                $Pagination->current_tag_close = '</li>'."\n";
                $Pagination->current_tag_open = '<li class="page-item active">';
                $Pagination->first_page_always_show = true;
                $Pagination->first_page_link_attributes = ['class' => 'page-link'];
                $Pagination->first_page_text = '|&lt;';
                $Pagination->first_tag_close = '</li>'."\n";
                $Pagination->first_tag_open = '<li class="page-item first">';
                $Pagination->items_per_page = $limit;
                $Pagination->last_page_always_show = true;
                $Pagination->last_page_link_attributes = ['class' => 'page-link'];
                $Pagination->last_page_text = '&gt;|';
                $Pagination->last_tag_close = '</li>'."\n";
                $Pagination->last_tag_open = '<li class="page-item last">';
                $Pagination->next_page_always_show = true;
                $Pagination->next_page_link_attributes = ['class' => 'page-link'];
                $Pagination->next_page_text = '&gt;';
                $Pagination->next_tag_close = '</li>'."\n";
                $Pagination->next_tag_open = '<li class="page-item next">';
                $Pagination->number_adjacent_pages = 3;
                $Pagination->number_display = true;
                $Pagination->number_page_link_attributes = ['class' => 'page-link'];
                $Pagination->number_tag_close = '</li>'."\n";
                $Pagination->number_tag_open = '<li class="page-item">';
                $Pagination->overall_tag_close = '</ul>';
                $Pagination->overall_tag_open = '<ul class="pagination">'."\n";
                $Pagination->page_number_type = $page_number_type;
                $Pagination->previous_page_always_show = true;
                $Pagination->previous_page_link_attributes = ['class' => 'page-link'];
                $Pagination->previous_page_text = '&lt;';
                $Pagination->previous_tag_close = '</li>'."\n";
                $Pagination->previous_tag_open = '<li class="page-item previous">';
                $Pagination->unavailable_after = 3;
                $Pagination->unavailable_before = 1;
                $Pagination->unavailable_display = true;
                $Pagination->unavailable_tag_close = '</a></li>'."\n";
                $Pagination->unavailable_tag_open = '<li class="page-item unavailable"><a class="page-link">';
                $Pagination->unavailable_text = '..';
                // create pagination links.
                echo $Pagination->createLinks(':pagination');
                // clear style.
                $Pagination->clear();
                ?> 
            </div>

            <h4>Select box style</h4>
            <p>This example just get pagination data and render in your own style.</p>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                    // Set options to the pagination class.
                    $Pagination->base_url = $base_url;// *This property must be set.
                    $Pagination->total_records = $total_records;// *This property must be set.
                    $Pagination->page_number_value = $start;// *This property must be set.
                    $Pagination->current_page_link = true;
                    $Pagination->current_tag_close = null;
                    $Pagination->current_tag_open = null;
                    $Pagination->first_page_always_show = false;
                    $Pagination->first_page_text = false;
                    //$Pagination->first_tag_close = '</span>'."\n";// while first_page_text is set to false this option is no need.
                    //$Pagination->first_tag_open = '<span class="first">';// while first_page_text is set to false this option is no need.
                    $Pagination->items_per_page = $limit;
                    $Pagination->last_page_always_show = false;
                    $Pagination->last_page_text = false;
                    //$Pagination->last_tag_close = '</span>'."\n";// while last_page_text is set to false this option is no need.
                    //$Pagination->last_tag_open = '<span class="last">';// while last_page_text is set to false this option is no need.
                    $Pagination->next_page_always_show = false;
                    $Pagination->next_page_text = false;
                    //$Pagination->next_tag_close = '</span>'."\n";// while next_page_text is set to false this option is no need.
                    //$Pagination->next_tag_open = '<span class="next">';// while next_page_text is set to false this option is no need.
                    $Pagination->number_adjacent_pages = 10;
                    $Pagination->number_display = true;
                    $Pagination->number_tag_close = null;
                    $Pagination->number_tag_open = null;
                    $Pagination->overall_tag_close = null;
                    $Pagination->overall_tag_open = null;
                    $Pagination->page_number_type = $page_number_type;
                    $Pagination->previous_page_always_show = false;
                    $Pagination->previous_page_text = false;
                    //$Pagination->previous_tag_close = '</span>'."\n";// while previous_page_text is set to false this option is no need.
                    //$Pagination->previous_tag_open = '<span class="previous">';// while previous_page_text is set to false this option is no need.
                    $Pagination->unavailable_after = false;
                    $Pagination->unavailable_before = false;
                    //$Pagination->unavailable_display = true;// while both unavailable_before & unavailable_after is set to false this option is set to false by default.
                    //$Pagination->unavailable_tag_close = '</span>'."\n";// while [both unavailable_before & unavailable_after] or unavailable_display is set to false this option is no need.
                    //$Pagination->unavailable_tag_open = '<span class="unavailable">';// while [both unavailable_before & unavailable_after] or unavailable_display is set to false this option is no need.
                    $Pagination->unavailable_text = '..';
                    // create pagination links.
                    $pagination_data = $Pagination->getPaginationData();
                    // clear style.
                    $Pagination->clear();
                    if (is_array($pagination_data) && array_key_exists('generated_pages', $pagination_data)) {
                        echo '<select id="selectbox-pagination" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">'."\n";
                        foreach ($pagination_data['generated_pages'] as $page_key => $page_item) {
                            if (!is_string($page_key) && is_array($page_item)) {
                                if (array_key_exists('link', $page_item) && array_key_exists('page_value', $page_item) && array_key_exists('text', $page_item)) {
                                    echo "    ";
                                    echo '<option value="'.$page_item['link'].'"';
                                    if (isset($start) && $start == $page_item['page_value']) {
                                        echo ' selected="selected"';
                                    }
                                    echo '>';
                                    echo $page_item['text'];
                                    echo '</option>'."\n";
                                }
                            }
                        }// endforeach;
                        unset($page_item, $page_key);
                        echo '</select>'."\n";
                    }
                    unset($pagination_data);
                ?>
            </div>

            <h4>Method POST</h4>
            <p>This example send pagination via method POST.</p>
            <div class="each-example-block">
                <?php
                $Pagination = new \Rundiz\Pagination\Pagination();
                    // Set options to the pagination class.
                    $Pagination->base_url = $base_url;// *This property must be set.
                    $Pagination->total_records = $total_records;// *This property must be set.
                    $Pagination->page_number_value = $start;// *This property must be set.
                    $Pagination->current_page_link = true;
                    $Pagination->current_tag_close = null;
                    $Pagination->current_tag_open = null;
                    $Pagination->first_page_always_show = false;
                    $Pagination->first_page_text = false;
                    //$Pagination->first_tag_close = '</span>'."\n";// while first_page_text is set to false this option is no need.
                    //$Pagination->first_tag_open = '<span class="first">';// while first_page_text is set to false this option is no need.
                    $Pagination->items_per_page = $limit;
                    $Pagination->last_page_always_show = false;
                    $Pagination->last_page_text = false;
                    //$Pagination->last_tag_close = '</span>'."\n";// while last_page_text is set to false this option is no need.
                    //$Pagination->last_tag_open = '<span class="last">';// while last_page_text is set to false this option is no need.
                    $Pagination->next_page_always_show = false;
                    $Pagination->next_page_text = false;
                    //$Pagination->next_tag_close = '</span>'."\n";// while next_page_text is set to false this option is no need.
                    //$Pagination->next_tag_open = '<span class="next">';// while next_page_text is set to false this option is no need.
                    $Pagination->number_adjacent_pages = 4;
                    $Pagination->number_display = true;
                    $Pagination->number_tag_close = null;
                    $Pagination->number_tag_open = null;
                    $Pagination->overall_tag_close = null;
                    $Pagination->overall_tag_open = null;
                    $Pagination->page_number_type = $page_number_type;
                    $Pagination->previous_page_always_show = false;
                    $Pagination->previous_page_text = false;
                    //$Pagination->previous_tag_close = '</span>'."\n";// while previous_page_text is set to false this option is no need.
                    //$Pagination->previous_tag_open = '<span class="previous">';// while previous_page_text is set to false this option is no need.
                    $Pagination->unavailable_after = false;
                    $Pagination->unavailable_before = false;
                    //$Pagination->unavailable_display = true;// while both unavailable_before & unavailable_after is set to false this option is set to false by default.
                    //$Pagination->unavailable_tag_close = '</span>'."\n";// while [both unavailable_before & unavailable_after] or unavailable_display is set to false this option is no need.
                    //$Pagination->unavailable_tag_open = '<span class="unavailable">';// while [both unavailable_before & unavailable_after] or unavailable_display is set to false this option is no need.
                    $Pagination->unavailable_text = '..';
                    // create pagination links.
                    $pagination_data = $Pagination->getPaginationData();
                    // clear style.
                    $Pagination->clear();
                    if (is_array($pagination_data) && array_key_exists('generated_pages', $pagination_data)) {
                        echo '<form id="pagination-method-post-form" method="post" action="'.str_replace(array('start=%PAGENUMBER%&amp;', 'start=%PAGENUMBER%'), '', $base_url).'">'."\n";
                        echo '<input type="hidden" id="method-post-start-value" name="start" value="">'."\n";
                        foreach ($pagination_data['generated_pages'] as $page_key => $page_item) {
                            if (!is_string($page_key) && is_array($page_item)) {
                                if (array_key_exists('link', $page_item) && array_key_exists('page_value', $page_item) && array_key_exists('text', $page_item)) {
                                    echo '<button type="button" class="button btn';
                                    if (isset($start) && $start == $page_item['page_value']) {
                                        echo ' btn-primary';
                                    } else {
                                        echo ' btn-default';
                                    }
                                    echo '"';
                                    echo ' name="start"';
                                    echo ' value="'.$page_item['page_value'].'"';
                                    echo '>';
                                    echo $page_item['text'];
                                    echo '</button> '."\n";
                                }
                            }
                        }// endforeach;
                        unset($page_item, $page_key);
                        echo '</form>'."\n";
                    }
                    unset($pagination_data);
                ?>
            </div>
        </div>

        <div class="example-source-code-block">
            <h3>Source code</h3>
            <p>Please open this file to the view source code. File location: <?php echo __FILE__; ?></p>
        </div>
        <footer>
            <small>Dummy data from <a href="https://www.mockaroo.com" target="_blank" rel="nofollow">mockaroo.com</a></small>
        </footer>

        <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
        <script src="script.js"></script>
    </body>
</html>
<?php
// Clear memory
unset($base_url, $limit, $offset, $page_number_type, $Pagination, $start, $total_records);
?>
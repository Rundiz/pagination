<?php
// get URI segments.
$uri_nextto_thisfile = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
$uri_segments = explode('/', ltrim($uri_nextto_thisfile, '/'));// This will get uri segments array where key [0] is /page and key [1] is /pagevalue
unset($uri_nextto_thisfile);

// get uri before this file
$this_file_uri_exp = explode('/', $_SERVER['SCRIPT_NAME']);
unset($this_file_uri_exp[count($this_file_uri_exp) - 1]);
$this_file_uri = implode('/', $this_file_uri_exp) . '/';
unset($this_file_uri_exp);

// You must require the class file if you did not install this class via Composer.
require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'Rundiz' . DIRECTORY_SEPARATOR . 'Pagination' . DIRECTORY_SEPARATOR . 'Pagination.php';


// This test will not use database, it uses dummy data.
require __DIR__ . DIRECTORY_SEPARATOR . 'includes-dummy-data.php';


$total_records = count($data);
$start = (isset($uri_segments[1]) ? intval($uri_segments[1]) : 0);
if ($start < 0) {
    $start = 0;
}
$limit = 10;
// Warning! The offset value between start_num style and page_num style are different, please use carefully.
$offset = $start;


// Slice data array to limited per page and start by number of $start
// In most real project this will be replaced with SQL query and its OFFSET, LIMIT commands.
$newdata = array_slice($data, $offset, $limit);
unset($data);


// Setup base URL and generate dynamic query string. In your real project, replace these code with your code if you have different way to setup base URL.
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/page/%PAGENUMBER%';
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


$Pagination = new \Rundiz\Pagination\Pagination();
// Set options to the pagination class.
$Pagination->base_url = $base_url;// *This property must be set.
$Pagination->total_records = $total_records;// *This property must be set.
$Pagination->page_number_value = $start;// *This property must be set.
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Pagination test</title>
        <link rel="stylesheet" href="<?php echo $this_file_uri; ?>style.css">
    </head>
    <body>
        <h1>Pagination test</h1>
        <p>Test the pagination class.</p>
        <h2>Basic test, Page number type as start number, Set page number via URI</h2>
        <p>The page value will be start at 0 and next start is total items that were displayed. Example: 0, 10, 20, 30 for display 10 items per page.</p>
        <p>
            Total records = <?php echo $total_records; ?><br />
            Display items per page = <?php echo $limit; ?><br />
            Use page number type as <strong>start number</strong> (0, 10, 20, 30, ...)
        </p>
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
        <?php 
        if ($i > 500) {
            echo '<div class="alert">You are displaying too many items per page! This is for prevent your system use too much resources so it limits the items per page at 500.</div>'."\n";
        }

        // create pagination links.
        echo $Pagination->createLinks(); 
        ?> 

        <div class="example-source-code-block">
            <h3>Source code</h3>
<pre><code class="language-php">&lt;?php
$Pagination = new \Rundiz\Pagination\Pagination();
// Set options to the pagination class.
$Pagination->base_url = '<?php echo $base_url; ?>';// *This property must be set.
$Pagination->total_records = <?php echo $total_records; ?>;// *This property must be set.
$Pagination->page_number_value = <?php echo $start; ?>;// *This property must be set.
echo $Pagination->createLinks();
?&gt;
</code></pre>
        </div>
        <footer>
            <small>Dummy data from <a href="https://www.mockaroo.com" target="_blank" rel="nofollow">mockaroo.com</a></small>
        </footer>
    </body>
</html>
<?php
// Clear memory
unset($base_url, $limit, $offset, $Pagination, $start, $this_file_uri, $total_records, $uri_segments);
?>
<?php

/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}



$standards = get_all_standards();
?>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/search_publisher/index.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="<?php echo get_template_directory_uri(); ?>/template-parts/techbook/search_publisher/index.js"></script>

<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>



<div class="container-fullwidth">
    <div class="container-boxed">
        <div class=" title-home">
            <a href="<?php echo home_url(); ?>/home/" class="home-link">Home</a> > <a href="<?php echo home_url(); ?>/book/" class="home-link"> Publisher</a> > <span style="color: #1E00AE;"> Advanced search </span>
        </div>
    </div>

    <div class="container-boxed-banner">
        <div class="titile-banner">Search Standard</div>
    </div>

    <div class="container-boxed-form">
        <div class="search-box">
            <h2>Advanced search</h2>
            <div class="search-panel">
                <div class="search-table-1">
                    <div class="input-field">
                        <label for="ref-number">Reference number</label>
                        <input type="text" id="ref-number" placeholder="Example: ISO 9001">
                    </div>

                    <div class="input-field">
                        <label for="std-title">Standard Title</label>
                        <input type="text" id="std-title" placeholder="Example: Quality management systems - Requirements">
                    </div>

                    <!-- Select Publisher -->
                    <!-- <div class="input-field">
                    <label for="select-publisher">Publisher</label>
                    <select id="select-publisher">
                        <option value="">All</option>
                        <?php
                        $publishers = get_all_publishers();

                        // Lọc các publisher_code duy nhất và hiển thị
                        if (! empty($publishers)) {
                            $publisher_codes = array_unique(array_column($publishers, 'publisherCode'));
                            foreach ($publisher_codes as $publisher_code) : ?>
                                <option value="<?php echo esc_attr($publisher_code); ?>"><?php echo esc_html($publisher_code); ?></option>
                            <?php endforeach;
                        } else {
                            echo '<option value="">No publishers found</option>';
                        }
                            ?>
                    </select>
                </div> -->

                    <div class="input-field">
                        <label for="select-ics">ICS Code</label>
                        <select id="select-ics">
                            <option value="">All</option>
                            <?php
                            // Gọi hàm để lấy tất cả dữ liệu ICS codes
                            $ics_codes = get_all_ics_codes();
                            if (! empty($ics_codes)) {
                                foreach ($ics_codes as $ics_code) : ?>
                                    <option value="<?php echo esc_attr($ics_code->icsCode); ?>">
                                        <?php echo esc_html($ics_code->icsCode . ' - ' . $ics_code->nameInEnglish); ?>
                                    </option>

                            <?php endforeach;
                            } else {
                                echo '<option value="">No ICS codes found</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-field">
                        <label for="select-lang">Publisher</label>
                        <select id="select-lang">
                            <option value="">All</option>
                            <?php
                            $publishers = get_all_publishers();

                            // Lọc các publisher_code duy nhất và hiển thị
                            if (! empty($publishers)) {
                                $publisher_codes = array_unique(array_column($publishers, 'publisherCode'));
                                foreach ($publisher_codes as $publisher_code) : ?>
                                    <option value="<?php echo esc_attr($publisher_code); ?>"><?php echo esc_html($publisher_code); ?></option>
                            <?php endforeach;
                            } else {
                                echo '<option value="">No publishers found</option>';
                            }
                            ?>
                        </select>
                    </div>



                    <div class="input-field">
                        <label for="pub-year-min">Published year</label>
                        <div class="year-selection">
                            <select id="pub-year">
                                <option value="">Chọn năm</option>
                                <?php
                                // Lấy năm hiện tại
                                $currentYear = date('Y');

                                // Hiển thị các năm từ 2000 đến năm hiện tại
                                for ($year = 1980; $year <= $currentYear; $year++): ?>
                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="input-field">
                        <label for="replace-to-text">By technology</label>
                        <select id="by-technology-text">
                            <option value="">All</option>
                            <?php
                            // Gọi hàm để lấy tất cả dữ liệu ICS codes
                            $industrys = get_all_technology();
                            if (! empty($industrys)) {
                                foreach ($industrys as $industry) : ?>
                                    <option value="<?php echo esc_attr($industry->SubjectCode); ?>">
                                        <?php echo esc_html($industry->englishTitle); ?>
                                    </option>
                            <?php endforeach;
                            } else {
                                echo '<option value="">No Industry codes found</option>';
                            }
                            ?>
                        </select>

                    </div>

                </div>


                <div class="search-table-2">


                    <div class="input-field">
                        <label for="replace-to-text">Replace to</label>
                        <input type="text" id="replace-to-text" placeholder="Text">
                    </div>

                    <div class="input-field">
                        <label for="replace-by-text">Replace by</label>
                        <input type="text" id="replace-by-text" placeholder="Text">
                    </div>

                    <div class="input-field">
                        <label for="replace-by-text">Referenced Standards</label>
                        <input type="text" id="referenced-standards-text" placeholder="Text">
                    </div>

                    <div class="input-field">
                        <label for="replace-by-text">Referencing Standards</label>
                        <input type="text" id="referencing-standards-text" placeholder="Text">
                    </div>
                    <div class="input-field">
                        <label for="replace-to-text">By industry</label>
                        <select id="by-industry-text">
                            <option value="">All</option>
                            <?php
                            // Gọi hàm để lấy tất cả dữ liệu ICS codes
                            $industrys = get_all_industry();
                            if (! empty($industrys)) {
                                foreach ($industrys as $industry) : ?>
                                    <option value="<?php echo esc_attr($industry->SubjectCode); ?>">
                                        <?php echo esc_html($industry->englishTitle); ?>
                                    </option>
                            <?php endforeach;
                            } else {
                                echo '<option value="">No Industry codes found</option>';
                            }
                            ?>
                        </select>

                    </div>

                    <div class="input-field status-options">
                        <label>Status</label>
                        <select id="select-status">
                            <option value="" selected disabled hidden>Select status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Revised">Revised</option>
                            <option value="Withdrawn">Withdrawn</option>
                        </select>
                    </div>


                </div>

                <div class="search-table-3">




                    <div class="input-field keyword-field">
                        <label for="keyword-search">Keyword</label>
                        <textarea id="keyword-search" placeholder="Text"></textarea>
                    </div>

                    <div class="action-buttons">
                        <button type="button" class="btn-refresh">
                            <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/refresh-cw-05.svg" alt="icon" class="icon1">
                            Refresh</button>
                        <button type="submit" class="btn-search">
                            <img src="<?php echo home_url(); ?>/wp-content/uploads/2024/09/search-md.svg" alt="icon" class="icon2">
                            Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-boxed">
        <span class="standard-topic">Browse Engineering Standards Topics</span>
        <div class="alphabet-numbers">
            <span class="char">A</span>
            <span class="char">B</span>
            <span class="char">C</span>
            <span class="char">D</span>
            <span class="char">E</span>
            <span class="char">F</span>
            <span class="char">G</span>
            <span class="char">H</span>
            <span class="char">I</span>
            <span class="char">J</span>
            <span class="char">K</span>
            <span class="char">L</span>
            <span class="char">M</span>
            <span class="char">N</span>
            <span class="char">O</span>
            <span class="char">P</span>
            <span class="char">Q</span>
            <span class="char">R</span>
            <span class="char">S</span>
            <span class="char">T</span>
            <span class="char">U</span>
            <span class="char">V</span>
            <span class="char">W</span>
            <span class="char">X</span>
            <span class="char">Y</span>
            <span class="char">Z</span>
        </div>
        <p class="topic-start">Browsing engineering standards topics starting with: <span id="lua-chon-topic"></span></p>

        <div id="results-container"></div>
        <input type="hidden" id="topics" value="">

    </div>






    <!-- phần dưới -->
    <div class="container-boxed">
        <div class="container-title">
            <p>Search topic: <span id="title-topics"></span></p>
            <p>Search results: <span id="dem-so-luong">0</span></p>
            <!-- <div class="sort-container">
            <div class="sort-by">
                <p>Sort by: </p>
                <select id="sort-reference">
                    <option value="reference-number">Reference number</option>
                    <option value="date">Date</option>
                    
                </select>
            </div>
            <div class="sort-newest">
                <select id="sort-order">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    
                </select>
            </div>
        </div> -->
        </div>

        <!-- phần dưới -->
        <div class="document-list"></div>

        <div id="page-size-select-container">
            <label for="page-size-select">Number of products per page</label>
            <select id="page-size-select">
                <option value="12" selected>10</option>
                <option value="36">20</option>
                <option value="60">50</option>
                <option value="120">100</option>
            </select>
        </div>


        <div class="custom-pagination"></div>


        <div id="loading-container">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    </div>


</div>


<script>
    const priceFactor = <?php echo json_encode(get_option('techbookapi_price_factor', 1)); ?>;
</script>
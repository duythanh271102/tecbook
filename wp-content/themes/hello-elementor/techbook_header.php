<?php

function advanced_search_shortcode() {

    $appPath = get_bloginfo('wpurl');
    //HTML
    $output = '
    <div class="advanced-search-container">
        <button class="advanced-search-btn">
            <img src="' . $appPath . '/wp-content/uploads/2024/09/Icon-3.svg" alt="Icon Left" class="icon-left">
            <span class="text-label"> '. __('Advanced search', 'hello-elementor') . '</span>
            <img src="' . $appPath . '/wp-content/uploads/2024/09/Symbol.svg" alt="Icon Right" class="icon-right">
        </button>
                <div class="advanced-search-dropdown" translate="no">
            <a href="' . $appPath . '/search-book/">' . __('Books', 'hello-elementor') . '</a>
            <a href="' . $appPath . '/search-publisher/">' . __('Standards', 'hello-elementor') . '</a>
        </div>

    </div>';

    // CSS để tạo giao diện
    $output .= '
    <style>
        .advanced-search-container {
            position: relative;
            display: inline-block;
        }

        .advanced-search-btn {
            background-color: #fff;
            color: #157FFF;
            border: 1.3px solid #157FFF;
            padding: 15px 30px 15px 15px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .icon-left {
            height: 20px;
            margin-right: 4px;
            margin-left: -10px;
        }

        .icon-right {
            height: 15px;
            margin-left: 4px;
        }

        .text-label {
            font-weight: bold;
            color: #157FFF;
            font-size: 14px;
            font-family: Ford Antenna;
            font-weight: 400;
            line-height: 19.6px;

        }

        .advanced-search-btn:hover {
            background-color: #f1f1f1;
        }
        
        .advanced-search-btn:action {
            background-color: #f1f1f1;
        }

        .advanced-search-dropdown {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            min-width: 165px;
            padding: 12px 16px;
            z-index: 1;
            border-radius: 5px;
        }

        .advanced-search-dropdown a {
            color: black;
            text-decoration: none;
            display: block;
            border-radius: 8px;
            margin: 5px;
            text-align: center;
            background: #F5F5F5;
            border: 1px solid #E8E8E8;
        }

        .advanced-search-dropdown a:hover {
            background-color: #ddd;
        }

        .advanced-search-container:hover .advanced-search-dropdown {
            display: block;
        }

    </style>';

    // JavaScript 
    $output .= '
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var btn = document.querySelector(".advanced-search-btn");
            var dropdown = document.querySelector(".advanced-search-dropdown");

            btn.addEventListener("click", function(e) {
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
            });

            document.addEventListener("click", function() {
                dropdown.style.display = "none";
            });

             btn.addEventListener("mouseleave", function() {
                this.style.backgroundColor = "#fff";
            });
        });
    </script>';

    return $output;
}
add_shortcode('advanced_search', 'advanced_search_shortcode');




function custom_search_shortcode() {
    ob_start();
    ?>
    <style>
        .custom-search-bar {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f5f5f5;
        }
        .custom-search-bar input[type="text"] {
            border: none;
            outline: none;
            padding: 8px;
            width: 100%;
            color: #333;
            background-color: transparent;
            font-family: 'Ford Antenna';
        }
        .custom-search-bar .search-options {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
            border: 1px solid #dee2e6;
            height: 35px;
            background-color: #fff;
            margin-right: 5px;
            border-radius: 5px;
            padding-left: 12px;
        }
        .custom-search-bar .search-options button {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            width: 35px;
        }
        .custom-search-bar .search-options img {
            width: 16px;
        }
        .custom-search-bar .dropdown {
            position: absolute;
            top: 35px;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: none;
            width: 150px;
            z-index: 1;
            margin-top: 7px;
            padding: 10px;
        }
        .custom-search-bar .dropdown a {
            text-decoration: none;
            display: block;
            border-radius: 8px;
            margin: 5px;
            text-align: center;
            background: #F5F5F5;
            border: 1px solid #E8E8E8;
            color: #2C2C2C;
        }
        .custom-search-bar .dropdown a:hover {
            background-color: #f0f0f0;
        }
        .custom-search-bar button.search-button {
            background: #007bff;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            height: 50px;
        }
        .custom-search-bar button.search-button img {
            width: 25px;
            height: 30px;
            color: white;
        }
        span#selected-option-label {
    font-family: 'Ford Antenna';
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 13px;
}
    </style>

<div class="custom-search-bar">
    <input type="text" id="search-input" placeholder="<?php echo esc_attr__('Search book ', 'hello-elementor'); ?>">
    <div class="search-options" onclick="toggleDropdown()">
        <span id="selected-option-label" data-option-book="<?php echo esc_attr__('Book', 'hello-elementor'); ?>" 
              data-option-standard="<?php echo esc_attr__('Standard', 'hello-elementor'); ?>">
            <?php echo __('Book', 'hello-elementor'); ?>
        </span>
        <button>
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAACXBIWXMAAAsTAAALEwEAmpwYAAAArklEQVR4nO3RsWoCARBF0aNoFAstREQbLSJ2Eiv/xM+T1AE70S6QThAhYhFCIGAQbRR/IQRSbKGLrotY7C3nzdziDQl3TybCTfZU0Mc3GhfIejigeyzMYYwVmmfInrDHAOlTSwW84gv1EFkHOzyHyYLSN3yidiRvY4uXSzovYooPVAPzFjYYRnlgCTO8o4xHrP97/us7EmUsMMcPRnhwJRUsMUFeTGSQikuWcGN+AZ8KGRyYFYGoAAAAAElFTkSuQmCC" alt="expand-arrow">
        </button>
        <div class="dropdown" id="dropdown-options">
            <a href="#" data-placeholder="<?php echo esc_attr__('Search book ', 'hello-elementor'); ?>" 
               onclick="selectSearchOption(this)">
                <?php echo __('Book', 'hello-elementor'); ?>
            </a>
            <a href="#" data-placeholder="<?php echo esc_attr__('Search standard ', 'hello-elementor'); ?>" 
               onclick="selectSearchOption(this)">
                <?php echo __('Standard', 'hello-elementor'); ?>
            </a>
        </div>
    </div>
    <button class="search-button" onclick="performSearch()">
        <img src="<?php echo esc_url(home_url('/wp-content/uploads/2024/09/Vector-2.svg')); ?>" alt="<?php echo esc_attr__('Search Icon', 'hello-elementor'); ?>">
    </button>
</div>


<script>
    let currentOption = document.querySelector("#selected-option-label").dataset.optionBook; // Default to "Book"

function toggleDropdown() {
    var dropdown = document.getElementById("dropdown-options");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

function selectSearchOption(element) {
    var searchInput = document.getElementById("search-input");
    var selectedLabel = document.getElementById("selected-option-label");

    // Update the current option and label
    currentOption = element.textContent.trim();
    selectedLabel.textContent = currentOption;

    // Update the placeholder based on the data-placeholder attribute
    searchInput.placeholder = element.getAttribute("data-placeholder");

    toggleDropdown();
}

function performSearch() {
    let searchQuery = document.getElementById("search-input").value;
    let baseUrl;

    // Set the URL based on the selected option
    if (currentOption === document.querySelector("#selected-option-label").dataset.optionBook) {
        baseUrl = "<?= home_url(); ?>/search-book/?title=";
    } else if (currentOption === document.querySelector("#selected-option-label").dataset.optionStandard) {
        baseUrl = "<?= home_url(); ?>/search-publisher/?reference=";
    }

    // Redirect to the appropriate URL with the search query
    window.location.href = baseUrl + encodeURIComponent(searchQuery);
}

// Hide dropdown when clicking outside
document.addEventListener("click", function(event) {
    var dropdown = document.getElementById("dropdown-options");
    var searchOptions = document.querySelector(".search-options");
    if (!searchOptions.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = "none";
    }
});

</script>


    <?php
    return ob_get_clean();
}
add_shortcode('custom_search', 'custom_search_shortcode');











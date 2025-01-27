<?php
/**
 * The template for displaying footer.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>


<style>
    
.organization-card {
  background-color: white;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 20px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  display: flex;
  justify-content: space-between;
  align-items: center;
  text-decoration: none !important;
  color: #2c2c2c;

}

.organization-card:hover{
  box-shadow: 0px 0px 15px 0px #00000026;
  cursor: pointer;
}

.organization-card:hover .document-action{
  background-color: #1E00AE;
}
.organization-card:hover .icon-card{
  filter: invert(1);
}
.card-content {
  display: flex;
  align-items: center;
  width: 100%;
}

.image-organization img {
  width: 100px;
  height: 100px;
  object-fit: contain;
  border-radius: 4px;
}

.description {
  flex-grow: 1;
  margin-left: 15px;
  width: 80%;
}



.description p {
  margin: 5px 0;
  color: #2C2C2C;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  max-height: 3.2em;
  white-space: normal;
  font-family: Ford Antenna;
  font-size: 13px;
  font-weight: 300;
  line-height: 22px;
  text-align: left;
}


.document-action img {
  width: 17px;
  height: 17px;
}

.document-action {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  background-color: #ffffff;
  border-radius: 50%;
  box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.1);
  transition: background-color 0.2s ease;
}

@media screen and (max-width: 440px) {
  .description p{
    font-size: 12px !important;
    line-height: 16.5px !important;
  }
}
</style>

<!-- Đây là nội dung của file product-list-publisher1.php -->
<a href="<?php echo home_url(); ?>/detail/publisher-<?= isset($organization->id) ? intval($organization->id) : ''; ?>" class="organization-card">
    <div class="card-content">
    <div class="image-organization">
        <img src="<?= isset($organization->avatarPath) && !empty($organization->avatarPath) 
                    ? 'https://techdoc-storage.s3.ap-southeast-1.amazonaws.com/' . htmlspecialchars($organization->avatarPath) 
                    : home_url() . '/wp-content/uploads/2024/09/Rectangle-17873.png'; ?>" 
            alt="<?= isset($organization->abbreviation) 
                    ? htmlspecialchars($organization->abbreviation) . ' Logo' 
                    : 'Default Logo'; ?>">
    </div>

        <div class="description">
            <p style="font-family: Ford Antenna; font-size: 16px; font-weight: 500; line-height: 25.5px; letter-spacing: 0.015em; text-align: left;">
                <?php 
                if (isset($organization->englishTitle) && !empty($organization->englishTitle)) {
                    $parts = explode(' - ', htmlspecialchars($organization->englishTitle), 2);
                    if (count($parts) == 2) {
                        echo '<span style="color: #1E00AE;">' . $parts[0] . '</span> - ' . $parts[1];
                    } else {
                        echo htmlspecialchars($organization->englishTitle);
                    }
                } else {
                    echo 'N/A';
                }
                ?>
            </p>
            <p><?= isset($organization->englishDescription) && !empty($organization->englishDescription) ? htmlspecialchars($organization->englishDescription) : 'No title available'; ?></p>
        </div>
        <div class="document-action">
            <img src="<?= home_url(); ?>/wp-content/uploads/2024/09/Icon-8.svg" alt="Arrow Icon" class="icon-card">
        </div>
    </div>
</a>





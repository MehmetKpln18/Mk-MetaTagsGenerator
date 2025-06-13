<?php
require_once 'MetaTagsGenerator.php';

$meta = new MetaTagsGenerator();

// Temel SEO
$meta->metaCharset('UTF-8');
$meta->metaTitle('iPhone 15 Pro Max - 256GB Titanyum', '-', 'TechStore');
$meta->metaDescription('iPhone 15 Pro Max 256GB Titanyum renk. Ücretsiz kargo, 2 yıl garanti. Hemen sipariş verin!');
$meta->metaKeywords(['iPhone 15', 'Pro Max', '256GB', 'Apple', 'smartphone']);
$meta->metaAuthor('TechStore');
$meta->metaRobots('index, follow');
$meta->metaCanonical('https://techstore.com/iphone-15-pro-max-256gb');

// Social Media
$meta->metaOpenGraph([
    'title' => 'iPhone 15 Pro Max - 256GB Titanyum',
    'description' => 'En yeni iPhone modeli artık TechStore\'da!',
    'image' => 'https://techstore.com/images/iphone-15-pro-max.jpg',
    'url' => 'https://techstore.com/iphone-15-pro-max-256gb',
    'type' => 'product',
    'site_name' => 'TechStore'
]);

$meta->metaTwitterCard('summary_large_image', [
    'site' => '@techstore',
    'title' => 'iPhone 15 Pro Max - 256GB',
    'description' => 'En yeni iPhone modeli artık TechStore\'da!',
    'image' => 'https://techstore.com/images/iphone-15-pro-max.jpg'
]);

// Mobile & PWA
$meta->metaViewport();
$meta->metaThemeColor('#007bff');
$meta->metaAppleTags('TechStore', 'yes');

// Structured Data
$productSchema = [
    "@context" => "https://schema.org/",
    "@type" => "Product",
    "name" => "iPhone 15 Pro Max 256GB",
    "description" => "iPhone 15 Pro Max 256GB Titanyum renk",
    "image" => "https://techstore.com/images/iphone-15-pro-max.jpg",
    "brand" => ["@type" => "Brand", "name" => "Apple"],
    "offers" => [
        "@type" => "Offer",
        "price" => "54999",
        "priceCurrency" => "TRY",
        "availability" => "https://schema.org/InStock",
        "seller" => ["@type" => "Organization", "name" => "TechStore"]
    ]
];
$meta->addStructuredData($productSchema);

// Assets
$meta->metaCss('/assets/css/product-page.css');
$meta->metaJs('/assets/js/product-gallery.js', 'text/javascript', false, true);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <?php $meta->renderMetaTags(); ?>
</head>
<body>
    <!-- Ürün sayfası içeriği -->
</body>
</html>

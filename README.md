# Mk-MetaTagsGenerator - PHP Meta Tag Management Library

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.4-blue)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![Version](https://img.shields.io/badge/Version-2.1-orange)](CHANGELOG.md)

**MetaTagsGenerator**, web sayfalarınız için HTML meta etiketlerini kolayca yönetmenizi sağlayan performans odaklı bir PHP sınıfıdır. SEO optimizasyonu, sosyal medya paylaşımları ve web uygulaması meta verilerini tek bir yerden kontrol edebilirsiniz.

## 🚀 Özellikler

- **🎯 Performans Odaklı**: Caching ve output buffering ile optimize edilmiş
- **🔒 Duplicate Prevention**: Aynı meta etiketlerin tekrarını engeller
- **📱 Responsive Ready**: Viewport ve mobile-first yaklaşım
- **🌐 SEO Friendly**: Tüm temel SEO meta etiketlerini destekler
- **📊 Social Media**: Open Graph ve Twitter Card desteği
- **🍎 Apple Devices**: Apple-specific meta etiketleri
- **⚡ Memory Efficient**: Minimal bellek kullanımı
- **🔧 Debug Mode**: Geliştirme sürecinde detaylı loglama
- **📤 Export/Import**: Meta etiket konfigürasyonlarını kaydetme/yükleme

## 📦 Kurulum

### Composer ile Kurulum (Önerilen)

```bash
composer require mehmetkpln18/meta-tags-generator
```

### Manuel Kurulum

```php
require_once 'path/to/MetaTagsGenerator.php';
```

## 🛠️ Temel Kullanım

### Basit Örnek

```php
<?php
require_once 'MetaTagsGenerator.php';

// Meta tag generator'ı başlat
$meta = new MetaTagsGenerator();

// Temel meta etiketleri ekle
$meta->metaTitle('Ana Sayfa', '-', 'Şirket Adı');
$meta->metaDescription('Bu sayfa şirketimizin ana sayfasıdır.');
$meta->metaKeywords(['ana sayfa', 'şirket', 'hizmetler']);
$meta->metaCharset('UTF-8');
$meta->metaViewport();

// Meta etiketleri render et
?>
<!DOCTYPE html>
<html>
<head>
    <?php $meta->renderMetaTags(); ?>
</head>
<body>
    <!-- Sayfa içeriği -->
</body>
</html>
```

### Debug Mode ile Kurulum

```php
$meta = new MetaTagsGenerator(true); // Debug mode aktif
```

## 📖 Detaylı Kullanım Örnekleri

### SEO Meta Etiketleri

```php
// Sayfa başlığı (max 60 karakter önerilen)
$meta->metaTitle('Ürün Sayfası', '-', 'E-Ticaret Sitesi', 60);

// Meta açıklama (max 160 karakter önerilen)
$meta->metaDescription('En kaliteli ürünleri uygun fiyatlarla satın alın.', 160);

// Anahtar kelimeler
$meta->metaKeywords(['ürün', 'satış', 'kaliteli', 'uygun fiyat']);

// Yazar bilgisi
$meta->metaAuthor('Mehmet Kaplan');

// Robot direktifleri
$meta->metaRobots('index, follow');

// Canonical URL
$meta->metaCanonical('https://example.com/urun-sayfasi');
```

### Open Graph (Facebook, LinkedIn vb.)

```php
// Tek tek ekleme
$meta->metaOpenGraph('title', 'Harika Bir Ürün');
$meta->metaOpenGraph('description', 'Bu ürün gerçekten harika!');
$meta->metaOpenGraph('image', 'https://example.com/image.jpg');
$meta->metaOpenGraph('url', 'https://example.com/urun');

// Toplu ekleme
$meta->metaOpenGraph([
    'title' => 'Harika Bir Ürün',
    'description' => 'Bu ürün gerçekten harika!',
    'image' => 'https://example.com/image.jpg',
    'url' => 'https://example.com/urun',
    'type' => 'product',
    'site_name' => 'E-Ticaret Sitesi'
]);
```

### Twitter Card

```php
// Basit Twitter Card
$meta->metaTwitterCard('summary_large_image', [
    'site' => '@sirketadi',
    'creator' => '@mehmetkpln18',
    'title' => 'Harika Bir Ürün',
    'description' => 'Bu ürün gerçekten harika!',
    'image' => 'https://example.com/image.jpg'
]);
```

### CSS ve JavaScript Dosyaları

```php
// Tek CSS dosyası
$meta->metaCss('https://example.com/style.css');

// Media query ile CSS
$meta->metaCss('https://example.com/print.css', 'print');

// Birden fazla CSS dosyası
$meta->metaMultipleCss([
    'https://example.com/bootstrap.css',
    'https://example.com/custom.css'
]);

// JavaScript dosyaları
$meta->metaJs('https://example.com/script.js', 'text/javascript', true, false); // async=true
$meta->metaJs('https://example.com/defer-script.js', 'text/javascript', false, true); // defer=true
```

### Mobile ve PWA Desteği

```php
// Viewport ayarları
$meta->metaViewport('width=device-width, initial-scale=1.0, user-scalable=no');

// Apple meta etiketleri
$meta->metaAppleTags('Uygulama Adı', 'yes', 'yes', 'black-translucent');

// Theme color
$meta->metaThemeColor('#007bff');

// Favicon
$meta->metaFavicon('image/png', '/favicon-32x32.png', '32x32');

// Apple touch icons
$meta->metaAppleTouchIcon([
    ['href' => '/apple-icon-57x57.png', 'sizes' => '57x57'],
    ['href' => '/apple-icon-72x72.png', 'sizes' => '72x72'],
    ['href' => '/apple-icon-114x114.png', 'sizes' => '114x114']
]);

// Manifest dosyası
$meta->metaManifestJson(['href' => '/manifest.json']);
```

### Structured Data (JSON-LD)

```php
// Ürün structured data
$productData = [
    "@context" => "https://schema.org/",
    "@type" => "Product",
    "name" => "Harika Ürün",
    "description" => "Bu gerçekten harika bir ürün",
    "image" => "https://example.com/product-image.jpg",
    "offers" => [
        "@type" => "Offer",
        "price" => "299.99",
        "priceCurrency" => "TRY",
        "availability" => "https://schema.org/InStock"
    ]
];

$meta->addStructuredData($productData);
```

### Toplu Meta Etiket Ekleme

```php
$tags = [
    ['tag' => 'meta', 'attributes' => ['name' => 'author', 'content' => 'Mehmet Kaplan'], 'priority' => 5],
    ['tag' => 'meta', 'attributes' => ['name' => 'robots', 'content' => 'index, follow'], 'priority' => 6],
    ['tag' => 'link', 'attributes' => ['rel' => 'canonical', 'href' => 'https://example.com'], 'priority' => 7]
];

$meta->addBulkMetaTags($tags);
```

## 🔧 Gelişmiş Özellikler

### Tag Yönetimi

```php
// Belirli bir tag tipindeki tüm etiketleri getir
$metaTags = $meta->getTagsByType('meta');

// Tag güncelle
$meta->updateTag('meta_name_description', ['name' => 'description', 'content' => 'Yeni açıklama']);

// Tag sil
$meta->removeTag('meta_name_keywords');

// Tüm etiketleri temizle
$meta->clearTags();
```

### Performans İzleme

```php
// Tag sayısını öğren
echo "Toplam tag sayısı: " . $meta->getTagCount();

// Bellek kullanımını kontrol et
$memoryInfo = $meta->getMemoryUsage();
echo "Mevcut bellek: " . $memoryInfo['current'] . " bytes";
echo "Peak bellek: " . $memoryInfo['peak'] . " bytes";
```

### Export/Import

```php
// Mevcut konfigürasyonu export et
$config = $meta->exportTags();
file_put_contents('meta-config.json', $config);

// Konfigürasyonu import et
$config = file_get_contents('meta-config.json');
$meta->importTags($config);
```

### String Olarak Render

```php
// Meta etiketleri string olarak al (echo etmeden)
$metaHtml = $meta->getRenderedTags();
echo $metaHtml;

// Öncelik sırasına göre sıralanmış olarak al
$sortedMetaHtml = $meta->getRenderedTags(true);
```

## 🎨 Özelleştirme

### Priority Sistemi

Meta etiketleri önem sırasına göre sıralanır:

1. **Charset** (priority: 1)
2. **Title** (priority: 2) 
3. **Viewport** (priority: 3)
4. **Description** (priority: 4)
5. **Keywords** (priority: 5)
6. **Diğer etiketler** (priority: 10)

### Custom Priority

```php
$meta->addMetaTag('meta', ['name' => 'custom', 'content' => 'value'], 1); // Yüksek öncelik
```

## 📋 Tam Örnek - E-Ticaret Ürün Sayfası

```php
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
```

## 🔍 API Referansı

### Temel Metodlar

| Method | Açıklama | Parametreler |
|--------|----------|-------------|
| `metaTitle($title, $separator, $suffix, $maxLength)` | Sayfa başlığı | string, string, string, int |
| `metaDescription($description, $maxLength)` | Meta açıklama | string, int |
| `metaKeywords($keywords)` | Anahtar kelimeler | array\|string |
| `metaCharset($charset)` | Karakter seti | string |
| `metaAuthor($author)` | Yazar | string |
| `metaRobots($content)` | Robot direktifleri | string |

### SEO Metodları

| Method | Açıklama | Parametreler |
|--------|----------|-------------|
| `metaCanonical($url)` | Canonical URL | string |
| `metaViewport($content)` | Viewport ayarları | string |
| `metaBaseHref($url)` | Base href | string |

### Social Media Metodları

| Method | Açıklama | Parametreler |
|--------|----------|-------------|
| `metaOpenGraph($property, $content)` | Open Graph | string\|array, string |
| `metaTwitterCard($type, $data)` | Twitter Card | string, array |

### Asset Metodları

| Method | Açıklama | Parametreler |
|--------|----------|-------------|
| `metaCss($css, $media)` | CSS dosyası | string\|array, string |
| `metaJs($js, $type, $async, $defer)` | JavaScript dosyası | string\|array, string, bool, bool |

### Utility Metodları

| Method | Açıklama | Dönüş Tipi |
|--------|----------|------------|
| `getTagCount()` | Tag sayısı | int |
| `getMemoryUsage()` | Bellek kullanımı | array |
| `exportTags()` | Config export | string |
| `importTags($json)` | Config import | bool |
| `clearTags()` | Tüm tagları temizle | void |

## 🚀 Performans İpuçları

1. **Caching Kullanın**: Sınıf otomatik olarak rendered output'u cache'ler
2. **Bulk Operations**: Çok sayıda tag için `addBulkMetaTags()` kullanın
3. **Priority Sistemi**: Kritik etiketler için düşük priority değeri verin
4. **Memory Monitoring**: `getMemoryUsage()` ile bellek kullanımını takip edin
5. **Debug Mode**: Production'da debug mode'u kapatın

## 🤝 Katkıda Bulunma

1. Bu repository'yi fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Değişikliklerinizi commit edin (`git commit -m 'Add amazing feature'`)
4. Branch'i push edin (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## 📝 Changelog

### v2.1 (2024)
- ✅ Priority sistemi eklendi
- ✅ Debug mode desteği
- ✅ Structured data (JSON-LD) desteği
- ✅ Enhanced tag management
- ✅ Export/Import özelliği
- ✅ Gelişmiş bellek raporlama

### v2.0 (2024-04-15)
- ✅ Performance optimizations
- ✅ Duplicate prevention
- ✅ Enhanced caching
- ✅ Memory efficiency improvements

## 📞 Destek

- **Website**: [https://www.mehmetkaplan.net/](https://www.mehmetkaplan.net/)
- **Email**: mehmetkpln18@gmail.com
- **GitHub**: [@mehmetkpln18](https://github.com/mehmetkpln18)

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için [LICENSE](LICENSE) dosyasına bakın.

## 🙏 Teşekkürler

Bu projeyi kullandığınız için teşekkürler! Herhangi bir sorun yaşarsanız veya öneriniz varsa lütfen issue açmaktan çekinmeyin.

---

**Made with ❤️ by [Mehmet Kaplan](https://www.mehmetkaplan.net/)**

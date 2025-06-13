<?php 
/*
* Title: Mk-MetaTagsGenerator - PHP Meta Tag Management Library
* Author: Mehmet Kaplan (Enhanced Version)
* Username: mehmetkpln18
* E-Mail: mehmetkpln18@gmail.com
* Website: https://www.mehmetkaplan.net/
* Creation Date: 15.04.24
* Latest Version: v2.1 (Enhanced with additional features)
*/

class MetaTagsGenerator {
    private $metaTags = [];
    private $rendered = false;
    private $outputBuffer = '';
    private $debugMode = false;
    
    // Self-closing tags için statik array (memory efficient)
    private static $selfClosingTags = [
        'meta' => true,
        'link' => true,
        'base' => true,
        'img' => true,
        'input' => true,
        'br' => true,
        'hr' => true,
    ];
    
    // Commonly used attributes için cache
    private static $commonViewport = 'width=device-width, initial-scale=1.0, user-scalable=yes';
    
    // Priority levels for tag ordering
    private static $tagPriorities = [
        'meta_charset' => 1,
        'title_tag' => 2,
        'meta_name_viewport' => 3,
        'meta_name_description' => 4,
        'meta_name_keywords' => 5,
    ];
    
    public function __construct($debugMode = false) {
        $this->debugMode = $debugMode;
    }
    
    /**
     * Adds a meta tag to the list of meta tags with validation
     * Enhanced: Added priority handling and better error reporting
     */
    public function addMetaTag($tag, $attributes = [], $priority = 10) {
        // Input validation (performance için minimal)
        if (empty($tag) || !is_string($tag)) {
            $this->debugLog("Invalid tag provided: " . print_r($tag, true));
            return false;
        }
        
        // Tag normalization (case-insensitive)
        $tag = strtolower(trim($tag));
        
        // Validate attributes
        if (!is_array($attributes)) {
            $this->debugLog("Invalid attributes provided for tag: $tag");
            return false;
        }
        
        // Duplicate meta tag kontrolü (aynı name/property/rel için)
        $key = $this->generateUniqueKey($tag, $attributes);
        
        // Set priority
        $effectivePriority = self::$tagPriorities[$key] ?? $priority;
        
        $this->metaTags[$key] = [
            'tag' => $tag,
            'attributes' => $attributes,
            'priority' => $effectivePriority,
            'timestamp' => microtime(true)
        ];
        
        // Rendered cache'i temizle
        $this->invalidateCache();
        $this->debugLog("Added tag: $tag with key: $key");
        return true;
    }
    
    /**
     * Enhanced unique key generator with better collision handling
     */
    private function generateUniqueKey($tag, $attributes) {
        // Meta taglar için unique key oluştur
        if ($tag === 'meta') {
            if (isset($attributes['name'])) {
                return 'meta_name_' . strtolower($attributes['name']);
            } elseif (isset($attributes['property'])) {
                return 'meta_property_' . strtolower($attributes['property']);
            } elseif (isset($attributes['charset'])) {
                return 'meta_charset';
            } elseif (isset($attributes['http-equiv'])) {
                return 'meta_http_equiv_' . strtolower($attributes['http-equiv']);
            }
        } elseif ($tag === 'link') {
            if (isset($attributes['rel'])) {
                $key = 'link_rel_' . strtolower($attributes['rel']);
                if (isset($attributes['sizes'])) {
                    $key .= '_' . $attributes['sizes'];
                }
                if (isset($attributes['media'])) {
                    $key .= '_' . $attributes['media'];
                }
                return $key;
            }
        } elseif ($tag === 'title') {
            return 'title_tag';
        } elseif ($tag === 'script') {
            if (isset($attributes['src'])) {
                return 'script_src_' . md5($attributes['src']);
            }
        }
        
        // Fallback: hash based key with better collision handling
        return $tag . '_' . md5(serialize($attributes));
    }
    
    /**
     * Enhanced render method with priority sorting and caching
     */
    public function renderMetaTags($sorted = true) {
        // Cache kontrolü
        if ($this->rendered && !empty($this->outputBuffer)) {
            echo $this->outputBuffer;
            return;
        }
        
        // Sort by priority if requested
        $tags = $this->metaTags;
        if ($sorted) {
            uasort($tags, function($a, $b) {
                return $a['priority'] <=> $b['priority'];
            });
        }
        
        // Output buffering kullan
        ob_start();
        
        foreach ($tags as $metaTag) {
            echo $this->buildTagString($metaTag['tag'], $metaTag['attributes']);
        }
        
        $this->outputBuffer = ob_get_clean();
        $this->rendered = true;
        
        echo $this->outputBuffer;
    }
    
    /**
     * Enhanced tag building method with better HTML5 support
     */
    private function buildTagString($tag, $attributes) {
        $html = '<' . $tag;
        
        // Special handling for title tag
        if ($tag === 'title' && isset($attributes['text'])) {
            return '<title>' . htmlspecialchars($attributes['text'], ENT_QUOTES, 'UTF-8') . '</title>' . "\n";
        }
        
        // Special handling for script tags
        if ($tag === 'script' && isset($attributes['content'])) {
            $content = $attributes['content'];
            unset($attributes['content']);
            $html .= $this->buildAttributesString($attributes);
            return $html . '>' . $content . '</script>' . "\n";
        }
        
        // Build attributes string
        if (!empty($attributes)) {
            $html .= $this->buildAttributesString($attributes);
        }
        
        // Self-closing tags
        if (isset(self::$selfClosingTags[$tag])) {
            $html .= ' />';
        } else {
            $html .= '></' . $tag . '>';
        }
        
        return $html . "\n";
    }
    
    /**
     * Enhanced attributes building with boolean attribute support
     */
    private function buildAttributesString($attributes) {
        $result = '';
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                // Boolean attributes (HTML5)
                $result .= ' ' . $key;
            } elseif ($value !== null && $value !== false && $value !== '') {
                $result .= ' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
            }
        }
        return $result;
    }
    
    /**
     * Debug logging method
     */
    private function debugLog($message) {
        if ($this->debugMode) {
            error_log("[MetaTagsGenerator] " . $message);
        }
    }
    
    /**
     * Cache invalidation
     */
    private function invalidateCache() {
        $this->rendered = false;
        $this->outputBuffer = '';
    }
    
    /**
     * Get rendered HTML as string with sorting option
     */
    public function getRenderedTags($sorted = true) {
        if (!$this->rendered || empty($this->outputBuffer)) {
            $tags = $this->metaTags;
            if ($sorted) {
                uasort($tags, function($a, $b) {
                    return $a['priority'] <=> $b['priority'];
                });
            }
            
            ob_start();
            foreach ($tags as $metaTag) {
                echo $this->buildTagString($metaTag['tag'], $metaTag['attributes']);
            }
            $this->outputBuffer = ob_get_clean();
            $this->rendered = true;
        }
        return $this->outputBuffer;
    }
    
    // Enhanced SEO Methods
    
    public function metaTitle($title, $separator = '-', $suffix = '', $maxLength = 60) {
        $fullTitle = trim($title);
        if (!empty($suffix)) {
            $fullTitle .= ' ' . $separator . ' ' . $suffix;
        }
        
        // Truncate if too long
        if (strlen($fullTitle) > $maxLength) {
            $fullTitle = substr($fullTitle, 0, $maxLength - 3) . '...';
        }
        
        $this->addMetaTag('title', ['text' => $fullTitle], 2);
    }

    public function metaDescription($description, $maxLength = 160) {
        $description = trim($description);
        if (strlen($description) > $maxLength) {
            $description = substr($description, 0, $maxLength - 3) . '...';
        }
        $this->addMetaTag('meta', ['name' => 'description', 'content' => $description], 4);
    }

    public function metaKeywords($keywords) {
        if (is_array($keywords)) {
            $keywords = implode(', ', $keywords);
        }
        $this->addMetaTag('meta', ['name' => 'keywords', 'content' => trim($keywords)], 5);
    }

    public function metaCharset($charset = 'UTF-8') {
        $this->addMetaTag('meta', ['charset' => strtoupper($charset)], 1);
    }

    // Enhanced structured data support
    public function addStructuredData($data, $type = 'application/ld+json') {
        $jsonData = is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data;
        $this->addMetaTag('script', [
            'type' => $type,
            'content' => $jsonData
        ]);
    }
    
    // Enhanced Open Graph with validation
    public function metaOpenGraph($property, $content) {
        if (is_array($property)) {
            foreach ($property as $prop => $cont) {
                if (!empty($cont)) {
                    $this->addMetaTag('meta', ['property' => 'og:' . $prop, 'content' => $cont]);
                }
            }
        } else {
            if (!empty($content)) {
                $this->addMetaTag('meta', ['property' => 'og:' . $property, 'content' => $content]);
            }
        }
    }
    
    // Enhanced Twitter Card with more card types
    public function metaTwitterCard($cardType = 'summary', $data = []) {
        $validCardTypes = ['summary', 'summary_large_image', 'app', 'player'];
        if (!in_array($cardType, $validCardTypes)) {
            $cardType = 'summary';
        }
        
        $this->addMetaTag('meta', ['name' => 'twitter:card', 'content' => $cardType]);
        
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $this->addMetaTag('meta', ['name' => 'twitter:' . $key, 'content' => $value]);
            }
        }
    }
    
    // Performance and utility methods
    
    public function getTagsByType($tagType) {
        $result = [];
        foreach ($this->metaTags as $key => $tag) {
            if ($tag['tag'] === $tagType) {
                $result[$key] = $tag;
            }
        }
        return $result;
    }
    
    public function removeTag($key) {
        if (isset($this->metaTags[$key])) {
            unset($this->metaTags[$key]);
            $this->invalidateCache();
            return true;
        }
        return false;
    }
    
    public function updateTag($key, $attributes) {
        if (isset($this->metaTags[$key])) {
            $this->metaTags[$key]['attributes'] = $attributes;
            $this->invalidateCache();
            return true;
        }
        return false;
    }
    
    public function getTagCount() {
        return count($this->metaTags);
    }
    
    public function getMemoryUsage() {
        return [
            'current' => memory_get_usage(true),
            'peak' => memory_get_peak_usage(true),
            'tags_size' => strlen(serialize($this->metaTags))
        ];
    }
    
    public function clearTags() {
        $this->metaTags = [];
        $this->invalidateCache();
    }
    
    // Export/Import functionality
    public function exportTags() {
        return json_encode($this->metaTags, JSON_PRETTY_PRINT);
    }
    
    public function importTags($jsonData) {
        $data = json_decode($jsonData, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            $this->metaTags = array_merge($this->metaTags, $data);
            $this->invalidateCache();
            return true;
        }
        return false;
    }
    
    // All original methods preserved with enhancements...
    // (keeping all the existing methods from the original class)
    
    public function metaAuthor($author) {
        $this->addMetaTag('meta', ['name' => 'author', 'content' => trim($author)]);
    }

    public function metaRobots($content) {
        $this->addMetaTag('meta', ['name' => 'robots', 'content' => $content]);
    }

    public function metaBaseHref($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $this->addMetaTag('base', ['href' => $url]);
        return true;
    }

    public function metaCss($css, $media = null) {
        if (is_array($css)) {
            $this->metaMultipleCss($css, $media);
        } else {
            $attributes = ['rel' => 'stylesheet', 'href' => $css];
            if ($media) {
                $attributes['media'] = $media;
            }
            $this->addMetaTag('link', $attributes);
        }
    }

    public function metaMultipleCss($cssArray, $media = null) {
        foreach ($cssArray as $css) {
            $this->metaCss($css, $media);
        }
    }

    public function metaJs($js, $type = 'text/javascript', $async = false, $defer = false) {
        if (is_array($js)) {
            $this->metaMultipleJs($js, $type, $async, $defer);
        } else {
            $attributes = ['type' => $type, 'src' => $js];
            if ($async) $attributes['async'] = true;
            if ($defer) $attributes['defer'] = true;
            $this->addMetaTag('script', $attributes);
        }
    }

    public function metaMultipleJs($jsArray, $type = 'text/javascript', $async = false, $defer = false) {
        foreach ($jsArray as $js) {
            $this->metaJs($js, $type, $async, $defer);
        }
    }

    public function metaViewport($content = null) {
        $content = $content ?? self::$commonViewport;
        $this->addMetaTag('meta', ['name' => 'viewport', 'content' => $content], 3);
    }

    public function metaFavicon($type, $url, $sizes = '') {
        $attributes = [
            'rel' => 'icon',
            'type' => $type,
            'href' => $url,
        ];
        
        if (!empty($sizes)) {
            $attributes['sizes'] = $sizes;
        }
        
        $this->addMetaTag('link', $attributes);
    }

    public function metaAppleTags($appName, $appStatus, $appCapable = 'yes', $appBarStyle = 'default') {
        $appleTags = [
            ['name' => 'apple-mobile-web-app-capable', 'content' => $appCapable],
            ['name' => 'apple-mobile-web-app-status-bar-style', 'content' => $appBarStyle],
            ['name' => 'apple-mobile-web-app-title', 'content' => $appName],
            ['name' => 'apple-touch-fullscreen', 'content' => $appStatus],
        ];
        
        foreach ($appleTags as $tag) {
            $this->addMetaTag('meta', $tag);
        }
    }

    public function metaThemeColor($color) {
        if (!preg_match('/^#[a-fA-F0-9]{6}$/', $color)) {
            return false;
        }
        $this->addMetaTag('meta', ['name' => 'theme-color', 'content' => $color]);
        return true;
    }

    public function metaIECompatibility($version = 'edge') {
        $this->addMetaTag('meta', ['http-equiv' => 'X-UA-Compatible', 'content' => 'IE=' . $version]);
    }

    public function metaCanonical($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $this->addMetaTag('link', ['rel' => 'canonical', 'href' => $url]);
        return true;
    }

    public function metaManifestJson($manifestData) {
        $this->addMetaTag('link', [
            'rel' => 'manifest',
            'href' => $manifestData['href'],
        ]);
    }

    public function metaAppleTouchIcon($icons) {
        foreach ($icons as $icon) {
            $attributes = ['rel' => 'apple-touch-icon', 'href' => $icon['href']];
            if (!empty($icon['sizes'])) {
                $attributes['sizes'] = $icon['sizes'];
            }
            $this->addMetaTag('link', $attributes);
        }
    }
    
    public function addBulkMetaTags($tags) {
        foreach ($tags as $tag) {
            $this->addMetaTag($tag['tag'], $tag['attributes'] ?? [], $tag['priority'] ?? 10);
        }
    }
}

?>

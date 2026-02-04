<?php
/**
 * 图库
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$photosTitle = trim((string) clarity_opt('photos_title', '图库'));
clarity_set('showAside', false);
clarity_set('pageTitle', $photosTitle);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$photosDesc = trim((string) clarity_opt('photos_desc', '记录生活中的美好瞬间'));
$groups = [];
$photos = [];

$attachments = $this->attachments();
if ($attachments && $attachments->have()) {
    while ($attachments->next()) {
        $attachment = $attachments->attachment;
        if ($attachment && isset($attachment->isImage) && !$attachment->isImage) {
            continue;
        }
        $url = '';
        if ($attachment && isset($attachment->url)) {
            $url = (string) $attachment->url;
        }
        if ($url === '' && isset($attachments->url)) {
            $url = (string) $attachments->url;
        }
        if ($url === '') {
            continue;
        }
        $title = $attachments->title ?? '';
        if ($title === '' && $attachment && isset($attachment->name)) {
            $title = (string) $attachment->name;
        }
        $desc = '';
        if (isset($attachments->text) && trim((string) $attachments->text) !== '') {
            $desc = trim(strip_tags((string) $attachments->text));
        }
        $photos[] = [
            'url' => $url,
            'cover' => $url,
            'displayName' => $title,
            'description' => $desc
        ];
    }
}

if (!empty($photos)) {
    $groupName = $this->slug ? (string) $this->slug : 'photos';
    $groups[] = [
        'name' => $groupName,
        'displayName' => $photosTitle,
        'photoCount' => count($photos),
        'photos' => $photos
    ];
} else {
    $rawGroups = clarity_json_option('photos_data', []);
    $groupIndex = 0;
    if (is_array($rawGroups)) {
        foreach ($rawGroups as $group) {
            if (!is_array($group)) continue;
            $name = trim((string) ($group['name'] ?? ''));
            if ($name === '') {
                $name = 'group-' . $groupIndex;
            }
            $displayName = $group['displayName'] ?? $group['title'] ?? $name;
            $groupPhotos = $group['photos'] ?? [];
            $normalized = [];
            if (is_array($groupPhotos)) {
                foreach ($groupPhotos as $photo) {
                    if (!is_array($photo)) continue;
                    $url = $photo['url'] ?? '';
                    if ($url === '') continue;
                    $normalized[] = [
                        'url' => $url,
                        'cover' => $photo['cover'] ?? '',
                        'displayName' => $photo['displayName'] ?? $photo['title'] ?? '',
                        'description' => $photo['description'] ?? ''
                    ];
                }
            }
            $groups[] = [
                'name' => $name,
                'displayName' => $displayName,
                'photoCount' => count($normalized),
                'photos' => $normalized
            ];
            $groupIndex++;
        }
    }
}
?>

<div class="page-header">
  <h1 class="page-title text-creative">
    <span class="icon-[ph--images-bold]"></span>
    <span><?php echo htmlspecialchars($photosTitle, ENT_QUOTES, 'UTF-8'); ?></span>
  </h1>
  <p class="page-desc"><?php echo htmlspecialchars($photosDesc, ENT_QUOTES, 'UTF-8'); ?></p>
</div>

<div class="photos-container">
  <div id="photo-gallery"></div>
  <noscript>
    <div class="empty-state">
      <span class="icon-[ph--image-broken]"></span>
      <p>请开启 JavaScript 以加载图库</p>
    </div>
  </noscript>
</div>

<script>
  (function () {
    const el = document.getElementById('photo-gallery');
    if (!el) return;
    const groups = <?php echo json_encode($groups, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
    const mount = () => {
      if (window.mountPhotoGallery) {
        window.mountPhotoGallery(el, groups);
        return true;
      }
      return false;
    };
    if (!mount()) {
      window.addEventListener('load', () => mount());
    }
  })();
</script>

<?php $this->need('footer.php'); ?>

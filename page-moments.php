<?php
/**
 * 瞬间
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$momentsTitle = trim((string) clarity_opt('moments_title', '瞬间'));
clarity_set('showAside', true);
clarity_set('pageTitle', $momentsTitle);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$moments = clarity_json_option('moments_data', []);
$tagParam = trim((string) $this->request->get('tag', ''));
$momentsBase = $this->permalink;
$momentsSep = strpos($momentsBase, '?') === false ? '?' : '&';
$tagLink = function (string $tag) use ($momentsBase, $momentsSep): string {
    return $momentsBase . $momentsSep . 'tag=' . urlencode($tag);
};

$tagsMap = [];
$normalized = [];
if (is_array($moments)) {
    foreach ($moments as $index => $moment) {
        if (!is_array($moment)) continue;
        $tags = $moment['tags'] ?? [];
        if (!is_array($tags)) $tags = [];
        foreach ($tags as $tag) {
            if ($tag === '') continue;
            if (!isset($tagsMap[$tag])) $tagsMap[$tag] = 0;
            $tagsMap[$tag]++;
        }
        $moment['__index'] = $index;
        $normalized[] = $moment;
    }
}

$tagList = [];
foreach ($tagsMap as $tagName => $count) {
    $tagList[] = ['name' => $tagName, 'count' => $count];
}

$filtered = [];
foreach ($normalized as $moment) {
    $tags = $moment['tags'] ?? [];
    if (!is_array($tags)) $tags = [];
    if ($tagParam !== '' && !in_array($tagParam, $tags, true)) {
        continue;
    }
    $filtered[] = $moment;
}
?>

<div class="moments-header">
  <div class="moments-title">
    <span class="icon-[ph--shooting-star-bold]"></span>
    <h1 class="text-creative"><?php echo htmlspecialchars($momentsTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
  </div>
  <a href="<?php $this->options->feedUrl(); ?>" class="rss-link" title="RSS 订阅" target="_blank" rel="noopener">
    <span class="icon-[ph--rss-simple-bold]"></span>
    RSS 订阅
  </a>
</div>

<?php if (!empty($tagList)): ?>
  <div class="moments-tags-wrapper">
    <nav class="moments-tags scrollcheck-x">
      <a href="<?php echo htmlspecialchars($momentsBase, ENT_QUOTES, 'UTF-8'); ?>" class="tag-item<?php echo $tagParam === '' ? ' active' : ''; ?>">全部</a>
      <?php foreach ($tagList as $tag): ?>
        <?php
        $tagName = $tag['name'];
        $active = ($tagParam !== '' && $tagParam === $tagName) ? ' active' : '';
        ?>
        <a href="<?php echo htmlspecialchars($tagLink((string) $tagName), ENT_QUOTES, 'UTF-8'); ?>" class="tag-item<?php echo $active; ?>">
          <span><?php echo htmlspecialchars((string) $tagName, ENT_QUOTES, 'UTF-8'); ?></span>
          <span class="tag-count"><?php echo (int) $tag['count']; ?></span>
        </a>
      <?php endforeach; ?>
    </nav>
    <div class="at-slide-hover">
      <span class="icon-[ph--mouse-simple-bold]"></span>
      <span>滚动查看更多</span>
    </div>
  </div>
<?php endif; ?>

<div class="moments-list proper-height">
  <?php if (!empty($filtered)): ?>
    <?php foreach ($filtered as $idx => $moment): ?>
      <?php
      $momentId = $moment['id'] ?? $moment['name'] ?? $moment['slug'] ?? ('moment-' . $idx);
      $contentHtml = $moment['content'] ?? '';
      $timeRaw = $moment['time'] ?? '';
      $timestamp = $timeRaw ? strtotime($timeRaw) : false;
      $timeDisplay = $timestamp ? date('Y-m-d', $timestamp) : (string) $timeRaw;
      $timeTitle = $timestamp ? date('Y-m-d H:i', $timestamp) : (string) $timeRaw;
      $timeDatetime = $timestamp ? date('c', $timestamp) : (string) $timeRaw;
      $author = $moment['author'] ?? [];
      $authorName = is_array($author) ? ($author['name'] ?? '') : '';
      $authorAvatar = is_array($author) ? ($author['avatar'] ?? '') : '';
      $tags = $moment['tags'] ?? [];
      if (!is_array($tags)) $tags = [];
      $media = $moment['media'] ?? [];
      if (!is_array($media)) $media = [];
      $mediaCount = count($media);
      $mediaClass = $mediaCount === 1 ? 'single' : ($mediaCount === 2 ? 'double' : 'grid');
      $likes = (int) ($moment['likes'] ?? 0);
      $comments = (int) ($moment['comments'] ?? 0);
      ?>
      <article class="moment-card" id="<?php echo htmlspecialchars((string) $momentId, ENT_QUOTES, 'UTF-8'); ?>" style="--delay: <?php echo ($idx * 0.05); ?>s">
        <header class="moment-header">
          <?php if ($authorName !== '' || $authorAvatar !== ''): ?>
            <div class="author-info">
              <?php if ($authorAvatar !== ''): ?>
                <img src="<?php echo htmlspecialchars((string) $authorAvatar, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) $authorName, ENT_QUOTES, 'UTF-8'); ?>" class="author-avatar" loading="lazy" />
              <?php endif; ?>
              <?php if ($authorName !== ''): ?>
                <span class="author-name"><?php echo htmlspecialchars((string) $authorName, ENT_QUOTES, 'UTF-8'); ?></span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <time class="moment-time" datetime="<?php echo htmlspecialchars((string) $timeDatetime, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars((string) $timeTitle, ENT_QUOTES, 'UTF-8'); ?>">
            <span class="icon-[ph--calendar-dots-bold]"></span>
            <span><?php echo htmlspecialchars((string) $timeDisplay, ENT_QUOTES, 'UTF-8'); ?></span>
          </time>
        </header>

        <div class="moment-body">
          <?php if ($contentHtml !== ''): ?>
            <div class="moment-text"><?php echo $contentHtml; ?></div>
          <?php endif; ?>

          <?php if ($mediaCount > 0): ?>
            <div class="moment-media <?php echo $mediaClass; ?>">
              <?php foreach ($media as $item): ?>
                <?php
                if (!is_array($item)) continue;
                $type = strtoupper((string) ($item['type'] ?? 'PHOTO'));
                $url = $item['url'] ?? '';
                if ($url === '') continue;
                ?>
                <?php if ($type === 'VIDEO'): ?>
                  <figure class="media-item video">
                    <video src="<?php echo htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8'); ?>" controls preload="metadata"></video>
                  </figure>
                <?php elseif ($type === 'AUDIO'): ?>
                  <figure class="media-item audio">
                    <audio src="<?php echo htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8'); ?>" controls></audio>
                  </figure>
                <?php else: ?>
                  <figure class="media-item photo">
                    <img src="<?php echo htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8'); ?>" alt="瞬间图片" loading="lazy" />
                  </figure>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <footer class="moment-footer">
          <?php if (!empty($tags)): ?>
            <div class="moment-tags">
              <?php foreach ($tags as $tag): ?>
                <a href="<?php echo htmlspecialchars($tagLink((string) $tag), ENT_QUOTES, 'UTF-8'); ?>" class="moment-tag">
                  <span class="tag-hash">#</span>
                  <span><?php echo htmlspecialchars((string) $tag, ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="moment-actions">
            <button class="action-btn like" title="点赞">
              <span class="icon-[ph--heart-bold]"></span>
              <span><?php echo $likes; ?></span>
            </button>
            <button class="action-btn comment" title="评论" onclick="this.closest('.moment-card').querySelector('.moment-comment')?.classList.toggle('show')">
              <span class="icon-[ph--chat-circle-bold]"></span>
              <span><?php echo $comments; ?></span>
            </button>
            <button class="action-btn share" title="复制链接" data-url="<?php echo htmlspecialchars('#' . $momentId, ENT_QUOTES, 'UTF-8'); ?>" onclick="(function(btn){ var text = location.origin + location.pathname + btn.dataset.url; if (window.clarityCopyText) { window.clarityCopyText(text); } else if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(text); } btn.classList.add('copied'); setTimeout(function(){ btn.classList.remove('copied'); }, 2000); })(this);">
              <span class="icon-[ph--link-bold]"></span>
            </button>
          </div>
        </footer>

        <div class="moment-comment">
          <div class="comment-header">
            <span class="icon-[ph--chat-circle-text-bold]"></span>
            <span>评论</span>
          </div>
          <div class="comment-body">此页面暂不支持独立评论。</div>
        </div>
      </article>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="moments-empty">
      <span class="icon-[ph--shooting-star-bold]"></span>
      <p>暂无瞬间</p>
    </div>
  <?php endif; ?>
</div>

<?php $this->need('footer.php'); ?>

<?php
/**
 * 追番
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$bangumisTitle = trim((string) clarity_opt('bangumis_title', '追番'));
clarity_set('showAside', true);
clarity_set('pageTitle', $bangumisTitle);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$raw = clarity_get_bangumis_data();
$items = [];
if (is_array($raw)) {
    foreach ($raw as $item) {
        if (!is_array($item)) continue;
        $statusRaw = $item['status'] ?? 0;
        if (is_string($statusRaw)) {
            $statusRaw = strtolower(trim($statusRaw));
            if (in_array($statusRaw, ['1', 'want', 'wish', '想看'], true)) $statusRaw = 1;
            elseif (in_array($statusRaw, ['2', 'watching', 'doing', '在看'], true)) $statusRaw = 2;
            elseif (in_array($statusRaw, ['3', 'done', 'finished', '已看'], true)) $statusRaw = 3;
            else $statusRaw = 0;
        }
        $status = (int) $statusRaw;
        $items[] = [
            'title' => $item['title'] ?? $item['name'] ?? '',
            'cover' => $item['cover'] ?? '',
            'type' => $item['type'] ?? '',
            'area' => $item['area'] ?? '',
            'totalCount' => $item['totalCount'] ?? '',
            'follow' => $item['follow'] ?? '',
            'view' => $item['view'] ?? '',
            'danmaku' => $item['danmaku'] ?? '',
            'coin' => $item['coin'] ?? '',
            'score' => $item['score'] ?? '',
            'desc' => $item['desc'] ?? $item['description'] ?? '',
            'url' => $item['url'] ?? '',
            'status' => $status
        ];
    }
}

$statusParam = trim((string) $this->request->get('status', '0'));
$statusParam = is_numeric($statusParam) ? (int) $statusParam : 0;
$statusBase = $this->permalink;
$statusSep = strpos($statusBase, '?') === false ? '?' : '&';
$statusLink = function (int $status) use ($statusBase, $statusSep): string {
    return $statusBase . $statusSep . 'status=' . $status;
};

$counts = [0 => 0, 1 => 0, 2 => 0, 3 => 0];
foreach ($items as $item) {
    $counts[0]++;
    if (isset($counts[$item['status']])) {
        $counts[$item['status']]++;
    }
}

$filtered = [];
foreach ($items as $item) {
    if ($statusParam === 0 || $item['status'] === $statusParam) {
        $filtered[] = $item;
    }
}
?>

<div class="bangumi-header">
  <div class="bangumi-title">
    <span class="icon-[ph--television-bold]"></span>
    <h1 class="text-creative"><?php echo htmlspecialchars($bangumisTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
  </div>
</div>

<nav class="bangumi-tags scrollcheck-x">
  <a href="<?php echo htmlspecialchars($statusLink(0), ENT_QUOTES, 'UTF-8'); ?>" class="tag-item<?php echo $statusParam === 0 ? ' active' : ''; ?>">
    <span>全部</span>
    <span class="tag-count"><?php echo (int) $counts[0]; ?></span>
  </a>
  <a href="<?php echo htmlspecialchars($statusLink(1), ENT_QUOTES, 'UTF-8'); ?>" class="tag-item<?php echo $statusParam === 1 ? ' active' : ''; ?>">
    <span>想看</span>
    <span class="tag-count"><?php echo (int) $counts[1]; ?></span>
  </a>
  <a href="<?php echo htmlspecialchars($statusLink(2), ENT_QUOTES, 'UTF-8'); ?>" class="tag-item<?php echo $statusParam === 2 ? ' active' : ''; ?>">
    <span>在看</span>
    <span class="tag-count"><?php echo (int) $counts[2]; ?></span>
  </a>
  <a href="<?php echo htmlspecialchars($statusLink(3), ENT_QUOTES, 'UTF-8'); ?>" class="tag-item<?php echo $statusParam === 3 ? ' active' : ''; ?>">
    <span>已看</span>
    <span class="tag-count"><?php echo (int) $counts[3]; ?></span>
  </a>
</nav>

<div class="bangumi-tabs-content">
  <div class="bangumi-tab-pane active">
    <?php if (!empty($filtered)): ?>
      <div class="bangumi-list">
        <?php foreach ($filtered as $item): ?>
          <div class="bangumi-item">
            <?php if ($item['cover'] !== ''): ?>
              <img src="<?php echo htmlspecialchars((string) $item['cover'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) $item['title'], ENT_QUOTES, 'UTF-8'); ?>" class="bangumi-cover" loading="lazy" referrerpolicy="no-referrer" />
            <?php else: ?>
              <div class="bangumi-cover" style="display:flex;align-items:center;justify-content:center;">
                <span class="icon-[ph--image-bold]"></span>
              </div>
            <?php endif; ?>

            <div class="bangumi-info">
              <h2><?php echo htmlspecialchars((string) $item['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
              <div class="bangumi-meta">
                <?php if ($item['type'] !== ''): ?><span><?php echo '类型: ' . htmlspecialchars((string) $item['type'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if ($item['area'] !== ''): ?><span><?php echo '地区: ' . htmlspecialchars((string) $item['area'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if ($item['totalCount'] !== ''): ?><span><?php echo '集数: ' . htmlspecialchars((string) $item['totalCount'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
              </div>

              <div class="bangumi-stats">
                <?php if ($item['follow'] !== ''): ?><span><?php echo '关注: ' . htmlspecialchars((string) $item['follow'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if ($item['view'] !== ''): ?><span><?php echo '观看: ' . htmlspecialchars((string) $item['view'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if ($item['danmaku'] !== ''): ?><span><?php echo '弹幕: ' . htmlspecialchars((string) $item['danmaku'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if ($item['coin'] !== ''): ?><span><?php echo '投币: ' . htmlspecialchars((string) $item['coin'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if ($item['score'] !== ''): ?><span><?php echo '评分: ' . htmlspecialchars((string) $item['score'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
              </div>

              <?php if ($item['desc'] !== ''): ?>
                <p class="bangumi-des"><?php echo htmlspecialchars((string) $item['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>

              <?php if ($item['url'] !== ''): ?>
                <a href="<?php echo htmlspecialchars((string) $item['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="bangumi-link">查看详情</a>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <span class="icon-[ph--television-bold]"></span>
        <p>暂无追番</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php $this->need('footer.php'); ?>

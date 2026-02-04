<?php
/**
 * 标签云
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

clarity_set('showAside', true);
clarity_set('pageTitle', '标签云');
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$tagsList = [];
$this->widget('Widget_Metas_Tag_Cloud', 'ignoreZero=0')->to($tags);
while ($tags->next()) {
    $tagsList[] = [
        'name' => $tags->name,
        'permalink' => $tags->permalink,
        'count' => (int) ($tags->count ?? 0)
    ];
}
$tagsCount = count($tagsList);
?>

<div class="tags-archive proper-height">
  <header class="tags-header">
    <h1 class="tags-title">
      <span class="icon-[ph--tag-bold]"></span>
      <span>标签云</span>
    </h1>
    <p class="tags-count">
      共 <strong><?php echo $tagsCount; ?></strong> 个标签
    </p>
  </header>

  <div class="tags-cloud">
    <?php foreach ($tagsList as $idx => $tag): ?>
      <a href="<?php echo htmlspecialchars((string) $tag['permalink'], ENT_QUOTES, 'UTF-8'); ?>" class="tag-item" style="--delay: <?php echo ($idx * 0.02); ?>s" title="<?php echo htmlspecialchars($tag['name'] . ' (' . $tag['count'] . ' 篇文章)', ENT_QUOTES, 'UTF-8'); ?>">
        <span class="tag-name">#<span><?php echo htmlspecialchars((string) $tag['name'], ENT_QUOTES, 'UTF-8'); ?></span></span>
        <span class="tag-count"><?php echo (int) $tag['count']; ?></span>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($tagsCount === 0): ?>
    <div class="tags-empty">
      <span class="icon-[ph--tag-simple-bold]"></span>
      <p>暂无标签</p>
    </div>
  <?php endif; ?>
</div>

<?php $this->need('footer.php'); ?>

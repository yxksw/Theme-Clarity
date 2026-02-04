<?php
/**
 * 分类
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

clarity_set('showAside', true);
clarity_set('pageTitle', '分类');
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$items = [];
$this->widget('Widget_Metas_Category_List')->to($categories);
while ($categories->next()) {
    $items[] = [
        'name' => $categories->name,
        'permalink' => $categories->permalink,
        'description' => $categories->description,
        'count' => (int) ($categories->count ?? 0)
    ];
}
?>

<div class="categories-page">
  <div class="categories-grid">
    <?php foreach ($items as $idx => $cat): ?>
      <a href="<?php echo htmlspecialchars((string) $cat['permalink'], ENT_QUOTES, 'UTF-8'); ?>" style="--delay: <?php echo ($idx * 0.05); ?>s" class="category-card card">
        <span class="category-card-icon icon-[ph--folder-bold]"></span>
        <div class="category-card-body">
          <h2 class="category-card-title"><?php echo htmlspecialchars((string) $cat['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
          <?php if (!empty($cat['description'])): ?>
            <p class="category-card-desc"><?php echo htmlspecialchars((string) $cat['description'], ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
        </div>
        <div class="category-card-footer">
          <span class="category-card-count"><?php echo (int) $cat['count']; ?> 篇文章</span>
          <span class="category-card-arrow icon-[ph--arrow-right-bold]"></span>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php $this->need('footer.php'); ?>

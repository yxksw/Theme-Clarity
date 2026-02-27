<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
clarity_set('showAside', true);
ob_start();
$this->archiveTitle([
    'category' => _t('分类 %s'),
    'search' => _t('搜索 %s'),
    'tag' => _t('标签 %s'),
    'author' => _t('%s 的文章'),
    'date' => _t('%s 归档')
], '', '');
$archiveTitle = trim(ob_get_clean());
clarity_set('pageTitle', $archiveTitle);
clarity_set('isLinksPage', false);
$showDefaultCover = clarity_bool(clarity_opt('default_cover', '1'));
$showAuthor = clarity_bool(clarity_opt('show_post_author', '1'));
$featuredOnlyHome = clarity_bool(clarity_opt('featured_posts_page', '1'));
$featuredPosts = $featuredOnlyHome ? [] : clarity_featured_posts();
$showFeatured = !empty($featuredPosts);
?>
<?php $this->need('header.php'); ?>

<div class="archive proper-height">
  <header class="tag-header">
    <div class="tag-info">
      <h1 class="tag-name"><span class="icon-[ph--article-bold]"></span><span><?php echo clarity_display_text($archiveTitle); ?></span></h1>
      <p class="tag-meta">共 <strong><?php echo (int) $this->getTotal(); ?></strong> 篇文章</p>
    </div>
  </header>
  <?php if ($showFeatured): ?>
    <?php clarity_render_featured_posts($featuredPosts); ?>
  <?php endif; ?>

  <menu class="posts-container proper-height">
    <?php $delayIndex = 0; ?>
    <?php while ($this->next()): ?>
      <?php
      $cover = clarity_get_cover($this);
      $excerpt = clarity_get_excerpt($this, 120);
      $views = clarity_get_views($this);
      $postTitle = clarity_display_text((string) $this->title);
      ?>
      <article class="article-card card" style="--delay: <?php echo ($delayIndex * 0.05); ?>s">
        <a href="<?php $this->permalink(); ?>">
          <?php if ($cover !== ''): ?>
            <img src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" class="article-cover" loading="lazy" alt="<?php echo $postTitle; ?>" />
          <?php elseif ($showDefaultCover): ?>
            <div class="article-cover default-cover"><span class="default-cover-title"><?php echo $postTitle; ?></span></div>
          <?php endif; ?>
        </a>
        <div class="article-body">
          <h2 class="article-title text-creative"><a href="<?php $this->permalink(); ?>"><?php echo $postTitle; ?></a></h2>
          <?php if ($excerpt !== ''): ?>
            <p class="article-description"><?php echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
          <div class="article-info">
            <span class="info-item"><span class="icon-[ph--calendar-dots-bold]"></span><time><?php $this->date('Y-m-d'); ?></time></span>
            <?php if ($this->categories): ?>
              <span class="info-item article-category"><span class="icon-[ph--folder-bold]"></span><?php $this->category(','); ?></span>
            <?php endif; ?>
            <span class="info-item"><span class="icon-[ph--eye-bold]"></span><span><?php echo $views !== null ? $views : 0; ?></span></span>
            <?php if ($showAuthor): ?>
              <?php clarity_render_author_capsule($this); ?>
            <?php endif; ?>
          </div>
        </div>
      </article>
      <?php $delayIndex++; ?>
    <?php endwhile; ?>
  </menu>

  <?php if (!$this->have()): ?>
    <div class="empty-state">
      <span class="icon-[ph--article-bold]"></span>
      <p>暂无内容</p>
    </div>
  <?php endif; ?>

  <?php clarity_render_pagination($this, 'archive'); ?>
</div>

<?php $this->need('footer.php'); ?>

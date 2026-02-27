<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
clarity_set('showAside', true);
ob_start();
$this->archiveTitle('tag', '', '');
$tagName = trim(ob_get_clean());
clarity_set('pageTitle', $tagName);
clarity_set('isLinksPage', false);
$switchLayout = clarity_bool(clarity_opt('switch_tag_layout', '0'));
$featuredOnlyHome = clarity_bool(clarity_opt('featured_posts_page', '1'));
$featuredPosts = $featuredOnlyHome ? [] : clarity_featured_posts();
$showFeatured = !empty($featuredPosts);
$safeTagName = clarity_display_text($tagName);
?>
<?php $this->need('header.php'); ?>

<?php if (!$switchLayout): ?>
  <div class="category-page">
    <header class="category-header card">
      <div class="category-info">
        <h1 class="category-title text-creative">
          <span class="icon-[ph--tag-bold]"></span>
          <span><?php echo $safeTagName; ?></span>
        </h1>
        <div class="category-meta">
          <span class="meta-item">
            <span class="icon-[ph--article-bold]"></span>
            <span><?php echo (int) $this->getTotal(); ?> 篇文章</span>
          </span>
        </div>
      </div>
    </header>
    <?php if ($showFeatured): ?>
      <?php clarity_render_featured_posts($featuredPosts); ?>
    <?php endif; ?>

    <div class="post-list">
      <menu class="posts-container proper-height">
        <?php $delayIndex = 0; ?>
        <?php while ($this->next()): ?>
          <?php $cover = clarity_get_cover($this); ?>
          <?php $excerpt = clarity_get_excerpt($this, 120); ?>
          <?php $views = clarity_get_views($this); ?>
          <?php $postTitle = clarity_display_text((string) $this->title); ?>
          <article class="article-card card" style="--delay: <?php echo ($delayIndex * 0.05); ?>s">
            <a href="<?php $this->permalink(); ?>">
              <?php if ($cover !== ''): ?>
                <img src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" class="article-cover" loading="lazy" alt="<?php echo $postTitle; ?>" />
              <?php endif; ?>
            </a>
            <div class="article-body">
              <h2 class="article-title text-creative"><a href="<?php $this->permalink(); ?>"><?php echo $postTitle; ?></a></h2>
              <?php if ($excerpt !== ''): ?>
                <p class="article-description"><?php echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>
              <div class="article-info">
                <span class="info-item"><span class="icon-[ph--calendar-dots-bold]"></span><time><?php $this->date('Y-m-d'); ?></time></span>
                <span class="info-item"><span class="icon-[ph--eye-bold]"></span><span><?php echo $views !== null ? $views : 0; ?></span></span>
              </div>
            </div>
          </article>
          <?php $delayIndex++; ?>
        <?php endwhile; ?>
      </menu>
      <?php clarity_render_pagination($this, 'tag'); ?>
    </div>
  </div>
<?php else: ?>
  <div class="archive proper-height">
    <header class="tag-header">
      <a href="<?php echo $this->options->siteUrl; ?>/tags" class="tag-back" title="返回标签云">
        <span class="icon-[ph--arrow-left-bold]"></span>
        <span>标签云</span>
      </a>
      <div class="tag-info">
        <h1 class="tag-name"><span class="tag-hash">#</span><span><?php echo $safeTagName; ?></span></h1>
        <p class="tag-meta">共 <strong><?php echo (int) $this->getTotal(); ?></strong> 篇文章</p>
      </div>
    </header>
    <?php if ($showFeatured): ?>
      <?php clarity_render_featured_posts($featuredPosts); ?>
    <?php endif; ?>

    <menu class="tag-list">
      <?php $delayIndex = 0; ?>
      <?php while ($this->next()): ?>
        <?php $cover = clarity_get_cover($this); ?>
        <?php $postTitle = clarity_display_text((string) $this->title); ?>
        <li class="article-item" style="--delay: <?php echo ($delayIndex * 0.03); ?>s">
          <time><?php $this->date('m/d'); ?></time>
          <a href="<?php $this->permalink(); ?>" class="article-link gradient-card" title="<?php echo htmlspecialchars(clarity_get_excerpt($this, 80), ENT_QUOTES, 'UTF-8'); ?>">
            <span class="article-title"><?php echo $postTitle; ?></span>
            <?php if ($cover !== ''): ?>
              <img src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" class="article-cover" loading="lazy" alt="<?php echo $postTitle; ?>" />
            <?php endif; ?>
          </a>
        </li>
        <?php $delayIndex++; ?>
      <?php endwhile; ?>
    </menu>

    <?php if (!$this->have()): ?>
      <div class="empty-state">
        <span class="icon-[ph--article-bold]"></span>
        <p>该标签下暂无文章</p>
      </div>
    <?php endif; ?>

    <?php clarity_render_pagination($this, 'tag'); ?>
  </div>
<?php endif; ?>

<?php $this->need('footer.php'); ?>

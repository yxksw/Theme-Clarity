<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$enableToc = clarity_should_show_toc($this, 'page');
clarity_set('showAside', $enableToc);
clarity_set('pageTitle', null);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$cover = clarity_get_cover($this);
$centerTitle = clarity_bool(clarity_opt('center_title', '0'));
$commentCount = $this->commentsNum ?? 0;
$views = clarity_get_views($this);
?>

<div class="post-header <?php echo ($cover !== '' ? 'has-cover ' : '') . ($centerTitle ? 'center-title' : ''); ?>">
  <?php if ($cover !== ''): ?>
    <img src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" class="post-cover" alt="<?php $this->title(); ?>" />
  <?php endif; ?>

  <div class="post-nav">
    <div class="operations">
      <button class="z-btn" id="share-btn" title="复制分享文本">
        <span class="icon-[ph--share-bold]"></span>
        <span>文字分享</span>
      </button>
    </div>

    <div class="post-info">
      <?php clarity_render_author_capsule($this); ?>

      <span>
        <span class="icon-[ph--calendar-dots-bold]"></span>
        <time><?php $this->date('Y-m-d'); ?></time>
      </span>

      <span>
        <span class="icon-[ph--chat-circle-dots-bold]"></span>
        <span><?php echo (int) $commentCount; ?></span> 评论
      </span>

      <span>
        <span class="icon-[ph--eye-bold]"></span>
        <span><?php echo $views !== null ? $views : 0; ?></span> 阅读
      </span>
    </div>
  </div>

  <h1 class="post-title text-creative"><?php $this->title(); ?></h1>
</div>

<article class="article">
  <?php $this->content(); ?>
</article>

<?php if ($this->allow('comment')): ?>
  <h3 class="comment-title"><span class="icon-[ph--chat-circle-text-bold]"></span>评论区</h3>
  <section class="z-comment" id="comment">
    <?php $this->need('comments.php'); ?>
  </section>
<?php endif; ?>

<?php
$user = \Typecho\Widget::widget('Widget_User');
if (clarity_bool(clarity_opt('enable_edit', '0')) && $user->hasLogin()):
?>
  <button id="edit-page-btn" class="edit-page-btn" data-url="<?php echo $this->options->adminUrl . 'write-page.php?cid=' . $this->cid; ?>" title="编辑页面">
    <span class="icon-[ph--pencil-bold]"></span>
  </button>
<?php endif; ?>

<script>
  (function () {
    const shareBtn = document.getElementById('share-btn');
    if (shareBtn) {
      const title = <?php echo json_encode($this->title); ?>;
      const url = window.location.href;
      const siteTitle = <?php echo json_encode($this->options->title); ?>;
      const shareText = `【${siteTitle}】${title}\n\n${url}`;
      shareBtn.addEventListener('click', async () => {
        try {
          if (window.clarityCopyText) {
            await window.clarityCopyText(shareText);
          } else if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(shareText);
          }
          const icon = shareBtn.querySelector('span:first-child');
          const text = shareBtn.querySelector('span:last-child');
          icon.className = 'icon-[ph--check-bold]';
          text.textContent = '已复制';
          setTimeout(() => {
            icon.className = 'icon-[ph--share-bold]';
            text.textContent = '文字分享';
          }, 2000);
        } catch (err) {
          console.error('复制失败:', err);
        }
      });
    }

    const editPageBtn = document.getElementById('edit-page-btn');
    if (editPageBtn) {
      editPageBtn.addEventListener('click', () => {
        const editUrl = editPageBtn.dataset.url;
        if (editUrl) window.location.href = editUrl;
      });
    }
  })();
</script>

<?php $this->need('footer.php'); ?>

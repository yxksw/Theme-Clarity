<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$enableToc = clarity_should_show_toc($this, 'post');
clarity_set('showAside', $enableToc);
clarity_set('pageTitle', null);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$cover = clarity_get_cover($this);
$centerTitle = clarity_bool(clarity_opt('center_title', '0'));
$showExcerpt = clarity_bool(clarity_opt('show_excerpt', '1'));
$excerpt = clarity_get_excerpt($this, 120);
$excerptAnimation = clarity_bool(clarity_opt('excerpt_animation', '1'));
$excerptSpeed = (int) clarity_opt('excerpt_speed', '50');
$excerptCaret = clarity_opt('excerpt_caret', '_');
$outdatedEnabled = clarity_bool(clarity_opt('outdated_enabled', '1'));
$outdatedDays = (int) clarity_opt('outdated_days', '180');
$outdatedMessage = clarity_opt('outdated_message', '本文发布于 {days} 天前，内容可能已过时，请注意甄别。');
$enableTitleColor = isset($this->fields->enable_post_title_color) && clarity_bool($this->fields->enable_post_title_color);
$titleColor = isset($this->fields->post_title_color) ? $this->fields->post_title_color : '#FFFFFF';
$isOriginal = !isset($this->fields->post_original) || clarity_bool($this->fields->post_original, true);
$hasCustomLicense = isset($this->fields->post_license) && clarity_bool($this->fields->post_license);
$customLicenseText = $this->fields->post_license_text ?? '';
$customLicenseUrl = $this->fields->post_license_url ?? '';
$originName = $this->fields->post_original_name ?? '';
$originUrl = $this->fields->post_original_url ?? '';
$originText = $this->fields->post_original_text ?? '此文来自 {post_original} ，侵删。';
$aiGenerated = isset($this->fields->ai_generated) && clarity_bool($this->fields->ai_generated);
$aiDesc = $this->fields->ai_generated_desc ?? '本文内容由 AI 辅助生成，已经人工审核和编辑。';
$views = clarity_get_views($this);
$commentCount = $this->commentsNum ?? 0;
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
      <button class="z-btn" id="poster-btn" title="生成分享海报">
        <span class="icon-[ph--image-bold]"></span>
        <span>海报分享</span>
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

      <?php if ($this->categories): ?>
        <span>
          <span class="icon-[ph--folder-bold]"></span>
          <?php $this->category(','); ?>
        </span>
      <?php endif; ?>

      <span>
        <span class="icon-[ph--eye-bold]"></span>
        <span><?php echo $views !== null ? $views : 0; ?></span> 阅读
      </span>
    </div>
  </div>

  <h1 class="post-title text-creative"<?php echo $enableTitleColor ? ' style="color:' . htmlspecialchars($titleColor, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>><?php $this->title(); ?></h1>
</div>

<?php if ($showExcerpt && $excerpt !== ''): ?>
  <div class="md-excerpt gradient-card" data-animation="<?php echo $excerptAnimation ? 'true' : 'false'; ?>" data-speed="<?php echo (int) $excerptSpeed; ?>" data-caret="<?php echo htmlspecialchars($excerptCaret, ENT_QUOTES, 'UTF-8'); ?>">
    <span class="icon-[ph--highlighter-bold]"></span>
    <span id="excerpt-text" data-text="<?php echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $excerptAnimation ? '' : htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8'); ?></span>
    <?php if ($excerptAnimation): ?>
      <span id="excerpt-caret" class="excerpt-caret"><?php echo htmlspecialchars($excerptCaret, ENT_QUOTES, 'UTF-8'); ?></span>
    <?php endif; ?>
  </div>
  <script>
    (function () {
      const container = document.querySelector('.md-excerpt');
      if (!container || container.dataset.animation === 'false') return;
      const el = document.getElementById('excerpt-text');
      const caret = document.getElementById('excerpt-caret');
      if (!el) return;
      const text = el.dataset.text || '';
      const speed = parseInt(container.dataset.speed) || 50;
      let index = 0;
      function type() {
        if (index < text.length) {
          el.textContent += text[index];
          index++;
          setTimeout(type, speed);
        } else if (caret) {
          caret.style.display = 'none';
        }
      }
      type();
    })();
  </script>
<?php endif; ?>

<?php if ($outdatedEnabled): ?>
  <div class="outdated-notice" data-publish-time="<?php echo $this->date('c'); ?>" data-threshold="<?php echo (int) $outdatedDays; ?>" data-message="<?php echo htmlspecialchars($outdatedMessage, ENT_QUOTES, 'UTF-8'); ?>" style="display:none">
    <span class="icon-[ph--warning-circle-bold]"></span>
    <span class="notice-text"></span>
  </div>
  <script>
    (function () {
      const notice = document.querySelector('.outdated-notice');
      if (!notice) return;
      const publishTime = new Date(notice.dataset.publishTime).getTime();
      const threshold = parseInt(notice.dataset.threshold) || 180;
      const messageTemplate = notice.dataset.message;
      const now = Date.now();
      const daysPassed = Math.floor((now - publishTime) / (1000 * 60 * 60 * 24));
      if (daysPassed >= threshold) {
        const text = messageTemplate.replace('{days}', daysPassed);
        notice.querySelector('.notice-text').textContent = text;
        notice.style.display = '';
      }
    })();
  </script>
<?php endif; ?>

<?php if ($aiGenerated): ?>
  <div class="ai-notice">
    <span class="icon-[ph--sparkle-bold]"></span>
    <span class="notice-text"><?php echo htmlspecialchars($aiDesc, ENT_QUOTES, 'UTF-8'); ?></span>
  </div>
<?php endif; ?>

<article class="article">
  <?php $this->content(); ?>
</article>

<div class="post-footer">
  <?php if (!empty($this->tags)): ?>
    <section class="tags-section">
      <div class="title text-creative">文章标签</div>
      <div class="content tags-list">
        <?php foreach ($this->tags as $tag): ?>
          <a class="tag-item" href="<?php echo htmlspecialchars($tag['permalink'], ENT_QUOTES, 'UTF-8'); ?>">#<?php echo htmlspecialchars($tag['name'], ENT_QUOTES, 'UTF-8'); ?></a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>

  <section class="license">
    <?php if ($isOriginal): ?>
      <div class="title text-creative">许可协议</div>
      <div class="content">
        本文采用
        <a href="<?php echo htmlspecialchars($hasCustomLicense ? $customLicenseUrl : clarity_opt('license_url', ''), ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
          <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" style="vertical-align:-0.125em;display:inline">
            <path fill="currentColor" d="M9 8c1.104 0 2.105.448 2.829 1.173l-1.414 1.413a2 2 0 1 0 0 2.828l1.413 1.414A4.001 4.001 0 0 1 5 12c0-2.208 1.792-4 4-4m9.829 1.173A4.001 4.001 0 0 0 12 12a4.001 4.001 0 0 0 6.828 2.828l-1.414-1.414a2 2 0 1 1 0-2.828zM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12m10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16" />
          </svg>
          <?php if ($hasCustomLicense): ?>
            <span><?php echo htmlspecialchars($customLicenseText, ENT_QUOTES, 'UTF-8'); ?></span>
          <?php else: ?>
            <span><?php echo htmlspecialchars(clarity_opt('license', ''), ENT_QUOTES, 'UTF-8'); ?></span>
          <?php endif; ?>
        </a>
        许可协议，转载请注明出处。
      </div>
    <?php else: ?>
      <?php
      $replaceHtml = '<a href="' . htmlspecialchars($originUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank">' . htmlspecialchars($originName, ENT_QUOTES, 'UTF-8') . '</a>';
      $originContent = str_replace('{post_original}', $replaceHtml, $originText);
      ?>
      <div class="title text-creative">文章来源</div>
      <div class="content"><?php echo $originContent; ?></div>
    <?php endif; ?>
  </section>
</div>

<?php
$prevPost = null;
$nextPost = null;
try {
    $db = \Typecho\Db::get();
    $created = $this->created;
    $type = $this->type;
    if (clarity_bool(clarity_opt('cursor_order', '1'))) {
        $prevQuery = $db->select()->from('table.contents')
            ->where('table.contents.created < ?', $created)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $type)
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->order('table.contents.created', \Typecho\Db::SORT_DESC)
            ->limit(1);
        $nextQuery = $db->select()->from('table.contents')
            ->where('table.contents.created > ? AND table.contents.created < ?', $created, $this->options->time)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $type)
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->order('table.contents.created', \Typecho\Db::SORT_ASC)
            ->limit(1);
    } else {
        $prevQuery = $db->select()->from('table.contents')
            ->where('table.contents.created > ? AND table.contents.created < ?', $created, $this->options->time)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $type)
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->order('table.contents.created', \Typecho\Db::SORT_ASC)
            ->limit(1);
        $nextQuery = $db->select()->from('table.contents')
            ->where('table.contents.created < ?', $created)
            ->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', $type)
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->order('table.contents.created', \Typecho\Db::SORT_DESC)
            ->limit(1);
    }
    $prevPost = clarity_contents_from('clarity_prev_' . $this->cid, $prevQuery);
    $nextPost = clarity_contents_from('clarity_next_' . $this->cid, $nextQuery);
} catch (\Throwable $e) {
}
?>

<?php if (($prevPost && $prevPost->have()) || ($nextPost && $nextPost->have())): ?>
  <div class="surround-post">
    <?php if ($prevPost && $prevPost->have() && $prevPost->next()): ?>
      <a href="<?php echo $prevPost->permalink; ?>" class="surround-link">
        <span class="icon-[solar--rewind-back-bold-duotone] rtl-flip"></span>
        <div class="surround-text">
          <strong class="title text-creative"><?php echo htmlspecialchars($prevPost->title, ENT_QUOTES, 'UTF-8'); ?></strong>
          <span class="date"><?php echo $prevPost->date('Y-m-d'); ?></span>
        </div>
      </a>
    <?php else: ?>
      <div class="surround-link no-link">
        <span class="icon-[solar--document-add-bold-duotone]"></span>
        <div class="surround-text"><strong class="title">新故事即将发生</strong></div>
      </div>
    <?php endif; ?>

    <?php if ($nextPost && $nextPost->have() && $nextPost->next()): ?>
      <a href="<?php echo $nextPost->permalink; ?>" class="surround-link align-end">
        <span class="icon-[solar--rewind-forward-bold-duotone] rtl-flip"></span>
        <div class="surround-text">
          <strong class="title text-creative"><?php echo htmlspecialchars($nextPost->title, ENT_QUOTES, 'UTF-8'); ?></strong>
          <span class="date"><?php echo $nextPost->date('Y-m-d'); ?></span>
        </div>
      </a>
    <?php else: ?>
      <div class="surround-link align-end no-link">
        <span class="icon-[solar--reel-bold-duotone]"></span>
        <div class="surround-text"><strong class="title">已抵达博客尽头</strong></div>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>

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
  <button id="edit-post-btn" class="edit-post-btn" data-url="<?php echo $this->options->adminUrl . 'write-post.php?cid=' . $this->cid; ?>" title="编辑文章">
    <span class="icon-[ph--pencil-bold]"></span>
  </button>
<?php endif; ?>

<div id="poster-modal" class="poster-modal" style="display:none">
  <div class="poster-modal-overlay"></div>
  <div class="poster-modal-content">
    <button class="poster-close" id="poster-close"><span class="icon-[ph--x-bold]"></span></button>
    <div class="poster-preview">
      <div class="poster-card" id="poster-card">
        <div class="poster-header">
          <?php if ($cover !== ''): ?>
            <img src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" class="poster-cover" alt="<?php $this->title(); ?>" crossorigin="anonymous" />
          <?php endif; ?>
          <div class="poster-gradient"></div>
        </div>
        <div class="poster-body">
          <h2 class="poster-title"><?php $this->title(); ?></h2>
          <?php if ($excerpt !== ''): ?>
            <p class="poster-excerpt"><?php echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>
          <div class="poster-meta">
            <span><?php $this->date('Y-m-d'); ?></span>
            <?php if ($this->categories): ?>
              <span><?php $this->category(','); ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div class="poster-footer">
          <div class="poster-qrcode" id="poster-qrcode"></div>
          <div class="poster-site">
            <div class="poster-site-name"><?php echo htmlspecialchars($this->options->title, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="poster-site-tip">扫码阅读全文</div>
          </div>
        </div>
      </div>
    </div>
    <div class="poster-actions">
      <button class="z-btn primary" id="poster-download">
        <span class="icon-[ph--download-bold]"></span>
        <span>保存海报</span>
      </button>
    </div>
  </div>
</div>

<script>
  (function () {
    const title = <?php echo json_encode($this->title); ?>;
    const excerpt = <?php echo json_encode($excerpt); ?>;
    const siteTitle = <?php echo json_encode($this->options->title); ?>;
    const url = window.location.href;

    const shareBtn = document.getElementById('share-btn');
    if (shareBtn) {
      const shareText = `【${siteTitle}】${title}\n\n${excerpt ? excerpt + "\n\n" : ""}${url}`;
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

    const posterBtn = document.getElementById('poster-btn');
    const posterModal = document.getElementById('poster-modal');
    const posterClose = document.getElementById('poster-close');
    const posterDownload = document.getElementById('poster-download');
    const posterQrcode = document.getElementById('poster-qrcode');
    const posterCard = document.getElementById('poster-card');

    const editPostBtn = document.getElementById('edit-post-btn');
    if (editPostBtn) {
      editPostBtn.addEventListener('click', () => {
        const editUrl = editPostBtn.dataset.url;
        if (editUrl) window.location.href = editUrl;
      });
    }

    if (posterBtn && posterModal) {
      let qrGenerated = false;
      posterBtn.addEventListener('click', () => {
        posterModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        if (!qrGenerated && window.generateQRCode) {
          window.generateQRCode(posterQrcode, url);
          qrGenerated = true;
        }
      });
      const closeModal = () => {
        posterModal.style.display = 'none';
        document.body.style.overflow = '';
      };
      posterClose.addEventListener('click', closeModal);
      posterModal.querySelector('.poster-modal-overlay').addEventListener('click', closeModal);

      posterDownload.addEventListener('click', async () => {
        const icon = posterDownload.querySelector('span:first-child');
        const text = posterDownload.querySelector('span:last-child');
        const oldIcon = icon.className;
        const oldText = text.textContent;
        icon.className = 'icon-[ph--spinner] animate-spin';
        text.textContent = '生成中...';
        try {
          if (window.generatePoster) {
            await window.generatePoster(posterCard, title);
            icon.className = 'icon-[ph--check-bold]';
            text.textContent = '已保存';
          }
        } catch (err) {
          icon.className = 'icon-[ph--warning-bold]';
          text.textContent = '生成失败';
        }
        setTimeout(() => {
          icon.className = oldIcon;
          text.textContent = oldText;
        }, 2000);
      });
    }
  })();
</script>

<?php $this->need('footer.php'); ?>

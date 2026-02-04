<?php
/**
 * 友链
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$linksTitle = trim((string) clarity_opt('links_title', '友链'));
clarity_set('showAside', false);
clarity_set('pageTitle', $linksTitle);
clarity_set('isLinksPage', true);
?>
<?php $this->need('header.php'); ?>
<?php
$logoFallback = \Typecho\Common::url('assets/images/logo.svg', $this->options->themeUrl);
$logo = clarity_site_logo($logoFallback);
$showTitle = clarity_bool(clarity_opt('show_title', '1'));
$emojiTail = trim((string) clarity_opt('emoji_tail', ''));
$siteSubtitle = trim((string) clarity_opt('subtitle', $this->options->description));
$groups = clarity_links_groups();

$myInfoRaw = trim((string) clarity_opt('links_my_info', ''));
$myInfo = $myInfoRaw !== '' ? json_decode($myInfoRaw, true) : [];
if (!is_array($myInfo)) $myInfo = [];
$myTitle = $myInfo['title'] ?? $this->options->title;
$myUrl = $myInfo['url'] ?? $this->options->siteUrl;
$myLogo = $myInfo['logo'] ?? $logo;
$myDesc = $myInfo['description'] ?? $siteSubtitle;
$myRss = $myInfo['rss'] ?? $this->options->feedUrl;
$linksApply = trim((string) clarity_opt('links_apply', ''));

$flatLinks = [];
if (is_array($groups)) {
    foreach ($groups as $group) {
        if (!is_array($group)) continue;
        $links = $group['links'] ?? [];
        if (!is_array($links)) continue;
        foreach ($links as $link) {
            if (!is_array($link)) continue;
            $flatLinks[] = [
                'name' => $link['name'] ?? $link['title'] ?? '',
                'url' => $link['url'] ?? '',
                'logo' => $link['logo'] ?? '',
                'desc' => $link['desc'] ?? $link['description'] ?? ''
            ];
        }
    }
}
?>

<div class="clarity-header mobile-only">
  <?php if ($emojiTail !== ''): ?>
    <div class="emoji-tail">
      <?php
      $emojis = array_filter(array_map('trim', explode(',', $emojiTail)));
      foreach ($emojis as $index => $emoji):
      ?>
        <span class="split-char" style="--delay: <?php echo ($index * 0.6 - 3); ?>s"><?php echo $emoji; ?></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <a href="<?php echo $this->options->siteUrl; ?>">
    <?php if ($logo): ?>
      <img src="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" class="clarity-logo<?php echo $showTitle ? ' circle' : ''; ?>" alt="<?php echo htmlspecialchars($this->options->title, ENT_QUOTES, 'UTF-8'); ?>" />
    <?php endif; ?>
  </a>

  <?php if ($showTitle): ?>
    <div class="clarity-text">
      <div class="header-title">
        <?php
        $chars = preg_split('//u', $this->options->title, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($chars as $idx => $char):
        ?>
          <span class="split-char" style="--delay: <?php echo (($idx + 1) * 0.1); ?>s"><?php echo htmlspecialchars($char, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endforeach; ?>
      </div>
      <div class="header-subtitle"><?php echo htmlspecialchars($linksTitle, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>
  <?php endif; ?>
</div>

<div class="links-page">
  <?php if (is_array($groups)): ?>
    <?php foreach ($groups as $group): ?>
      <?php
      if (!is_array($group)) continue;
      $groupTitle = $group['title'] ?? '';
      $groupDesc = $group['description'] ?? '';
      $links = $group['links'] ?? [];
      if (!is_array($links) || empty($links)) continue;
      ?>
      <section class="feed-group">
        <h3 class="feed-title"><?php echo htmlspecialchars((string) $groupTitle, ENT_QUOTES, 'UTF-8'); ?></h3>
        <?php if (!empty($groupDesc)): ?>
          <p class="feed-desc"><?php echo htmlspecialchars((string) $groupDesc, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <menu class="feed-list">
          <?php foreach ($links as $link): ?>
            <?php
            if (!is_array($link)) continue;
            $name = $link['name'] ?? $link['title'] ?? '';
            $url = $link['url'] ?? '#';
            $logoUrl = $link['logo'] ?? '';
            $desc = $link['desc'] ?? $link['description'] ?? '';
            $domain = preg_replace('#^https?://#', '', (string) $url);
            ?>
            <li>
              <div class="feed-card-wrapper" data-link="<?php echo htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8'); ?>">
                <a href="<?php echo htmlspecialchars((string) $url, ENT_QUOTES, 'UTF-8'); ?>" class="feed-card gradient-card" target="_blank" rel="noopener noreferrer">
                  <div class="avatar">
                    <?php if ($logoUrl !== ''): ?>
                      <img src="<?php echo htmlspecialchars((string) $logoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" />
                    <?php else: ?>
                      <span class="icon-[ph--link-bold]"></span>
                    <?php endif; ?>
                  </div>
                  <span class="author"><?php echo htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'); ?></span>
                </a>

                <div class="feed-tooltip">
                  <div class="site-content">
                    <?php if ($logoUrl !== ''): ?>
                      <img class="site-icon" src="<?php echo htmlspecialchars((string) $logoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'); ?>" />
                    <?php else: ?>
                      <span class="icon-[ph--link-bold]"></span>
                    <?php endif; ?>
                    <div class="site-info">
                      <h4 class="text-creative"><?php echo htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8'); ?></h4>
                      <code class="domain"><?php echo htmlspecialchars((string) $domain, ENT_QUOTES, 'UTF-8'); ?></code>
                    </div>
                  </div>
                  <div class="desc-content">
                    <p><?php echo htmlspecialchars((string) $desc, ENT_QUOTES, 'UTF-8'); ?></p>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>
        </menu>
      </section>
    <?php endforeach; ?>
  <?php endif; ?>

  <div class="custom-tabs-container" id="link-tabs">
    <div class="custom-tabs-header">
      <div class="custom-tabs-nav">
        <button class="custom-tab-item active" data-tab="tab-info" onclick="switchTab(this)">我的博客信息</button>
        <button class="custom-tab-item" data-tab="tab-apply" onclick="switchTab(this)">申请友链</button>
      </div>
    </div>

    <div class="custom-tabs-content">
      <div id="tab-info" class="custom-tab-pane active">
        <div class="link-tab">
          <div class="my-feed-card gradient-card">
            <div class="avatar">
              <?php if ($myLogo !== ''): ?>
                <img src="<?php echo htmlspecialchars((string) $myLogo, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) $myTitle, ENT_QUOTES, 'UTF-8'); ?>" />
              <?php else: ?>
                <span class="icon-[ph--planet-bold]"></span>
              <?php endif; ?>
            </div>
            <div class="info">
              <span class="author"><?php echo htmlspecialchars((string) $myTitle, ENT_QUOTES, 'UTF-8'); ?></span>
              <span class="title"><?php echo htmlspecialchars((string) $myDesc, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
          </div>

          <div class="copy-fields">
            <div class="copy-item">
              <span class="prompt">名称</span>
              <div class="code"><?php echo htmlspecialchars((string) $myTitle, ENT_QUOTES, 'UTF-8'); ?></div>
              <button class="operation copy-btn" aria-label="复制" data-copy="<?php echo htmlspecialchars((string) $myTitle, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="icon-[ph--copy-bold]"></span>
              </button>
            </div>
            <div class="copy-item">
              <span class="prompt">网址</span>
              <div class="code"><?php echo htmlspecialchars((string) $myUrl, ENT_QUOTES, 'UTF-8'); ?></div>
              <button class="operation copy-btn" aria-label="复制" data-copy="<?php echo htmlspecialchars((string) $myUrl, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="icon-[ph--copy-bold]"></span>
              </button>
            </div>
            <div class="copy-item">
              <span class="prompt">Logo</span>
              <div class="code"><?php echo htmlspecialchars((string) $myLogo, ENT_QUOTES, 'UTF-8'); ?></div>
              <button class="operation copy-btn" aria-label="复制" data-copy="<?php echo htmlspecialchars((string) $myLogo, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="icon-[ph--copy-bold]"></span>
              </button>
            </div>
            <div class="copy-item">
              <span class="prompt">描述</span>
              <div class="code"><?php echo htmlspecialchars((string) $myDesc, ENT_QUOTES, 'UTF-8'); ?></div>
              <button class="operation copy-btn" aria-label="复制" data-copy="<?php echo htmlspecialchars((string) $myDesc, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="icon-[ph--copy-bold]"></span>
              </button>
            </div>
            <div class="copy-item">
              <span class="prompt">RSS</span>
              <div class="code"><?php echo htmlspecialchars((string) $myRss, ENT_QUOTES, 'UTF-8'); ?></div>
              <button class="operation copy-btn" aria-label="复制" data-copy="<?php echo htmlspecialchars((string) $myRss, ENT_QUOTES, 'UTF-8'); ?>">
                <span class="icon-[ph--copy-bold]"></span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div id="tab-apply" class="custom-tab-pane">
        <div class="link-tab">
          <div class="apply-content article">
            <?php if ($linksApply !== ''): ?>
              <?php echo $linksApply; ?>
            <?php else: ?>
              <p>欢迎与我交换友链！请在评论区留下你的博客信息：</p>
              <ul>
                <li><strong>博客名称</strong>：你的博客标题</li>
                <li><strong>博客地址</strong>：你的博客 URL</li>
                <li><strong>博客描述</strong>：一句话介绍</li>
                <li><strong>博客头像</strong>：Logo 或头像链接</li>
              </ul>
              <p>我会尽快审核并添加你的友链。</p>
            <?php endif; ?>
          </div>
          <div class="link-submit-actions">
            <div id="link-submit-unavailable" class="link-submit-unavailable">
              <span class="icon-[ph--warning-circle-bold]"></span>
              <span>自主提交插件未安装</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if ($this->allow('comment')): ?>
    <h3 class="comment-title">
      <span class="icon-[ph--chat-circle-text-bold]"></span>
      评论区
    </h3>
    <section class="z-comment" id="comment">
      <?php $this->need('comments.php'); ?>
    </section>
  <?php endif; ?>
</div>

<button id="random-link-btn" class="random-link-btn" onclick="randomVisitLink()" title="随机穿梭">
  <span class="icon-[ph--shuffle-bold]"></span>
</button>

<script>
  window.linkSubmitConfig = { enableSubmit: false, enableUpdate: false, verifyType: 'email' };
  window.clarityLinks = <?php echo json_encode($flatLinks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

  window.randomVisitLink = window.randomVisitLink || function () {
    const linkCards = document.querySelectorAll('.feed-card-wrapper[data-link]');
    if (linkCards.length === 0) {
      alert('暂无友链可访问');
      return;
    }
    const linksInfo = Array.from(linkCards)
      .map((card) => ({
        url: card.getAttribute('data-link') || '',
        name: card.querySelector('.author')?.textContent?.trim() || '',
        logo: card.querySelector('.avatar img')?.src || '',
        desc: card.querySelector('.desc-content p')?.textContent?.trim() || ''
      }))
      .filter((item) => item.url);

    const randomLink = linksInfo[Math.floor(Math.random() * linksInfo.length)];
    if (window.openShuttle) {
      window.openShuttle(randomLink);
    } else {
      window.open(randomLink.url, '_blank', 'noopener,noreferrer');
    }
  };

  window.switchTab = window.switchTab || function (btn) {
    const targetId = btn.getAttribute('data-tab');
    const tabsContainer = btn.closest('.custom-tabs-container');
    if (!tabsContainer) return;
    tabsContainer.querySelectorAll('.custom-tab-item').forEach((t) => t.classList.remove('active'));
    btn.classList.add('active');
    tabsContainer.querySelectorAll('.custom-tab-pane').forEach((content) => {
      content.classList.toggle('active', content.id === targetId);
    });
  };

  (function () {
    document.querySelectorAll('.copy-btn').forEach((btn) => {
      btn.addEventListener('click', async function () {
        const text = this.getAttribute('data-copy');
        try {
          if (window.clarityCopyText) {
            await window.clarityCopyText(text);
          } else if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(text);
          }
          const icon = this.querySelector('span');
          if (!icon) return;
          icon.className = 'icon-[ph--check-bold]';
          setTimeout(() => {
            icon.className = 'icon-[ph--copy-bold]';
          }, 2000);
        } catch (err) {
          console.error('复制失败:', err);
        }
      });
    });

    document.querySelectorAll('.feed-card-wrapper').forEach((wrapper) => {
      const link = wrapper.getAttribute('data-link') || '';
      let hash = 0;
      for (const char of link) hash = hash * 31 + char.charCodeAt(0);
      wrapper.style.setProperty('--delay', (Math.abs(hash) % 1000) / 1000 + 's');
    });

    document.querySelectorAll('.feed-card-wrapper').forEach((wrapper) => {
      wrapper.addEventListener('mouseenter', function () {
        const tooltip = this.querySelector('.feed-tooltip');
        if (!tooltip) return;
        tooltip.style.visibility = 'visible';
        tooltip.style.opacity = '0';
        const rect = tooltip.getBoundingClientRect();
        if (rect.top < 10) {
          tooltip.classList.add('tooltip-bottom');
        } else {
          tooltip.classList.remove('tooltip-bottom');
        }
        tooltip.style.visibility = '';
        tooltip.style.opacity = '';
      });

      wrapper.addEventListener('mouseleave', function () {
        const tooltip = this.querySelector('.feed-tooltip');
        if (tooltip) {
          tooltip.classList.remove('tooltip-bottom');
        }
      });
    });
  })();
</script>

<?php $this->need('footer.php'); ?>

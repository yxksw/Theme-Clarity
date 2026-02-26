<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$exploreLinks = clarity_json_option('footer_explore_json', []);
$footerLinks = clarity_json_option('footer_links_json', []);
$showRss = clarity_bool(clarity_opt('footer_show_rss', '1'));
$showTravellings = clarity_bool(clarity_opt('footer_show_travellings', '1'));
$siteBase = rtrim((string) $this->options->siteUrl, '/');
$rssUrl = $siteBase . '/feed';
$beian = trim((string) clarity_opt('footer_beian', ''));
$gongan = trim((string) clarity_opt('footer_gongan', ''));
$enableUptime = clarity_bool(clarity_opt('footer_uptime_kuma', '0'));
$uptimeBadgeUrl = trim((string) clarity_opt('footer_uptime_kuma_badge', ''));
$uptimeStatusUrl = trim((string) clarity_opt('footer_uptime_kuma_url', ''));
$customFooterHtml = trim((string) clarity_opt('footerhtml', ''));
$isLinksPage = clarity_get('isLinksPage', false);
$linksRandom = clarity_bool(clarity_opt('links_random', '1'));
$logoFallback = \Typecho\Common::url('assets/images/logo.svg', $this->options->themeUrl);
$logo = clarity_site_logo($logoFallback);
$authError = '';
if (isset($_COOKIE['__typecho_notice_type']) && $_COOKIE['__typecho_notice_type'] === 'error' && isset($_COOKIE['__typecho_notice'])) {
  $noticeRaw = json_decode((string) $_COOKIE['__typecho_notice'], true);
  if (is_array($noticeRaw) && isset($noticeRaw[0])) {
    $authError = (string) $noticeRaw[0];
  } elseif (is_string($noticeRaw)) {
    $authError = $noticeRaw;
  }
}
$authForgotUrl = rtrim((string) $this->options->adminUrl, '/') . '/forgot-password.php';
$renderIcon = function ($icon) {
  $icon = trim((string) $icon);
  if ($icon === '') {
    return '';
  }
  if (preg_match('/icon-\[([a-z0-9]+)--([^\]]+)\]/i', $icon, $match)) {
    $prefix = strtolower($match[1]);
    $name = $match[2];
    $safeName = preg_replace('/[^a-z0-9\-:_]/i', '', $name);
    $iconName = $prefix . ':' . $safeName;
    $iconUrl = 'https://api.iconify.design/' . rawurlencode($prefix) . '/' . rawurlencode($safeName) . '.svg';
    return '<span class="iconify-mask ' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" data-icon="' . htmlspecialchars($iconName, ENT_QUOTES, 'UTF-8') . '" style="--icon-url:url(\'' . htmlspecialchars($iconUrl, ENT_QUOTES, 'UTF-8') . '\')"></span>';
  }
  if (preg_match('/\\bph\\s+ph-([a-z0-9-]+)\\b/i', $icon, $match)) {
    $name = strtolower($match[1]);
    $iconName = 'ph:' . $name;
    $iconUrl = 'https://api.iconify.design/ph/' . rawurlencode($name) . '.svg';
    return '<span class="iconify-mask ' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" data-icon="' . htmlspecialchars($iconName, ENT_QUOTES, 'UTF-8') . '" style="--icon-url:url(\'' . htmlspecialchars($iconUrl, ENT_QUOTES, 'UTF-8') . '\')"></span>';
  }
  if (preg_match('/^(https?:)?\\//i', $icon) || preg_match('/^\\.\\//', $icon) || preg_match('/^\\.\\.\\//', $icon)) {
    return '<img src="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '" alt="" />';
  }
  return '<span class="' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '"></span>';
};
?>
      <footer class="z-footer">
        <nav class="footer-nav">
          <?php if ($showRss || $showTravellings || !empty($exploreLinks)): ?>
            <div class="footer-nav-group">
              <h3><?php echo htmlspecialchars('探索', ENT_QUOTES, 'UTF-8'); ?></h3>
              <menu>
                <?php if ($showRss): ?>
                  <li>
                    <a href="<?php echo htmlspecialchars($rssUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                      <span class="icon-[ph--rss-simple-bold]"></span>
                      <span class="nav-text">RSS 订阅</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php if ($showTravellings): ?>
                  <li>
                    <a href="https://www.travellings.cn/go.html" target="_blank">
                      <span class="icon-[ph--train-regional-bold]"></span>
                      <span class="nav-text">开往</span>
                    </a>
                  </li>
                <?php endif; ?>
                <?php foreach ($exploreLinks as $link): ?>
                  <?php
                  $url = $link['url'] ?? '#';
                  $text = $link['text'] ?? '';
                  $icon = $link['icon'] ?? '';
                  ?>
                  <li>
                    <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                      <?php if (!empty($icon)): ?>
                        <?php echo $renderIcon($icon); ?>
                      <?php endif; ?>
                      <span class="nav-text"><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                  </li>
                <?php endforeach; ?>
              </menu>
            </div>
          <?php endif; ?>

          <?php if (!empty($footerLinks)): ?>
            <div class="footer-nav-group">
              <h3><?php echo htmlspecialchars('链接', ENT_QUOTES, 'UTF-8'); ?></h3>
              <menu>
                <?php foreach ($footerLinks as $link): ?>
                  <?php
                  $url = $link['url'] ?? '#';
                  $text = $link['text'] ?? '';
                  $icon = $link['icon'] ?? '';
                  ?>
                  <li>
                    <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                      <?php if (!empty($icon)): ?>
                        <?php echo $renderIcon($icon); ?>
                      <?php endif; ?>
                      <span class="nav-text"><?php echo htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                  </li>
                <?php endforeach; ?>
              </menu>
            </div>
          <?php endif; ?>

          <div class="footer-nav-group">
            <h3>信息</h3>
            <menu>
              <li>
                <a href="https://typecho.org" target="_blank">
                  <span class="icon-[ph--cube-bold]"></span>
                  <span class="nav-text">由 Typecho 驱动</span>
                </a>
              </li>
              <li>
                <a href="https://github.com/jkjoy/theme-clarity" target="_blank">
                  <span class="icon-[ph--mountains-bold]"></span>
                  <span class="nav-text">主题 Clarity <?php echo CLARITY_VERSION; ?></span>
                </a>
              </li>
              <?php if ($beian !== ''): ?>
                <li>
                  <a href="https://beian.miit.gov.cn/" target="_blank">
                    <img src="<?php $this->options->themeUrl('assets/images/icp.webp'); ?>" alt="ICP" class="beian-icon" />
                    <span class="nav-text"><?php echo htmlspecialchars($beian, ENT_QUOTES, 'UTF-8'); ?></span>
                  </a>
                </li>
              <?php endif; ?>
              <?php if ($gongan !== ''): ?>
                <li>
                  <a id="gongan-beian-link" href="https://beian.mps.gov.cn/#/query/webSearch" target="_blank">
                    <img src="<?php $this->options->themeUrl('assets/images/gonganbeian.png'); ?>" alt="公安备案" class="beian-icon" />
                    <span id="gongan-beian-text" class="nav-text"><?php echo htmlspecialchars($gongan, ENT_QUOTES, 'UTF-8'); ?></span>
                  </a>
                  <script>
                    (function () {
                      const text = document.getElementById('gongan-beian-text')?.innerText || '';
                      const code = text.replace(/[^0-9]/g, '');
                      if (code) {
                        document.getElementById('gongan-beian-link').href = 'https://beian.mps.gov.cn/#/query/webSearch?code=' + code;
                      }
                    })();
                  </script>
                </li>
              <?php endif; ?>
              <?php if ($enableUptime): ?>
                <?php if ($uptimeBadgeUrl !== ''): ?>
                  <?php $uptimeLink = $uptimeStatusUrl !== '' ? $uptimeStatusUrl : $uptimeBadgeUrl; ?>
                  <li class="xhhaocom-dataStatistics-v2-uptime-kuma uptime-kuma-badge">
                    <a href="<?php echo htmlspecialchars($uptimeLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                      <img src="<?php echo htmlspecialchars($uptimeBadgeUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Uptime Kuma" loading="lazy" />
                    </a>
                  </li>
                <?php else: ?>
                  <li class="xhhaocom-dataStatistics-v2-uptime-kuma"></li>
                <?php endif; ?>
              <?php endif; ?>
            </menu>
          </div>
        </nav>

        <div class="footer-inject">
          <?php if ($customFooterHtml !== ''): ?>
            <?php echo $customFooterHtml; ?>
          <?php endif; ?>
          <?php $this->footer(); ?>
        </div>

        <div class="copyright-card">
          <div class="copyright-nav">
            <div class="time-load">
              <span class="power-by">© <?php echo date('Y'); ?> — <?php echo date('Y'); ?> Powerby</span>
              <a class="copyright-name" href="<?php echo htmlspecialchars($this->options->siteUrl, ENT_QUOTES, 'UTF-8'); ?>" title="<?php echo htmlspecialchars($this->options->title, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                <img class="logo" src="<?php echo htmlspecialchars($logo, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($this->options->title, ENT_QUOTES, 'UTF-8'); ?>" width="25" height="25" loading="lazy" />
                <span class="title"><?php echo htmlspecialchars($this->options->title, ENT_QUOTES, 'UTF-8'); ?></span>
              </a>
            </div>
            <div class="themes">
              <div class="themes-info">
                <span>采用</span>
                <a href="https://github.com/yxksw/Theme-Clarity" target="_blank" rel="noopener noreferrer">Clarity</a>
                <span>主题</span>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </main>

    <?php $this->need('aside.php'); ?>
  </div>

  <div id="z-panel">
    <button id="toggle-sidebar" aria-label="切换菜单" onclick="(function (btn) { var sb = document.getElementById('z-sidebar'), mk = document.getElementById('z-sidebar-bgmask'); sb.classList.toggle('show'); mk.classList.toggle('hidden'); btn.classList.toggle('active'); })(this)">
      <span class="icon-[ph--sidebar-duotone] rtl-flip" style="display:block"></span>
    </button>
    <?php if (clarity_get('showAside', true) && clarity_bool(clarity_opt('aside_enable', '1'))): ?>
      <button id="toggle-aside" aria-label="切换侧边栏" onclick="(function (btn) { var as = document.getElementById('z-aside'), mk = document.getElementById('z-aside-bgmask'); as.classList.toggle('show'); mk.classList.toggle('hidden'); btn.classList.toggle('active'); })(this)">
        <span class="icon-[ph--align-right-duotone] rtl-flip" style="display:block"></span>
      </button>
    <?php endif; ?>
    <?php if ($isLinksPage && $linksRandom): ?>
      <button id="mobile-random-link-btn" aria-label="随机穿梭" onclick="randomVisitLink()">
        <span class="icon-[ph--shuffle-bold]" style="display:block"></span>
      </button>
    <?php endif; ?>
    <button id="back-to-top" aria-label="返回顶部" style="display:none">
      <span class="icon-[ph--arrow-up-bold]" style="display:block"></span>
    </button>
  </div>

  <button id="pc-back-to-top" class="pc-back-to-top" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })">
    <svg class="svgIcon" viewBox="0 0 384 512">
      <path d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2L329.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"></path>
    </svg>
  </button>

  <div id="search-modal" class="search-modal" style="display:none">
    <div class="search-modal-overlay" onclick="SearchWidget.close()"></div>
    <div class="search-modal-content">
      <form class="search-form" method="get" action="<?php echo $this->options->siteUrl; ?>">
        <input type="text" name="s" class="search-input" placeholder="输入关键词" autocomplete="off" />
        <button class="search-submit" type="submit"><span class="icon-[ph--magnifying-glass-bold]"></span></button>
      </form>
    </div>
  </div>

  <div id="auth-modal" class="auth-modal" style="display:none" aria-hidden="true">
    <div class="auth-modal-overlay" data-auth-close></div>
    <div class="auth-modal-content" role="dialog" aria-modal="true" aria-labelledby="auth-modal-title">
      <button type="button" class="auth-modal-close" aria-label="关闭登录窗口" data-auth-close>
        <span class="icon-[ph--x-bold]"></span>
      </button>
      <h3 id="auth-modal-title" class="auth-modal-title">登录</h3>
      <form class="auth-form" method="post" action="<?php $this->options->loginAction(); ?>">
        <?php if ($authError !== ''): ?>
          <div class="auth-alert" role="alert"><?php echo htmlspecialchars($authError, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <label class="auth-form-label" for="auth-name">用户名或邮箱</label>
        <input id="auth-name" class="auth-input" type="text" name="name" autocomplete="username" required />

        <label class="auth-form-label" for="auth-password">密码</label>
        <input id="auth-password" class="auth-input" type="password" name="password" autocomplete="current-password" required />

        <label class="auth-form-check">
          <input type="checkbox" name="remember" value="1" />
          <span>下次自动登录</span>
        </label>

        <input id="auth-referer" type="hidden" name="referer" value="<?php echo htmlspecialchars($this->options->siteUrl, ENT_QUOTES, 'UTF-8'); ?>" />

        <button class="auth-submit" type="submit">登录</button>
      </form>
      <div class="auth-extra-links">
        <a href="<?php echo htmlspecialchars($authForgotUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">忘记密码</a>
        <?php if ($this->options->allowRegister): ?>
          <a href="<?php $this->options->registerUrl(); ?>">注册账号</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    window.updateModalBodyLock = window.updateModalBodyLock || function () {
      const hasOpenModal = Array.from(document.querySelectorAll('.search-modal, .auth-modal')).some((modal) => modal && modal.style.display === 'flex');
      document.body.style.overflow = hasOpenModal ? 'hidden' : '';
    };

    window.SearchWidget = window.SearchWidget || {
      open: function () {
        const modal = document.getElementById('search-modal');
        if (!modal) return;
        modal.style.display = 'flex';
        window.updateModalBodyLock();
        const input = modal.querySelector('.search-input');
        if (input) input.focus();
      },
      close: function () {
        const modal = document.getElementById('search-modal');
        if (!modal) return;
        modal.style.display = 'none';
        window.updateModalBodyLock();
      }
    };

    window.AuthModal = window.AuthModal || {
      open: function () {
        const modal = document.getElementById('auth-modal');
        if (!modal) return;
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        const refererInput = document.getElementById('auth-referer');
        if (refererInput) {
          refererInput.value = window.location.href;
        }
        window.updateModalBodyLock();
        const input = modal.querySelector('#auth-name');
        if (input) input.focus();
      },
      close: function () {
        const modal = document.getElementById('auth-modal');
        if (!modal) return;
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        window.updateModalBodyLock();
      }
    };

    const authShouldOpen = <?php echo ($authError !== '') ? 'true' : 'false'; ?>;
    if (authShouldOpen) {
      window.AuthModal.open();
    }

    document.querySelectorAll('[data-auth-trigger]').forEach((trigger) => {
      trigger.addEventListener('click', function (event) {
        event.preventDefault();
        window.AuthModal.open();
      });
    });

    document.querySelectorAll('[data-auth-close]').forEach((trigger) => {
      trigger.addEventListener('click', function () {
        window.AuthModal.close();
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') return;
      const authModal = document.getElementById('auth-modal');
      if (authModal && authModal.style.display === 'flex') {
        window.AuthModal.close();
        return;
      }
      const searchModal = document.getElementById('search-modal');
      if (searchModal && searchModal.style.display === 'flex') {
        window.SearchWidget.close();
      }
    });

    window.randomVisitLink = window.randomVisitLink || function () {
      const list = window.clarityLinks || [];
      if (!list.length) return;
      const item = list[Math.floor(Math.random() * list.length)];
      if (window.openShuttle) {
        window.openShuttle({
          url: item.url,
          name: item.name || item.text || item.title || '友链',
          logo: item.logo || item.icon || '',
          desc: item.desc || item.description || ''
        });
      } else if (item.url) {
        window.open(item.url, '_blank', 'noopener,noreferrer');
      }
    };

    window.switchTab = window.switchTab || function (btn) {
      const container = document.getElementById('link-tabs');
      if (!container) return;
      const target = btn.dataset.tab;
      container.querySelectorAll('.custom-tab-item').forEach((el) => el.classList.remove('active'));
      container.querySelectorAll('.custom-tab-pane').forEach((el) => el.classList.remove('active'));
      btn.classList.add('active');
      const pane = document.getElementById(target);
      if (pane) pane.classList.add('active');
    };

    document.querySelectorAll('.copy-btn').forEach((btn) => {
      btn.addEventListener('click', async () => {
        const text = btn.dataset.copy || '';
        if (!text) return;
        try {
          if (window.clarityCopyText) {
            await window.clarityCopyText(text);
          } else if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(text);
          }
          btn.classList.add('copied');
          setTimeout(() => btn.classList.remove('copied'), 2000);
        } catch (e) {}
      });
    });
  </script>

  <!-- 新文章通知组件 - 参考 fuwari 样式 -->
  <style>
    #new-post-notification {
      position: fixed;
      bottom: 9rem;
      right: 1rem;
      z-index: 9999;
    }
    @media (max-width: 768px) {
      #new-post-notification {
        bottom: 9rem;
        right: 1rem;
      }
    }
    #np-notification-panel {
      position: relative;
      width: 3.75rem;
      height: 3.75rem;
      border-radius: 50%;
      overflow: hidden;
      box-sizing: border-box;
      background: var(--c-bg, #fff);
      border: 2px solid var(--c-primary, #3b82f6);
      color: var(--c-primary, #3b82f6);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
      transition: all 360ms cubic-bezier(0.4, 0, 0.2, 1);
      transform: translateY(5rem);
      opacity: 0;
    }
    #np-notification-panel.is-visible {
      transform: translateY(0);
      opacity: 1;
    }
    #np-notification-panel.is-open {
      width: 20rem;
      max-width: calc(100vw - 2rem);
      height: auto;
      max-height: 70vh;
      border-radius: 1rem;
    }
    #np-notification-minimized {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: opacity 160ms ease;
    }
    #np-notification-content {
      display: none;
      flex-direction: column;
      padding: 1.25rem;
      max-height: 70vh;
    }
    #np-notification-panel.is-open #np-notification-minimized {
      display: none;
    }
    #np-notification-panel.is-open #np-notification-content {
      display: flex;
    }
    #np-new-post-list {
      overflow-y: auto;
      overflow-x: hidden;
      max-height: 50vh;
    }
    #np-new-post-list::-webkit-scrollbar {
      width: 4px;
    }
    #np-new-post-list::-webkit-scrollbar-track {
      background: transparent;
    }
    #np-new-post-list::-webkit-scrollbar-thumb {
      background: var(--c-border, #e5e7eb);
      border-radius: 2px;
    }
  </style>

  <div id="new-post-notification">
    <div id="np-notification-panel">
      <button id="np-notification-minimized" aria-label="新文章通知">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
        <span id="np-notification-dot" style="position:absolute;top:0;right:0;width:0.75rem;height:0.75rem;background:#ef4444;border-radius:50%;border:2px solid var(--c-bg,#fff);display:none;"></span>
      </button>

      <div id="np-notification-content">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
          <div style="display:flex;align-items:center;gap:0.5rem;color:var(--c-primary,#3b82f6);">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>
            <h3 style="font-weight:700;color:inherit;">发现新文章</h3>
          </div>
          <div style="display:flex;align-items:center;gap:0.25rem;">
            <button id="np-clear-notification" style="padding:0.25rem;color:var(--c-text-2,#9ca3af);border-radius:0.375rem;background:transparent;border:none;cursor:pointer;" title="清空通知">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
            </button>
            <button id="np-minimize-notification" style="padding:0.25rem;color:var(--c-text-2,#9ca3af);border-radius:0.375rem;background:transparent;border:none;cursor:pointer;" title="隐藏">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </button>
          </div>
        </div>
        <div id="np-new-post-list" style="font-size:0.875rem;flex:1;color:var(--c-text);"></div>
      </div>
    </div>
  </div>

  <!-- diff库 -->
  <script src="https://cdn.jsdelivr.net/npm/diff@8.0.3/dist/diff.min.js"></script>
  
  <!-- 新文章通知逻辑 -->
  <script>
  (function() {
    const RSS_URL = '<?php echo $rssUrl; ?>';
    const STORAGE_KEY = 'clarity_rss_cache';
    const LAST_CHECK_KEY = 'clarity_rss_last_check';
    
    // 获取存储的RSS数据
    function getStoredPosts() {
      try {
        const data = localStorage.getItem(STORAGE_KEY);
        return data ? JSON.parse(data) : [];
      } catch (e) {
        return [];
      }
    }
    
    // 保存RSS数据
    function savePosts(posts) {
      try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(posts));
        localStorage.setItem(LAST_CHECK_KEY, Date.now().toString());
      } catch (e) {}
    }
    
    // 解析RSS
    async function fetchRSS() {
      try {
        const response = await fetch(RSS_URL + '?t=' + Date.now(), { cache: 'no-store' });
        const text = await response.text();
        const parser = new DOMParser();
        const xml = parser.parseFromString(text, 'text/xml');
        const items = Array.from(xml.querySelectorAll('item'));
        
        return items.map(item => {
          const title = item.querySelector('title')?.textContent || '';
          const link = item.querySelector('link')?.textContent || '';
          const guid = item.querySelector('guid')?.textContent || link;
          const pubDate = item.querySelector('pubDate')?.textContent || '';
          const description = item.querySelector('description')?.textContent || '';
          
          return { title, link, guid, pubDate, description };
        });
      } catch (e) {
        console.error('RSS获取失败:', e);
        return [];
      }
    }
    
    // 比较差异
    function findDifferences(oldPosts, newPosts) {
      const oldGuids = new Set(oldPosts.map(p => p.guid));
      const newGuids = new Set(newPosts.map(p => p.guid));
      
      // 新增的文章
      const added = newPosts.filter(p => !oldGuids.has(p.guid));
      
      // 更新的文章（有相同guid但内容不同）
      const updated = [];
      newPosts.forEach(newPost => {
        const oldPost = oldPosts.find(p => p.guid === newPost.guid);
        if (oldPost && oldPost.description !== newPost.description) {
          // 使用diff库比较内容差异
          const diff = Diff.diffWords(oldPost.description, newPost.description);
          const hasChanges = diff.some(part => part.added || part.removed);
          if (hasChanges) {
            updated.push({ ...newPost, diff });
          }
        }
      });
      
      return { added, updated };
    }
    
    // 渲染文章列表
    function renderPostList(posts, added, updated) {
      const listDiv = document.getElementById('np-new-post-list');
      if (!listDiv) return;
      
      const addedGuids = new Set(added.map(p => p.guid));
      const updatedGuids = new Set(updated.map(p => p.guid));
      
      let html = '';
      
      if (posts.length === 0) {
        html = '<div style="text-align:center;color:var(--c-text-2);padding:1rem 0;">暂无文章</div>';
      } else {
        if (added.length === 0 && updated.length === 0) {
          html += '<div style="text-align:center;color:var(--c-text-2);padding:1rem 0;"><p style="font-size:0.875rem;font-weight:500;margin-bottom:0.5rem;">暂无文章更新</p></div>';
        } else {
          html += '<div style="display:flex;flex-direction:column;gap:0.75rem;">';
          
          // 新增文章
          if (added.length > 0) {
            html += '<div style="margin-bottom:0.75rem;"><div style="font-size:0.75rem;color:#10b981;font-weight:500;margin-bottom:0.5rem;">🆕 新增文章</div>';
            added.forEach(post => {
              html += `<a href="${post.link}" style="display:block;padding:0.75rem;border-radius:0.5rem;background:var(--c-bg-2);text-decoration:none;color:var(--c-text);margin-bottom:0.5rem;transition:background 0.2s;" onmouseover="this.style.background='var(--c-bg-1)'" onmouseout="this.style.background='var(--c-bg-2)'">
                <div style="font-weight:500;font-size:0.875rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${post.title}</div>
                <div style="font-size:0.75rem;color:var(--c-text-2);margin-top:0.25rem;">${new Date(post.pubDate).toLocaleDateString()}</div>
              </a>`;
            });
            html += '</div>';
          }
          
          // 更新文章
          if (updated.length > 0) {
            html += '<div style="margin-bottom:0.75rem;"><div style="font-size:0.75rem;color:var(--c-primary);font-weight:500;margin-bottom:0.5rem;">✏️ 更新文章</div>';
            updated.forEach(post => {
              html += `<a href="${post.link}" style="display:block;padding:0.75rem;border-radius:0.5rem;background:var(--c-bg-2);text-decoration:none;color:var(--c-text);margin-bottom:0.5rem;transition:background 0.2s;" onmouseover="this.style.background='var(--c-bg-1)'" onmouseout="this.style.background='var(--c-bg-2)'">
                <div style="font-weight:500;font-size:0.875rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${post.title}</div>
                <div style="font-size:0.75rem;color:var(--c-text-2);margin-top:0.25rem;">内容有更新</div>
              </a>`;
            });
            html += '</div>';
          }
          
          html += '</div>';
        }
      }
      
      listDiv.innerHTML = html;
    }
    
    // 显示通知红点
    function showNotificationDot() {
      const dot = document.getElementById('np-notification-dot');
      if (dot) dot.classList.remove('hidden');
    }
    
    // 隐藏通知红点
    function hideNotificationDot() {
      const dot = document.getElementById('np-notification-dot');
      if (dot) dot.classList.add('hidden');
    }
    
    // 展开面板
    function openPanel(posts, added, updated) {
      const panel = document.getElementById('np-notification-panel');
      
      renderPostList(posts, added, updated);
      
      panel.classList.add('is-open');
      
      // 点击后隐藏红点
      hideNotificationDot();
    }
    
    // 最小化面板
    function minimizePanel() {
      const panel = document.getElementById('np-notification-panel');
      panel.classList.remove('is-open');
    }
    
    // 最小化按钮事件
    document.getElementById('np-minimize-notification')?.addEventListener('click', minimizePanel);
    
    // 铃铛按钮点击事件 - 展开面板显示文章列表
    let currentPosts = [];
    let currentAdded = [];
    let currentUpdated = [];
    
    document.getElementById('np-notification-minimized')?.addEventListener('click', () => {
      openPanel(currentPosts, currentAdded, currentUpdated);
    });
    
    // 清空通知
    document.getElementById('np-clear-notification')?.addEventListener('click', () => {
      localStorage.removeItem(STORAGE_KEY);
      currentPosts = [];
      currentAdded = [];
      currentUpdated = [];
      renderPostList([], [], []);
    });
    
    // 显示面板（初始动画）
    function showPanel() {
      const panel = document.getElementById('np-notification-panel');
      if (panel) {
        panel.classList.add('is-visible');
      }
    }
    
    // 主逻辑
    async function init() {
      console.log('[NewPostNotify] 开始检查...');
      const oldPosts = getStoredPosts();
      console.log('[NewPostNotify] 旧文章数:', oldPosts.length);
      
      const newPosts = await fetchRSS();
      console.log('[NewPostNotify] 新文章数:', newPosts.length);
      
      // 保存当前数据供后续使用
      currentPosts = newPosts;
      
      if (oldPosts.length === 0) {
        // 首次访问，只保存不通知
        console.log('[NewPostNotify] 首次访问，保存数据');
        savePosts(newPosts);
        return;
      }
      
      const { added, updated } = findDifferences(oldPosts, newPosts);
      console.log('[NewPostNotify] 新增:', added.length, '更新:', updated.length);
      
      // 保存新增和更新数据
      currentAdded = added;
      currentUpdated = updated;
      
      // 如果有新文章或更新，显示红点
      if (added.length > 0 || updated.length > 0) {
        showNotificationDot();
      }
      
      // 保存最新数据
      savePosts(newPosts);
      
      // 显示面板（带动画）
      showPanel();
    }
    
    // 页面加载后执行
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', init);
    } else {
      init();
    }
  })();
  </script>
  
  <!-- 网站分析仪表板 -->
  <script defer src="https://han.050815.xyz/tracker.min.js" data-website-id="clarity"></script>
</body>
</html>

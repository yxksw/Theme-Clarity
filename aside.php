<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$showAside = clarity_get('showAside', true);
$enableAside = clarity_bool(clarity_opt('aside_enable', '1'));
$showAside = $showAside && $enableAside;
$isPost = $this->is('post');
$isPage = $this->is('page');

$pageTemplate = $isPage ? ($this->template ?? '') : '';
// 特殊页面不显示侧边栏
$isSpecialPage = in_array($pageTemplate, ['gallery', 'photos', 'links']);

// 获取组件列表
$widgets = $isPost ? [] : clarity_get_widgets();
$enablePostToc = $isPost ? clarity_should_show_toc($this, 'post') : false;
$enablePageToc = $isPage ? clarity_should_show_toc($this, 'page') : false;
$momentsCount = (int) clarity_opt('moments_widget_count', '3');
$momentsTitle = clarity_opt('moments_widget_title', '微语');
$momentsNoText = clarity_opt('moments_widget_no_text', '');
$moments = $momentsCount > 0 ? clarity_moments_items($momentsCount) : [];
$momentsPageUrl = clarity_moments_base_url();
$momentsPageSep = strpos($momentsPageUrl, '?') === false ? '?' : '&';
$momentsTagLink = function (string $tag) use ($momentsPageUrl, $momentsPageSep): string {
    return $momentsPageUrl . $momentsPageSep . 'tag=' . urlencode($tag);
};
$weatherApiUrl = trim((string) clarity_opt('weather_api_url', 'https://60s.050815.xyz/v2/weather?query=%E5%B8%B8%E7%86%9F'));
$metingEnableOpt = clarity_opt('meting_enable', []);
$metingEnable = is_array($metingEnableOpt) ? in_array('1', $metingEnableOpt) : clarity_bool($metingEnableOpt);
$metingApi = trim((string) clarity_opt('meting_api', 'https://meting.kemiaosw.top'));
$metingServer = trim((string) clarity_opt('meting_server', 'netease'));
$metingType = trim((string) clarity_opt('meting_type', 'playlist'));
$metingId = trim((string) clarity_opt('meting_id', ''));
$metingTitle = trim((string) clarity_opt('meting_title', '随心听'));
$welcomeEnableOpt = clarity_opt('welcome_enable', []);
$welcomeEnable = is_array($welcomeEnableOpt) ? in_array('1', $welcomeEnableOpt) : clarity_bool($welcomeEnableOpt);
$welcomeTitle = trim((string) clarity_opt('welcome_title', '欢迎来访者'));
$welcomeApi = trim((string) clarity_opt('welcome_api', 'https://whois.pconline.com.cn/ipJson.jsp'));
$poetryEnableOpt = clarity_opt('poetry_enable', []);
$poetryEnable = is_array($poetryEnableOpt) ? in_array('1', $poetryEnableOpt) : clarity_bool($poetryEnableOpt);
$poetryTitle = trim((string) clarity_opt('poetry_title', '每日诗词'));
?>

<?php if (!$showAside): ?>
  <aside id="z-aside" style="display:none"></aside>
  <?php return; ?>
<?php endif; ?>

<aside id="z-aside">
  <?php if ($showAside): ?>
    <?php if (($isPost || $isPage) && ($enablePostToc || $enablePageToc)): ?>
      <section class="widget toc-widget" id="catalog-widget" style="display:none">
        <hgroup class="widget-title">
          <span class="title-text">文章目录</span>
          <a href="#content" aria-label="返回顶部" data-title="返回顶部" onclick="window.scrollTo({ top: 0, behavior: 'smooth' }); return false;">
            <span class="icon-[ph--arrow-circle-up-bold]"></span>
          </a>
          <a href="#comment" aria-label="评论区" data-title="评论区" onclick="document.getElementById('comment')?.scrollIntoView({ behavior: 'smooth' }); return false;">
            <span class="icon-[ph--chat-circle-text-bold]"></span>
          </a>
        </hgroup>
        <div class="widget-body widget-card toc-body">
          <nav id="catalog-content" class="toc-nav"></nav>
          <p id="no-toc-tip" class="no-toc" style="display:none">暂无目录信息</p>
        </div>
      </section>

      <script>
        (function () {
          function generateCatalog() {
            const article = document.querySelector('.article');
            const catalogWidget = document.getElementById('catalog-widget');
            const catalogContent = document.getElementById('catalog-content');
            const noTocTip = document.getElementById('no-toc-tip');

            if (!article || !catalogWidget || !catalogContent) return;

            const headers = Array.from(article.querySelectorAll('h1, h2, h3, h4, h5, h6'));
            if (headers.length === 0) {
              catalogWidget.style.display = 'block';
              catalogContent.style.display = 'none';
              noTocTip.style.display = 'block';
              return;
            }

            catalogWidget.style.display = 'block';
            catalogContent.style.display = 'block';
            noTocTip.style.display = 'none';
            catalogContent.innerHTML = '';

            const root = { children: [] };
            const stack = [root];

            headers.forEach((header, index) => {
              if (!header.id) header.id = 'heading-' + index;
              const level = parseInt(header.tagName.substring(1));
              const item = { id: header.id, text: header.textContent, level: level, children: [] };
              while (stack.length > 1 && stack[stack.length - 1].level >= level) {
                stack.pop();
              }
              stack[stack.length - 1].children.push(item);
              stack.push(item);
            });

            function renderTree(items) {
              if (!items.length) return null;
              const ol = document.createElement('ol');
              items.forEach((item) => {
                const li = document.createElement('li');
                li.dataset.id = item.id;
                const a = document.createElement('a');
                a.href = '#' + item.id;
                a.textContent = item.text;
                a.title = item.text;
                a.onclick = (e) => {
                  e.preventDefault();
                  const target = document.getElementById(item.id);
                  if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    history.pushState(null, null, '#' + item.id);
                    target.classList.remove('toc-highlight');
                    void target.offsetWidth;
                    target.classList.add('toc-highlight');
                    setTimeout(() => target.classList.remove('toc-highlight'), 2000);
                  }
                };
                li.appendChild(a);
                const childrenOl = renderTree(item.children);
                if (childrenOl) li.appendChild(childrenOl);
                ol.appendChild(li);
              });
              return ol;
            }

            const treeDom = renderTree(root.children);
            if (treeDom) catalogContent.appendChild(treeDom);

            if (window.tocObserver) window.tocObserver.disconnect();

            const observerCallback = (entries) => {
              entries.forEach((entry) => {
                const li = catalogContent.querySelector('li[data-id="' + entry.target.id + '"]');
                if (!li) return;
                if (entry.isIntersecting) li.classList.add('active');
                else li.classList.remove('active');
              });

              catalogContent.querySelectorAll('.has-active').forEach((el) => el.classList.remove('has-active'));
              let currentActive = catalogContent.querySelector('li.active');
              if (currentActive) {
                let parent = currentActive.parentElement.closest('li');
                while (parent) {
                  parent.classList.add('has-active');
                  parent = parent.parentElement.closest('li');
                }
              }
            };

            window.tocObserver = new IntersectionObserver(observerCallback, { rootMargin: '-80px 0px -70% 0px' });
            headers.forEach((h) => window.tocObserver.observe(h));
          }

          generateCatalog();
        })();
      </script>
  <?php endif; ?>
  <?php endif; ?>
  
  <?php if (!empty($widgets)): ?>
    <?php foreach ($widgets as $widget): ?>
      <?php switch ($widget):
        case 'announcement': ?>
          <?php 
          $announcementEnable = clarity_bool(clarity_opt('announcement_enable', '0'));
          $announcementLevel = clarity_opt('announcement_level', 'info');
          $announcementContent = trim((string) clarity_opt('announcement_content', ''));
          ?>
          <?php if ($announcementEnable && $announcementContent !== ''): ?>
            <?php
            // SVG icon paths from fuwari
            $levelIconPaths = [
              'info' => 'M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0Zm0 1.5a6.5 6.5 0 1 1 0 13 6.5 6.5 0 0 1 0-13ZM6.5 7.75A.75.75 0 0 1 7.25 7h1a.75.75 0 0 1 .75.75v2.75h.25a.75.75 0 0 1 0 1.5h-2a.75.75 0 0 1 0-1.5h.25v-2h-.25a.75.75 0 0 1-.75-.75ZM8 6a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z',
              'note' => 'M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8Zm8-6.5a6.5 6.5 0 1 0 0 13 6.5 6.5 0 0 0 0-13ZM6.5 7.75A.75.75 0 0 1 7.25 7h1a.75.75 0 0 1 .75.75v2.75h.25a.75.75 0 0 1 0 1.5h-2a.75.75 0 0 1 0-1.5h.25v-2h-.25a.75.75 0 0 1-.75-.75ZM8 6a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z',
              'tip' => 'M8 1.5c-2.363 0-4 1.69-4 3.75 0 .984.424 1.625.984 2.304l.214.253c.223.264.47.556.673.848.284.411.537.896.621 1.49a.75.75 0 0 1-1.484.211c-.04-.282-.163-.547-.37-.847a8.456 8.456 0 0 0-.542-.68c-.084-.1-.173-.205-.268-.32C3.201 7.75 2.5 6.766 2.5 5.25 2.5 2.31 4.863 0 8 0s5.5 2.31 5.5 5.25c0 1.516-.701 2.5-1.328 3.259-.095.115-.184.22-.268.319-.207.245-.383.453-.541.681-.208.3-.33.565-.37.847a.751.751 0 0 1-1.485-.212c.084-.593.337-1.078.621-1.489.203-.292.45-.584.673-.848.075-.088.147-.173.213-.253.561-.679.985-1.32.985-2.304 0-2.06-1.637-3.75-4-3.75ZM5.75 12h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1 0-1.5ZM6 15.25a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 0 1.5h-2.5a.75.75 0 0 1-.75-.75Z',
              'important' => 'M0 1.75C0 .784.784 0 1.75 0h12.5C15.216 0 16 .784 16 1.75v9.5A1.75 1.75 0 0 1 14.25 13H8.06l-2.573 2.573A1.458 1.458 0 0 1 3 14.543V13H1.75A1.75 1.75 0 0 1 0 11.25Zm1.75-.25a.25.25 0 0 0-.25.25v9.5c0 .138.112.25.25.25h2a.75.75 0 0 1 .75.75v2.19l2.72-2.72a.749.749 0 0 1 .53-.22h6.5a.25.25 0 0 0 .25-.25v-9.5a.25.25 0 0 0-.25-.25Zm7 2.25v2.5a.75.75 0 0 1-1.5 0v-2.5a.75.75 0 0 1 1.5 0ZM9 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z',
              'warning' => 'M6.457 1.047c.659-1.234 2.427-1.234 3.086 0l6.082 11.378A1.75 1.75 0 0 1 14.082 15H1.918a1.75 1.75 0 0 1-1.543-2.575Zm1.763.707a.25.25 0 0 0-.44 0L1.698 13.132a.25.25 0 0 0 .22.368h12.164a.25.25 0 0 0 .22-.368Zm.53 3.996v2.5a.75.75 0 0 1-1.5 0v-2.5a.75.75 0 0 1 1.5 0ZM9 11a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z',
              'caution' => 'M4.47.22A.749.749 0 0 1 5 0h6c.199 0 .389.079.53.22l4.25 4.25c.141.14.22.331.22.53v6a.749.749 0 0 1-.22.53l-4.25 4.25A.749.749 0 0 1 11 16H5a.749.749 0 0 1-.53-.22L.22 11.53A.749.749 0 0 1 0 11V5c0-.199.079-.389.22-.53Zm.84 1.28L1.5 5.31v5.38l3.81 3.81h5.38l3.81-3.81V5.31L10.69 1.5ZM8 4a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 8 4Zm0 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z',
            ];
            $levelColors = [
              'info' => 'var(--c-primary)',
              'note' => 'var(--c-text-2)',
              'tip' => '#10b981',
              'happy' => 'happy',
              'important' => '#f59e0b',
              'warning' => '#ef4444',
              'caution' => '#dc2626',
            ];
            $iconPath = $levelIconPaths[$announcementLevel] ?? $levelIconPaths['info'];
            $color = $levelColors[$announcementLevel] ?? $levelColors['info'];
            $isHappy = $announcementLevel === 'happy';
            ?>
            <section class="widget announcement-widget">
              <div class="notice-card <?php echo $isHappy ? 'notice-happy' : ''; ?>" <?php echo !$isHappy ? 'style="--notice-color: ' . $color . '"' : ''; ?>>
                <div class="notice-content">
                  <div class="icon-wrapper">
                    <?php if ($isHappy): ?>
                      <span class="notice-emoji" aria-hidden="true">🎉</span>
                    <?php else: ?>
                      <svg width="24" height="24" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="<?php echo $iconPath; ?>" fill="currentColor"></path>
                      </svg>
                    <?php endif; ?>
                  </div>
                  <div class="text-wrapper">
                    <div class="notice-text">
                      <?php echo clarity_markdown_to_html($announcementContent); ?>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          <?php endif; ?>
          <?php break; ?>
        <?php case 'welcome': ?>
          <?php if ($welcomeEnable): ?>
          <section class="widget">
            <hgroup class="widget-title text-creative"><?php echo htmlspecialchars($welcomeTitle, ENT_QUOTES, 'UTF-8'); ?></hgroup>
            <div class="widget-body widget-card">
              <div class="welcome-visitor" data-api="<?php echo htmlspecialchars($welcomeApi, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="welcome-main-text">正在获取您的位置...</div>
                <div class="welcome-sub-text">
                  <span class="welcome-moon">🌙</span>
                  <span class="greeting-text">晚上好，愿您放松</span>
                </div>
                <div class="welcome-tip-text">💡 带我去你的城市逛逛吧！</div>
              </div>
            </div>
          </section>
          <?php endif; ?>
          <?php break; ?>
        <?php case 'poetry': ?>
          <?php if ($poetryEnable): ?>
          <section class="widget">
            <hgroup class="widget-title text-creative"><?php echo htmlspecialchars($poetryTitle, ENT_QUOTES, 'UTF-8'); ?></hgroup>
            <div class="widget-body widget-card">
              <div class="daily-poetry">
                <div class="poetry-loading">正在加载诗词...</div>
                <div class="poetry-content" style="display: none;">
                  <div class="poetry-sentence"></div>
                  <div class="poetry-info">
                    <span class="poetry-dynasty"></span>
                    <span class="poetry-author"></span>
                    <span class="poetry-title"></span>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <?php endif; ?>
          <?php break; ?>
        <?php case 'meting': ?>
          <?php if ($metingEnable && $metingId !== ''): ?>
          <section class="widget">
            <hgroup class="widget-title text-creative"><?php echo htmlspecialchars($metingTitle, ENT_QUOTES, 'UTF-8'); ?></hgroup>
            <div class="widget-body widget-card">
              <div class="meting-player" 
                   data-api="<?php echo htmlspecialchars($metingApi, ENT_QUOTES, 'UTF-8'); ?>"
                   data-server="<?php echo htmlspecialchars($metingServer, ENT_QUOTES, 'UTF-8'); ?>"
                   data-type="<?php echo htmlspecialchars($metingType, ENT_QUOTES, 'UTF-8'); ?>"
                   data-id="<?php echo htmlspecialchars($metingId, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="meting-main">
                  <div class="meting-cover">
                    <img src="" alt="cover" class="meting-cover-img" loading="lazy">
                    <div class="meting-cover-placeholder">
                      <span class="icon-[ph--music-note-bold]"></span>
                    </div>
                  </div>
                  <div class="meting-info">
                    <div class="meting-song-name">加载中...</div>
                    <div class="meting-artist">请稍候</div>
                    <div class="meting-controls">
                      <button class="meting-btn meting-prev" title="上一首">
                        <span class="icon-[ph--skip-back-bold]"></span>
                      </button>
                      <button class="meting-btn meting-play" title="播放/暂停">
                        <span class="icon-[ph--play-bold] meting-icon-play"></span>
                        <span class="icon-[ph--pause-bold] meting-icon-pause" style="display:none;"></span>
                      </button>
                      <button class="meting-btn meting-next" title="下一首">
                        <span class="icon-[ph--skip-forward-bold]"></span>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="meting-progress">
                  <span class="meting-time-current">0:00</span>
                  <div class="meting-progress-bar">
                    <div class="meting-progress-fill"></div>
                  </div>
                  <span class="meting-time-duration">0:00</span>
                </div>
                <audio class="meting-audio" preload="metadata"></audio>
              </div>
            </div>
          </section>
          <?php endif; ?>
          <?php break; ?>
        <?php case 'stats': ?>
          <section class="widget">
            <hgroup class="widget-title text-creative">博客统计</hgroup>
            <div class="widget-body widget-card">
              <dl class="dl-group small">
                <div>
                  <dt>运营时长</dt>
                  <dd id="site-runtime" title="博客于 <?php echo htmlspecialchars(clarity_opt('site_start_time', '2024-01-01'), ENT_QUOTES, 'UTF-8'); ?> 上线">-</dd>
                </div>
                <div>
                  <dt>上次更新</dt>
                  <?php $latest = clarity_get_latest_post_time(); ?>
                  <dd class="time-ago" id="latest-update-time" data-time="<?php echo htmlspecialchars($latest ?? '', ENT_QUOTES, 'UTF-8'); ?>">-</dd>
                </div>
                <div>
                  <dt>文章数目</dt>
                  <dd><?php echo clarity_get_post_count(); ?></dd>
                </div>
              </dl>
            </div>
          </section>
          <?php break; ?>
        <?php case 'blog-log': ?>
          <?php
          $blogLogRaw = trim((string) clarity_opt('blog_log_data', ''));
          $blogLogData = [];
          if ($blogLogRaw !== '' && $blogLogRaw !== '[]') {
              $decoded = json_decode($blogLogRaw, true);
              if (is_array($decoded) && count($decoded) > 0) {
                  $blogLogData = $decoded;
              }
          }
          $blogLogTitle = clarity_opt('blog_log_title', '更新日志');
          ?>
          <section class="widget">
            <hgroup class="widget-title text-creative"><?php echo htmlspecialchars($blogLogTitle, ENT_QUOTES, 'UTF-8'); ?></hgroup>
            <div class="widget-body widget-card">
              <?php if (empty($blogLogData)): ?>
                <p style="color: #999; font-size: 0.9em;">暂无日志数据，请在后台配置</p>
              <?php else: ?>
              <dl class="dl-group large">
                <?php foreach ($blogLogData as $log): ?>
                  <?php if (!is_array($log)) continue; ?>
                  <div>
                    <dt><?php echo htmlspecialchars($log['date'] ?? '', ENT_QUOTES, 'UTF-8'); ?></dt>
                    <dd><?php echo htmlspecialchars($log['content'] ?? '', ENT_QUOTES, 'UTF-8'); ?></dd>
                  </div>
                <?php endforeach; ?>
              </dl>
              <?php endif; ?>
            </div>
          </section>
          <?php break; ?>
        <?php case 'recent-comments': ?>
          <?php
          $recentCommentsCount = (int) clarity_opt('recent_comments_count', '5');
          $recentCommentsTitle = clarity_opt('recent_comments_title', '最新评论');
          $recentCommentsPageUrl = $this->options->siteUrl;
          $recentComments = clarity_get_recent_comments($recentCommentsCount);
          ?>
          <section class="widget">
            <hgroup class="widget-title text-creative">
              <?php echo htmlspecialchars($recentCommentsTitle, ENT_QUOTES, 'UTF-8'); ?>
              <a href="<?php echo htmlspecialchars($recentCommentsPageUrl, ENT_QUOTES, 'UTF-8'); ?>" class="widget-more" title="查看更多">»</a>
            </hgroup>
            <div class="widget-body widget-card">
              <?php if (empty($recentComments)): ?>
                <p style="color: #999; font-size: 0.9em;">暂无评论</p>
              <?php else: ?>
                <ul class="recent-comments-list">
                  <?php foreach ($recentComments as $comment): ?>
                    <li class="recent-comment-item">
                      <a href="<?php echo htmlspecialchars($comment['post_permalink'] ?? '#', ENT_QUOTES, 'UTF-8'); ?>#comment-<?php echo $comment['coid']; ?>" class="recent-comment-link">
                        <div class="recent-comment-avatar">
                          <?php 
                          $avatarMail = isset($comment['mail']) ? trim($comment['mail']) : '';
                          // 调试: echo '<!-- mail=' . htmlspecialchars($avatarMail) . ' -->';
                          if ($avatarMail !== '' && filter_var($avatarMail, FILTER_VALIDATE_EMAIL)) {
                              // 使用 cravatar 头像服务
                              $avatarHash = md5(strtolower($avatarMail));
                              $avatarUrl = 'https://cn.cravatar.com/avatar/' . $avatarHash . '?s=40&d=mp';
                              echo '<img src="' . htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($comment['author'] ?? '', ENT_QUOTES, 'UTF-8') . '" onerror="this.src=\'https://cn.cravatar.com/avatar/?d=mp\'" />';
                          } else {
                              $defaultAvatar = 'https://cn.cravatar.com/avatar/?d=mp';
                              echo '<img src="' . htmlspecialchars($defaultAvatar, ENT_QUOTES, 'UTF-8') . '" alt="" />';
                          }
                          ?>
                        </div>
                        <div class="recent-comment-content">
                          <div class="recent-comment-author"><?php echo htmlspecialchars($comment['author'] ?? '匿名', ENT_QUOTES, 'UTF-8'); ?></div>
                          <div class="recent-comment-text"><?php echo htmlspecialchars(mb_substr(strip_tags($comment['text'] ?? ''), 0, 50), ENT_QUOTES, 'UTF-8'); ?></div>
                          <div class="recent-comment-meta">
                            <span class="recent-comment-post"><?php echo htmlspecialchars($comment['post_title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                          </div>
                        </div>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </div>
          </section>
          <?php break; ?>
        <?php case 'tech-info': ?>
          <section class="widget">
            <hgroup class="widget-title text-creative">技术信息</hgroup>
            <div class="widget-body widget-card">
              <dl class="dl-group medium">
                <div>
                  <dt>软件协议</dt>
                  <dd>GPL-3.0</dd>
                </div>
                <div>
                  <dt>文章许可</dt>
                  <dd><a href="<?php echo htmlspecialchars(clarity_opt('license_url', ''), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars(clarity_opt('license', ''), ENT_QUOTES, 'UTF-8'); ?></a></dd>
                </div>
                <div>
                  <dt>规范域名</dt>
                  <dd class="domain-text" title="<?php echo htmlspecialchars($this->options->siteUrl, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php
                    $cleanUrl = preg_replace('#^https?://#', '', $this->options->siteUrl);
                    $cleanUrl = preg_replace('#^www\.#', '', $cleanUrl);
                    $cleanUrl = rtrim($cleanUrl, '/');
                    echo htmlspecialchars($cleanUrl, ENT_QUOTES, 'UTF-8');
                    ?>
                  </dd>
                </div>
              </dl>
              <div class="z-expand" x-data="{ expand: false }">
                <div class="expand-content" x-show="expand" x-collapse>
                  <dl class="dl-group small build-info">
                    <div><dt>Theme</dt><dd>Clarity</dd></div>
                    <div><dt>Version</dt><dd><?php echo CLARITY_VERSION; ?></dd></div>
                    <div><dt>Framework</dt><dd>Typecho</dd></div>
                  </dl>
                </div>
                <button class="toggle-btn in-place" @click="expand = !expand">
                  <span class="icon-[ph--caret-double-down-bold] toggle-icon" :class="{ 'expand': expand }"></span>
                  <span x-text="expand ? '收起构建信息' : '展开构建信息'"></span>
                </button>
              </div>
            </div>
          </section>
          <?php break; ?>
        <?php case 'weather': ?>
          <section class="widget">
            <hgroup class="widget-title text-creative">天气预报</hgroup>
            <div class="widget-body widget-card" id="weather-container-<?php echo uniqid(); ?>">
              <div class="weather-loading" style="display:flex;align-items:center;justify-content:center;gap:.5em;padding:1rem;font-size:.9em;color:var(--c-text-3);">
                <span class="icon-[ph--spinner] animate-spin"></span>
                <span>加载中...</span>
              </div>
            </div>
            <script>
              (function() {
                const container = document.currentScript.previousElementSibling;
                if (!container) return;
                
                const apiUrl = '<?php echo htmlspecialchars($weatherApiUrl, ENT_QUOTES, 'UTF-8'); ?>';
                
                // 天气状况转表情
                const weatherEmoji = {
                  '晴': '☀️',
                  '多云': '⛅',
                  '阴': '☁️',
                  '小雨': '🌦️',
                  '中雨': '🌧️',
                  '大雨': '⛈️',
                  '暴雨': '⛈️',
                  '雷阵雨': '⛈️',
                  '雪': '❄️',
                  '小雪': '🌨️',
                  '中雪': '🌨️',
                  '大雪': '❄️',
                  '雾': '🌫️',
                  '霾': '😷',
                  '沙尘': '🌪️',
                  '大风': '💨',
                  '台风': '🌀'
                };
                
                fetch(apiUrl)
                  .then(r => r.json())
                  .then(data => {
                    if (data.code !== 200 || !data.data) {
                      container.innerHTML = '<div style="padding:1rem;color:var(--c-text-3);">天气数据获取失败</div>';
                      return;
                    }
                    
                    const w = data.data.weather;
                    const loc = data.data.location;
                    const air = data.data.air_quality;
                    const emoji = weatherEmoji[w.condition] || '🌡️';
                    
                    container.innerHTML = `
                      <div style="padding:1rem;">
                        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:.75rem;">
                          <div style="font-size:3rem;line-height:1;">${emoji}</div>
                          <div style="flex:1;">
                            <div style="font-size:1.75rem;font-weight:700;color:var(--c-text);">${w.temperature}°C</div>
                            <div style="font-size:.9rem;color:var(--c-text-2);margin-top:.25rem;">${w.condition}</div>
                          </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:var(--c-text-2);margin-bottom:.75rem;padding-bottom:.75rem;border-bottom:1px solid var(--c-border);">
                          <span class="icon-[ph--map-pin-bold]"></span>
                          <span>${loc.name}</span>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;font-size:.8rem;">
                          <div style="text-align:center;padding:.5rem;background:var(--c-bg-2);border-radius:.5rem;">
                            <div style="color:var(--c-text-3);margin-bottom:.25rem;">湿度</div>
                            <div style="color:var(--c-text);font-weight:600;">${w.humidity}%</div>
                          </div>
                          <div style="text-align:center;padding:.5rem;background:var(--c-bg-2);border-radius:.5rem;">
                            <div style="color:var(--c-text-3);margin-bottom:.25rem;">风力</div>
                            <div style="color:var(--c-text);font-weight:600;">${w.wind_power}级</div>
                          </div>
                          <div style="text-align:center;padding:.5rem;background:var(--c-bg-2);border-radius:.5rem;">
                            <div style="color:var(--c-text-3);margin-bottom:.25rem;">空气</div>
                            <div style="color:var(--c-text);font-weight:600;">${air.quality}</div>
                          </div>
                        </div>
                      </div>
                    `;
                  })
                  .catch(() => {
                    container.innerHTML = '<div style="padding:1rem;color:var(--c-text-3);">天气数据获取失败</div>';
                  });
              })();
            </script>
          </section>
          <?php break; ?>
        <?php case 'moments': ?>
          <section class="widget">
            <hgroup class="widget-title">
              <span class="title-text text-creative"><?php echo htmlspecialchars($momentsTitle, ENT_QUOTES, 'UTF-8'); ?></span>
              <a href="<?php echo htmlspecialchars($momentsPageUrl, ENT_QUOTES, 'UTF-8'); ?>" aria-label="查看全部" title="查看全部">
                <span class="icon-[ph--arrow-right-bold]"></span>
              </a>
            </hgroup>
            <div class="widget-body widget-card moments-widget" data-title="<?php echo htmlspecialchars($momentsTitle, ENT_QUOTES, 'UTF-8'); ?>" data-no-text="<?php echo htmlspecialchars($momentsNoText, ENT_QUOTES, 'UTF-8'); ?>">
              <?php
              $shown = 0;
              foreach ($moments as $moment):
                  if ($shown >= $momentsCount) break;
                  $content = $moment['content'] ?? '';
                  $time = $moment['time'] ?? '';
                  $tags = $moment['tags'] ?? [];
                  $url = $moment['url'] ?? '#';
                  $hasMedia = !empty($moment['media']);
                  $shown++;
              ?>
                <article class="moment-item" data-has-media="<?php echo $hasMedia ? 'true' : 'false'; ?>">
                  <script type="text/plain" class="moment-raw-html"><?php echo $content; ?></script>
                  <a class="moment-content" href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>"></a>
                  <div class="moment-meta">
                    <time class="moment-time" datetime="<?php echo htmlspecialchars($time, ENT_QUOTES, 'UTF-8'); ?>">
                      <span class="icon-[ph--clock-bold]"></span>
                      <span><?php echo htmlspecialchars($time, ENT_QUOTES, 'UTF-8'); ?></span>
                    </time>
                    <?php if (!empty($tags)): ?>
                      <div class="moment-tags">
                        <?php foreach (array_slice($tags, 0, 2) as $tag): ?>
                          <a class="moment-tag" href="<?php echo htmlspecialchars($momentsTagLink((string) $tag), ENT_QUOTES, 'UTF-8'); ?>">
                            <span class="tag-hash">#</span><span><?php echo htmlspecialchars($tag, ENT_QUOTES, 'UTF-8'); ?></span>
                          </a>
                        <?php endforeach; ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </article>
              <?php endforeach; ?>
              <?php if ($shown === 0): ?>
                <div class="empty-tip">
                  <span class="icon-[ph--shooting-star-bold]"></span>
                  暂无微语
                </div>
              <?php endif; ?>
              <script>
                (function () {
                  const widget = document.querySelector('.moments-widget');
                  if (!widget) return;
                  const items = Array.from(widget.querySelectorAll('.moment-item'));
                  const momentsTitle = widget.dataset.title || '微语';
                  const noTextConfig = widget.dataset.noText;
                  if (!items.length) return;
                  const parser = new DOMParser();
                  const run = () => {
                    items.forEach((item) => {
                      const rawHtmlScript = item.querySelector('.moment-raw-html');
                      const raw = rawHtmlScript ? rawHtmlScript.textContent || rawHtmlScript.innerText || '' : '';
                      let displayText = '';
                      let hasMedia = item.dataset.hasMedia === 'true';
                      if (raw) {
                        const doc = parser.parseFromString(raw, 'text/html');
                        const tagElements = doc.querySelectorAll('.tag');
                        tagElements.forEach((tag) => { tag.textContent = ''; });
                        const text = doc.body.textContent || doc.body.innerText || '';
                        displayText = text.trim();
                        const mediaSelectors = ['img[src]','video[src]','audio[src]','figure[data-content-type="image"]','figure[data-content-type="video"]','iframe[src]','source[src]'];
                        hasMedia = hasMedia || mediaSelectors.some((selector) => doc.querySelector(selector));
                      }
                      if (!displayText) {
                        if (hasMedia) {
                          if (noTextConfig && noTextConfig.trim()) displayText = noTextConfig.trim();
                          else displayText = `此${momentsTitle}无文字，请点击查看。`;
                        } else {
                          item.style.display = 'none';
                          return;
                        }
                      }
                      const content = item.querySelector('.moment-content');
                      if (content) content.textContent = displayText;
                    });
                  };
                  window.requestIdleCallback ? requestIdleCallback(run) : run();
                })();
              </script>
            </div>
          </section>
          <?php break; ?>
        <?php case 'community': ?>
          <?php $communityImage = trim((string) clarity_opt('community_image', '')); ?>
          <?php if ($communityImage !== ''): ?>
            <section class="widget dim">
              <hgroup class="widget-title text-creative"><?php echo htmlspecialchars(clarity_opt('community_title', '博客/技术社区'), ENT_QUOTES, 'UTF-8'); ?></hgroup>
              <div class="widget-body widget-card with-bg">
                <img class="bg-img bg-right" src="<?php echo htmlspecialchars($communityImage, ENT_QUOTES, 'UTF-8'); ?>" alt="" loading="lazy" />
                <div class="comm-title text-creative"><?php echo htmlspecialchars(clarity_opt('community_name', '技术交流群'), ENT_QUOTES, 'UTF-8'); ?></div>
                <p class="comm-tip">
                  <span class="icon-[ph--chat-circle-dots-bold]"></span>
                  <span><?php echo htmlspecialchars(clarity_opt('community_desc', ''), ENT_QUOTES, 'UTF-8'); ?></span>
                </p>
              </div>
            </section>
          <?php endif; ?>
          <?php break; ?>
        <?php case 'sponsor': ?>
          <section class="widget dim">
            <hgroup class="widget-title text-creative"><?php echo htmlspecialchars(clarity_opt('sponsor_title', '云计算支持'), ENT_QUOTES, 'UTF-8'); ?></hgroup>
            <a href="<?php echo htmlspecialchars(clarity_opt('sponsor_url', '#'), ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" class="widget-body widget-card with-bg sponsor-card">
              <?php $sponsorLogo = trim((string) clarity_opt('sponsor_logo', '')); ?>
              <?php if ($sponsorLogo !== ''): ?>
                <img class="bg-img bg-right" src="<?php echo htmlspecialchars($sponsorLogo, ENT_QUOTES, 'UTF-8'); ?>" alt="" loading="lazy" />
              <?php endif; ?>
              <div class="sponsor-name text-creative"><?php echo htmlspecialchars(clarity_opt('sponsor_name', '赞助商'), ENT_QUOTES, 'UTF-8'); ?></div>
              <p class="sponsor-tip">
                <span class="icon-[ph--cloud-bold]"></span>
                <span><?php echo htmlspecialchars(clarity_opt('sponsor_desc', '提供云计算服务'), ENT_QUOTES, 'UTF-8'); ?></span>
              </p>
            </a>
          </section>
          <?php break; ?>
        <?php case 'custom': ?>
          <section class="widget">
            <hgroup class="widget-title text-creative"><?php echo htmlspecialchars(clarity_opt('aside_custom_title', '自定义'), ENT_QUOTES, 'UTF-8'); ?></hgroup>
            <div class="widget-body widget-card"><?php echo clarity_opt('aside_custom_html', ''); ?></div>
          </section>
          <?php break; ?>
      <?php endswitch; ?>
    <?php endforeach; ?>
  <?php endif; ?>

  <script>
    (function () {
      const startTime = '<?php echo htmlspecialchars(clarity_opt('site_start_time', ''), ENT_QUOTES, 'UTF-8'); ?>';
      if (startTime) {
        const start = new Date(startTime).getTime();
        const now = Date.now();
        const days = Math.floor((now - start) / (1000 * 60 * 60 * 24));
        const years = Math.floor(days / 365);
        const remainingDays = days % 365;
        const months = Math.floor(remainingDays / 30);
        let text = '';
        if (years > 0) text += years + '年';
        if (months > 0) text += months + '个月';
        if (years === 0 && months === 0) text = days + '天';
        const el = document.getElementById('site-runtime');
        if (el) el.textContent = text;
      }

      document.querySelectorAll('.time-ago').forEach((el) => {
        let timeStr = el.getAttribute('data-time');
        if (!timeStr) timeStr = (el.textContent || '').trim();
        if (!timeStr) return;

        const parsed = new Date(timeStr);
        if (Number.isNaN(parsed.getTime())) return;

        const time = parsed.getTime();
        const now = Date.now();
        const diff = now - time;
        const minutes = Math.floor(diff / (1000 * 60));
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        let text = '';
        if (days > 0) text = days + '天前';
        else if (hours > 0) text = hours + '小时前';
        else if (minutes > 0) text = minutes + '分钟前';
        else text = '刚刚';
        el.textContent = text;
        el.title = '更新于 ' + parsed.toLocaleString('zh-CN');
      });

      // Meting 音乐播放器
      (function initMetingPlayer() {
        const player = document.querySelector('.meting-player');
        if (!player) return;

        const api = player.dataset.api;
        const server = player.dataset.server;
        const type = player.dataset.type;
        const id = player.dataset.id;
        const audio = player.querySelector('.meting-audio');
        const coverImg = player.querySelector('.meting-cover-img');
        const coverPlaceholder = player.querySelector('.meting-cover-placeholder');
        const songNameEl = player.querySelector('.meting-song-name');
        const artistEl = player.querySelector('.meting-artist');
        const playBtn = player.querySelector('.meting-play');
        const prevBtn = player.querySelector('.meting-prev');
        const nextBtn = player.querySelector('.meting-next');
        const iconPlay = player.querySelector('.meting-icon-play');
        const iconPause = player.querySelector('.meting-icon-pause');
        const progressBar = player.querySelector('.meting-progress-bar');
        const progressFill = player.querySelector('.meting-progress-fill');
        const timeCurrent = player.querySelector('.meting-time-current');
        const timeDuration = player.querySelector('.meting-time-duration');

        let playlist = [];
        let currentIndex = 0;
        let history = [];
        let isPlaying = false;

        function formatTime(sec) {
          if (!sec || isNaN(sec)) return '0:00';
          const m = Math.floor(sec / 60);
          const s = Math.floor(sec % 60);
          return `${m}:${s.toString().padStart(2, '0')}`;
        }

        function loadSong(index) {
          if (!playlist.length || index < 0 || index >= playlist.length) return;
          currentIndex = index;
          const song = playlist[index];
          if (!song) return;
          
          audio.src = song.url || '';
          // Meting API 使用 title/author，标准格式使用 name/artist
          songNameEl.textContent = song.name || song.title || '未知歌曲';
          artistEl.textContent = song.artist || song.author || '未知歌手';
          
          if (song.pic) {
            coverImg.src = song.pic;
            coverImg.style.display = 'block';
            coverPlaceholder.style.display = 'none';
          } else {
            coverImg.style.display = 'none';
            coverPlaceholder.style.display = 'flex';
          }
          
          if (isPlaying) {
            audio.play().catch(() => {});
          }
        }

        function togglePlay() {
          if (audio.paused) {
            audio.play().catch(() => {});
          } else {
            audio.pause();
          }
        }

        function prev() {
          if (history.length) {
            const prevIndex = history.pop();
            loadSong(prevIndex);
          }
        }

        function next() {
          if (!playlist.length) return;
          history.push(currentIndex);
          let newIndex;
          do {
            newIndex = Math.floor(Math.random() * playlist.length);
          } while (newIndex === currentIndex && playlist.length > 1);
          loadSong(newIndex);
        }

        function updateProgress() {
          const current = audio.currentTime || 0;
          const duration = audio.duration || 0;
          const percent = duration ? (current / duration) * 100 : 0;
          progressFill.style.width = percent + '%';
          timeCurrent.textContent = formatTime(current);
          timeDuration.textContent = formatTime(duration);
        }

        function seek(e) {
          const rect = progressBar.getBoundingClientRect();
          const percent = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
          if (audio.duration) {
            audio.currentTime = percent * audio.duration;
          }
        }

        // 事件监听
        audio.addEventListener('play', () => {
          isPlaying = true;
          iconPlay.style.display = 'none';
          iconPause.style.display = 'block';
        });

        audio.addEventListener('pause', () => {
          isPlaying = false;
          iconPlay.style.display = 'block';
          iconPause.style.display = 'none';
        });

        audio.addEventListener('timeupdate', updateProgress);
        audio.addEventListener('loadedmetadata', updateProgress);
        audio.addEventListener('ended', next);

        playBtn.addEventListener('click', togglePlay);
        prevBtn.addEventListener('click', prev);
        nextBtn.addEventListener('click', next);
        progressBar.addEventListener('click', seek);

        // 获取播放列表
        const apiBase = api.endsWith('/api') ? api : (api.endsWith('/') ? api + 'api' : api + '/api');
        const apiUrl = `${apiBase}?server=${server}&type=${type}&id=${id}&r=${Date.now()}`;
        fetch(apiUrl)
          .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
          })
          .then(data => {
            console.log('Meting API response:', data);
            if (Array.isArray(data) && data.length) {
              playlist = data;
              currentIndex = Math.floor(Math.random() * playlist.length);
              loadSong(currentIndex);
            } else {
              songNameEl.textContent = '暂无歌曲';
              artistEl.textContent = '播放列表为空';
            }
          })
          .catch(err => {
            console.error('Meting API error:', err);
            songNameEl.textContent = '加载失败';
            artistEl.textContent = '请检查配置';
          });
      })();

      // 欢迎来访者组件
      (function initWelcomeVisitor() {
        const welcomeEl = document.querySelector('.welcome-visitor');
        if (!welcomeEl) return;

        const api = welcomeEl.dataset.api;
        const mainTextEl = welcomeEl.querySelector('.welcome-main-text');
        const greetingTextEl = welcomeEl.querySelector('.greeting-text');
        const moonEl = welcomeEl.querySelector('.welcome-moon');
        const tipTextEl = welcomeEl.querySelector('.welcome-tip-text');

        // 获取问候语
        function getGreeting() {
          const hour = new Date().getHours();
          if (hour < 6) return { text: '夜深了，注意休息', icon: '🌙' };
          if (hour < 9) return { text: '早上好，开启美好一天', icon: '🌅' };
          if (hour < 12) return { text: '上午好，工作顺利', icon: '☀️' };
          if (hour < 14) return { text: '中午好，记得休息', icon: '🍜' };
          if (hour < 18) return { text: '下午好，继续加油', icon: '☕' };
          return { text: '晚上好，愿您放松', icon: '🌙' };
        }

        // 尝试获取位置信息
        async function fetchLocation() {
          // 首先尝试使用浏览器的 Geolocation API
          if (navigator.geolocation) {
            try {
              const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                  timeout: 5000,
                  enableHighAccuracy: false
                });
              });
              
              // 使用经纬度通过反向地理编码获取位置
              const { latitude, longitude } = position.coords;
              
              // 尝试使用 BigDataCloud 免费反向地理编码 API
              try {
                const res = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=zh`);
                const data = await res.json();
                
                const city = data.city || data.locality || '';
                const region = data.principalSubdivision || data.localityInfo?.administrative?.[1]?.name || '';
                
                if (city || region) {
                  const locationStr = region && city ? `${region} ${city}` : (city || region);
                  const greeting = getGreeting();
                  mainTextEl.innerHTML = `欢迎来自 <span class="welcome-location-highlight">${locationStr}</span> 的小友 💕`;
                  greetingTextEl.textContent = greeting.text;
                  moonEl.textContent = greeting.icon;
                  tipTextEl.style.display = 'block';
                  return;
                }
              } catch (e) {
                console.warn('Geolocation API failed:', e);
              }
            } catch (e) {
              console.warn('Browser geolocation failed:', e);
            }
          }
          
          // 备用：尝试 IP API
          const apis = [
            { url: 'https://ipapi.co/json/', type: 'ipapi' },
            { url: 'https://ipinfo.io/json', type: 'ipinfo' }
          ];
          
          for (const apiConfig of apis) {
            try {
              const controller = new AbortController();
              const timeoutId = setTimeout(() => controller.abort(), 3000);
              
              const res = await fetch(apiConfig.url, { signal: controller.signal });
              clearTimeout(timeoutId);
              
              if (!res.ok) continue;
              
              const data = await res.json();
              console.log('Welcome API response:', apiConfig.type, data);
              
              let locationStr = '';
              const region = data.region || '';
              const city = data.city || '';
              
              if (region && city) {
                locationStr = `${region} ${city}`;
              } else if (region) {
                locationStr = region;
              } else if (city) {
                locationStr = city;
              }
              
              if (locationStr) {
                const greeting = getGreeting();
                mainTextEl.innerHTML = `欢迎来自 <span class="welcome-location-highlight">${locationStr}</span> 的小友 💕`;
                greetingTextEl.textContent = greeting.text;
                moonEl.textContent = greeting.icon;
                tipTextEl.style.display = 'block';
                return;
              }
            } catch (err) {
              console.warn(`API ${apiConfig.type} failed:`, err);
              continue;
            }
          }
          
          // 所有方法都失败，显示基于时间的问候
          const greeting = getGreeting();
          mainTextEl.innerHTML = `${greeting.icon} ${greeting.text}`;
          greetingTextEl.textContent = '欢迎来访，愿您有美好的一天';
          moonEl.textContent = '';
          tipTextEl.style.display = 'none';
        }
        
        fetchLocation();
      })();

      // 每日诗词组件
      (function initPoetry() {
        const poetryEl = document.querySelector('.daily-poetry');
        if (!poetryEl) {
          console.log('Poetry element not found');
          return;
        }

        const loadingEl = poetryEl.querySelector('.poetry-loading');
        const contentEl = poetryEl.querySelector('.poetry-content');
        const sentenceEl = poetryEl.querySelector('.poetry-sentence');
        const dynastyEl = poetryEl.querySelector('.poetry-dynasty');
        const authorEl = poetryEl.querySelector('.poetry-author');
        const titleEl = poetryEl.querySelector('.poetry-title');

        console.log('Initializing poetry component...');
        console.log('jinrishici SDK exists:', typeof jinrishici !== 'undefined');

        // 等待 SDK 加载完成
        function waitForSdk(callback, maxAttempts = 100) {
          let attempts = 0;
          const interval = setInterval(() => {
            attempts++;
            console.log('Checking SDK... attempt', attempts);
            if (typeof jinrishici !== 'undefined') {
              console.log('SDK found!');
              clearInterval(interval);
              callback();
            } else if (attempts >= maxAttempts) {
              clearInterval(interval);
              console.error('今日诗词 SDK 加载超时');
              loadingEl.textContent = '加载诗词失败：SDK 超时';
            }
          }, 100);
        }

        waitForSdk(function() {
          console.log('Calling jinrishici.load...');
          try {
            jinrishici.load(function(result) {
              console.log('Poetry result:', result);
              if (result && result.data) {
                const data = result.data;
                
                // 显示诗词内容
                sentenceEl.textContent = data.content || '';
                dynastyEl.textContent = (data.origin && data.origin.dynasty) ? `[${data.origin.dynasty}]` : '';
                authorEl.textContent = data.origin && data.origin.author ? data.origin.author : '';
                titleEl.textContent = data.origin && data.origin.title ? `《${data.origin.title}》` : '';
                
                // 隐藏加载，显示内容
                loadingEl.style.display = 'none';
                contentEl.style.display = 'block';
              } else {
                console.error('No data in result:', result);
                loadingEl.textContent = '加载诗词失败：无数据';
              }
            }, function(err) {
              console.error('诗词加载失败:', err);
              loadingEl.textContent = '加载诗词失败：' + (err ? err.message : '未知错误');
            });
          } catch (e) {
            console.error('Error calling jinrishici.load:', e);
            loadingEl.textContent = '加载诗词失败：' + e.message;
          }
        });
      })();
    })();
  </script>
</aside>

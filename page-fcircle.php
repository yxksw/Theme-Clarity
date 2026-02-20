<?php
/**
 * 友链朋友圈
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$fcircleTitle = trim((string) clarity_opt('fcircle_title', '友链朋友圈'));
$fcircleDesc = trim((string) clarity_opt('fcircle_desc', '探索友链博客的最新动态'));
$fcircleCoverImg = trim((string) clarity_opt('fcircle_cover_img', ''));
clarity_set('showAside', true);
clarity_set('pageTitle', $fcircleTitle);
clarity_set('isLinksPage', false);

// 配置参数（从后台读取，使用默认值）
$privateApiUrl = trim((string) clarity_opt('fcircle_api_url', 'https://moments.myxz.top/'));
if (empty($privateApiUrl)) {
    $privateApiUrl = 'https://moments.myxz.top/';
}
$pageTurningNumber = (int) clarity_opt('fcircle_page_size', '20');
if ($pageTurningNumber <= 0) {
    $pageTurningNumber = 20;
}
if ($pageTurningNumber > 100) {
    $pageTurningNumber = 100;
}
$errorImg = trim((string) clarity_opt('fcircle_error_img', 'https://fastly.jsdelivr.net/gh/willow-god/Friend-Circle-Lite@latest/static/favicon.ico'));
if (empty($errorImg)) {
    $errorImg = 'https://fastly.jsdelivr.net/gh/willow-god/Friend-Circle-Lite@latest/static/favicon.ico';
}

// 获取数据
$fcircleData = clarity_get_fcircle_data($privateApiUrl);
$articles = $fcircleData['article_data'] ?? [];
$stats = $fcircleData['statistical_data'] ?? [];

// 分页处理
$pageParam = (int) $this->request->get('page', 1);
$currentPage = $pageParam > 0 ? $pageParam : 1;
$totalArticles = count($articles);
$totalPages = $totalArticles > 0 ? (int) ceil($totalArticles / $pageTurningNumber) : 1;
if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}
$offset = ($currentPage - 1) * $pageTurningNumber;
$pagedArticles = array_slice($articles, $offset, $pageTurningNumber);

// 构建分页链接
$fcircleBase = $this->permalink;
$fcircleSep = strpos($fcircleBase, '?') === false ? '?' : '&';
?>
<?php $this->need('header.php'); ?>

<!-- 页面 Banner - 参考 header.vue 组件风格 -->
<div class="fcircle-page-banner">
  <div class="fcircle-cover-wrapper">
    <?php if (!empty($fcircleCoverImg)): ?>
      <img src="<?php echo htmlspecialchars($fcircleCoverImg, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($fcircleTitle, ENT_QUOTES, 'UTF-8'); ?>" class="fcircle-cover-image">
    <?php else: ?>
      <span class="icon-[ph--users-three-bold] fcircle-cover-icon"></span>
    <?php endif; ?>
  </div>
  <div class="fcircle-header-wrapper">
    <h3 class="fcircle-page-title"><?php echo htmlspecialchars($fcircleTitle, ENT_QUOTES, 'UTF-8'); ?></h3>
    <span class="fcircle-page-desc"><?php echo htmlspecialchars($fcircleDesc, ENT_QUOTES, 'UTF-8'); ?></span>
  </div>
</div>

<!-- 统计信息 -->
<?php if (!empty($stats)): ?>
<div class="fcircle-stats">
  <div class="stat-item">
    <span class="stat-value"><?php echo (int) ($stats['friends_num'] ?? 0); ?></span>
    <span class="stat-label">友链数</span>
  </div>
  <div class="stat-item">
    <span class="stat-value"><?php echo (int) ($stats['active_num'] ?? 0); ?></span>
    <span class="stat-label">活跃数</span>
  </div>
  <div class="stat-item">
    <span class="stat-value"><?php echo (int) ($stats['article_num'] ?? 0); ?></span>
    <span class="stat-label">文章数</span>
  </div>
  <?php if (!empty($stats['last_updated_time'])): ?>
  <div class="stat-item">
    <span class="stat-value"><?php echo htmlspecialchars(substr($stats['last_updated_time'], 0, 10), ENT_QUOTES, 'UTF-8'); ?></span>
    <span class="stat-label">更新时间</span>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<div class="fcircle-container">
  <!-- 随机文章区域 -->
  <div class="random-article-section" id="random-article-section">
    <div class="random-header">
      <span class="random-title">随机钓鱼</span>
      <button class="refresh-btn gradient-card" onclick="refreshRandomArticle()" title="换一篇">
        <span class="icon-[ph--shuffle-bold]"></span>
      </button>
    </div>
    <div class="random-article-content" id="random-article-content">
      <!-- 随机文章将通过 JS 加载 -->
      <div class="random-placeholder">正在加载...</div>
    </div>
  </div>

  <!-- 文章列表 -->
  <div class="fcircle-list" id="fcircle-list">
    <?php if (!empty($pagedArticles)): ?>
      <?php foreach ($pagedArticles as $index => $article): ?>
        <?php
        $author = $article['author'] ?? '';
        $avatar = $article['avatar'] ?? '';
        $title = $article['title'] ?? '';
        $link = $article['link'] ?? '';
        $created = $article['created'] ?? '';
        $dateStr = $created ? substr($created, 0, 10) : '';
        ?>
        <div class="fcircle-item" style="--delay: <?php echo ($index * 0.05); ?>s">
          <div class="fcircle-avatar" onclick="showAuthorModal('<?php echo htmlspecialchars($author, ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8'); ?>', '<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>')">
            <img src="<?php echo htmlspecialchars($avatar ?: $errorImg, ENT_QUOTES, 'UTF-8'); ?>" 
                 alt="<?php echo htmlspecialchars($author, ENT_QUOTES, 'UTF-8'); ?>"
                 onerror="this.src='<?php echo htmlspecialchars($errorImg, ENT_QUOTES, 'UTF-8'); ?>'">
          </div>
          <div class="fcircle-content gradient-card" onclick="openArticle('<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>')">
            <div class="fcircle-author"><?php echo htmlspecialchars($author, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="fcircle-title-text"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="fcircle-date"><?php echo htmlspecialchars($dateStr, ENT_QUOTES, 'UTF-8'); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="fcircle-empty">
        <span class="icon-[ph--article-ny-times-bold]"></span>
        <p>暂无文章数据</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- 分页 -->
  <?php if ($totalPages > 1): ?>
    <nav class="pagination-wrapper">
      <div class="pagination">
        <?php if ($currentPage > 1): ?>
          <a class="page-btn page-prev" href="<?php echo htmlspecialchars($fcircleBase . $fcircleSep . 'page=' . ($currentPage - 1), ENT_QUOTES, 'UTF-8'); ?>">
            <span class="icon-[ph--caret-left-bold]"></span>
          </a>
        <?php else: ?>
          <span class="page-btn page-prev disabled"><span class="icon-[ph--caret-left-bold]"></span></span>
        <?php endif; ?>

        <div class="page-numbers">
          <?php if ($currentPage > 2): ?>
            <a class="page-num" href="<?php echo htmlspecialchars($fcircleBase, ENT_QUOTES, 'UTF-8'); ?>">1</a>
          <?php endif; ?>
          <?php if ($currentPage > 3): ?>
            <span class="page-ellipsis">...</span>
          <?php endif; ?>
          <?php if ($currentPage > 1): ?>
            <a class="page-num" href="<?php echo htmlspecialchars($fcircleBase . $fcircleSep . 'page=' . ($currentPage - 1), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $currentPage - 1; ?></a>
          <?php endif; ?>
          <span class="page-num active"><?php echo $currentPage; ?></span>
          <?php if ($currentPage < $totalPages): ?>
            <a class="page-num" href="<?php echo htmlspecialchars($fcircleBase . $fcircleSep . 'page=' . ($currentPage + 1), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $currentPage + 1; ?></a>
          <?php endif; ?>
          <?php if ($currentPage < $totalPages - 2): ?>
            <span class="page-ellipsis">...</span>
          <?php endif; ?>
          <?php if ($currentPage < $totalPages - 1): ?>
            <a class="page-num" href="<?php echo htmlspecialchars($fcircleBase . $fcircleSep . 'page=' . $totalPages, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $totalPages; ?></a>
          <?php endif; ?>
        </div>

        <?php if ($currentPage < $totalPages): ?>
          <a class="page-btn page-next" href="<?php echo htmlspecialchars($fcircleBase . $fcircleSep . 'page=' . ($currentPage + 1), ENT_QUOTES, 'UTF-8'); ?>">
            <span class="icon-[ph--caret-right-bold]"></span>
          </a>
        <?php else: ?>
          <span class="page-btn page-next disabled"><span class="icon-[ph--caret-right-bold]"></span></span>
        <?php endif; ?>
      </div>
    </nav>
  <?php endif; ?>
</div>

<!-- 作者文章模态框 -->
<div id="author-modal" class="fcircle-modal" onclick="hideAuthorModal(event)">
  <div class="modal-content" onclick="event.stopPropagation()">
    <div class="modal-header">
      <img id="modal-avatar" src="" alt="" onerror="this.src='<?php echo htmlspecialchars($errorImg, ENT_QUOTES, 'UTF-8'); ?>'">
      <a id="modal-author-link" href="" target="_blank" rel="noopener noreferrer"></a>
      <button class="modal-close" onclick="hideAuthorModal()">
        <span class="icon-[ph--x-bold]"></span>
      </button>
    </div>
    <div class="modal-body" id="modal-articles">
      <!-- 作者文章列表将通过 JS 加载 -->
    </div>
    <img id="modal-bg" src="" alt="" onerror="this.src='<?php echo htmlspecialchars($errorImg, ENT_QUOTES, 'UTF-8'); ?>'">
  </div>
</div>

<script>
// 配置参数
const FCIRCLE_CONFIG = {
  private_api_url: '<?php echo htmlspecialchars($privateApiUrl, ENT_QUOTES, 'UTF-8'); ?>',
  page_turning_number: <?php echo (int) $pageTurningNumber; ?>,
  error_img: '<?php echo htmlspecialchars($errorImg, ENT_QUOTES, 'UTF-8'); ?>',
  current_page: <?php echo (int) $currentPage; ?>,
  total_pages: <?php echo (int) $totalPages; ?>
};

// 所有文章数据
let allArticles = <?php echo json_encode($articles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

// 打开文章链接
function openArticle(link) {
  if (link) {
    window.open(link, '_blank', 'noopener,noreferrer');
  }
}

// 刷新随机文章
function refreshRandomArticle() {
  const container = document.getElementById('random-article-content');
  if (!container || allArticles.length === 0) return;
  
  const randomIndex = Math.floor(Math.random() * allArticles.length);
  const article = allArticles[randomIndex];
  
  container.innerHTML = `
    <div class="random-item" onclick="openArticle('${escapeHtml(article.link || '')}')">
      <div class="random-author">${escapeHtml(article.author || '')}</div>
      <div class="random-title-text">${escapeHtml(article.title || '')}</div>
      <div class="random-date">${escapeHtml((article.created || '').substring(0, 10))}</div>
    </div>
  `;
}

// 显示作者模态框
function showAuthorModal(author, avatar, link) {
  const modal = document.getElementById('author-modal');
  const avatarEl = document.getElementById('modal-avatar');
  const authorLink = document.getElementById('modal-author-link');
  const articlesContainer = document.getElementById('modal-articles');
  const bgEl = document.getElementById('modal-bg');
  
  if (!modal) return;
  
  // 设置作者信息
  avatarEl.src = avatar || FCIRCLE_CONFIG.error_img;
  avatarEl.alt = author;
  authorLink.textContent = author;
  
  // 安全地获取 origin
  let origin = '#';
  if (link) {
    try {
      origin = new URL(link).origin;
    } catch (e) {
      origin = link;
    }
  }
  authorLink.href = origin;
  bgEl.src = avatar || FCIRCLE_CONFIG.error_img;
  
  // 获取该作者的文章
  const authorArticles = allArticles.filter(a => a.author === author).slice(0, 5);
  
  if (authorArticles.length > 0) {
    articlesContainer.innerHTML = authorArticles.map((article, index) => `
      <div class="modal-article" style="--delay: ${index * 0.1}s">
        <a class="modal-article-title" href="${escapeHtml(article.link || '')}" target="_blank" rel="noopener noreferrer">
          ${escapeHtml(article.title || '')}
        </a>
        <div class="modal-article-date">${escapeHtml((article.created || '').substring(0, 10))}</div>
      </div>
    `).join('');
  } else {
    articlesContainer.innerHTML = '<div class="modal-empty">暂无文章</div>';
  }
  
  modal.classList.add('modal-open');
  document.body.style.overflow = 'hidden';
}

// 隐藏作者模态框
function hideAuthorModal(event) {
  if (event && event.target !== event.currentTarget) return;
  
  const modal = document.getElementById('author-modal');
  if (modal) {
    modal.classList.remove('modal-open');
    document.body.style.overflow = '';
  }
}

// HTML 转义
function escapeHtml(text) {
  if (!text) return '';
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// 初始化
(function() {
  // 加载随机文章
  refreshRandomArticle();
  
  // ESC 键关闭模态框
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      hideAuthorModal();
    }
  });
})();
</script>

<?php $this->need('footer.php'); ?>

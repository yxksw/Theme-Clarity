<?php
/**
 * 画廊页面
 *
 * @package custom
 * @template gallery
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$galleryTitle = trim((string) clarity_opt('gallery_title', '画廊'));
clarity_set('showAside', false);
clarity_set('pageTitle', $galleryTitle);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>
<?php
$galleryDesc = trim((string) clarity_opt('gallery_desc', '随机图片画廊'));
$galleryDomain = 'https://img.050815.xyz'; // 用户部署的随机图片API域名
?>

<div class="gallery-wrapper">
  <div class="gallery-header">
    <div class="gallery-header-left">
      <div class="gallery-icon">
        <span class="icon-[ph--images-bold]"></span>
      </div>
      <div class="gallery-title-section">
        <h1 class="gallery-title">
          <?php echo htmlspecialchars($galleryTitle, ENT_QUOTES, 'UTF-8'); ?>
          <span id="gallery-status" class="gallery-count"></span>
        </h1>
        <p class="gallery-info">
          图片二进制自托管于：<a href="<?php echo $galleryDomain; ?>" target="_blank" rel="noopener noreferrer" class="gallery-link"><?php echo $galleryDomain; ?></a>
        </p>
      </div>
    </div>
    <div class="gallery-toolbar">
      <button id="btn-h" type="button" class="gallery-btn active">横屏</button>
      <button id="btn-v" type="button" class="gallery-btn">竖屏</button>
      <button id="btn-sort" type="button" class="gallery-btn">最新</button>
    </div>
  </div>

  <div class="gallery-container">
    <div class="gallery-grid" id="gallery-grid" data-ready="false"></div>
    <div id="gallery-footer-status" class="gallery-status-text"></div>
    <div class="gallery-load-more">
      <button id="btn-load-more" type="button" class="load-more-btn">加载更多</button>
    </div>
  </div>
</div>

<style>
/* 画廊页面样式 - 参考图片优化 */
.gallery-wrapper {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
}

.gallery-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.gallery-header-left {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
}

.gallery-icon {
  width: 2.5rem;
  height: 2.5rem;
  background: var(--c-primary);
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.25rem;
  flex-shrink: 0;
}

.gallery-title-section {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.gallery-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--c-text);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.gallery-count {
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--c-text-2);
}

.gallery-info {
  font-size: 0.875rem;
  color: var(--c-text-2);
  margin: 0;
}

.gallery-link {
  color: var(--c-primary);
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

.gallery-link:hover {
  color: var(--c-primary-soft);
  text-decoration: underline;
}

.gallery-toolbar {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.gallery-btn {
  padding: 0.375rem 0.875rem;
  border-radius: 0.375rem;
  border: 1px solid var(--c-border);
  background: var(--c-bg);
  color: var(--c-text);
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s;
}

.gallery-btn:hover {
  background: var(--c-bg-2);
  border-color: var(--c-primary);
}

.gallery-btn.active {
  background: var(--c-primary);
  color: white;
  border-color: var(--c-primary);
}

.gallery-container {
  min-height: 400px;
  position: relative;
}

.gallery-grid {
  width: 100%;
  opacity: 0;
  transform: translateY(4px);
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.gallery-grid[data-ready="true"] {
  opacity: 1;
  transform: translateY(0);
}

.gallery-item {
  margin-bottom: 1rem;
  break-inside: avoid;
  border-radius: 0.75rem;
  overflow: hidden;
  cursor: pointer;
  background: var(--c-bg-2);
  transition: transform 0.3s, box-shadow 0.3s;
  opacity: 0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.gallery-item.loaded {
  opacity: 1;
}

.gallery-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.dark .gallery-item:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.gallery-item img {
  width: 100%;
  height: auto;
  display: block;
  transition: transform 0.4s;
}

.gallery-item:hover img {
  transform: scale(1.03);
}

.gallery-status-text {
  text-align: center;
  font-size: 0.875rem;
  color: var(--c-text-2);
  height: 2rem;
  margin-top: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.gallery-load-more {
  display: flex;
  justify-content: center;
  margin-top: 1.5rem;
  margin-bottom: 2rem;
}

.load-more-btn {
  padding: 0.625rem 1.75rem;
  border-radius: 0.5rem;
  border: 1px solid var(--c-primary);
  background: var(--c-primary);
  color: white;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
}

.load-more-btn:hover {
  background: var(--c-primary-soft);
  color: var(--c-primary);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
}

.load-more-btn:disabled {
  background: var(--c-bg-2);
  color: var(--c-text-3);
  border-color: var(--c-border);
  cursor: not-allowed;
  box-shadow: none;
  transform: none;
}

/* 响应式布局 */
@media (max-width: 768px) {
  .gallery-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .gallery-toolbar {
    width: 100%;
    justify-content: flex-start;
  }

  .gallery-grid {
    column-count: 2;
    column-gap: 0.75rem;
  }

  .gallery-item {
    margin-bottom: 0.75rem;
    border-radius: 0.5rem;
  }

  .gallery-title {
    font-size: 1.25rem;
  }
}

@media (max-width: 480px) {
  .gallery-wrapper {
    padding: 0.75rem;
  }

  .gallery-grid {
    column-count: 2;
    column-gap: 0.5rem;
  }

  .gallery-item {
    margin-bottom: 0.5rem;
    border-radius: 0.375rem;
  }

  .gallery-btn {
    padding: 0.25rem 0.625rem;
    font-size: 0.8125rem;
  }
}

@media (min-width: 769px) {
  .gallery-grid {
    column-count: 3;
    column-gap: 1rem;
  }
}

@media (min-width: 1024px) {
  .gallery-grid {
    column-count: 4;
    column-gap: 1rem;
  }
}

@media (min-width: 1280px) {
  .gallery-grid {
    column-count: 4;
    column-gap: 1.25rem;
  }

  .gallery-item {
    margin-bottom: 1.25rem;
  }
}
</style>

<script>
(function() {
  const DOMAIN = '<?php echo $galleryDomain; ?>';
  const RANDOM_JS_URL = DOMAIN + '/random.js';
  const FIRST_LOAD_COUNT = 30; // 首次加载数量
  const LOAD_MORE_COUNT = 20;  // 每次加载更多数量

  let counts = { h: 0, v: 0 };
  let currentType = 'h';
  let isReverse = false;
  let nextIndex = 1;
  let loading = false;
  let hasMore = true;

  const galleryGrid = document.getElementById('gallery-grid');
  const galleryStatus = document.getElementById('gallery-status');
  const galleryFooterStatus = document.getElementById('gallery-footer-status');
  const btnLoadMore = document.getElementById('btn-load-more');
  const btnH = document.getElementById('btn-h');
  const btnV = document.getElementById('btn-v');
  const btnSort = document.getElementById('btn-sort');

  // 获取图片总数
  async function fetchCounts() {
    try {
      const res = await fetch(RANDOM_JS_URL, { cache: 'no-store' });
      if (!res.ok) throw new Error(String(res.status));
      const text = await res.text();
      const m = text.match(/var\s+counts\s*=\s*(\{[^;]+\})\s*;/);
      if (!m) throw new Error('counts not found');
      const parsed = JSON.parse(m[1]);
      const h = Number(parsed.h || 0);
      const v = Number(parsed.v || 0);
      if (!Number.isFinite(h) || !Number.isFinite(v)) throw new Error('counts invalid');
      counts = { h, v };
      updateStatus();
    } catch (e) {
      // 使用默认值
      counts = { h: 100, v: 100 };
      updateStatus();
    }
  }

  // 更新状态显示
  function updateStatus() {
    const count = counts[currentType] || 0;
    if (galleryStatus) {
      galleryStatus.textContent = `共 ${count} 张`;
    }
  }

  // 更新按钮状态
  function setActiveButton() {
    if (btnH && btnV) {
      btnH.classList.toggle('active', currentType === 'h');
      btnV.classList.toggle('active', currentType === 'v');
    }
  }

  // 更新排序按钮
  function updateSortButton() {
    if (btnSort) {
      btnSort.textContent = isReverse ? '最早' : '最新';
      btnSort.classList.toggle('active', isReverse);
    }
  }

  // 重置索引
  function resetIndex() {
    const max = counts[currentType] || 0;
    if (isReverse) {
      nextIndex = max;
    } else {
      nextIndex = 1;
    }
    hasMore = true;
  }

  // 获取下一个索引
  function getNextIndex(max) {
    if (isReverse) {
      if (nextIndex < 1) return null;
      return nextIndex--;
    }
    if (nextIndex > max) return null;
    return nextIndex++;
  }

  // 构建图片URL
  function buildImgUrl(n) {
    return `${DOMAIN}/ri/${currentType}/${n}.webp`;
  }

  // 创建图片卡片
  function buildCard(url) {
    const a = document.createElement('a');
    a.href = url;
    a.className = 'gallery-item';

    const img = document.createElement('img');
    img.src = url;
    img.loading = 'lazy';
    img.decoding = 'async';
    img.alt = 'gallery';

    // 图片加载完成后显示
    img.onload = function() {
      a.classList.add('loaded');
    };

    img.onerror = function() {
      a.classList.add('loaded');
    };

    // 添加点击事件，使用Fancybox打开图片
    a.onclick = function(e) {
      e.preventDefault();
      const imagesArray = [{
        src: url,
        caption: 'Gallery Image'
      }];

      const openFancybox = function() {
        window.Fancybox && window.Fancybox.show(imagesArray, {
          groupAll: false,
          Carousel: {
            Navigation: false
          }
        });
      };

      if (window.Fancybox) {
        openFancybox();
      } else {
        window.__clarityFancyboxLoading && window.__clarityFancyboxLoading.then(() => {
          openFancybox();
        });
      }
    };

    a.appendChild(img);
    return a;
  }

  // 更新加载更多按钮状态
  function updateLoadMoreButton() {
    if (!btnLoadMore) return;

    if (loading) {
      btnLoadMore.disabled = true;
      btnLoadMore.textContent = '加载中...';
    } else if (!hasMore) {
      btnLoadMore.disabled = true;
      btnLoadMore.textContent = '已加载全部';
    } else {
      btnLoadMore.disabled = false;
      btnLoadMore.textContent = '加载更多';
    }
  }

  // 清空画廊
  function clearGallery() {
    if (!galleryGrid) return;
    galleryGrid.innerHTML = '';
    galleryGrid.dataset.ready = 'false';
    resetIndex();
    updateLoadMoreButton();
  }

  // 加载图片
  async function loadImages(batchSize) {
    if (!galleryGrid || loading) return;
    const max = counts[currentType];
    if (!max) return;

    loading = true;
    updateLoadMoreButton();

    try {
      const frag = document.createDocumentFragment();
      const items = [];

      for (let i = 0; i < batchSize; i++) {
        const n = getNextIndex(max);
        if (!n) {
          hasMore = false;
          break;
        }
        const url = buildImgUrl(n);
        const el = buildCard(url);
        items.push(el);
        frag.appendChild(el);
      }

      if (items.length > 0) {
        galleryGrid.appendChild(frag);

        // 等待所有图片加载完成
        items.forEach(item => {
          const img = item.querySelector('img');
          if (img.complete) {
            item.classList.add('loaded');
          }
        });

        // 设置ready状态
        if (galleryGrid.dataset.ready !== 'true') {
          galleryGrid.dataset.ready = 'true';
        }
      }

      // 检查是否还有更多
      if (isReverse) {
        hasMore = nextIndex >= 1;
      } else {
        hasMore = nextIndex <= max;
      }

      if (galleryFooterStatus) {
        galleryFooterStatus.textContent = hasMore ? '' : '已加载全部';
      }
    } finally {
      loading = false;
      updateLoadMoreButton();
    }
  }

  // 切换类型
  function setType(type) {
    if (type !== 'h' && type !== 'v') return;
    if (currentType === type) return;
    currentType = type;
    setActiveButton();
    updateStatus();
    clearGallery();
    loadImages(FIRST_LOAD_COUNT);
  }

  // 切换排序
  function toggleSort() {
    isReverse = !isReverse;
    updateSortButton();
    clearGallery();
    loadImages(FIRST_LOAD_COUNT);
  }

  // 加载更多
  function loadMore() {
    if (!hasMore || loading) return;
    loadImages(LOAD_MORE_COUNT);
  }

  // 初始化
  async function init() {
    if (!galleryGrid) return;
    if (galleryGrid.dataset.inited === 'true') return;
    galleryGrid.dataset.inited = 'true';

    setActiveButton();
    await fetchCounts();
    updateSortButton();
    clearGallery();
    await loadImages(FIRST_LOAD_COUNT);

    // 绑定按钮事件
    if (btnH) btnH.addEventListener('click', () => setType('h'));
    if (btnV) btnV.addEventListener('click', () => setType('v'));
    if (btnSort) btnSort.addEventListener('click', toggleSort);
    if (btnLoadMore) btnLoadMore.addEventListener('click', loadMore);
  }

  // 启动
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
</script>

<?php $this->need('footer.php'); ?>
<?php
/**
 * 装备页面
 *
 * @package custom
 * @template equipment
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php');

// 获取配置
$equipmentApi = trim((string) clarity_opt('equipment_api', 'https://cofe.050815.xyz/api/devices'));
$equipmentTitle = trim((string) clarity_opt('equipment_title', '我的装备'));
$equipmentDesc = trim((string) clarity_opt('equipment_desc', '记录我的数字生活装备'));
?>

<div class="z-container">
  <div class="equipment-page">
    <!-- 页面头部 -->
    <div class="equipment-header">
      <h1 class="equipment-title"><?php echo htmlspecialchars($equipmentTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
      <p class="equipment-desc"><?php echo htmlspecialchars($equipmentDesc, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- 统计信息 -->
    <div class="equipment-stats" id="equipment-stats" style="display: none;">
      <div class="stat-item">
        <span class="stat-value" id="stat-total">0</span>
        <span class="stat-label">件装备</span>
      </div>
      <div class="stat-item">
        <span class="stat-value" id="stat-money">0</span>
        <span class="stat-label">总价值</span>
      </div>
      <div class="stat-item">
        <span class="stat-value" id="stat-categories">0</span>
        <span class="stat-label">个分类</span>
      </div>
    </div>

    <!-- 分类筛选 -->
    <div class="equipment-categories" id="equipment-categories">
      <button class="category-btn active" data-category="all">全部</button>
    </div>

    <!-- 装备列表 -->
    <div class="equipment-list" id="equipment-list">
      <div class="equipment-loading">
        <div class="loading-spinner"></div>
        <p>正在加载装备数据...</p>
      </div>
    </div>

    <!-- 更新时间 -->
    <div class="equipment-footer" id="equipment-footer" style="display: none;">
      <p>最后更新：<span id="update-time">-</span></p>
    </div>
  </div>
</div>

<script>
(function() {
  const apiUrl = '<?php echo htmlspecialchars($equipmentApi, ENT_QUOTES, 'UTF-8'); ?>';
  const listEl = document.getElementById('equipment-list');
  const statsEl = document.getElementById('equipment-stats');
  const categoriesEl = document.getElementById('equipment-categories');
  const footerEl = document.getElementById('equipment-footer');
  
  let allDevices = [];
  let currentCategory = 'all';

  // 格式化金额
  function formatMoney(money) {
    if (!money && money !== 0) return '-';
    return '¥' + money.toLocaleString('zh-CN');
  }

  // 格式化日期
  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('zh-CN', { year: 'numeric', month: 'long' });
  }

  // 获取分类颜色
  function getCategoryColor(category) {
    const colors = {
      '生产力': '#3af',
      '出行': '#3ba',
      '娱乐': '#f3a',
      '生活': '#a3f'
    };
    return colors[category] || '#3af';
  }

  // 渲染装备卡片
  function renderDevice(device) {
    const categoryColor = getCategoryColor(device.category);
    const infoHtml = device.info ? Object.entries(device.info).map(([key, value]) => 
      `<div class="info-item">
        <span class="info-key">${key}</span>
        <span class="info-value">${value}</span>
      </div>`
    ).join('') : '';

    const tagsHtml = device.tag ? device.tag.map(tag => 
      `<span class="device-tag" style="background: ${categoryColor}20; color: ${categoryColor}">${tag}</span>`
    ).join('') : '';

    return `
      <div class="equipment-card" data-category="${device.category}">
        <div class="card-image">
          ${device.image ? `<img src="${device.image}" alt="${device.name}" loading="lazy">` : '<div class="no-image">暂无图片</div>'}
          <span class="card-category" style="background: ${categoryColor}">${device.category}</span>
        </div>
        <div class="card-content">
          <div class="card-header">
            <h3 class="device-name">${device.name}</h3>
            <span class="device-category-tag" style="background: ${categoryColor}">${device.category}</span>
          </div>
          <p class="device-desc">${device.desc || ''}</p>
          ${infoHtml ? `<div class="device-info">${infoHtml}</div>` : ''}
          ${tagsHtml ? `<div class="device-tags">${tagsHtml}</div>` : ''}
          <div class="card-footer">
            <div class="footer-left">
              <span class="device-date">
                <span class="icon-[ph--calendar-blank]"></span>
                ${formatDate(device.date)}
              </span>
              <span class="device-money">${formatMoney(device.money)}</span>
            </div>
            <div class="footer-right">
              ${device.src ? `<a href="${device.src}" target="_blank" rel="noopener noreferrer" class="device-link">详情</a>` : ''}
              <a href="#comments" class="comment-btn" title="评论">
                <span class="icon-[ph--chat-circle-dots]"></span>
              </a>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  // 渲染装备列表
  function renderList() {
    const filtered = currentCategory === 'all' 
      ? allDevices 
      : allDevices.filter(d => d.category === currentCategory);
    
    if (filtered.length === 0) {
      listEl.innerHTML = '<div class="equipment-empty">暂无装备数据</div>';
      return;
    }

    listEl.innerHTML = filtered.map(renderDevice).join('');
  }

  // 渲染分类按钮
  function renderCategories(categories) {
    const categoryNames = Object.keys(categories);
    if (categoryNames.length <= 1) return;

    const buttonsHtml = categoryNames.map(cat => 
      `<button class="category-btn" data-category="${cat}" style="--category-color: ${getCategoryColor(cat)}">${cat}</button>`
    ).join('');
    
    categoriesEl.innerHTML = `
      <button class="category-btn active" data-category="all">全部</button>
      ${buttonsHtml}
    `;

    // 绑定分类切换事件
    categoriesEl.querySelectorAll('.category-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        categoriesEl.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentCategory = this.dataset.category;
        renderList();
      });
    });
  }

  // 渲染统计信息
  function renderStats(data) {
    document.getElementById('stat-total').textContent = data.total || 0;
    document.getElementById('stat-money').textContent = formatMoney(data.totalMoney);
    document.getElementById('stat-categories').textContent = Object.keys(data.categoryCount || {}).length;
    statsEl.style.display = 'flex';
  }

  // 渲染更新时间
  function renderUpdateTime(timeStr) {
    if (!timeStr) return;
    const date = new Date(timeStr);
    document.getElementById('update-time').textContent = date.toLocaleString('zh-CN');
    footerEl.style.display = 'block';
  }

  // 加载数据
  async function loadData() {
    try {
      const response = await fetch(apiUrl);
      if (!response.ok) throw new Error('Network response was not ok');
      
      const result = await response.json();
      console.log('Equipment API response:', result);
      
      if (result.code !== 0 || !result.data) {
        throw new Error(result.msg || 'Invalid data');
      }

      const data = result.data;
      allDevices = data.list || [];

      // 渲染各个部分
      renderStats(data);
      renderCategories(data.categoryCount || {});
      renderList();
      renderUpdateTime(data.updatedAt);

    } catch (error) {
      console.error('Failed to load equipment data:', error);
      listEl.innerHTML = `
        <div class="equipment-error">
          <p>加载装备数据失败</p>
          <p class="error-detail">${error.message}</p>
        </div>
      `;
    }
  }

  // 初始化
  loadData();
})();
</script>

<?php $this->need('comments.php'); ?>

<?php $this->need('footer.php'); ?>

<?php
/**
 * 打赏页面
 *
 * @package custom
 * @template reward
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php');

// 获取配置
$rewardApi = 'https://cdn.jsdmirror.cn/gh/yxksw/Friends@main/data/sponsors.json';
$rewardTitle = trim((string) clarity_opt('reward_title', '打赏支持'));
$rewardDesc = trim((string) clarity_opt('reward_desc', '您的支持是我前进的动力'));

// 内联CSS样式
$inlineCss = <<<EOT
<style>
/* 打赏页面样式 */
.reward-page {
  min-height: 100vh;
  padding: 2rem 0;
  position: relative;
  background: url('https://img.314926.xyz/v') no-repeat center center fixed;
  background-size: cover;
}

.reward-page::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.85);
  z-index: 1;
}

.dark .reward-page::before {
  background: rgba(0, 0, 0, 0.7);
}

.reward-page > * {
  position: relative;
  z-index: 2;
}

.reward-header {
  text-align: center;
  margin-bottom: 2rem;
  animation: reward-float-in 0.3s ease backwards;
}

.reward-title {
  font-size: 2rem;
  font-weight: 700;
  color: var(--c-text);
  margin-bottom: 0.5rem;
  line-height: 1.2;
}

.reward-desc {
  font-size: 1rem;
  color: var(--c-text-2);
  margin: 0;
}

.reward-stats {
  display: flex;
  justify-content: center;
  gap: 2rem;
  margin-bottom: 3rem;
  padding: 1rem;
  background: rgba(255, 255, 255, 0.9);
  border-radius: 1rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  animation: reward-float-in 0.3s ease 0.1s backwards;
}

.dark .reward-stats {
  background: rgba(30, 30, 30, 0.9);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.stat-item {
  text-align: center;
}

.stat-value {
  display: block;
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--c-primary);
  line-height: 1.2;
}

.stat-label {
  font-size: 0.875rem;
  color: var(--c-text-2);
  margin-top: 0.25rem;
}

.reward-qrcodes {
  margin-bottom: 3rem;
  animation: reward-float-in 0.3s ease 0.2s backwards;
}

.qrcode-section {
  margin-bottom: 2rem;
  text-align: center;
}

.section-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--c-text);
  margin-bottom: 1.5rem;
  text-align: center;
}

.qrcode-grid {
  display: flex;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
  max-width: 600px;
  margin: 0 auto;
}

.qrcode-item {
  display: none;
}

.qrcode-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 1rem;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  border: 1px solid rgba(0, 0, 0, 0.1);
  flex: 1;
  min-width: 250px;
  max-width: 300px;
}

.dark .qrcode-card {
  background: rgba(30, 30, 30, 0.95);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.qrcode-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.dark .qrcode-card:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.qrcode-header {
  margin-bottom: 1rem;
}

.qrcode-name {
  font-size: 1rem;
  font-weight: 600;
  color: var(--c-text);
}

.qrcode-image {
  margin: 0 auto;
  max-width: 180px;
}

.qrcode-image img {
  width: 100%;
  height: auto;
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.dark .qrcode-image img {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.thank-section {
  text-align: center;
  margin-top: 2rem;
}

.thank-card {
  display: inline-block;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  border: 1px solid rgba(0, 0, 0, 0.1);
}

.dark .thank-card {
  background: rgba(30, 30, 30, 0.95);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.thank-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.dark .thank-card:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.thank-card img {
  max-width: 300px;
  height: auto;
  border-radius: 0.5rem;
}

.reward-list-section {
  margin-bottom: 2rem;
  animation: reward-float-in 0.3s ease 0.3s backwards;
}

.reward-list {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(0, 0, 0, 0.1);
  max-width: 800px;
  margin: 0 auto;
}

.dark .reward-list {
  background: rgba(30, 30, 30, 0.95);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.reward-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  padding: 3rem 0;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid var(--c-border);
  border-top: 3px solid var(--c-primary);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.reward-empty {
  text-align: center;
  padding: 3rem 0;
  color: var(--c-text-3);
  font-size: 1rem;
}

.reward-error {
  text-align: center;
  padding: 3rem 0;
  color: var(--c-danger);
}

.error-detail {
  font-size: 0.875rem;
  margin-top: 0.5rem;
  color: var(--c-text-2);
}

.supporters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}

.supporter-card {
  background: var(--c-bg);
  border-radius: 0.75rem;
  padding: 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s, box-shadow 0.2s;
  border: 1px solid var(--c-border);
}

.dark .supporter-card {
  background: var(--c-bg-2);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.supporter-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.dark .supporter-card:hover {
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
}

.supporter-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
}

.supporter-avatar {
  flex-shrink: 0;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.supporter-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.supporter-details {
  flex: 1;
  min-width: 0;
}

.supporter-name {
  font-size: 0.9375rem;
  font-weight: 600;
  color: var(--c-text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 0.25rem;
}

.supporter-meta {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.75rem;
  color: var(--c-text-2);
}

.supporter-amount {
  font-weight: 600;
  color: var(--c-primary);
  white-space: nowrap;
}

.supporter-date {
  font-family: var(--font-mono);
  white-space: nowrap;
}

.supporter-website {
  color: var(--c-primary);
  text-decoration: none;
  transition: color 0.2s;
  font-size: 0.75rem;
}

.supporter-website:hover {
  text-decoration: underline;
  color: var(--c-primary-soft);
}

.supporter-description {
  font-size: 0.8125rem;
  color: var(--c-text-2);
  line-height: 1.5;
  margin: 0.5rem 0 0;
}

.reward-footer {
  margin-top: 3rem;
  text-align: center;
  animation: reward-float-in 0.3s ease 0.4s backwards;
}

.reward-notes {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(0, 0, 0, 0.1);
  max-width: 800px;
  margin: 0 auto;
}

.dark .reward-notes {
  background: rgba(30, 30, 30, 0.95);
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.reward-notes p {
  margin: 0.5rem 0;
  color: var(--c-text-2);
  line-height: 1.6;
}

@keyframes reward-float-in {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .reward-page {
    padding: 1rem 0;
  }
  
  .reward-title {
    font-size: 1.5rem;
  }
  
  .reward-stats {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
    max-width: 300px;
    margin: 0 auto 2rem;
  }
  
  .stat-value {
    font-size: 1.25rem;
  }
  
  .qrcode-grid {
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
  }
  
  .qrcode-card {
    max-width: 280px;
  }
  
  .qrcode-image {
    max-width: 180px;
  }
  
  .supporters-grid {
    grid-template-columns: 1fr;
  }
  
  .reward-list {
    padding: 1rem;
  }
  
  .thank-card img {
    max-width: 250px;
  }
  
  .reward-notes {
    padding: 1rem;
  }
}

@media (max-width: 480px) {
  .qrcode-card {
    padding: 1rem;
  }
  
  .qrcode-image {
    max-width: 150px;
  }
  
  .thank-card img {
    max-width: 200px;
  }
  
  .reward-list {
    padding: 0.75rem;
  }
}

@media (prefers-reduced-motion: reduce) {
  .reward-header,
  .reward-stats,
  .reward-qrcodes,
  .reward-list-section,
  .reward-footer {
    animation: none;
  }
  
  .loading-spinner {
    animation: none;
  }
  
  .qrcode-card:hover,
  .thank-card:hover,
  .supporter-card:hover {
    transform: none;
  }
}
</style>
EOT;
echo $inlineCss;
?>

<div class="z-container">
  <div class="reward-page">
    <!-- 页面头部 -->
    <div class="reward-header">
      <h1 class="reward-title"><?php echo htmlspecialchars($rewardTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
      <p class="reward-desc"><?php echo htmlspecialchars($rewardDesc, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- 统计信息 -->
    <div class="reward-stats" id="reward-stats" style="display: none;">
      <div class="stat-item">
        <span class="stat-value" id="stat-total">0</span>
        <span class="stat-label">位支持者</span>
      </div>
      <div class="stat-item">
        <span class="stat-value" id="stat-amount">0</span>
        <span class="stat-label">总金额</span>
      </div>
      <div class="stat-item">
        <span class="stat-value" id="stat-update">-</span>
        <span class="stat-label">最后更新</span>
      </div>
    </div>



    <!-- 赞赏者列表 -->
    <div class="reward-list-section">
      <h2 class="section-title">赞赏者名单</h2>
      <div class="reward-list" id="reward-list">
        <div class="reward-loading">
          <div class="loading-spinner"></div>
          <p>正在加载赞赏数据...</p>
        </div>
      </div>
    </div>

    <!-- 说明文字 -->
    <div class="reward-footer">
      <div class="reward-notes">
        <p>每一份支持都是对我的鼓励与肯定，我会继续努力创作更多优质内容。</p>
        <p>感谢每一位支持我的朋友，您的名字将永远记录在这里。</p>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  const apiUrl = '<?php echo htmlspecialchars($rewardApi, ENT_QUOTES); ?>';
  const statsEl = document.getElementById('reward-stats');
  const rewardListEl = document.getElementById('reward-list');

  // 格式化日期
  function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('zh-CN', { year: 'numeric', month: 'long', day: 'numeric' });
  }

  // 渲染赞赏者列表
  function renderSupporters(list) {
    if (!list || list.length === 0) {
      rewardListEl.innerHTML = '<div class="reward-empty">暂无赞赏记录</div>';
      return;
    }

    const supportersHtml = `
      <div class="supporters-grid">
        ${list.map(item => `
          <div class="supporter-card">
            <div class="supporter-info">
              <div class="supporter-avatar">
                <img src="${item.avatar || 'https://cn.cravatar.com/avatar/default'}" alt="${item.name}" loading="lazy">
              </div>
              <div class="supporter-details">
                <div class="supporter-name">${item.name}</div>
                <div class="supporter-meta">
                  <span class="supporter-amount">${item.amount}</span>
                  <span class="supporter-date">${formatDate(item.date)}</span>
                  ${item.website ? `<a href="${item.website}" target="_blank" rel="noopener noreferrer" class="supporter-website">访问网站</a>` : ''}
                </div>
              </div>
            </div>
            ${item.description ? `<p class="supporter-description">${item.description}</p>` : ''}
          </div>
        `).join('')}
      </div>
    `;

    rewardListEl.innerHTML = supportersHtml;
  }

  // 加载数据
  async function loadData() {
    try {
      const response = await fetch(apiUrl);
      if (!response.ok) throw new Error('Network response was not ok');
      
      const list = await response.json();
      console.log('Reward data:', list);
      
      if (!Array.isArray(list)) {
        throw new Error('Invalid data format');
      }

      // 计算统计数据
      const total = list.length;
      const totalAmount = list.reduce((sum, item) => {
        const amount = parseFloat(item.amount?.replace(/[^\d.]/g, '') || 0);
        return sum + amount;
      }, 0).toFixed(2) + '￥';
      
      const latestDate = list.length > 0 ? list[0].date : null;

      // 渲染统计数据
      document.getElementById('stat-total').textContent = total;
      document.getElementById('stat-amount').textContent = totalAmount;
      document.getElementById('stat-update').textContent = latestDate ? formatDate(latestDate) : '-';
      statsEl.style.display = 'flex';

      // 渲染赞赏者列表
      renderSupporters(list);

    } catch (error) {
      console.error('Failed to load reward data:', error);
      rewardListEl.innerHTML = `
        <div class="reward-error">
          <p>加载打赏数据失败</p>
          <p class="error-detail">${error.message}</p>
        </div>
      `;
    }
  }

  // 初始化
  loadData();
  

})();
</script>

<?php $this->need('footer.php'); ?>

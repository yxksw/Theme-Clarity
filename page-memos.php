<?php
/**
 * Memos/说说页面
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$memosTitle = trim((string) clarity_opt('memos_title', '说说'));
$memosDesc = trim((string) clarity_opt('memos_desc', '记录生活点滴，一些想法'));
$memosApiUrl = trim((string) clarity_opt('memos_api_url', 'https://tg-api.050815.xyz/'));
$memosAuthorName = trim((string) clarity_opt('memos_author_name', '博主'));
$memosAuthorAvatar = trim((string) clarity_opt('memos_author_avatar', ''));

if (empty($memosAuthorAvatar)) {
    $memosAuthorAvatar = $this->options->themeUrl . '/assets/images/avatar.png';
}

clarity_set('showAside', true);
clarity_set('pageTitle', $memosTitle);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>

<div id="memos-page" class="memos-container">
    <!-- 页面标题 -->
    <div class="memos-header">
        <h1 class="memos-title"><?php echo htmlspecialchars($memosTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p class="memos-desc"><?php echo htmlspecialchars($memosDesc, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <!-- 加载状态 -->
    <div id="memos-loading" class="memos-loading">
        <div class="memos-skeleton">
            <div class="skeleton-meta">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-info">
                    <div class="skeleton-nick"></div>
                    <div class="skeleton-date"></div>
                </div>
            </div>
            <div class="skeleton-content">
                <div class="skeleton-text"></div>
                <div class="skeleton-text"></div>
            </div>
        </div>
        <div class="memos-skeleton">
            <div class="skeleton-meta">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-info">
                    <div class="skeleton-nick"></div>
                    <div class="skeleton-date"></div>
                </div>
            </div>
            <div class="skeleton-content">
                <div class="skeleton-text"></div>
                <div class="skeleton-text"></div>
            </div>
        </div>
        <div class="memos-skeleton">
            <div class="skeleton-meta">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-info">
                    <div class="skeleton-nick"></div>
                    <div class="skeleton-date"></div>
                </div>
            </div>
            <div class="skeleton-content">
                <div class="skeleton-text"></div>
                <div class="skeleton-text"></div>
            </div>
        </div>
    </div>

    <!-- 错误状态 -->
    <div id="memos-error" class="memos-error" style="display: none;">
        <div class="error-content">
            <span class="icon-[ph--alert-circle-bold] error-icon"></span>
            <h3>加载失败</h3>
            <p>获取说说数据时出现错误，请稍后重试</p>
            <button class="retry-btn" onclick="loadMemos()">
                <span class="icon-[ph--refresh-bold]"></span>
                重试
            </button>
        </div>
    </div>

    <!-- 空状态 -->
    <div id="memos-empty" class="memos-empty" style="display: none;">
        <span class="icon-[ph--chat-circle-bold] empty-icon"></span>
        <p>暂无说说内容</p>
    </div>

    <!-- 内容列表 -->
    <div id="memos-list" class="memos-list" style="display: none;"></div>

    <!-- 页脚 -->
    <div id="memos-footer" class="memos-footer" style="display: none;">
        <p>仅显示最近记录</p>
    </div>
</div>

<!-- 原生评论系统 -->
<div class="memos-comments">
    <?php $this->need('comments.php'); ?>
</div>

<!-- 引入 Emaction 表情反应组件 -->
<script type="module" src="https://cdn.jsdelivr.net/gh/emaction/frontend.dist@1.0.11/bundle.js"></script>

<script>
// Memos 配置
const MEMOS_CONFIG = {
    apiUrl: '<?php echo htmlspecialchars($memosApiUrl, ENT_QUOTES, 'UTF-8'); ?>',
    authorName: '<?php echo htmlspecialchars($memosAuthorName, ENT_QUOTES, 'UTF-8'); ?>',
    authorAvatar: '<?php echo htmlspecialchars($memosAuthorAvatar, ENT_QUOTES, 'UTF-8'); ?>',
    emactionEndpoint: 'https://api-emaction.050815.xyz/'
};

// 格式化时间
function formatMemosDate(timestamp) {
    if (!timestamp) return '';
    const date = new Date(timestamp);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hour = String(date.getHours()).padStart(2, '0');
    const minute = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day} ${hour}:${minute}`;
}

// 转义 HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// 渲染 Memos 内容
function renderMemosContent(text) {
    if (!text) return '';
    // 处理换行
    return escapeHtml(text).replace(/\n/g, '<br>');
}

// 创建 Memos 项 HTML
function createMemoItem(memo, index) {
    const id = memo.id || index;
    const text = memo.text || '';
    const images = memo.images || [];
    const time = memo.time || memo.date || '';
    const views = memo.views || '';
    
    let imagesHtml = '';
    if (images && images.length > 0) {
        imagesHtml = '<div class="memo-images">';
        images.forEach((img, imgIndex) => {
            imagesHtml += `
                <div class="memo-image-wrapper" onclick="openMemoImage('${escapeHtml(img)}')">
                    <img src="${escapeHtml(img)}" alt="图片" loading="lazy" onerror="this.src='<?php echo $this->options->themeUrl; ?>/assets/images/error.png'">
                </div>
            `;
        });
        imagesHtml += '</div>';
    }
    
    // 生成唯一的 target ID 用于表情反应 - 使用当前页面URL + memo ID
    const pageUrl = window.location.origin + window.location.pathname;
    const targetId = `${pageUrl}#memo-${id}`;
    
    return `
        <div class="memo-item" style="--delay: ${index * 0.1}s" data-id="${id}">
            <div class="memo-meta">
                <img class="memo-avatar" src="${escapeHtml(MEMOS_CONFIG.authorAvatar)}" alt="${escapeHtml(MEMOS_CONFIG.authorName)}" onerror="this.src='<?php echo $this->options->themeUrl; ?>/assets/images/avatar.png'">
                <div class="memo-info">
                    <div class="memo-nick">
                        ${escapeHtml(MEMOS_CONFIG.authorName)}
                        <span class="icon-[ph--check-circle-bold] memo-verified"></span>
                    </div>
                    <div class="memo-date">
                        ${formatMemosDate(time)}
                        ${views ? `<span class="memo-views">· ${views} 次浏览</span>` : ''}
                    </div>
                </div>
            </div>
            <div class="memo-content">
                <div class="memo-text">${renderMemosContent(text)}</div>
                ${imagesHtml}
            </div>
            <div class="memo-bottom">
                <div class="memo-reactions" data-memo-id="${id}">
                    <div class="emaction-container">
                        <!-- 笑脸按钮 -->
                        <button class="emaction-smile-btn" onclick="toggleEmojiPicker('${id}')" title="添加反应">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Zm3.82 1.636a.75.75 0 0 1 1.038.175l.007.009c.103.118.22.222.35.31.264.178.683.37 1.285.37.602 0 1.02-.192 1.285-.371.13-.088.247-.192.35-.31l.007-.008a.75.75 0 0 1 1.222.87l-.022-.015c.02.013.021.015.021.015v.001l-.001.002-.002.003-.005.007-.014.019a2.066 2.066 0 0 1-.184.213c-.16.166-.338.316-.53.445-.63.418-1.37.638-2.127.629-.946 0-1.652-.308-2.126-.63a3.331 3.331 0 0 1-.715-.657l-.014-.02-.005-.006-.002-.003v-.002h-.001l.613-.432-.614.43a.75.75 0 0 1 .183-1.044ZM12 7a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM5 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm5.25 2.25.592.416a97.71 97.71 0 0 0-.592-.416Z"></path>
                            </svg>
                        </button>
                        <!-- 已选表情列表 -->
                        <div class="emaction-selected-list" id="emaction-selected-${id}"></div>
                        <!-- 表情选择器弹窗 -->
                        <div class="emaction-picker" id="emaction-picker-${id}" style="display: none;">
                            <div class="emaction-picker-mask" onclick="closeEmojiPicker('${id}')"></div>
                            <div class="emaction-picker-popup">
                                <span class="emaction-picker-emoji" onclick="selectEmoji('${id}', '👍')">👍</span>
                                <span class="emaction-picker-emoji" onclick="selectEmoji('${id}', '❤️')">❤️</span>
                                <span class="emaction-picker-emoji" onclick="selectEmoji('${id}', '😄')">😄</span>
                                <span class="emaction-picker-emoji" onclick="selectEmoji('${id}', '🎉')">🎉</span>
                                <span class="emaction-picker-emoji" onclick="selectEmoji('${id}', '🚀')">🚀</span>
                                <span class="emaction-picker-emoji" onclick="selectEmoji('${id}', '👀')">👀</span>
                                <span class="emaction-picker-emoji" onclick="selectEmoji('${id}', '😕')">😕</span>
                                <span class="emaction-picker-emoji" onclick="selectEmoji('${id}', '👎')">👎</span>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="memo-reply-btn" onclick="replyMemo('${escapeHtml(text).replace(/'/g, "\\'")}')" title="评论">
                    <span class="icon-[ph--chat-circle-bold]"></span>
                </button>
            </div>
        </div>
    `;
}

// 加载 Memos 数据
async function loadMemos() {
    const loadingEl = document.getElementById('memos-loading');
    const errorEl = document.getElementById('memos-error');
    const emptyEl = document.getElementById('memos-empty');
    const listEl = document.getElementById('memos-list');
    const footerEl = document.getElementById('memos-footer');
    
    // 显示加载状态
    loadingEl.style.display = 'block';
    errorEl.style.display = 'none';
    emptyEl.style.display = 'none';
    listEl.style.display = 'none';
    footerEl.style.display = 'none';
    
    try {
        const response = await fetch(MEMOS_CONFIG.apiUrl);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        // 解析 API 数据
        let memos = [];
        if (data.ChannelMessageData) {
            // 处理 Telegram API 格式
            Object.keys(data.ChannelMessageData).forEach(key => {
                const item = data.ChannelMessageData[key];
                memos.push({
                    id: key,
                    text: item.text,
                    images: item.image || [],
                    time: item.time,
                    views: item.views
                });
            });
        } else if (Array.isArray(data)) {
            memos = data;
        } else if (data.data && Array.isArray(data.data.items)) {
            memos = data.data.items;
        }
        
        // 按时间排序（最新的在前）
        memos.sort((a, b) => {
            const timeA = a.time || a.date || 0;
            const timeB = b.time || b.date || 0;
            return timeB - timeA;
        });
        
        // 限制显示数量
        memos = memos.slice(0, 30);
        
        loadingEl.style.display = 'none';
        
        if (memos.length === 0) {
            emptyEl.style.display = 'block';
        } else {
            // 渲染列表
            listEl.innerHTML = memos.map((memo, index) => createMemoItem(memo, index)).join('');
            listEl.style.display = 'block';
            footerEl.style.display = 'block';
        }
    } catch (error) {
        console.error('加载说说失败:', error);
        loadingEl.style.display = 'none';
        errorEl.style.display = 'block';
    }
}

// 打开图片预览
function openMemoImage(src) {
    // 简单的图片预览
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        cursor: zoom-out;
    `;
    
    const img = document.createElement('img');
    img.src = src;
    img.style.cssText = `
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 8px;
    `;
    
    overlay.appendChild(img);
    document.body.appendChild(overlay);
    
    overlay.onclick = () => {
        document.body.removeChild(overlay);
    };
}

// 回复说说
function replyMemo(content) {
    // 查找原生评论框
    const commentTextarea = document.querySelector('.comment-form textarea[name="text"], #respond textarea[name="text"], .comment-respond textarea[name="text"]');
    if (commentTextarea) {
        const quote = content.split('\n').map(line => '> ' + line).join('\n');
        commentTextarea.value = quote + '\n\n';
        commentTextarea.focus();
        commentTextarea.dispatchEvent(new Event('input'));
        
        // 滚动到评论区
        const commentForm = document.querySelector('.comment-respond, #respond, .comment-form');
        if (commentForm) {
            commentForm.scrollIntoView({ behavior: 'smooth' });
        }
    } else {
        // 如果没有找到评论框，滚动到页面底部尝试加载评论
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        // 显示提示
        showToast('请滚动到页面底部查看评论框');
    }
}

// 显示提示消息
function showToast(message) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.8);
        color: #fff;
        padding: 12px 24px;
        border-radius: 24px;
        font-size: 14px;
        z-index: 9999;
        animation: fadeInUp 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 2000);
}

// ========== Emaction 风格表情反应系统 ==========

// 获取存储的反应数据
function getMemoReactions(memoId) {
    const storageKey = `memo_reactions_${window.location.pathname}`;
    const allReactions = JSON.parse(localStorage.getItem(storageKey) || '{}');
    return allReactions[memoId] || {};
}

function saveMemoReaction(memoId, emoji, count) {
    const storageKey = `memo_reactions_${window.location.pathname}`;
    const allReactions = JSON.parse(localStorage.getItem(storageKey) || '{}');
    if (!allReactions[memoId]) {
        allReactions[memoId] = {};
    }
    if (count > 0) {
        allReactions[memoId][emoji] = count;
    } else {
        delete allReactions[memoId][emoji];
    }
    localStorage.setItem(storageKey, JSON.stringify(allReactions));
}

function getUserReactions(memoId) {
    const storageKey = `memo_user_reactions_${window.location.pathname}`;
    const userReactions = JSON.parse(localStorage.getItem(storageKey) || '{}');
    return userReactions[memoId] || [];
}

function saveUserReaction(memoId, emoji, isAdding) {
    const storageKey = `memo_user_reactions_${window.location.pathname}`;
    const userReactions = JSON.parse(localStorage.getItem(storageKey) || '{}');
    if (!userReactions[memoId]) {
        userReactions[memoId] = [];
    }
    const index = userReactions[memoId].indexOf(emoji);
    if (isAdding && index === -1) {
        userReactions[memoId].push(emoji);
    } else if (!isAdding && index > -1) {
        userReactions[memoId].splice(index, 1);
    }
    localStorage.setItem(storageKey, JSON.stringify(userReactions));
}

// 切换表情选择器
function toggleEmojiPicker(memoId) {
    const picker = document.getElementById(`emaction-picker-${memoId}`);
    if (picker) {
        const isVisible = picker.style.display !== 'none';
        // 关闭所有其他选择器
        document.querySelectorAll('.emaction-picker').forEach(p => p.style.display = 'none');
        // 切换当前选择器
        picker.style.display = isVisible ? 'none' : 'block';
    }
}

// 关闭表情选择器
function closeEmojiPicker(memoId) {
    const picker = document.getElementById(`emaction-picker-${memoId}`);
    if (picker) {
        picker.style.display = 'none';
    }
}

// 选择表情
function selectEmoji(memoId, emoji) {
    const reactions = getMemoReactions(memoId);
    const userReactions = getUserReactions(memoId);
    const hasReacted = userReactions.includes(emoji);
    
    let count = reactions[emoji] || 0;
    if (hasReacted) {
        // 取消反应
        count = Math.max(0, count - 1);
        saveUserReaction(memoId, emoji, false);
    } else {
        // 添加反应
        count += 1;
        saveUserReaction(memoId, emoji, true);
    }
    
    saveMemoReaction(memoId, emoji, count);
    renderSelectedEmojis(memoId);
    closeEmojiPicker(memoId);
}

// 渲染已选表情列表
function renderSelectedEmojis(memoId) {
    const container = document.getElementById(`emaction-selected-${memoId}`);
    if (!container) return;
    
    const reactions = getMemoReactions(memoId);
    const userReactions = getUserReactions(memoId);
    
    let html = '';
    Object.keys(reactions).forEach(emoji => {
        const count = reactions[emoji];
        if (count > 0) {
            const isActive = userReactions.includes(emoji);
            html += `
                <button class="emaction-reaction-btn ${isActive ? 'emaction-reacted' : ''}" 
                        onclick="selectEmoji('${memoId}', '${emoji}')" 
                        title="${isActive ? '取消反应' : '添加反应'}">
                    <span class="emaction-emoji">${emoji}</span>
                    <span class="emaction-count">${count}</span>
                </button>
            `;
        }
    });
    
    container.innerHTML = html;
}

// 初始化表情反应
function initMemoReactions() {
    document.querySelectorAll('.memo-item').forEach(item => {
        const memoId = item.dataset.id;
        renderSelectedEmojis(memoId);
    });
}

// 点击页面其他地方关闭所有选择器
document.addEventListener('click', function(e) {
    if (!e.target.closest('.emaction-container')) {
        document.querySelectorAll('.emaction-picker').forEach(p => p.style.display = 'none');
    }
});

// 初始化
document.addEventListener('DOMContentLoaded', function() {
    loadMemos().then(() => {
        // 数据加载完成后初始化表情反应
        setTimeout(initMemoReactions, 100);
    });
});
</script>

<?php $this->need('footer.php'); ?>

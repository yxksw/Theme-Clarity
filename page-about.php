<?php
/**
 * 关于页面
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$aboutTitle = trim((string) clarity_opt('about_title', '关于'));
clarity_set('showAside', true);
clarity_set('pageTitle', $aboutTitle);
clarity_set('isLinksPage', false);

// 获取关于页面数据
$aboutData = clarity_get_about_data();
$authorData = $aboutData['author'] ?? [];
$myInfo = $aboutData['myinfo'] ?? [];
$maxim = $aboutData['maxim'] ?? [];
$single = $aboutData['single'] ?? [];
$skills = $aboutData['skills'] ?? [];

// 获取作者头像
$authorAvatar = clarity_opt('author_avatar', '');
if (empty($authorAvatar)) {
    $authorAvatar = $this->options->themeUrl . '/assets/images/avatar.png';
}
?>
<?php $this->need('header.php'); ?>

<div id="about-page" style="margin-top: 1rem;margin-left: 1rem;margin-right: 1rem;">
    <!-- Author 组件 -->
    <div class="author-main">
        <?php if (!empty($authorData['左侧'])): ?>
        <div class="author-tag-left">
            <?php foreach ($authorData['左侧'] as $left): ?>
                <?php if (!empty($left['标签1'])): ?><span class="author-tag"><?php echo htmlspecialchars($left['标签1'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if (!empty($left['标签2'])): ?><span class="author-tag"><?php echo htmlspecialchars($left['标签2'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if (!empty($left['标签3'])): ?><span class="author-tag"><?php echo htmlspecialchars($left['标签3'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if (!empty($left['标签4'])): ?><span class="author-tag"><?php echo htmlspecialchars($left['标签4'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div class="mainports">
            <div class="author-box">
                <div class="author-img">
                    <img class="author-avatar" src="<?php echo htmlspecialchars($authorAvatar, ENT_QUOTES, 'UTF-8'); ?>" alt="作者头像" loading="eager" width="180" height="180">
                </div>
            </div>
        </div>
        
        <?php if (!empty($authorData['右侧'])): ?>
        <div class="author-tag-right">
            <?php foreach ($authorData['右侧'] as $right): ?>
                <?php if (!empty($right['标签1'])): ?><span class="author-tag"><?php echo htmlspecialchars($right['标签1'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if (!empty($right['标签2'])): ?><span class="author-tag"><?php echo htmlspecialchars($right['标签2'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if (!empty($right['标签3'])): ?><span class="author-tag"><?php echo htmlspecialchars($right['标签3'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                <?php if (!empty($right['标签4'])): ?><span class="author-tag"><?php echo htmlspecialchars($right['标签4'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="author-page-content">
        <!-- MyInfoAndSayHello 组件 -->
        <div class="author-content">
            <?php if (!empty($myInfo)): ?>
                <?php foreach ($myInfo as $info): ?>
                <div class="author-content-item myInfoAndSayHello" style="text-align: center; width: 100%">
                    <div class="title1"><?php echo htmlspecialchars($info['标题一'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                    <div class="title2">
                        <?php echo htmlspecialchars($info['标题二'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        <span class="inline-word"><?php echo htmlspecialchars($info['博主名称'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="title1">
                        <?php echo htmlspecialchars($info['内容一'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        <span class="inline-word"><?php echo htmlspecialchars($info['内容二'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="author-content">
            <!-- Aboutsitetips 组件 -->
            <?php if (!empty($myInfo)): ?>
                <?php foreach ($myInfo as $info): ?>
                    <?php if (!empty($info['卡片'])): ?>
                        <?php foreach ($info['卡片'] as $card): ?>
                        <div class="author-content-item aboutsiteTips">
                            <div class="author-content-item-tips"><?php echo htmlspecialchars($card['标题'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                            <h2>
                                <?php echo htmlspecialchars($card['内容1'] ?? '', ENT_QUOTES, 'UTF-8'); ?><br>
                                <?php echo htmlspecialchars($card['内容2'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                                <span class="inline-word"><?php echo htmlspecialchars($card['显示'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if (!empty($card['轮播'])): ?>
                                <div class="mask">
                                    <?php $first = true; ?>
                                    <?php foreach ($card['轮播'] as $mask): ?>
                                        <span class="first-tips" <?php echo $first ? 'data-show' : ''; ?>><?php echo htmlspecialchars($mask['第一'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span><?php echo htmlspecialchars($mask['第二'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span data-up><?php echo htmlspecialchars($mask['第三'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span><?php echo htmlspecialchars($mask['第四'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <?php $first = false; ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </h2>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <!-- Maxim 组件 -->
            <?php if (!empty($maxim)): ?>
                <?php foreach ($maxim as $m): ?>
                <div class="author-content-item maxim">
                    <div class="author-content-item-tips"><?php echo htmlspecialchars($m['tip'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                    <span class="maxim-title">
                        <span style="opacity:.6;margin-bottom:8px"><?php echo htmlspecialchars($m['title1'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                        <span><?php echo htmlspecialchars($m['title2'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                    </span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="author-content">
            <!-- Skillinfo 组件 -->
            <?php if (!empty($skills)): ?>
            <div class="author-content-item creativityMain">
                <div class="author-content-item-tips">技能</div>
                <div class="author-content-item-list">
                    <?php foreach ($skills as $skill): ?>
                    <div class="cardInfo">
                        <div class="creativityIcon" style="background: <?php echo htmlspecialchars($skill['color'] ?? '#333', ENT_QUOTES, 'UTF-8'); ?>">
                            <img src="<?php echo htmlspecialchars($skill['icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($skill['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                        <div class="creativityTitle"><?php echo htmlspecialchars($skill['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Single 组件 - 显示页面内容 -->
        <div class="author-content">
            <div class="author-content-item single" style="width: 100%">
                <?php if (!empty($single)): ?>
                    <?php foreach ($single as $s): ?>
                        <div class="author-content-item-tips"><?php echo htmlspecialchars($s['tip'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="author-content-item-title"><?php echo htmlspecialchars($s['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                        <p class="author-content-item-content"><?php echo htmlspecialchars($s['content'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="lishi"><?php echo htmlspecialchars($s['lishi'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <!-- 页面正文内容 -->
                <div class="singlePost">
                    <?php if ($this->content): ?>
                        <div class="article-content">
                            <?php echo $this->content; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center">可于后台配置补充说明。</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 关于页面 JS - 轮播动画
(function() {
    var pursuitInterval = null;
    pursuitInterval = setInterval(function () {
        const show = document.querySelector('span[data-show]');
        if (!show) return;
        
        const next = show.nextElementSibling || document.querySelector('.first-tips');
        const up = document.querySelector('span[data-up]');
        
        if (up) {
            up.removeAttribute('data-up');
        }
        
        show.removeAttribute('data-show');
        show.setAttribute('data-up', '');
        
        if (next) {
            next.setAttribute('data-show', '');
        }
    }, 2000);
})();
</script>

<?php $this->need('footer.php'); ?>

<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

if (!defined('CLARITY_VERSION')) {
    $clarityVersion = '0.0.0';
    $indexFile = __DIR__ . '/index.php';
    $indexContent = @file_get_contents($indexFile, false, null, 0, 2048);
    if ($indexContent !== false && preg_match('/@version\s+([^\s*]+)/', $indexContent, $match)) {
        $clarityVersion = trim($match[1]);
    }
    define('CLARITY_VERSION', $clarityVersion);
}

if (!defined('CLARITY_BANGUMI_CACHE_TTL')) {
    define('CLARITY_BANGUMI_CACHE_TTL', 86400);
}

if (!defined('CLARITY_GITHUB_UPDATE_TTL')) {
    define('CLARITY_GITHUB_UPDATE_TTL', 1800);
}

if (!defined('CLARITY_GITHUB_REPO')) {
    define('CLARITY_GITHUB_REPO', 'jkjoy/Theme-Clarity');
}

function themeConfig($form)
{
    $logo = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_logo',
        null,
        '',
        _t('Logo URL'),
        _t('侧边栏/移动端 Logo，留空使用主题默认。')
    );
    $form->addInput($logo);

    $showTitle = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_show_title',
        ['1' => _t('显示标题')],
        ['1'],
        _t('显示站点标题')
    );
    $form->addInput($showTitle);

    $subtitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_subtitle',
        null,
        _t('Just another Typecho site'),
        _t('副标题'),
        _t('一句话介绍')
    );
    $form->addInput($subtitle);

    $emojiTail = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_emoji_tail',
        null,
        '📄,🦌,🙌,🐟,🏖️',
        _t('Emoji 尾巴'),
        _t('用英文逗号分隔，例如：📄,🦌,🙌')
    );
    $form->addInput($emojiTail);

    $primaryColor = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_primary_color',
        null,
        '#3b82f6',
        _t('主配色'),
        _t('按钮、链接、高亮等主要元素颜色')
    );
    $form->addInput($primaryColor);

    $accentColor = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_accent_color',
        null,
        '#60a5fa',
        _t('辅配色'),
        _t('次要高亮、悬停等颜色')
    );
    $form->addInput($accentColor);

    $logoFontCss = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_logo_font_css',
        null,
        '',
        _t('Logo 字体样式'),
        _t('填写 @font-face 代码，font-family 请使用 "Logo Font"，留空则使用系统字体')
    );
    $form->addInput($logoFontCss);

    $linksTitleFontCss = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_links_title_font_css',
        null,
        '',
        _t('友链标题字体样式'),
        _t('填写 @font-face 代码，font-family 请使用 "Links Title Font"，留空则使用系统字体')
    );
    $form->addInput($linksTitleFontCss);

    $pageTransition = new \Typecho\Widget\Helper\Form\Element\Select(
        'clarity_page_transition',
        ['fade-scale' => _t('淡入淡出 + 缩放'), 'sweep' => _t('黑幕扫光'), 'none' => _t('无动画')],
        'fade-scale',
        _t('页面切换动画')
    );
    $form->addInput($pageTransition);

    $navActive = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_nav_active_indicator',
        ['1' => _t('启用')],
        ['1'],
        _t('菜单激活指示器')
    );
    $form->addInput($navActive);

    $showPostAuthor = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_show_post_author',
        ['1' => _t('显示作者')],
        ['1'],
        _t('首页文章卡片显示作者')
    );
    $form->addInput($showPostAuthor);

    $switchCategoryLayout = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_switch_category_layout',
        ['1' => _t('启用新版布局')],
        [],
        _t('分类页布局切换')
    );
    $form->addInput($switchCategoryLayout);

    $switchTagLayout = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_switch_tag_layout',
        ['1' => _t('启用新版布局')],
        [],
        _t('标签页布局切换')
    );
    $form->addInput($switchTagLayout);

    $themeMode = new \Typecho\Widget\Helper\Form\Element\Radio(
        'clarity_theme_mode',
        ['light' => _t('浅色'), 'dark' => _t('深色'), 'system' => _t('跟随系统')],
        'light',
        _t('默认主题模式')
    );
    $form->addInput($themeMode);

    $menuJson = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_menu_json',
        null,
        '',
        _t('主导航菜单（JSON）'),
        _t("示例：[{\"text\":\"首页\",\"url\":\"/\",\"icon\":\"icon-[ph--house-bold]\"},{\"text\":\"归档\",\"url\":\"/archives\"}]（icon 支持 icon-[ph--...] 或图片 URL）")
    );
    $form->addInput($menuJson);

    $menuIconInvert = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_menu_icon_invert',
        ['1' => _t('启用')],
        [],
        _t('菜单图标颜色反转')
    );
    $form->addInput($menuIconInvert);

    $userAuth = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_user_auth',
        ['1' => _t('显示用户登录入口')],
        [],
        _t('用户登录入口')
    );
    $form->addInput($userAuth);

    $preconnectUrls = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_preconnect_urls',
        null,
        '',
        _t('预连接域名'),
        _t("每行一个 URL，例如：https://hm.baidu.com")
    );
    $form->addInput($preconnectUrls);

    $socialJson = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_social_json',
        null,
        '',
        _t('侧边栏社交图标（JSON）'),
        _t("示例：[{\"text\":\"GitHub\",\"url\":\"https://github.com\",\"icon\":\"icon-[ph--github-logo]\"}]（icon 支持 icon-[ph--...] 或图片 URL）")
    );
    $form->addInput($socialJson);

    $footerExploreJson = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_footer_explore_json',
        null,
        '',
        _t('页脚探索链接（JSON）'),
        _t("示例：[{\"text\":\"RSS 订阅\",\"url\":\"/feed\"}]，留空使用默认")
    );
    $form->addInput($footerExploreJson);

    $footerLinksJson = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_footer_links_json',
        null,
        '',
        _t('页脚链接（JSON）'),
        _t("示例：[{\"text\":\"GitHub\",\"url\":\"https://github.com\"}]")
    );
    $form->addInput($footerLinksJson);

    $footerShowRss = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_footer_show_rss',
        ['1' => _t('显示 RSS')],
        ['1'],
        _t('页脚显示 RSS')
    );
    $form->addInput($footerShowRss);

    $footerShowTravellings = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_footer_show_travellings',
        ['1' => _t('显示开往')],
        ['1'],
        _t('页脚显示“开往”')
    );
    $form->addInput($footerShowTravellings);

    $footerBeian = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_footer_beian',
        null,
        '',
        _t('ICP 备案号')
    );
    $form->addInput($footerBeian);

    $footerGongan = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_footer_gongan',
        null,
        '',
        _t('公安备案号')
    );
    $form->addInput($footerGongan);

    $footerUptime = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_footer_uptime_kuma',
        ['1' => _t('启用 Uptime Kuma')],
        [],
        _t('页脚显示 Uptime Kuma')
    );
    $form->addInput($footerUptime);

    $footerUptimeBadge = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_footer_uptime_kuma_badge',
        null,
        '',
        _t('Uptime Kuma Badge 地址'),
        _t('示例：https://status.example.com/api/badge/1/status?style=flat-square')
    );
    $form->addInput($footerUptimeBadge);

    $footerUptimeUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_footer_uptime_kuma_url',
        null,
        '',
        _t('Uptime Kuma 状态页链接'),
        _t('可选：点击徽章跳转到状态页')
    );
    $form->addInput($footerUptimeUrl);

    $asideEnable = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_aside_enable',
        ['1' => _t('显示侧边栏')],
        ['1'],
        _t('侧边栏开关')
    );
    $form->addInput($asideEnable);

    $asideWidgets = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_aside_widgets',
        null,
        "stats\ntech-info\ncommunity",
        _t('右侧边栏组件顺序'),
        _t('每行一个：stats / tech-info / weather / moments / community / sponsor / custom / meting / welcome / poetry / blog-log / recent-comments / announcement')
    );
    $form->addInput($asideWidgets);

    $blogLogTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_blog_log_title',
        null,
        '更新日志',
        _t('博客日志标题'),
        _t('显示在侧边栏的标题')
    );
    $form->addInput($blogLogTitle);

    $blogLogData = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_blog_log_data',
        null,
        '[]',
        _t('博客日志数据'),
        _t('JSON格式的日志数组，每行一个对象，如：[{"date": "2025-07-26", "content": "重构到 Nuxt 4..."}]')
    );
    $form->addInput($blogLogData);

    $siteStart = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_site_start_time',
        null,
        '2024-01-01',
        _t('建站时间'),
        _t('用于计算运营时长')
    );
    $form->addInput($siteStart);

    $license = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_license',
        null,
        '署名-非商业性使用-相同方式共享 4.0 国际',
        _t('许可协议名称')
    );
    $form->addInput($license);

    $licenseUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_license_url',
        null,
        'https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh-hans',
        _t('许可协议链接')
    );
    $form->addInput($licenseUrl);

    $communityImage = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_community_image',
        null,
        '',
        _t('社区群组背景图')
    );
    $form->addInput($communityImage);

    $communityTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_community_title',
        null,
        '博客/技术社区',
        _t('社区群组小标题')
    );
    $form->addInput($communityTitle);

    $communityName = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_community_name',
        null,
        '技术交流QQ群',
        _t('社区群组名称')
    );
    $form->addInput($communityName);

    $communityDesc = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_community_desc',
        null,
        '377202312',
        _t('社区群组描述')
    );
    $form->addInput($communityDesc);

    $sponsorTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_sponsor_title',
        null,
        '云计算支持',
        _t('赞助标题')
    );
    $form->addInput($sponsorTitle);

    $sponsorLogo = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_sponsor_logo',
        null,
        '',
        _t('赞助 Logo URL')
    );
    $form->addInput($sponsorLogo);

    $sponsorName = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_sponsor_name',
        null,
        '赞助商',
        _t('赞助名称')
    );
    $form->addInput($sponsorName);

    $sponsorUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_sponsor_url',
        null,
        '',
        _t('赞助链接')
    );
    $form->addInput($sponsorUrl);

    $sponsorDesc = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_sponsor_desc',
        null,
        '提供云计算服务',
        _t('赞助描述')
    );
    $form->addInput($sponsorDesc);

    $customWidgetTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_aside_custom_title',
        null,
        '自定义',
        _t('自定义组件标题')
    );
    $form->addInput($customWidgetTitle);

    $customWidgetHtml = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_aside_custom_html',
        null,
        '',
        _t('自定义组件内容'),
        _t('支持 HTML')
    );
    $form->addInput($customWidgetHtml);

    $weatherApiUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_weather_api_url',
        null,
        'https://60s.050815.xyz/v2/weather?query=%E5%B8%B8%E7%86%9F',
        _t('天气 API 地址'),
        _t('默认使用 60s API，可自定义其他天气 API 地址')
    );
    $form->addInput($weatherApiUrl);

    $announcementEnable = new \Typecho\Widget\Helper\Form\Element\Radio(
        'clarity_announcement_enable',
        ['1' => _t('开启'), '0' => _t('关闭')],
        '0',
        _t('公告组件'),
        _t('是否在侧边栏显示公告组件')
    );
    $form->addInput($announcementEnable);

    $announcementLevel = new \Typecho\Widget\Helper\Form\Element\Select(
        'clarity_announcement_level',
        [
            'info' => _t('信息'),
            'note' => _t('笔记'),
            'tip' => _t('提示'),
            'happy' => _t('快乐'),
            'important' => _t('重要'),
            'warning' => _t('警告'),
            'caution' => _t('注意'),
        ],
        'info',
        _t('公告类型'),
        _t('选择公告的样式类型')
    );
    $form->addInput($announcementLevel);

    $announcementContent = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_announcement_content',
        null,
        '',
        _t('公告内容'),
        _t('支持 Markdown 格式，可以使用 **粗体**、*斜体*、[链接](url) 等语法')
    );
    $form->addInput($announcementContent);

    $momentsWidgetTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_moments_widget_title',
        null,
        '微语',
        _t('微语组件标题')
    );
    $form->addInput($momentsWidgetTitle);

    $momentsWidgetCount = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_moments_widget_count',
        null,
        '3',
        _t('微语显示条数')
    );
    $form->addInput($momentsWidgetCount);

    $momentsWidgetNoText = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_moments_widget_no_text',
        null,
        '',
        _t('微语无文字文案')
    );
    $form->addInput($momentsWidgetNoText);

    $momentsData = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_moments_data',
        null,
        '',
        _t('微语数据（JSON）'),
        _t('示例：[{"content":"今天很棒","time":"2025-01-01 12:00","tags":["生活"]}]（Enhancement 插件未启用时使用）')
    );
    $form->addInput($momentsData);

    $momentsTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_moments_title',
        null,
        '瞬间',
        _t('瞬间页面标题')
    );
    $form->addInput($momentsTitle);

    $momentsPageSize = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_moments_page_size',
        null,
        '20',
        _t('瞬间分页条数'),
        _t('瞬间页面每页显示数量')
    );
    $momentsPageSize->input->setAttribute('class', 'w-10');
    $form->addInput($momentsPageSize->addRule('isInteger', _t('请填写整数数字')));

    // 打赏设置
    $rewardTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_reward_title',
        null,
        '感谢你赐予我前进的力量',
        _t('打赏弹窗标题')
    );
    $form->addInput($rewardTitle);

    $rewardWechat = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_reward_wechat',
        null,
        '',
        _t('微信打赏二维码'),
        _t('填写微信收款二维码图片URL')
    );
    $form->addInput($rewardWechat);

    $rewardAlipay = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_reward_alipay',
        null,
        '',
        _t('支付宝打赏二维码'),
        _t('填写支付宝收款二维码图片URL')
    );
    $form->addInput($rewardAlipay);

    $rewardListUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_reward_list_url',
        null,
        '/reward.html',
        _t('赞赏者名单页面链接'),
        _t('点击赞赏者名单跳转的页面地址')
    );
    $form->addInput($rewardListUrl);

    $linksTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_links_title',
        null,
        '友链',
        _t('友链页面标题')
    );
    $form->addInput($linksTitle);

    $linksRandom = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_links_random',
        ['1' => _t('启用随机访问')],
        ['1'],
        _t('随机友链')
    );
    $form->addInput($linksRandom);

    $linksMyInfo = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_links_my_info',
        null,
        '',
        _t('我的博客信息（JSON）'),
        _t('示例：{"title":"我的博客","url":"https://example.com","logo":"","description":"一句话","rss":"/feed"}')
    );
    $form->addInput($linksMyInfo);

    $linksApply = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_links_apply',
        null,
        '',
        _t('申请友链说明（HTML）')
    );
    $form->addInput($linksApply);

    $linksData = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_links_data',
        null,
        '',
        _t('友链数据（JSON，Enhancement 插件未启用时使用）'),
        _t('示例：[{"title":"友链","description":"","links":[{"name":"站点","url":"https://","logo":"","desc":""}]}]')
    );
    $form->addInput($linksData);

    $photosTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_photos_title',
        null,
        '图库',
        _t('图库页面标题')
    );
    $form->addInput($photosTitle);

    $photosDesc = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_photos_desc',
        null,
        '记录生活中的美好瞬间',
        _t('图库页面描述')
    );
    $form->addInput($photosDesc);

    $photosData = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_photos_data',
        null,
        '',
        _t('图库数据（JSON，页面附件为空时使用）'),
        _t('示例：[{"name":"travel","displayName":"旅行","photos":[{"url":"","cover":"","displayName":"","description":""}]}]')
    );
    $form->addInput($photosData);

    $bangumisTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_bangumis_title',
        null,
        '追番',
        _t('追番页面标题')
    );
    $form->addInput($bangumisTitle);

    $bangumisUid = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_bangumis_uid',
        null,
        '',
        _t('B 站 UID'),
        _t('填写后自动从 B 站拉取追番数据并缓存到 /usr/cache')
    );
    $form->addInput($bangumisUid);

    $fcircleTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_fcircle_title',
        null,
        '友链朋友圈',
        _t('友链朋友圈页面标题')
    );
    $form->addInput($fcircleTitle);

    $fcircleApiUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_fcircle_api_url',
        null,
        'https://fc.050815.xyz/',
        _t('友链朋友圈 API 地址'),
        _t('Friend Circle Lite API 地址，默认为 https://fc.050815.xyz/')
    );
    $form->addInput($fcircleApiUrl);

    $fcirclePageSize = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_fcircle_page_size',
        null,
        '20',
        _t('友链朋友圈每页数量'),
        _t('每页显示的文章数量')
    );
    $fcirclePageSize->input->setAttribute('class', 'w-10');
    $form->addInput($fcirclePageSize->addRule('isInteger', _t('请填写整数数字')));

    $fcircleErrorImg = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_fcircle_error_img',
        null,
        'https://img.314926.xyz',
        _t('友链朋友圈默认头像'),
        _t('头像加载失败时显示的默认图片')
    );
    $form->addInput($fcircleErrorImg);

    $fcircleCoverImg = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_fcircle_cover_img',
        null,
        '',
        _t('友链朋友圈封面图片'),
        _t('页面顶部封面图片 URL，留空使用默认渐变背景')
    );
    $form->addInput($fcircleCoverImg);

    $fcircleDesc = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_fcircle_desc',
        null,
        '探索友链博客的最新动态',
        _t('友链朋友圈页面描述'),
        _t('显示在标题下方的描述文字')
    );
    $form->addInput($fcircleDesc);

    // 关于页面配置
    $aboutTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_title',
        null,
        '关于',
        _t('关于页面标题')
    );
    $form->addInput($aboutTitle);

    $authorAvatar = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_author_avatar',
        null,
        '',
        _t('作者头像'),
        _t('关于页面作者头像 URL，留空使用主题默认头像')
    );
    $form->addInput($authorAvatar);

    $aboutAuthorLeftTags = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_about_author_left_tags',
        null,
        "💻 Like数码科技\n🥣 干饭魂 干饭人\n🕊 咕咕咕咕咕咕~\n🧱 CV工程师",
        _t('作者左侧标签'),
        _t('每行一个标签，最多4个')
    );
    $form->addInput($aboutAuthorLeftTags);

    $aboutAuthorRightTags = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_about_author_right_tags',
        null,
        "吃饭不如碎觉 💤\n乐观 积极 向上 🤝\n专攻各种困难 🔨\n人不狠话超多 💢",
        _t('作者右侧标签'),
        _t('每行一个标签，最多4个')
    );
    $form->addInput($aboutAuthorRightTags);

    $aboutMyInfoTitle1 = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_myinfo_title1',
        null,
        '你好，很高兴认识你👋',
        _t('个人介绍标题一')
    );
    $form->addInput($aboutMyInfoTitle1);

    $aboutMyInfoTitle2 = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_myinfo_title2',
        null,
        '我叫',
        _t('个人介绍标题二')
    );
    $form->addInput($aboutMyInfoTitle2);

    $aboutMyInfoName = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_myinfo_name',
        null,
        '博主',
        _t('博主名称')
    );
    $form->addInput($aboutMyInfoName);

    $aboutMyInfoContent1 = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_myinfo_content1',
        null,
        '是一名 前端工程师、学生、',
        _t('个人介绍内容一')
    );
    $form->addInput($aboutMyInfoContent1);

    $aboutMyInfoContent2 = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_myinfo_content2',
        null,
        '博主',
        _t('个人介绍内容二')
    );
    $form->addInput($aboutMyInfoContent2);

    $aboutCardTips = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_card_tips',
        null,
        '追求',
        _t('卡片提示文字')
    );
    $form->addInput($aboutCardTips);

    $aboutCardContent1 = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_card_content1',
        null,
        '源于',
        _t('卡片内容一')
    );
    $form->addInput($aboutCardContent1);

    $aboutCardContent2 = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_card_content2',
        null,
        '热爱而去',
        _t('卡片内容二')
    );
    $form->addInput($aboutCardContent2);

    $aboutCardInlineWord = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_card_inlineword',
        null,
        '感受',
        _t('卡片高亮文字')
    );
    $form->addInput($aboutCardInlineWord);

    $aboutCardMaskWords = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_about_card_mask_words',
        null,
        "学习\n生活\n程序\n体验",
        _t('轮播文字'),
        _t('每行一个，共4个，用于轮播动画')
    );
    $form->addInput($aboutCardMaskWords);

    $aboutMaximTip = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_maxim_tip',
        null,
        '座右铭',
        _t('座右铭提示')
    );
    $form->addInput($aboutMaximTip);

    $aboutMaximTitle1 = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_maxim_title1',
        null,
        '生活明朗，',
        _t('座右铭标题一')
    );
    $form->addInput($aboutMaximTitle1);

    $aboutMaximTitle2 = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_maxim_title2',
        null,
        '万物可爱。',
        _t('座右铭标题二')
    );
    $form->addInput($aboutMaximTitle2);

    $aboutSingleTip = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_single_tip',
        null,
        '心路历程',
        _t('历程提示')
    );
    $form->addInput($aboutSingleTip);

    $aboutSingleTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_single_title',
        null,
        '为何而建站',
        _t('历程标题')
    );
    $form->addInput($aboutSingleTitle);

    $aboutSingleContent = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_about_single_content',
        null,
        '本站是综合类型博客，集成文章、说说、友链、留言、装备等栏目。',
        _t('历程内容')
    );
    $form->addInput($aboutSingleContent);

    $aboutSingleLishi = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_about_single_lishi',
        null,
        '『博客』历史进程',
        _t('历程历史')
    );
    $form->addInput($aboutSingleLishi);

    $aboutSkills = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_about_skills',
        null,
        '{"name":"Vue","color":"#b8f0ae","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vuejs/vuejs-original.svg"}
{"name":"Java","color":"#fff","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg"}
{"name":"Webpack","color":"#2e3a41","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/webpack/webpack-original.svg"}
{"name":"Photoshop","color":"#4082c3","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/photoshop/photoshop-plain.svg"}
{"name":"Python","color":"#fff","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg"}
{"name":"Node","color":"#333","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg"}
{"name":"Git","color":"#df5b40","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/git/git-original.svg"}
{"name":"CSS","color":"#2c51db","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg"}
{"name":"JS","color":"#f7cb4f","icon":"https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg"}',
        _t('技能列表'),
        _t('每行一个技能，格式：{"name":"技能名","color":"背景色","icon":"图标URL"}')
    );
    $form->addInput($aboutSkills);

    // Memos/说说页面配置
    $memosTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_memos_title',
        null,
        '说说',
        _t('说说页面标题')
    );
    $form->addInput($memosTitle);

    $memosDesc = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_memos_desc',
        null,
        '记录生活点滴，一些想法',
        _t('说说页面描述')
    );
    $form->addInput($memosDesc);

    $memosApiUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_memos_api_url',
        null,
        'https://tg-api.050815.xyz/',
        _t('说说 API 地址'),
        _t('Memos 数据接口地址')
    );
    $form->addInput($memosApiUrl);

    $memosAuthorName = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_memos_author_name',
        null,
        '博主',
        _t('说说作者名称')
    );
    $form->addInput($memosAuthorName);

    $memosAuthorAvatar = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_memos_author_avatar',
        null,
        '',
        _t('说说作者头像'),
        _t('留空使用主题默认头像')
    );
    $form->addInput($memosAuthorAvatar);

    $bangumiCacheMinutes = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_bangumi_cache_minutes',
        null,
        '1440',
        _t('追番缓存时间（分钟）'),
        _t('默认 1440 分钟（24 小时），最小 1 分钟')
    );
    $form->addInput($bangumiCacheMinutes);

    $featuredPosts = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_featured_posts',
        null,
        '',
        _t('精选文章 CID 列表'),
        _t('多个用英文逗号或空格分隔')
    );
    $form->addInput($featuredPosts);

    $featuredOnlyHome = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_featured_posts_page',
        ['1' => _t('仅首页显示')],
        ['1'],
        _t('精选文章仅首页显示')
    );
    $form->addInput($featuredOnlyHome);

    $cursorOrder = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_cursor_order',
        ['1' => _t('倒序（旧 -> 新）')],
        ['1'],
        _t('上下篇排序')
    );
    $form->addInput($cursorOrder);

    $defaultCover = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_default_cover',
        ['1' => _t('启用默认封面')],
        ['1'],
        _t('无封面时显示默认封面')
    );
    $form->addInput($defaultCover);

    $centerTitle = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_center_title',
        ['1' => _t('标题居中')],
        [],
        _t('文章与页面标题居中')
    );
    $form->addInput($centerTitle);

    $showExcerpt = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_show_excerpt',
        ['1' => _t('显示摘要框')],
        ['1'],
        _t('文章页显示摘要框')
    );
    $form->addInput($showExcerpt);

    $excerptAnimation = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_excerpt_animation',
        ['1' => _t('启用打字机效果')],
        ['1'],
        _t('摘要动画')
    );
    $form->addInput($excerptAnimation);

    $excerptSpeed = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_excerpt_speed',
        null,
        '50',
        _t('打字速度（毫秒/字）')
    );
    $form->addInput($excerptSpeed);

    $excerptCaret = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_excerpt_caret',
        null,
        '_',
        _t('打字光标字符')
    );
    $form->addInput($excerptCaret);

    $outdatedEnabled = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_outdated_enabled',
        ['1' => _t('启用过时提示')],
        ['1'],
        _t('过时文章提示')
    );
    $form->addInput($outdatedEnabled);

    $outdatedDays = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_outdated_days',
        null,
        '180',
        _t('过时天数')
    );
    $form->addInput($outdatedDays);

    $outdatedMessage = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_outdated_message',
        null,
        '本文发布于 {days} 天前，内容可能已过时，请注意甄别。',
        _t('过时提示文案')
    );
    $form->addInput($outdatedMessage);

    $titleType = new \Typecho\Widget\Helper\Form\Element\Radio(
        'clarity_title_type',
        ['vertical' => _t('竖线（页面 | 网站）'), 'across' => _t('横线（页面 - 网站）')],
        'vertical',
        _t('网页标题分隔符')
    );
    $form->addInput($titleType);

    $imgAlt = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_img_alt',
        ['1' => _t('启用图片 alt 文本')],
        ['1'],
        _t('图片 alt 文本显示')
    );
    $form->addInput($imgAlt);

    $enablePostToc = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_enable_post_toc',
        ['1' => _t('启用文章目录')],
        ['1'],
        _t('文章目录')
    );
    $form->addInput($enablePostToc);

    $enablePageToc = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_enable_page_toc',
        ['1' => _t('启用页面目录')],
        [],
        _t('独立页面目录')
    );
    $form->addInput($enablePageToc);

    $ownerBirthday = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_owner_birthday',
        null,
        '2001-01-01',
        _t('站长生日')
    );
    $form->addInput($ownerBirthday);

    $archivesYears = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_archives_years',
        ['1' => _t('年龄来源为建站时间')],
        ['1'],
        _t('归档年龄来源')
    );
    $form->addInput($archivesYears);

    $enableEdit = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_enable_edit',
        ['1' => _t('启用编辑按钮')],
        [],
        _t('文章/页面编辑按钮')
    );
    $form->addInput($enableEdit);

    $enableFancybox = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_enable_fancybox',
        ['1' => _t('关闭 FancyBox')],
        [],
        _t('关闭 FancyBox 灯箱')
    );
    $form->addInput($enableFancybox);

    $enablePageJump = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_enable_page_jump',
        ['1' => _t('启用分页跳转')],
        [],
        _t('分页跳转')
    );
    $form->addInput($enablePageJump);

    $commentFormPosition = new \Typecho\Widget\Helper\Form\Element\Select(
        'clarity_comment_form_position',
        ['before' => _t('评论列表之前'), 'after' => _t('评论列表之后')],
        'before',
        _t('评论表单位置'),
        _t('控制评论表单显示在评论列表之前或之后')
    );
    $form->addInput($commentFormPosition);

    $headhtml = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_headhtml',
        null,
        '',
        _t('head中插入代码，支持HTML语法')
    );
    $form->addInput($headhtml);

    $footerhtml = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'clarity_footerhtml',
        null,
        '',
        _t('在页脚插入内容，支持HTML语法')
    );
    $form->addInput($footerhtml);

    $backupAction = new \Typecho\Widget\Helper\Form\Element\Hidden('clarity_backup_action', null, '');
    $backupAction->input->setAttribute('id', 'clarity-backup-action');
    $form->addInput($backupAction);

    $backupTarget = new \Typecho\Widget\Helper\Form\Element\Hidden('clarity_backup_target', null, '');
    $backupTarget->input->setAttribute('id', 'clarity-backup-target');
    $form->addInput($backupTarget);

    $updateAction = new \Typecho\Widget\Helper\Form\Element\Hidden('clarity_update_action', null, '');
    $updateAction->input->setAttribute('id', 'clarity-update-action');
    $form->addInput($updateAction);

    $repo = defined('CLARITY_GITHUB_REPO') ? CLARITY_GITHUB_REPO : 'jkjoy/Theme-Clarity';
    $updateInfo = clarity_github_update_info($repo);
    $backups = clarity_theme_backups_read();
    $backupListHtml = '';
    if (!empty($backups)) {
        $sorted = $backups;
        usort($sorted, function ($a, $b) {
            $aTime = (int) ($a['ts'] ?? 0);
            $bTime = (int) ($b['ts'] ?? 0);
            return $bTime <=> $aTime;
        });
        foreach ($sorted as $item) {
            $id = (string) ($item['id'] ?? '');
            if ($id === '') {
                continue;
            }
            $time = (string) ($item['time'] ?? $id);
            $safeId = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
            $safeTime = htmlspecialchars($time, ENT_QUOTES, 'UTF-8');
            $backupListHtml .= '<div class="clarity-backup-item" style="display:flex;align-items:center;justify-content:space-between;gap:10px;padding:6px 10px;border:1px solid #e6e6e6;border-radius:4px;">';
            $backupListHtml .= '<span>' . $safeTime . '</span>';
            $backupListHtml .= '<div style="display:flex;gap:6px;flex-wrap:wrap;">';
            $backupListHtml .= '<button type="button" class="btn" data-backup-action="restore" data-backup-id="' . $safeId . '">' . _t('恢复') . '</button>';
            $backupListHtml .= '<button type="button" class="btn" data-backup-action="delete" data-backup-id="' . $safeId . '">' . _t('删除') . '</button>';
            $backupListHtml .= '</div></div>';
        }
    }
    if ($backupListHtml === '') {
        $backupListHtml = '<div class="description">' . _t('暂无备份') . '</div>';
    }

    $diag = clarity_theme_diag_pull();
    if (is_array($diag) && !empty($diag['message'])) {
        $diagType = (string) ($diag['type'] ?? 'error');
        $diagColor = '#d14343';
        if ($diagType === 'warn') {
            $diagColor = '#b78103';
        } elseif ($diagType === 'success') {
            $diagColor = '#1a7f37';
        }
        $diagMsg = nl2br(htmlspecialchars((string) $diag['message'], ENT_QUOTES, 'UTF-8'));
        echo '<ul class="typecho-option"><li>';
        echo '<label class="typecho-label">' . _t('Clarity 设置诊断') . '</label>';
        echo '<div class="description" style="color:' . $diagColor . ';">' . $diagMsg . '</div>';
        echo '</li></ul>';
    }

    if (is_array($updateInfo) && !empty($updateInfo['latest'])) {
        echo '<ul class="typecho-option"><li>';
        echo '<label class="typecho-label">' . _t('Clarity主题更新') . '</label>';
        echo '<div class="description">';
        $current = htmlspecialchars((string) ($updateInfo['current'] ?? CLARITY_VERSION), ENT_QUOTES, 'UTF-8');
        $latest = htmlspecialchars((string) ($updateInfo['latest'] ?? ''), ENT_QUOTES, 'UTF-8');
        $checked = htmlspecialchars((string) ($updateInfo['checked_at'] ?? ''), ENT_QUOTES, 'UTF-8');
        if (!empty($updateInfo['need_update'])) {
            $url = htmlspecialchars((string) ($updateInfo['url'] ?? ''), ENT_QUOTES, 'UTF-8');
            echo _t('发现新版本：') . $latest . '，' . _t('当前版本：') . $current;
            if ($url !== '') {
                echo ' <a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . _t('前往下载') . '</a>';
            }
        } else {
            echo _t('当前已是最新版本：') . $current;
        }
        if ($checked !== '') {
            echo '<br><span class="description">' . _t('最近检查：') . $checked . '</span>';
        }
        echo '</div>';
        echo '<div style="margin-top:8px;">';
        echo '<button type="button" class="btn" data-update-action="check">' . _t('立即检查更新') . '</button>';
        echo '</div></li></ul>';
    } else {
        echo '<ul class="typecho-option"><li>';
        echo '<label class="typecho-label">' . _t('Clarity主题更新') . '</label>';
        echo '<div style="margin-top:8px;">';
        echo '<button type="button" class="btn" data-update-action="check">' . _t('立即检查更新') . '</button>';
        echo '</div></li></ul>';
    }

    echo '<ul class="typecho-option"><li>';
    echo '<label class="typecho-label">' . _t('Clarity主题设置备份') . '</label>';
    echo '<div class="description">' . _t('最多保留 3 份备份。备份/恢复/删除不会保存当前未保存的设置。') . '</div>';
    echo '<div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-top:8px;">';
    echo '<button type="button" class="btn" data-backup-action="backup">' . _t('备份当前设置') . '</button>';
    echo '</div>';
    echo '<div id="clarity-backup-list" style="margin-top:10px;display:flex;flex-direction:column;gap:8px;">' . $backupListHtml . '</div>';
    echo '<div id="clarity-backup-message" class="description" style="margin-top:6px;display:none;"></div>';
    echo '</li></ul>';

    // Meting 音乐播放器配置
    $metingEnable = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_meting_enable',
        ['1' => _t('启用音乐播放器')],
        [],
        _t('音乐播放器'),
        _t('在侧边栏显示 Meting 音乐播放器')
    );
    $form->addInput($metingEnable);

    $metingApi = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_meting_api',
        null,
        'https://meting.kemiaosw.top',
        _t('Meting API 地址'),
        _t('Meting API 服务地址，例如：https://meting.kemiaosw.top')
    );
    $form->addInput($metingApi);

    $metingServer = new \Typecho\Widget\Helper\Form\Element\Select(
        'clarity_meting_server',
        ['netease' => _t('网易云音乐'), 'tencent' => _t('QQ音乐'), 'kugou' => _t('酷狗音乐'), 'kuwo' => _t('酷我音乐'), 'bilibili' => _t('哔哩哔哩'), 'baidu' => _t('百度音乐')],
        'netease',
        _t('音乐平台')
    );
    $form->addInput($metingServer);

    $metingType = new \Typecho\Widget\Helper\Form\Element\Select(
        'clarity_meting_type',
        ['playlist' => _t('歌单'), 'album' => _t('专辑'), 'artist' => _t('歌手'), 'song' => _t('单曲')],
        'playlist',
        _t('获取类型')
    );
    $form->addInput($metingType);

    $metingId = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_meting_id',
        null,
        '',
        _t('歌单/专辑/歌手 ID'),
        _t('填写网易云音乐或其他平台的歌单/专辑/歌手 ID')
    );
    $form->addInput($metingId);

    $metingTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_meting_title',
        null,
        '随心听',
        _t('播放器标题')
    );
    $form->addInput($metingTitle);

    // 欢迎来访者组件配置
    $welcomeEnable = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_welcome_enable',
        ['1' => _t('启用欢迎来访者组件')],
        [],
        _t('欢迎来访者'),
        _t('在侧边栏显示欢迎来访者信息')
    );
    $form->addInput($welcomeEnable);

    $welcomeTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_welcome_title',
        null,
        '欢迎来访者',
        _t('欢迎组件标题')
    );
    $form->addInput($welcomeTitle);

    $welcomeApi = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_welcome_api',
        null,
        'https://whois.pconline.com.cn/ipJson.jsp',
        _t('IP 查询 API'),
        _t('用于获取来访者地理位置的 API 地址')
    );
    $form->addInput($welcomeApi);

    // 诗词组件配置
    $poetryEnable = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'clarity_poetry_enable',
        ['1' => _t('启用诗词组件')],
        [],
        _t('每日诗词'),
        _t('在侧边栏显示每日一句诗词（使用今日诗词 SDK）')
    );
    $form->addInput($poetryEnable);

    $poetryTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_poetry_title',
        null,
        '每日诗词',
        _t('诗词组件标题')
    );
    $form->addInput($poetryTitle);

    // 装备页面配置
    $equipmentApi = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_equipment_api',
        null,
        'https://cofe.050815.xyz/api/devices',
        _t('装备数据 API'),
        _t('装备页面数据来源 API 地址')
    );
    $form->addInput($equipmentApi);

    $equipmentTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_equipment_title',
        null,
        '我的装备',
        _t('装备页面标题')
    );
    $form->addInput($equipmentTitle);

    $equipmentDesc = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_equipment_desc',
        null,
        '记录我的数字生活装备',
        _t('装备页面描述')
    );
    $form->addInput($equipmentDesc);

    // 打赏页面配置
    $rewardApi = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_reward_api',
        null,
        'https://cofe.050815.xyz/api/rewards',
        _t('打赏数据 API'),
        _t('打赏页面数据来源 API 地址')
    );
    $form->addInput($rewardApi);

    $rewardTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_reward_title',
        null,
        '支持一下',
        _t('打赏页面标题')
    );
    $form->addInput($rewardTitle);

    $rewardDesc = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_reward_desc',
        null,
        '感谢您的支持与鼓励',
        _t('打赏页面描述')
    );
    $form->addInput($rewardDesc);

    // 画廊页面配置
    $galleryTitle = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_gallery_title',
        null,
        '画廊',
        _t('画廊页面标题')
    );
    $form->addInput($galleryTitle);

    $galleryDesc = new \Typecho\Widget\Helper\Form\Element\Text(
        'clarity_gallery_desc',
        null,
        '随机图片画廊',
        _t('画廊页面描述')
    );
    $form->addInput($galleryDesc);

    echo '<script>(function(){var init=function(){var form=document.querySelector(\'form[action*="themes-edit"]\');var actionInput=document.getElementById(\'clarity-backup-action\');var targetInput=document.getElementById(\'clarity-backup-target\');var updateInput=document.getElementById(\'clarity-update-action\');var message=document.getElementById(\'clarity-backup-message\');if(!form){return;}var showMsg=function(text,type){if(!message){return;}message.textContent=text;message.style.display=\'block\';if(type===\'success\'){message.style.color=\'#1a7f37\';}else if(type===\'warn\'){message.style.color=\'#b78103\';}else{message.style.color=\'#d14343\';}};document.querySelectorAll(\'[data-backup-action]\').forEach(function(btn){btn.addEventListener(\'click\',function(){if(!actionInput||!targetInput){return;}var action=btn.getAttribute(\'data-backup-action\');if(!action){return;}var target=btn.getAttribute(\'data-backup-id\')||\'\';if((action===\'restore\'||action===\'delete\')&&!target){showMsg(\'请选择要操作的备份\',\'error\');return;}if(action===\'delete\'){if(!btn.dataset.confirmed){btn.dataset.confirmed=\'1\';showMsg(\'再次点击删除以确认\', \'warn\');setTimeout(function(){btn.dataset.confirmed=\'\';}, 3000);return;}btn.dataset.confirmed=\'\';}actionInput.value=action;targetInput.value=target;if(updateInput){updateInput.value=\'\';}form.submit();});});document.querySelectorAll(\'[data-update-action]\').forEach(function(btn){btn.addEventListener(\'click\',function(){if(!updateInput){return;}var action=btn.getAttribute(\'data-update-action\');if(!action){return;}updateInput.value=action;if(actionInput){actionInput.value=\'\';}if(targetInput){targetInput.value=\'\';}form.submit();});});};if(document.readyState===\'loading\'){document.addEventListener(\'DOMContentLoaded\',init);}else{init();}})();</script>';
}

function themeConfigHandle($settings, $isInit)
{
    if ($isInit) {
        return false;
    }

    $action = isset($settings['clarity_backup_action']) ? (string) $settings['clarity_backup_action'] : '';
    $target = isset($settings['clarity_backup_target']) ? (string) $settings['clarity_backup_target'] : '';
    $updateAction = isset($settings['clarity_update_action']) ? (string) $settings['clarity_update_action'] : '';

    if ($updateAction === 'check') {
        clarity_db_set_option_value(clarity_theme_update_key(), '');
        $repo = defined('CLARITY_GITHUB_REPO') ? CLARITY_GITHUB_REPO : 'jkjoy/Theme-Clarity';
        $info = clarity_github_update_info($repo);
        if (is_array($info) && !empty($info['latest'])) {
            \Widget\Notice::alloc()->set(_t('已刷新更新信息'), 'success');
        } else {
            \Widget\Notice::alloc()->set(_t('暂无法获取更新信息'), 'error');
        }
        return true;
    }

    if ($action === 'backup') {
        $current = clarity_theme_read_options();
        if (empty($current)) {
            $current = clarity_theme_clean_settings($settings);
        }
        $backups = clarity_theme_backups_read();
        $backups[] = [
            'id' => uniqid('b', true),
            'time' => date('Y-m-d H:i:s'),
            'ts' => time(),
            'data' => $current
        ];
        if (count($backups) > 3) {
            usort($backups, function ($a, $b) {
                return ((int) ($a['ts'] ?? 0)) <=> ((int) ($b['ts'] ?? 0));
            });
            $backups = array_slice($backups, -3);
        }
        clarity_theme_backups_write($backups);
        \Widget\Notice::alloc()->set(_t('已创建主题设置备份'), 'success');
        return true;
    }

    if ($action === 'delete') {
        $backups = clarity_theme_backups_read();
        $filtered = [];
        foreach ($backups as $item) {
            $id = (string) ($item['id'] ?? '');
            if ($id === '' || $id === $target) {
                continue;
            }
            $filtered[] = $item;
        }
        clarity_theme_backups_write($filtered);
        \Widget\Notice::alloc()->set(_t('备份已删除'), 'success');
        return true;
    }

    if ($action === 'restore') {
        $backups = clarity_theme_backups_read();
        $restore = null;
        foreach ($backups as $item) {
            if ((string) ($item['id'] ?? '') === $target) {
                $restore = $item;
                break;
            }
        }
        if ($restore && is_array($restore['data'] ?? null)) {
            $data = clarity_theme_clean_settings($restore['data']);
            $saveError = '';
            if (clarity_theme_save_options_checked($data, $saveError)) {
                \Widget\Notice::alloc()->set(_t('已恢复备份设置'), 'success');
            } else {
                $msg = _t('恢复备份失败：%s', $saveError !== '' ? $saveError : _t('未知错误'));
                clarity_theme_diag_set($msg, 'error');
                \Widget\Notice::alloc()->set($msg, 'error');
            }
        } else {
            \Widget\Notice::alloc()->set(_t('备份不存在或数据无效'), 'error');
        }
        return true;
    }

    $clean = clarity_theme_clean_settings($settings);
    $saveError = '';
    if (!clarity_theme_save_options_checked($clean, $saveError)) {
        $msg = _t('主题设置保存失败：%s', $saveError !== '' ? $saveError : _t('未知错误'));
        clarity_theme_diag_set($msg, 'error');
    }
    return true;
}

function themeFields($layout)
{
    $sticky = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'sticky', 
        ['true' => _t('置顶文章')], 
        '', 
        _t('置顶文章')
        );
    $layout->addItem($sticky);

    $cover = new \Typecho\Widget\Helper\Form\Element\Text('cover', null, '', _t('封面图 URL'));
    $layout->addItem($cover);

    $toc = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'toc',
        ['1' => _t('启用目录')],
        ['1'],
        _t('目录开关')
    );
    $layout->addItem($toc);

    $enableTitleColor = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'enable_post_title_color',
        ['1' => _t('启用标题颜色')],
        [],
        _t('标题颜色')
    );
    $layout->addItem($enableTitleColor);

    $titleColor = new \Typecho\Widget\Helper\Form\Element\Text(
        'post_title_color',
        null,
        '#FFFFFF',
        _t('标题颜色值')
    );
    $layout->addItem($titleColor);

    $aiGenerated = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'ai_generated',
        ['1' => _t('AI 辅助生成')],
        [],
        _t('AI 标记')
    );
    $layout->addItem($aiGenerated);

    $aiDesc = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'ai_generated_desc',
        null,
        '本文内容由 AI 辅助生成，已经人工审核和编辑。',
        _t('AI 提示文案')
    );
    $layout->addItem($aiDesc);

    $postOriginal = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'post_original',
        ['1' => _t('原创')],
        ['1'],
        _t('文章来源')
    );
    $layout->addItem($postOriginal);

    $postLicense = new \Typecho\Widget\Helper\Form\Element\Checkbox(
        'post_license',
        ['1' => _t('自定义许可')],
        [],
        _t('自定义许可协议')
    );
    $layout->addItem($postLicense);

    $postLicenseText = new \Typecho\Widget\Helper\Form\Element\Text(
        'post_license_text',
        null,
        '',
        _t('许可协议名称')
    );
    $layout->addItem($postLicenseText);

    $postLicenseUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'post_license_url',
        null,
        '',
        _t('许可协议链接')
    );
    $layout->addItem($postLicenseUrl);

    $postOriginalName = new \Typecho\Widget\Helper\Form\Element\Text(
        'post_original_name',
        null,
        '',
        _t('文章来源名称')
    );
    $layout->addItem($postOriginalName);

    $postOriginalUrl = new \Typecho\Widget\Helper\Form\Element\Text(
        'post_original_url',
        null,
        '',
        _t('文章来源链接')
    );
    $layout->addItem($postOriginalUrl);

    $postOriginalText = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'post_original_text',
        null,
        '此文来自 {post_original} ，侵删。',
        _t('来源提示文案')
    );
    $layout->addItem($postOriginalText);

    $summary = new \Typecho\Widget\Helper\Form\Element\Textarea(
        'summary',
        null,
        '',
        _t('文章摘要'),
        _t('自定义文章摘要，留空则自动截取正文前 120 字')
    );
    $layout->addItem($summary);
}

function clarity_opt(string $key, $default = null)
{
    $options = \Typecho\Widget::widget('Widget_Options');
    $name = 'clarity_' . $key;
    if (isset($options->{$name}) && $options->{$name} !== '') {
        return $options->{$name};
    }
    return $default;
}

function clarity_markdown_to_html(string $content): string
{
    if (trim($content) === '') {
        return '';
    }
    
    // 简单的 Markdown 解析
    $html = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
    
    // 粗体 **text**
    $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
    // 斜体 *text*
    $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);
    // 链接 [text](url)
    $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>', $html);
    // 行内代码 `code`
    $html = preg_replace('/`([^`]+)`/', '<code>$1</code>', $html);
    // 换行符转 <br>
    $html = nl2br($html);
    
    return $html;
}

function clarity_theme_name(): string
{
    $options = \Typecho\Widget::widget('Widget_Options');
    $theme = $options->theme ?? '';
    if ($theme !== '') {
        return $theme;
    }
    return basename(__DIR__);
}

function clarity_db_get_option_value(string $name): ?string
{
    try {
        $db = \Typecho\Db::get();
        $row = $db->fetchRow(
            $db->select()->from('table.options')->where('name = ?', $name)->limit(1)
        );
        if (is_array($row) && array_key_exists('value', $row)) {
            return $row['value'];
        }
    } catch (\Throwable $e) {
    }
    return null;
}

function clarity_db_set_option_value(string $name, string $value): void
{
    try {
        $db = \Typecho\Db::get();
        $exists = $db->fetchRow(
            $db->select()->from('table.options')->where('name = ?', $name)->limit(1)
        );
        if ($exists) {
            $db->query(
                $db->update('table.options')->rows(['value' => $value])->where('name = ?', $name)
            );
        } else {
            $db->query(
                $db->insert('table.options')->rows(['name' => $name, 'value' => $value, 'user' => 0])
            );
        }
    } catch (\Throwable $e) {
    }
}

function clarity_json_encode_for_storage($data): ?string
{
    $flags = JSON_UNESCAPED_SLASHES;
    if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
        $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
    }
    $encoded = json_encode($data, $flags);
    return is_string($encoded) ? $encoded : null;
}

function clarity_theme_diag_cookie_key(): string
{
    return '__clarity_theme_diag';
}

function clarity_theme_diag_set(string $message, string $type = 'error'): void
{
    $payload = clarity_json_encode_for_storage([
        'message' => $message,
        'type' => $type,
        'time' => time(),
    ]);
    if (!is_string($payload) || $payload === '') {
        return;
    }

    if (class_exists('\\Typecho\\Cookie')) {
        \Typecho\Cookie::set(clarity_theme_diag_cookie_key(), $payload);
    } elseif (class_exists('Typecho_Cookie')) {
        Typecho_Cookie::set(clarity_theme_diag_cookie_key(), $payload);
    }
}

function clarity_theme_diag_pull(): ?array
{
    $raw = '';
    if (class_exists('\\Typecho\\Cookie')) {
        $raw = (string) \Typecho\Cookie::get(clarity_theme_diag_cookie_key(), '');
        \Typecho\Cookie::delete(clarity_theme_diag_cookie_key());
    } elseif (class_exists('Typecho_Cookie')) {
        $raw = (string) Typecho_Cookie::get(clarity_theme_diag_cookie_key());
        Typecho_Cookie::delete(clarity_theme_diag_cookie_key());
    }

    if ($raw === '') {
        return null;
    }

    $data = json_decode($raw, true);
    if (!is_array($data) || empty($data['message'])) {
        return null;
    }

    $type = (string) ($data['type'] ?? 'error');
    if (!in_array($type, ['error', 'warn', 'success'], true)) {
        $type = 'error';
    }

    return [
        'message' => (string) ($data['message'] ?? ''),
        'type' => $type,
        'time' => (int) ($data['time'] ?? 0),
    ];
}

function clarity_theme_option_key(): string
{
    return 'theme:' . clarity_theme_name();
}

function clarity_theme_backup_key(): string
{
    return clarity_theme_option_key() . ':backups';
}

function clarity_theme_update_key(): string
{
    return clarity_theme_option_key() . ':github-update';
}

function clarity_theme_read_options(): array
{
    $raw = clarity_db_get_option_value(clarity_theme_option_key());
    if (!is_string($raw) || $raw === '') {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function clarity_theme_save_options(array $settings): void
{
    $error = '';
    clarity_theme_save_options_checked($settings, $error);
}

function clarity_theme_save_options_checked(array $settings, ?string &$error = null): bool
{
    $error = '';

    $value = clarity_json_encode_for_storage($settings);
    if (!is_string($value) || $value === '') {
        $error = _t('JSON 编码失败，可能包含非法字符');
        return false;
    }

    clarity_db_set_option_value(clarity_theme_option_key(), $value);

    $savedRaw = clarity_db_get_option_value(clarity_theme_option_key());
    if (!is_string($savedRaw) || $savedRaw === '') {
        $error = _t('写入后未读取到配置，请检查数据库写入权限');
        return false;
    }

    if ($savedRaw !== $value) {
        $savedData = json_decode($savedRaw, true);
        $newData = json_decode($value, true);
        if (!is_array($savedData)) {
            $error = _t('写入后读取到的配置格式异常，可能是数据库字符集不兼容');
            return false;
        }
        if (!is_array($newData) || $savedData !== $newData) {
            $error = _t('写入后数据与提交不一致，可能被数据库截断或编码转换');
            return false;
        }
    }

    return true;
}

function clarity_theme_clean_settings(array $settings): array
{
    unset($settings['clarity_backup_action'], $settings['clarity_backup_target'], $settings['clarity_update_action']);
    return $settings;
}

function clarity_theme_backups_read(): array
{
    $raw = clarity_db_get_option_value(clarity_theme_backup_key());
    if (!is_string($raw) || $raw === '') {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function clarity_theme_backups_write(array $backups): void
{
    $value = clarity_json_encode_for_storage(array_values($backups));
    if (!is_string($value) || $value === '') {
        return;
    }
    clarity_db_set_option_value(clarity_theme_backup_key(), $value);
}

function clarity_github_update_info(string $repo): ?array
{
    $repo = trim($repo);
    if ($repo === '') {
        return null;
    }

    $cacheRaw = clarity_db_get_option_value(clarity_theme_update_key());
    $cache = [];
    if (is_string($cacheRaw) && $cacheRaw !== '') {
        $cache = json_decode($cacheRaw, true);
    }

    $cacheTime = (int) ($cache['time'] ?? 0);
    $cacheData = is_array($cache['data'] ?? null) ? $cache['data'] : null;
    $ttl = defined('CLARITY_GITHUB_UPDATE_TTL') ? (int) CLARITY_GITHUB_UPDATE_TTL : 21600;
    if ($cacheData && $cacheTime > 0 && (time() - $cacheTime) < $ttl) {
        $cacheData['checked_at'] = date('Y-m-d H:i:s', $cacheTime);
        return $cacheData;
    }

    $url = 'https://api.github.com/repos/' . $repo . '/releases/latest';
    $headers = [
        'User-Agent' => 'Typecho-Clarity',
        'Accept' => 'application/vnd.github+json',
    ];
    $data = clarity_http_get_json($url, $headers, 8);
    if (!is_array($data)) {
        if ($cacheData) {
            $cacheData['checked_at'] = $cacheTime ? date('Y-m-d H:i:s', $cacheTime) : '';
            return $cacheData;
        }
        return null;
    }

    $tag = (string) ($data['tag_name'] ?? $data['name'] ?? '');
    $tag = preg_replace('/^v/i', '', $tag);
    if ($tag === '') {
        if ($cacheData) {
            $cacheData['checked_at'] = $cacheTime ? date('Y-m-d H:i:s', $cacheTime) : '';
            return $cacheData;
        }
        return null;
    }
    $current = preg_replace('/^v/i', '', (string) CLARITY_VERSION);
    $needUpdate = $tag !== '' && $current !== '' ? version_compare($tag, $current, '>') : false;

    $info = [
        'current' => $current,
        'latest' => $tag,
        'url' => (string) ($data['html_url'] ?? ''),
        'published_at' => (string) ($data['published_at'] ?? ''),
        'need_update' => $needUpdate,
    ];

    $cachePayload = clarity_json_encode_for_storage(['time' => time(), 'data' => $info]);
    if (is_string($cachePayload) && $cachePayload !== '') {
        clarity_db_set_option_value(clarity_theme_update_key(), $cachePayload);
    }

    $info['checked_at'] = date('Y-m-d H:i:s');
    return $info;
}

function clarity_bool($value, bool $default = false): bool
{
    if ($value === null) {
        return $default;
    }
    if (is_bool($value)) {
        return $value;
    }
    if (is_array($value)) {
        if (empty($value)) {
            return false;
        }
        foreach ($value as $item) {
            if (clarity_bool($item, $default)) {
                return true;
            }
        }
        return false;
    }
    if (is_int($value) || is_float($value)) {
        return ((int) $value) === 1;
    }
    $val = strtolower(trim((string) $value));
    return in_array($val, ['1', 'true', 'yes', 'on'], true);
}

function clarity_set(string $key, $value): void
{
    if (!isset($GLOBALS['clarity']) || !is_array($GLOBALS['clarity'])) {
        $GLOBALS['clarity'] = [];
    }
    $GLOBALS['clarity'][$key] = $value;
}

function clarity_get(string $key, $default = null)
{
    if (!isset($GLOBALS['clarity']) || !is_array($GLOBALS['clarity'])) {
        return $default;
    }
    return $GLOBALS['clarity'][$key] ?? $default;
}

function clarity_parse_lines(string $value): array
{
    $lines = preg_split('/\r\n|\n|\r/', $value);
    $items = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
            $items[] = $line;
        }
    }
    return $items;
}

function clarity_json_option(string $key, array $default = []): array
{
    $raw = trim((string) clarity_opt($key, ''));
    if ($raw === '') {
        return $default;
    }
    $data = json_decode($raw, true);
    if (is_array($data)) {
        return $data;
    }
    return $default;
}

function clarity_http_get_json(string $url, array $headers = [], int $timeout = 8): ?array
{
    $response = '';
    $headerLines = [];
    foreach ($headers as $name => $value) {
        $headerLines[] = $name . ': ' . $value;
    }

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        $userAgent = isset($headers['User-Agent']) ? (string) $headers['User-Agent'] : 'Typecho-Clarity';
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headerLines,
            CURLOPT_USERAGENT => $userAgent,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    if (!is_string($response) || $response === '') {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headerLines),
                'timeout' => $timeout,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $response = @file_get_contents($url, false, $context);
    }

    if (!is_string($response) || $response === '') {
        return null;
    }
    $data = json_decode($response, true);
    return is_array($data) ? $data : null;
}

function clarity_bangumi_normalize_uid(string $uid): string
{
    $uid = preg_replace('/\D+/', '', $uid);
    return $uid ?? '';
}

function clarity_bangumi_cache_dir(): string
{
    return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'cache';
}

function clarity_bangumi_cache_file(string $uid): string
{
    $safeUid = clarity_bangumi_normalize_uid($uid);
    return clarity_bangumi_cache_dir() . DIRECTORY_SEPARATOR . 'clarity-bangumis-' . $safeUid . '.json';
}

function clarity_bangumi_cache_ttl(): int
{
    $defaultMinutes = (int) floor((int) CLARITY_BANGUMI_CACHE_TTL / 60);
    if ($defaultMinutes <= 0) {
        $defaultMinutes = 1440;
    }

    $minutes = (int) clarity_opt('bangumi_cache_minutes', (string) $defaultMinutes);
    if ($minutes <= 0) {
        $minutes = $defaultMinutes;
    }
    if ($minutes < 1) {
        $minutes = 1;
    }
    if ($minutes > 525600) {
        $minutes = 525600;
    }

    return $minutes * 60;
}

function clarity_bangumi_cache_read(string $uid): ?array
{
    $file = clarity_bangumi_cache_file($uid);
    if (!is_file($file)) {
        return null;
    }
    $raw = @file_get_contents($file);
    if (!is_string($raw) || $raw === '') {
        return null;
    }
    $payload = json_decode($raw, true);
    if (!is_array($payload) || !isset($payload['time'], $payload['data']) || !is_array($payload['data'])) {
        return null;
    }
    if (time() - (int) $payload['time'] > clarity_bangumi_cache_ttl()) {
        @unlink($file);
        return null;
    }
    return $payload['data'];
}

function clarity_bangumi_cache_write(string $uid, array $data): void
{
    $dir = clarity_bangumi_cache_dir();
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    if (!is_dir($dir) || !is_writable($dir)) {
        return;
    }
    $file = clarity_bangumi_cache_file($uid);
    $payload = [
        'time' => time(),
        'data' => array_values($data),
    ];
    @file_put_contents($file, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function clarity_bangumi_request_page(string $uid, int $page, int $pageSize, ?int $followStatus = null): ?array
{
    $params = [
        'type' => 1,
        'vmid' => $uid,
        'pn' => max(1, $page),
        'ps' => max(1, $pageSize),
    ];
    if ($followStatus !== null) {
        $params['follow_status'] = $followStatus;
    }

    $headers = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
        'Referer' => 'https://www.bilibili.com/',
        'Accept' => 'application/json',
    ];

    $endpoints = [
        'https://api.bilibili.com/x/space/bangumi/follow/list',
        'https://api.bilibili.com/pgc/space/follow/list',
    ];

    foreach ($endpoints as $base) {
        $url = $base . '?' . http_build_query($params);
        $data = clarity_http_get_json($url, $headers, 10);
        if (is_array($data) && isset($data['data'])) {
            return $data;
        }
    }
    return null;
}

function clarity_bangumi_fetch_list(string $uid, ?int $followStatus = null): ?array
{
    $page = 1;
    $pageSize = 30;
    $maxPages = 50;
    $items = [];
    do {
        $data = clarity_bangumi_request_page($uid, $page, $pageSize, $followStatus);
        if (!is_array($data) || !array_key_exists('code', $data)) {
            return null;
        }
        if ((int) $data['code'] !== 0) {
            return null;
        }
        $list = $data['data']['list'] ?? [];
        if (is_array($list)) {
            $items = array_merge($items, $list);
        }
        $total = (int) ($data['data']['total'] ?? 0);
        $pages = $total > 0 ? (int) ceil($total / $pageSize) : $page;
        $page++;
    } while ($page <= $pages && $page <= $maxPages);
    return $items;
}

function clarity_bangumi_map_item(array $item, int $statusFallback): array
{
    $title = (string) ($item['title'] ?? $item['name'] ?? '');
    $cover = (string) ($item['cover'] ?? '');
    if ($cover !== '' && strpos($cover, '//') === 0) {
        $cover = 'https:' . $cover;
    }
    $type = (string) ($item['season_type_name'] ?? $item['type_name'] ?? $item['type'] ?? '');
    $area = (string) ($item['area'] ?? '');
    if ($area === '' && isset($item['areas']) && is_array($item['areas'])) {
        $areaNames = [];
        foreach ($item['areas'] as $areaItem) {
            if (is_array($areaItem) && isset($areaItem['name'])) {
                $areaNames[] = $areaItem['name'];
            } elseif (is_string($areaItem)) {
                $areaNames[] = $areaItem;
            }
        }
        if (!empty($areaNames)) {
            $area = implode('/', $areaNames);
        }
    }
    $totalCount = (string) ($item['total_count'] ?? $item['totalCount'] ?? $item['total_ep'] ?? '');
    $stat = is_array($item['stat'] ?? null) ? $item['stat'] : [];
    $follow = (string) ($stat['follow'] ?? $item['follow'] ?? '');
    $view = (string) ($stat['view'] ?? $item['view'] ?? '');
    $danmaku = (string) ($stat['danmaku'] ?? $item['danmaku'] ?? '');
    $coin = (string) ($stat['coin'] ?? $item['coin'] ?? '');
    $rating = is_array($item['rating'] ?? null) ? $item['rating'] : [];
    $score = (string) ($rating['score'] ?? $item['score'] ?? ($stat['score'] ?? ''));
    $desc = (string) ($item['evaluate'] ?? $item['desc'] ?? $item['description'] ?? '');
    $seasonId = $item['season_id'] ?? $item['seasonId'] ?? '';
    $url = (string) ($item['url'] ?? '');
    if ($url === '' && $seasonId !== '') {
        $url = 'https://www.bilibili.com/bangumi/play/ss' . $seasonId;
    }
    $statusRaw = $item['follow_status'] ?? $item['status'] ?? $statusFallback;
    $status = (int) $statusRaw;
    if ($status < 0 || $status > 3) {
        $status = $statusFallback;
    }

    return [
        'title' => $title,
        'cover' => $cover,
        'type' => $type,
        'area' => $area,
        'totalCount' => $totalCount,
        'follow' => $follow,
        'view' => $view,
        'danmaku' => $danmaku,
        'coin' => $coin,
        'score' => $score,
        'desc' => $desc,
        'url' => $url,
        'status' => $status,
        '_id' => (string) ($item['season_id'] ?? $item['media_id'] ?? $item['seasonId'] ?? $title),
    ];
}

function clarity_bangumis_from_bilibili(string $uid): array
{
    static $memo = [];
    $uid = clarity_bangumi_normalize_uid($uid);
    if ($uid === '') {
        return [];
    }
    if (isset($memo[$uid])) {
        return $memo[$uid];
    }

    $cached = clarity_bangumi_cache_read($uid);
    if (is_array($cached)) {
        $memo[$uid] = $cached;
        return $cached;
    }

    $result = [];
    $seen = [];
    $statusGroups = [1, 2, 3];
    $usedStatusFetch = false;
    $fetchOk = false;

    foreach ($statusGroups as $status) {
        $list = clarity_bangumi_fetch_list($uid, $status);
        if ($list === null) {
            $result = [];
            $usedStatusFetch = false;
            break;
        }
        $usedStatusFetch = true;
        $fetchOk = true;
        foreach ($list as $item) {
            if (!is_array($item)) {
                continue;
            }
            $mapped = clarity_bangumi_map_item($item, $status);
            if ($mapped['title'] === '') {
                continue;
            }
            $id = $mapped['_id'] !== '' ? $mapped['_id'] : $mapped['title'];
            if (isset($seen[$id])) {
                continue;
            }
            $seen[$id] = true;
            unset($mapped['_id']);
            $result[] = $mapped;
        }
    }

    if (!$usedStatusFetch) {
        $list = clarity_bangumi_fetch_list($uid, null);
        if (is_array($list)) {
            $fetchOk = true;
            foreach ($list as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $mapped = clarity_bangumi_map_item($item, 0);
                if ($mapped['title'] === '') {
                    continue;
                }
                $id = $mapped['_id'] !== '' ? $mapped['_id'] : $mapped['title'];
                if (isset($seen[$id])) {
                    continue;
                }
                $seen[$id] = true;
                unset($mapped['_id']);
                $result[] = $mapped;
            }
        }
    }

    if ($fetchOk) {
        clarity_bangumi_cache_write($uid, $result);
    }
    $memo[$uid] = $result;
    return $result;
}

function clarity_get_bangumis_data(): array
{
    $uid = trim((string) clarity_opt('bangumis_uid', ''));
    if ($uid !== '') {
        $data = clarity_bangumis_from_bilibili($uid);
        if (!empty($data)) {
            return $data;
        }
    }
    return [];
}

function clarity_db()
{
    if (class_exists('\\Typecho\\Db') && method_exists('\\Typecho\\Db', 'get')) {
        return \Typecho\Db::get();
    }
    if (class_exists('Typecho_Db') && method_exists('Typecho_Db', 'get')) {
        return \Typecho_Db::get();
    }
    return null;
}

function clarity_widget_instance(string $alias, $params = null, $request = null)
{
    if (class_exists('\\Typecho\\Widget')) {
        return \Typecho\Widget::widget($alias, $params, $request);
    }
    if (class_exists('Typecho_Widget')) {
        return \Typecho_Widget::widget($alias, $params, $request);
    }
    return null;
}

function clarity_contents_from($alias, $query)
{
    if (class_exists('\\Widget\\Contents\\From')) {
        if ($alias !== null && $alias !== '') {
            return \Widget\Contents\From::allocWithAlias($alias, ['query' => $query]);
        }
        return \Widget\Contents\From::alloc(['query' => $query]);
    }

    if (class_exists('Widget_Contents_From')) {
        if ($alias !== null && $alias !== '') {
            return \Widget_Contents_From::allocWithAlias($alias, ['query' => $query]);
        }
        return \Widget_Contents_From::alloc(['query' => $query]);
    }

    return clarity_query_iterator_from_query($query, $alias);
}

function clarity_query_iterator_from_query($query, $aliasPrefix = null)
{
    $db = clarity_db();
    if (!$db) {
        return null;
    }

    try {
        $rows = $db->fetchAll($query);
    } catch (\Throwable $e) {
        return null;
    }

    $prefix = $aliasPrefix ? (string) $aliasPrefix : 'clarity_query';
    return new Clarity_Query_Iterator($rows, $prefix);
}

function clarity_widget_from_row($row, string $alias)
{
    $cid = 0;
    if (is_array($row) && isset($row['cid'])) {
        $cid = (int) $row['cid'];
    } elseif (is_object($row) && isset($row->cid)) {
        $cid = (int) $row->cid;
    }

    if ($cid > 0) {
        $widget = clarity_widget_instance('Widget_Archive@' . $alias, 'type=post', 'cid=' . $cid);
        if ($widget && method_exists($widget, 'have') && $widget->have()) {
            $widget->next();
            return $widget;
        }
    }

    return is_array($row) ? (object) $row : $row;
}

if (!class_exists('Clarity_Query_Iterator')) {
    class Clarity_Query_Iterator
    {
        private $rows = [];
        private $index = 0;
        private $current = null;
        private $aliasPrefix = '';

        public function __construct(array $rows, $aliasPrefix)
        {
            $this->rows = array_values($rows);
            $this->aliasPrefix = (string) $aliasPrefix;
        }

        public function have()
        {
            return !empty($this->rows);
        }

        public function next()
        {
            if ($this->index >= count($this->rows)) {
                $this->current = null;
                $this->index = 0;
                return false;
            }

            $row = $this->rows[$this->index++];
            $this->current = clarity_widget_from_row($row, $this->aliasPrefix . '_' . $this->index);
            return $this->current;
        }

        public function __get($name)
        {
            if (is_object($this->current)) {
                return $this->current->{$name} ?? null;
            }
            if (is_array($this->current)) {
                return $this->current[$name] ?? null;
            }
            return null;
        }

        public function __call($name, $args)
        {
            if (is_object($this->current) && method_exists($this->current, $name)) {
                return $this->current->{$name}(...$args);
            }

            $value = null;
            if (is_object($this->current) && isset($this->current->{$name})) {
                $value = $this->current->{$name};
            } elseif (is_array($this->current) && isset($this->current[$name])) {
                $value = $this->current[$name];
            }

            if ($value !== null) {
                echo $value;
            }

            return null;
        }

        public function __isset($name)
        {
            if (is_object($this->current)) {
                return isset($this->current->{$name});
            }
            if (is_array($this->current)) {
                return isset($this->current[$name]);
            }
            return false;
        }
    }
}

function clarity_parse_user_agent(string $ua): array
{
    $ua = strtolower($ua);
    $os = '';
    $osIcon = '';
    if (strpos($ua, 'windows') !== false) {
        $os = 'Windows';
        $osIcon = 'icon-[ph--monitor-bold]';
    } elseif (strpos($ua, 'android') !== false) {
        $os = 'Android';
        $osIcon = 'icon-[ph--monitor-bold]';
    } elseif (strpos($ua, 'iphone') !== false || strpos($ua, 'ipad') !== false || strpos($ua, 'ipod') !== false) {
        $os = 'iOS';
        $osIcon = 'icon-[ph--monitor-bold]';
    } elseif (strpos($ua, 'mac os x') !== false) {
        $os = 'macOS';
        $osIcon = 'icon-[ph--monitor-bold]';
    } elseif (strpos($ua, 'linux') !== false) {
        $os = 'Linux';
        $osIcon = 'icon-[ph--monitor-bold]';
    }

    $browser = '';
    $browserIcon = '';
    $version = '';

    if (strpos($ua, 'edg/') !== false || strpos($ua, 'edgios') !== false) {
        $browser = 'Edge';
        $browserIcon = 'icon-[ph--globe-bold]';
        if (preg_match('/edg(?:ios)?\\/([0-9.]+)/', $ua, $match)) {
            $version = explode('.', $match[1])[0];
        }
    } elseif (strpos($ua, 'opr/') !== false || strpos($ua, 'opera') !== false) {
        $browser = 'Opera';
        $browserIcon = 'icon-[ph--globe-bold]';
        if (preg_match('/(opr|opera)\\/([0-9.]+)/', $ua, $match)) {
            $version = explode('.', $match[2])[0];
        }
    } elseif (strpos($ua, 'crios/') !== false) {
        $browser = 'Chrome';
        $browserIcon = 'icon-[ph--globe-bold]';
        if (preg_match('/crios\\/([0-9.]+)/', $ua, $match)) {
            $version = explode('.', $match[1])[0];
        }
    } elseif (strpos($ua, 'chrome/') !== false && strpos($ua, 'edg/') === false && strpos($ua, 'opr/') === false) {
        $browser = 'Chrome';
        $browserIcon = 'icon-[ph--globe-bold]';
        if (preg_match('/chrome\\/([0-9.]+)/', $ua, $match)) {
            $version = explode('.', $match[1])[0];
        }
    } elseif (strpos($ua, 'fxios/') !== false) {
        $browser = 'Firefox';
        $browserIcon = 'icon-[ph--globe-bold]';
        if (preg_match('/fxios\\/([0-9.]+)/', $ua, $match)) {
            $version = explode('.', $match[1])[0];
        }
    } elseif (strpos($ua, 'firefox/') !== false) {
        $browser = 'Firefox';
        $browserIcon = 'icon-[ph--globe-bold]';
        if (preg_match('/firefox\\/([0-9.]+)/', $ua, $match)) {
            $version = explode('.', $match[1])[0];
        }
    } elseif (
        strpos($ua, 'safari') !== false &&
        strpos($ua, 'chrome') === false &&
        strpos($ua, 'chromium') === false &&
        strpos($ua, 'crios') === false &&
        strpos($ua, 'fxios') === false &&
        strpos($ua, 'edg') === false
    ) {
        $browser = 'Safari';
        $browserIcon = 'icon-[ph--globe-bold]';
        if (preg_match('/version\\/([0-9.]+)/', $ua, $match)) {
            $version = explode('.', $match[1])[0];
        }
    }

    if ($browser !== '' && $version !== '') {
        $browser .= ' ' . $version;
    }

    return [
        'os' => $os,
        'os_icon' => $osIcon,
        'browser' => $browser,
        'browser_icon' => $browserIcon
    ];
}

function clarity_links_groups(): array
{
    $options = \Typecho\Widget::widget('Widget_Options');
    $useEnhancement = isset($options->plugins['activated']['Enhancement']);
    $useLinks = isset($options->plugins['activated']['Links']);
    if ($useEnhancement || $useLinks) {
        try {
            $db = \Typecho\Db::get();
            $sql = $db->select()->from('table.links')->order('table.links.order', \Typecho\Db::SORT_ASC);
            $links = $db->fetchAll($sql);
        } catch (\Throwable $e) {
            $links = [];
        }

        $groups = [];
        foreach ($links as $link) {
            if (isset($link['state']) && (int) $link['state'] !== 1) {
                continue;
            }
            $groupKey = trim((string) ($link['sort'] ?? ''));
            if ($groupKey === '') {
                $groupKey = '友链';
            }
            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [
                    'title' => $groupKey,
                    'description' => '',
                    'links' => []
                ];
            }

            $image = $link['image'] ?? '';
            if (($image === null || $image === '') && !empty($link['email'])) {
                $avatarBase = $useEnhancement ? 'https://cn.cravatar.com/avatar/' : 'https://gravatar.helingqi.com/wavatar/';
                $image = $avatarBase . md5($link['email']) . '?s=64&d=mm';
            }
            if ($image === null || $image === '') {
                $nopicPath = $useEnhancement ? 'usr/plugins/Enhancement/nopic.png' : 'usr/plugins/Links/nopic.png';
                $image = \Typecho\Common::url($nopicPath, $options->siteUrl);
            } else {
                $image = trim((string) $image);
                if (!preg_match('#^https?://#i', $image) && strpos($image, '//') !== 0) {
                    $image = \Typecho\Common::url($image, $options->siteUrl);
                }
            }

            $groups[$groupKey]['links'][] = [
                'name' => $link['name'] ?? '',
                'url' => $link['url'] ?? '',
                'logo' => $image ?: '',
                'desc' => $link['description'] ?? ''
            ];
        }

        return array_values($groups);
    }

    return clarity_json_option('links_data', []);
}

function clarity_moments_base_url(): string
{
    $options = \Typecho\Widget::widget('Widget_Options');
    $indexBase = $options->index ?? '';
    $templates = ['moments', 'page-moments', 'page-moments.php', 'moments.php'];
    $normalizeUrl = function (string $url) use ($options): string {
        $url = trim($url);
        if ($url === '') {
            return $options->siteUrl;
        }
        if (preg_match('#^https?:/[^/]#i', $url)) {
            $url = preg_replace('#^(https?):/#i', '$1://', $url, 1);
        }
        if (preg_match('#^https?://#i', $url) || strpos($url, '//') === 0) {
            return $url;
        }
        return \Typecho\Common::url($url, $options->siteUrl);
    };

    try {
        $db = \Typecho\Db::get();
        $select = $db->select()
            ->from('table.contents')
            ->where('table.contents.type = ?', 'page')
            ->where('table.contents.status = ?', 'publish');

        $templateConditions = [];
        $templateParams = [];
        foreach ($templates as $template) {
            $templateConditions[] = 'table.contents.template = ?';
            $templateParams[] = $template;
        }
        if (!empty($templateConditions)) {
            $select->where('(' . implode(' OR ', $templateConditions) . ')', ...$templateParams);
        }

        $row = $db->fetchRow(
            $select
                ->order('table.contents.created', \Typecho\Db::SORT_DESC)
                ->limit(1)
        );

        if (!$row) {
            $row = $db->fetchRow(
                $db->select()
                    ->from('table.contents')
                    ->where('table.contents.type = ?', 'page')
                    ->where('table.contents.status = ?', 'publish')
                    ->where('table.contents.slug = ?', 'moments')
                    ->order('table.contents.created', \Typecho\Db::SORT_DESC)
                    ->limit(1)
            );
        }

        if ($row) {
            $url = \Typecho\Router::url('page', $row, $indexBase);
            return $normalizeUrl($url);
        }
    } catch (\Throwable $e) {
    }

    return $options->siteUrl;
}

function clarity_moments_parse_tags($raw): array
{
    if (is_array($raw)) {
        $tags = [];
        foreach ($raw as $tag) {
            $tag = trim((string) $tag);
            if ($tag !== '') {
                $tags[] = $tag;
            }
        }
        return array_values(array_unique($tags));
    }

    $raw = trim((string) $raw);
    if ($raw === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $tags = [];
        foreach ($decoded as $tag) {
            $tag = trim((string) $tag);
            if ($tag !== '') {
                $tags[] = $tag;
            }
        }
        return array_values(array_unique($tags));
    }

    $parts = preg_split('/\s*,\s*/', $raw);
    $tags = [];
    if (is_array($parts)) {
        foreach ($parts as $tag) {
            $tag = trim((string) $tag);
            if ($tag !== '') {
                $tags[] = $tag;
            }
        }
    }
    return array_values(array_unique($tags));
}

function clarity_moments_parse_media($raw, string $siteUrl): array
{
    if (is_array($raw)) {
        $decoded = $raw;
    } else {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return [];
        }
        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return [];
        }
    }

    if (!array_key_exists(0, $decoded)) {
        $decoded = [$decoded];
    }

    $items = [];
    foreach ($decoded as $item) {
        if (!is_array($item)) {
            continue;
        }
        $url = $item['url'] ?? '';
        $url = is_string($url) ? trim($url) : '';
        if ($url === '') {
            continue;
        }
        if (!preg_match('#^(https?:)?//#i', $url) && !preg_match('#^(data|blob|file):#i', $url) && strpos($url, '/') !== 0) {
            $url = \Typecho\Common::url($url, $siteUrl);
        }
        $item['url'] = $url;
        $items[] = $item;
    }

    return $items;
}

function clarity_moments_items(int $limit = 0): array
{
    $options = \Typecho\Widget::widget('Widget_Options');
    if (isset($options->plugins['activated']['Enhancement'])) {
        try {
            $db = \Typecho\Db::get();
            $sql = $db->select()->from('table.moments')->order('table.moments.mid', \Typecho\Db::SORT_DESC);
            if ($limit > 0) {
                $sql = $sql->limit($limit);
            }
            $rows = $db->fetchAll($sql);
            $items = [];
            $baseUrl = clarity_moments_base_url();
            $pageSize = (int) clarity_opt('moments_page_size', '20');
            if ($pageSize <= 0) {
                $pageSize = 20;
            }
            if ($pageSize > 100) {
                $pageSize = 100;
            }
            $baseSep = strpos($baseUrl, '?') === false ? '?' : '&';
            foreach ($rows as $index => $row) {
                $mid = (int) ($row['mid'] ?? 0);
                $id = $mid > 0 ? 'moment-' . $mid : '';
                $created = $row['created'] ?? 0;
                if (is_numeric($created)) {
                    $timestamp = (int) $created;
                } else {
                    $parsed = strtotime((string) $created);
                    $timestamp = $parsed ? $parsed : 0;
                }
                $time = $timestamp > 0 ? date('Y-m-d H:i', $timestamp) : '';
                $pageNumber = $pageSize > 0 ? (int) floor($index / $pageSize) + 1 : 1;
                $pageParam = $pageNumber > 1 ? ($baseSep . 'page=' . $pageNumber) : '';
                $statusRaw = strtolower(trim((string) ($row['status'] ?? 'public')));
                $status = $statusRaw === 'private' ? 'private' : 'public';
                $locationAddress = trim((string) ($row['location_address'] ?? ''));
                $latitude = trim((string) ($row['latitude'] ?? ''));
                $longitude = trim((string) ($row['longitude'] ?? ''));
                $location = $locationAddress;
                if ($location === '' && $latitude !== '' && $longitude !== '') {
                    $location = $latitude . ',' . $longitude;
                }
                $sourceRaw = strtolower(trim((string) ($row['source'] ?? 'web')));
                $source = in_array($sourceRaw, ['web', 'mobile', 'api'], true) ? $sourceRaw : 'web';
                $items[] = [
                    'id' => $id,
                    'content' => (string) ($row['content'] ?? ''),
                    'time' => $time,
                    'tags' => clarity_moments_parse_tags($row['tags'] ?? ''),
                    'media' => clarity_moments_parse_media($row['media'] ?? '', $options->siteUrl),
                    'status' => $status,
                    'location' => $location,
                    'location_address' => $locationAddress,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'source' => $source,
                    'url' => $id !== '' ? ($baseUrl . $pageParam . '#' . $id) : ($baseUrl . $pageParam),
                ];
            }
            return $items;
        } catch (\Throwable $e) {
        }
    }

    $data = clarity_json_option('moments_data', []);
    return is_array($data) ? $data : [];
}

function clarity_site_logo(string $fallback = ''): string
{
    $logo = trim((string) clarity_opt('logo', ''));
    if ($logo !== '') {
        return $logo;
    }
    return $fallback;
}

function clarity_menu_items(): array
{
    $items = clarity_json_option('menu_json', []);
    if (!empty($items)) {
        return $items;
    }

    $list = [];
    $options = \Typecho\Widget::widget('Widget_Options');
    $list[] = [
        'text' => _t('首页'),
        'url' => $options->siteUrl,
        'icon' => ''
    ];

    $pages = \Typecho\Widget::widget('Widget_Contents_Page_List');
    while ($pages->next()) {
        $list[] = [
            'text' => $pages->title,
            'url' => $pages->permalink,
            'icon' => ''
        ];
    }

    return $list;
}

function clarity_featured_posts(): array
{
    $raw = trim((string) clarity_opt('featured_posts', ''));
    if ($raw === '') {
        return [];
    }

    $ids = preg_split('/[\s,]+/', $raw, -1, PREG_SPLIT_NO_EMPTY);
    $ids = array_unique(array_filter(array_map('intval', $ids)));
    if (empty($ids)) {
        return [];
    }

    $posts = [];
    foreach ($ids as $cid) {
        try {
            $widget = \Typecho\Widget::widget('Widget_Archive@clarity_featured_' . $cid, 'type=post', 'cid=' . $cid);
            if ($widget->have()) {
                $widget->next();
                $posts[] = $widget;
            }
        } catch (\Throwable $e) {
            continue;
        }
    }

    return $posts;
}

function clarity_render_featured_posts(array $featuredPosts): void
{
    if (empty($featuredPosts)) {
        return;
    }

    static $instance = 0;
    $instance++;
    $containerId = 'slide-scroll-container-' . $instance;
    ?>
  <div class="z-slide">
    <div class="z-slide-header">
      <span class="title text-creative">精选文章</span>
      <div class="at-slide-hover">
        <span class="icon-[ph--mouse-simple-bold]"></span>
        <span>按住 Shift 横向滚动</span>
      </div>
    </div>

    <div class="z-slide-body">
      <div class="slide-list" id="<?php echo $containerId; ?>">
        <?php foreach ($featuredPosts as $post): ?>
          <?php
          $cover = clarity_get_cover($post);
          $postTitle = clarity_display_text((string) ($post->title ?? ''));
          ?>
          <a href="<?php echo $post->permalink; ?>" class="slide-item gradient-card" title="<?php echo htmlspecialchars(clarity_get_excerpt($post, 120), ENT_QUOTES, 'UTF-8'); ?>">
            <?php if ($cover !== ''): ?>
              <img src="<?php echo htmlspecialchars($cover, ENT_QUOTES, 'UTF-8'); ?>" class="cover" loading="lazy" alt="<?php echo $postTitle; ?>" />
            <?php else: ?>
              <div class="cover flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                <span class="icon-[ph--image-broken] text-4xl opacity-20"></span>
              </div>
            <?php endif; ?>
            <div class="info">
              <div class="title text-creative"><?php echo $postTitle; ?></div>
              <div class="desc">
                <span class="icon-[ph--calendar-dots-bold]"></span>
                <span><?php echo $post->date('Y-m-d'); ?></span>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <button class="carousel-action prev at-slide-hover" aria-label="上一页" onclick="document.getElementById('<?php echo $containerId; ?>').scrollBy({ left: -300, behavior: 'smooth' })">
        <span class="icon-[ph--caret-left-bold]"></span>
      </button>
      <button class="carousel-action next at-slide-hover" aria-label="下一页" onclick="document.getElementById('<?php echo $containerId; ?>').scrollBy({ left: 300, behavior: 'smooth' })">
        <span class="icon-[ph--caret-right-bold]"></span>
      </button>
    </div>

    <script>
      (function () {
        const container = document.getElementById('<?php echo $containerId; ?>');
        if (container) {
          container.addEventListener('wheel', (e) => {
            if (!e.shiftKey && Math.abs(e.deltaY) > Math.abs(e.deltaX)) {
              e.preventDefault();
              container.scrollLeft += e.deltaY;
            }
          }, { passive: false });
        }
      })();
    </script>
  </div>
<?php
}

function clarity_get_custom_field_value($post, string $key): string
{
    if (!is_object($post)) {
        return '';
    }
    try {
        $fields = $post->fields;
    } catch (\Throwable $e) {
        return '';
    }

    $value = '';
    if ($fields instanceof \Typecho\Config) {
        $data = $fields->toArray();
        if (array_key_exists($key, $data)) {
            $value = (string) $data[$key];
        }
    } elseif (is_array($fields) && array_key_exists($key, $fields)) {
        $value = (string) $fields[$key];
    } elseif (is_object($fields) && property_exists($fields, $key)) {
        $value = (string) $fields->{$key};
    } elseif (is_object($fields) && method_exists($fields, '__get')) {
        $value = (string) $fields->{$key};
    }

    return trim($value);
}

function clarity_is_truthy_field_row(array $row): bool
{
    $type = $row['type'] ?? 'str';
    if ($type === 'int') {
        return (int) ($row['int_value'] ?? 0) !== 0;
    }
    if ($type === 'float') {
        return (float) ($row['float_value'] ?? 0) != 0.0;
    }
    $value = trim((string) ($row['str_value'] ?? ''));
    if ($value === '') {
        return false;
    }
    $lower = strtolower($value);
    if (in_array($lower, ['0', 'false', 'no', 'off', 'null'], true)) {
        return false;
    }
    return true;
}

function clarity_get_sticky_cids(): array
{
    try {
        $db = \Typecho\Db::get();
    } catch (\Throwable $e) {
        return [];
    }

    $rows = $db->fetchAll(
        $db->select(
            'table.fields.cid',
            'table.fields.type',
            'table.fields.str_value',
            'table.fields.int_value',
            'table.fields.float_value',
            'table.contents.created'
        )
            ->from('table.fields')
            ->join('table.contents', 'table.fields.cid = table.contents.cid')
            ->where('table.fields.name = ?', 'sticky')
            ->where('table.contents.type = ?', 'post')
            ->where('table.contents.status = ?', 'publish')
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->order('table.contents.created', \Typecho\Db::SORT_DESC)
    );

    if (empty($rows)) {
        return [];
    }

    $cids = [];
    foreach ($rows as $row) {
        if (!clarity_is_truthy_field_row($row)) {
            continue;
        }
        $cid = (int) ($row['cid'] ?? 0);
        if ($cid > 0) {
            $cids[] = $cid;
        }
    }

    return array_values(array_unique($cids));
}

function clarity_get_sticky_posts(array $cids = []): array
{
    if (empty($cids)) {
        $cids = clarity_get_sticky_cids();
    }
    if (empty($cids)) {
        return [];
    }

    $posts = [];
    foreach ($cids as $cid) {
        try {
            $widget = \Typecho\Widget::widget('Widget_Archive@clarity_sticky_' . $cid, 'type=post', 'cid=' . $cid);
            if ($widget->have()) {
                $widget->next();
                $posts[] = $widget;
            }
        } catch (\Throwable $e) {
            continue;
        }
    }

    return $posts;
}

function clarity_get_cover($post): string
{
    if (!is_object($post)) {
        return '';
    }
    $cover = clarity_get_custom_field_value($post, 'cover');
    if ($cover !== '') {
        return $cover;
    }

    try {
        if (method_exists($post, 'attachments')) {
            $attachments = $post->attachments();
            if ($attachments && $attachments->have()) {
                while ($attachments->next()) {
                    $attachment = $attachments->attachment ?? null;
                    if ($attachment && isset($attachment->isImage) && !$attachment->isImage) {
                        continue;
                    }
                    $url = '';
                    if ($attachment && isset($attachment->url)) {
                        $url = (string) $attachment->url;
                    }
                    if ($url === '' && isset($attachments->url)) {
                        $url = (string) $attachments->url;
                    }
                    if ($url !== '') {
                        return $url;
                    }
                }
            }
        }
    } catch (\Throwable $e) {
    }

    $content = '';
    if (isset($post->text)) {
        $content = (string) $post->text;
    } elseif (isset($post->content)) {
        $content = (string) $post->content;
    }

    $content = trim($content);
    if ($content !== '') {
        $matches = [];
        if (preg_match('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $content, $matches)) {
            return $matches[1];
        }
        if (preg_match('/!\\[[^\\]]*\\]\\(([^)\\s]+)(?:\\s+\\"[^\\"]*\\")?\\)/', $content, $matches)) {
            return trim($matches[1]);
        }
        if (preg_match('/!\\[[^\\]]*\\]\\(([^)]+)\\)/', $content, $matches)) {
            return trim($matches[1]);
        }
    }

    return '';
}

function clarity_decode_text(string $text): string
{
    $decoded = $text;
    for ($i = 0; $i < 2; $i++) {
        $next = html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($next === $decoded) {
            break;
        }
        $decoded = $next;
    }
    return trim(strip_tags($decoded));
}

function clarity_display_text(string $text): string
{
    return htmlspecialchars(clarity_decode_text($text), ENT_QUOTES, 'UTF-8');
}

function clarity_get_excerpt($post, int $length = 120): string
{
    $summary = clarity_get_custom_field_value($post, 'summary');
    if ($summary !== '') {
        $summaryText = clarity_decode_text($summary);
        if ($summaryText !== '') {
            if ($length > 0) {
                return \Typecho\Common::subStr($summaryText, 0, $length, '...');
            }
            return $summaryText;
        }
    }
    ob_start();
    $post->excerpt($length, '...');
    return clarity_decode_text((string) ob_get_clean());
}

function clarity_render_author_capsule($post): void
{
    if (!isset($post->author)) {
        return;
    }
    $author = $post->author;
    $name = $author->screenName ?? '';
    if ($name === '') {
        return;
    }
    $email = $author->mail ?? '';
    $avatar = '';
    if ($email !== '') {
        $avatar = \Typecho\Common::gravatarUrl($email, 64, 'X', 'mp', isset($_SERVER['HTTPS']));
    }
    echo '<a class="author-capsule" href="' . $author->permalink . '">';
    if ($avatar !== '') {
        echo '<img src="' . $avatar . '" alt="' . $name . '" loading="lazy" />';
    }
    echo '<span>' . $name . '</span></a>';
}

function clarity_get_page_template($widget): ?string
{
    try {
        $ref = new \ReflectionClass($widget);
        if (!$ref->hasProperty('pageRow')) {
            return null;
        }
        $prop = $ref->getProperty('pageRow');
        $prop->setAccessible(true);
        $pageRow = $prop->getValue($widget);
        $type = $widget->parameter->type ?? 'index';
        if (false === strpos($type, '_page')) {
            $type .= '_page';
        }
        $indexBase = '';
        if (isset($widget->options) && isset($widget->options->index)) {
            $indexBase = (string) $widget->options->index;
        } else {
            $opts = \Typecho\Widget::widget('Widget_Options');
            if (isset($opts->index)) {
                $indexBase = (string) $opts->index;
            }
        }
        return \Typecho\Router::url($type, $pageRow, $indexBase);
    } catch (\Throwable $e) {
        return null;
    }
}

function clarity_page_url($widget, int $page): ?string
{
    if ($page <= 1 && method_exists($widget, 'getArchiveUrl')) {
        $firstUrl = $widget->getArchiveUrl();
        if (!empty($firstUrl)) {
            return $firstUrl;
        }
    }
    $template = clarity_get_page_template($widget);
    if (!$template) {
        return null;
    }
    $url = str_replace(['{page}', '%7Bpage%7D'], (string) $page, $template);
    return $url;
}

function clarity_render_pagination($widget, string $mode = 'index'): void
{
    $total = $widget->getTotal();
    $pageSize = $widget->parameter->pageSize ?? 10;
    $totalPages = (int) ceil($total / $pageSize);
    if ($totalPages <= 1) {
        return;
    }

    $current = (int) $widget->getCurrentPage();
    $current = $current > 0 ? $current : 1;

    $prevUrl = $current > 1 ? clarity_page_url($widget, $current - 1) : null;
    $nextUrl = $current < $totalPages ? clarity_page_url($widget, $current + 1) : null;
    $enableJump = clarity_bool(clarity_opt('enable_page_jump', '0'));

    echo '<nav class="pagination-wrapper">';
    echo '<div class="pagination">';

    if ($prevUrl) {
        echo '<a class="page-btn page-prev" href="' . $prevUrl . '"><span class="icon-[ph--caret-left-bold]"></span></a>';
    } else {
        echo '<span class="page-btn page-prev disabled"><span class="icon-[ph--caret-left-bold]"></span></span>';
    }

    if ($mode === 'index' && !$enableJump) {
        echo '<div class="page-numbers">';
        if ($current > 2) {
            $firstUrl = clarity_page_url($widget, 1);
            echo '<a class="page-num" href="' . $firstUrl . '">1</a>';
        }
        if ($current > 3) {
            echo '<span class="page-ellipsis">...</span>';
        }
        if ($current > 1) {
            $prevNumUrl = clarity_page_url($widget, $current - 1);
            echo '<a class="page-num" href="' . $prevNumUrl . '">' . ($current - 1) . '</a>';
        }
        echo '<span class="page-num active">' . $current . '</span>';
        if ($current < $totalPages) {
            $nextNumUrl = clarity_page_url($widget, $current + 1);
            echo '<a class="page-num" href="' . $nextNumUrl . '">' . ($current + 1) . '</a>';
        }
        if ($current < $totalPages - 2) {
            echo '<span class="page-ellipsis">...</span>';
        }
        if ($current < $totalPages - 1) {
            $lastUrl = clarity_page_url($widget, $totalPages);
            echo '<a class="page-num" href="' . $lastUrl . '">' . $totalPages . '</a>';
        }
        echo '</div>';
    } else {
        echo '<span class="page-info"><span class="page-current">' . $current . '</span><span class="page-sep">/</span><span class="page-total">' . $totalPages . '</span></span>';
    }

    if ($nextUrl) {
        echo '<a class="page-btn page-next" href="' . $nextUrl . '"><span class="icon-[ph--caret-right-bold]"></span></a>';
    } else {
        echo '<span class="page-btn page-next disabled"><span class="icon-[ph--caret-right-bold]"></span></span>';
    }

    if ($enableJump) {
        $pattern = clarity_get_page_template($widget) ?: '';
        $firstUrl = clarity_page_url($widget, 1) ?: '';
        echo '<div class="page-jump">';
        echo '<input type="number" class="page-input" min="1" max="' . $totalPages . '" data-current-page="' . $current . '" data-total-pages="' . $totalPages . '" data-url-pattern="' . htmlspecialchars($pattern) . '" data-first-page-url="' . htmlspecialchars($firstUrl) . '" placeholder="页码" aria-label="输入页码" onkeypress="if(event.keyCode==13) jumpToPageWithPattern(this.nextElementSibling)" oninput="if(this.value>' . $totalPages . ') this.value=' . $totalPages . '" />';
        echo '<button class="page-jump-btn" onclick="jumpToPageWithPattern(this)" aria-label="跳转到指定页" title="跳转到指定页"><span class="icon-[ph--arrow-right-bold]"></span></button>';
        echo '</div>';
    }

    echo '</div>';
    echo '</nav>';
}

function clarity_get_widgets(): array
{
    $raw = trim((string) clarity_opt('aside_widgets', ''));
    
    if ($raw === '') {
        return ['stats', 'tech-info', 'community'];
    }
    
    return clarity_parse_lines($raw);
}

function clarity_get_latest_post_time(): ?string
{
    $latest = \Typecho\Widget::widget('Widget_Contents_Post_Recent', 'pageSize=1');
    if ($latest->have()) {
        $latest->next();
        return $latest->date->format('c');
    }
    return null;
}

function clarity_get_post_count(): int
{
    $stat = \Typecho\Widget::widget('Widget_Stat');
    return (int) ($stat->publishedPostsNum ?? 0);
}

function clarity_get_recent_comments(int $limit = 5): array
{
    try {
        // 使用 Typecho 原生的 Widget 方式获取评论
        $comments = \Typecho\Widget::widget('Widget_Comments_Recent', 'pageSize=' . $limit);
        
        $results = [];
        while ($comments->next()) {
            // 获取文章信息
            $post = null;
            try {
                $db = \Typecho\Db::get();
                $post = $db->fetchRow(
                    $db->select('title', 'slug', 'type', 'created')
                        ->from('table.contents')
                        ->where('cid = ?', $comments->cid)
                        ->limit(1)
                );
            } catch (\Throwable $e) {
                // ignore
            }
            
            // 构建文章链接 - 根据你的固定链接规则
            $permalink = '';
            if (!empty($post)) {
                $options = \Typecho\Widget::widget('Widget_Options');
                $type = $post['type'];
                $slug = $post['slug'];
                $cid = $comments->cid;
                
                // 根据文章类型构建链接
                if ($type == 'post') {
                    // 文章链接: /archives/{cid}/
                    $permalink = $options->index . '/archives/' . $cid . '/';
                } else {
                    // 页面链接: /{slug}
                    $permalink = $options->index . '/' . $slug;
                }
            }
            
            $results[] = [
                'coid' => $comments->coid,
                'cid' => $comments->cid,
                'author' => $comments->author,
                'mail' => $comments->mail,
                'url' => $comments->url,
                'text' => $comments->text,
                'created' => $comments->created,
                'post_title' => $post['title'] ?? '',
                'post_permalink' => $permalink,
            ];
        }
        
        return $results;
    } catch (\Throwable $e) {
        return [];
    }
}

function clarity_views_cookie_key(): string
{
    return 'clarity_contents_views';
}

function clarity_views_cookie_read(): array
{
    $raw = '';
    if (class_exists('\\Typecho\\Cookie')) {
        $raw = (string) \Typecho\Cookie::get(clarity_views_cookie_key(), '');
    } elseif (class_exists('Typecho_Cookie')) {
        $raw = (string) Typecho_Cookie::get(clarity_views_cookie_key());
    }

    if ($raw === '') {
        return [];
    }

    $items = array_filter(array_map('trim', explode(',', $raw)), function ($item) {
        return $item !== '';
    });
    return array_values(array_unique($items));
}

function clarity_views_cookie_write(array $items): void
{
    $items = array_values(array_unique(array_filter(array_map('strval', $items), function ($item) {
        return $item !== '';
    })));
    if (count($items) > 1000) {
        $items = array_slice($items, -1000);
    }
    $value = implode(',', $items);

    if (class_exists('\\Typecho\\Cookie')) {
        \Typecho\Cookie::set(clarity_views_cookie_key(), $value);
    } elseif (class_exists('Typecho_Cookie')) {
        Typecho_Cookie::set(clarity_views_cookie_key(), $value);
    }
}

function clarity_views_cookie_has(int $cid): bool
{
    if ($cid <= 0) {
        return true;
    }
    return in_array((string) $cid, clarity_views_cookie_read(), true);
}

function clarity_views_cookie_add(int $cid): void
{
    if ($cid <= 0) {
        return;
    }
    $items = clarity_views_cookie_read();
    $items[] = (string) $cid;
    clarity_views_cookie_write($items);
}

function clarity_views_is_single($post): bool
{
    if (!is_object($post) || !method_exists($post, 'is')) {
        return false;
    }
    try {
        return (bool) $post->is('single');
    } catch (\Throwable $e) {
        return false;
    }
}

function clarity_views_ensure_column(): bool
{
    static $exists = null;
    if ($exists !== null) {
        return $exists;
    }

    $exists = false;
    $db = null;
    try {
        $db = \Typecho\Db::get();
        // If this query succeeds, the column exists even when the table has no rows.
        $db->fetchRow($db->select('views')->from('table.contents')->limit(1));
        $exists = true;
        return true;
    } catch (\Throwable $e) {
    }

    try {
        if (!$db) {
            $db = \Typecho\Db::get();
        }
        $adapterObj = method_exists($db, 'getAdapter') ? $db->getAdapter() : null;
        $quoteColumn = ($adapterObj && method_exists($adapterObj, 'quoteColumn'))
            ? [$adapterObj, 'quoteColumn']
            : null;
        $tableName = $db->getPrefix() . 'contents';
        $tableSql = $quoteColumn ? call_user_func($quoteColumn, $tableName) : $tableName;
        $viewsColumnSql = $quoteColumn ? call_user_func($quoteColumn, 'views') : 'views';
        $adapter = strtolower((string) $db->getAdapterName());
        if (strpos($adapter, 'pgsql') !== false) {
            $db->query('ALTER TABLE ' . $tableSql . ' ADD COLUMN ' . $viewsColumnSql . ' INTEGER DEFAULT 0');
        } elseif (strpos($adapter, 'sqlite') !== false) {
            $db->query('ALTER TABLE ' . $tableSql . ' ADD COLUMN ' . $viewsColumnSql . ' INTEGER NOT NULL DEFAULT 0');
        } else {
            $db->query('ALTER TABLE ' . $tableSql . ' ADD ' . $viewsColumnSql . ' INT(10) NOT NULL DEFAULT 0');
        }
        $db->fetchRow($db->select('views')->from('table.contents')->limit(1));
        $exists = true;
    } catch (\Throwable $e) {
        $exists = false;
    }

    return $exists;
}

function clarity_get_views($post): ?int
{
    $cid = isset($post->cid) ? (int) $post->cid : 0;
    if ($cid <= 0) {
        return null;
    }

    if (function_exists('get_post_view')) {
        try {
            ob_start();
            $value = get_post_view($post);
            $echoed = trim((string) ob_get_clean());
            if (is_numeric($value)) {
                return (int) $value;
            }
            if (is_numeric($echoed)) {
                return (int) $echoed;
            }
        } catch (\Throwable $e) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
        }
        try {
            ob_start();
            $value = get_post_view($cid);
            $echoed = trim((string) ob_get_clean());
            if (is_numeric($value)) {
                return (int) $value;
            }
            if (is_numeric($echoed)) {
                return (int) $echoed;
            }
        } catch (\Throwable $e) {
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
        }
    }
    // 适配 TePostViews 插件的 viewsNum 字段
    if (isset($post->viewsNum)) {
        return (int) $post->viewsNum;
    }

    if (!clarity_views_ensure_column()) {
        if (isset($post->views)) {
            return (int) $post->views;
        }
        return null;
    }

    try {
        $db = \Typecho\Db::get();
        $row = $db->fetchRow(
            $db->select('views')
                ->from('table.contents')
                ->where('cid = ?', $cid)
                ->limit(1)
        );
        $views = isset($row['views']) ? (int) $row['views'] : 0;

        if (clarity_views_is_single($post) && !clarity_views_cookie_has($cid)) {
            $views++;
            $db->query(
                $db->update('table.contents')
                    ->rows(['views' => $views])
                    ->where('cid = ?', $cid)
            );
            clarity_views_cookie_add($cid);
        }

        return $views;
    } catch (\Throwable $e) {
        if (isset($post->views)) {
            return (int) $post->views;
        }
        return null;
    }
}

function clarity_should_show_toc($widget, string $type): bool
{
    $enabled = $type === 'post'
        ? clarity_bool(clarity_opt('enable_post_toc', '1'))
        : clarity_bool(clarity_opt('enable_page_toc', '0'));
    if (!$enabled) {
        return false;
    }
    if (isset($widget->fields->toc)) {
        return clarity_bool($widget->fields->toc, $enabled);
    }
    return $enabled;
}

// 友链朋友圈相关功能

if (!defined('CLARITY_FCIRCLE_CACHE_TTL')) {
    define('CLARITY_FCIRCLE_CACHE_TTL', 600);
}

function clarity_fcircle_cache_dir(): string
{
    return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'cache';
}

function clarity_fcircle_cache_file(): string
{
    return clarity_fcircle_cache_dir() . DIRECTORY_SEPARATOR . 'clarity-fcircle-data.json';
}

function clarity_fcircle_cache_read(): ?array
{
    $file = clarity_fcircle_cache_file();
    if (!is_file($file)) {
        return null;
    }
    $raw = @file_get_contents($file);
    if (!is_string($raw) || $raw === '') {
        return null;
    }
    $payload = json_decode($raw, true);
    if (!is_array($payload) || !isset($payload['time'], $payload['data']) || !is_array($payload['data'])) {
        return null;
    }
    if (time() - (int) $payload['time'] > CLARITY_FCIRCLE_CACHE_TTL) {
        return null;
    }
    return $payload['data'];
}

function clarity_fcircle_cache_write(array $data): void
{
    $dir = clarity_fcircle_cache_dir();
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    if (!is_dir($dir) || !is_writable($dir)) {
        return;
    }
    $file = clarity_fcircle_cache_file();
    $payload = [
        'time' => time(),
        'data' => $data,
    ];
    @file_put_contents($file, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function clarity_fetch_fcircle_data(string $apiUrl): ?array
{
    $url = rtrim($apiUrl, '/') . '/all.json';
    $headers = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
        'Accept' => 'application/json',
    ];
    
    $response = '';
    $headerLines = [];
    foreach ($headers as $name => $value) {
        $headerLines[] = $name . ': ' . $value;
    }

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headerLines,
            CURLOPT_USERAGENT => $headers['User-Agent'],
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode !== 200) {
            $response = '';
        }
    }

    if (!is_string($response) || $response === '') {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headerLines),
                'timeout' => 15,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $response = @file_get_contents($url, false, $context);
    }

    if (!is_string($response) || $response === '') {
        return null;
    }
    
    $data = json_decode($response, true);
    if (!is_array($data)) {
        return null;
    }
    
    return $data;
}

function clarity_get_fcircle_data(string $apiUrl): array
{
    static $memo = null;
    if ($memo !== null) {
        return $memo;
    }

    $cached = clarity_fcircle_cache_read();
    if (is_array($cached)) {
        $memo = $cached;
        return $cached;
    }

    $data = clarity_fetch_fcircle_data($apiUrl);
    if (is_array($data)) {
        clarity_fcircle_cache_write($data);
        $memo = $data;
        return $data;
    }

    return [
        'statistical_data' => [
            'friends_num' => 0,
            'active_num' => 0,
            'article_num' => 0,
            'last_updated_time' => '',
        ],
        'article_data' => [],
    ];
}

// 关于页面数据获取函数
function clarity_get_about_data(): array
{
    // 作者标签
    $leftTagsRaw = trim((string) clarity_opt('about_author_left_tags', ''));
    $rightTagsRaw = trim((string) clarity_opt('about_author_right_tags', ''));
    
    $leftTags = [];
    $rightTags = [];
    
    if (!empty($leftTagsRaw)) {
        $lines = array_filter(array_map('trim', explode("\n", $leftTagsRaw)));
        $leftTags[] = [
            '标签1' => $lines[0] ?? '',
            '标签2' => $lines[1] ?? '',
            '标签3' => $lines[2] ?? '',
            '标签4' => $lines[3] ?? '',
        ];
    }
    
    if (!empty($rightTagsRaw)) {
        $lines = array_filter(array_map('trim', explode("\n", $rightTagsRaw)));
        $rightTags[] = [
            '标签1' => $lines[0] ?? '',
            '标签2' => $lines[1] ?? '',
            '标签3' => $lines[2] ?? '',
            '标签4' => $lines[3] ?? '',
        ];
    }
    
    // 轮播文字
    $maskWordsRaw = trim((string) clarity_opt('about_card_mask_words', ''));
    $maskWords = [];
    if (!empty($maskWordsRaw)) {
        $lines = array_filter(array_map('trim', explode("\n", $maskWordsRaw)));
        $maskWords[] = [
            '第一' => $lines[0] ?? '学习',
            '第二' => $lines[1] ?? '生活',
            '第三' => $lines[2] ?? '程序',
            '第四' => $lines[3] ?? '体验',
        ];
    }
    
    // 技能列表
    $skillsRaw = trim((string) clarity_opt('about_skills', ''));
    $skills = [];
    if (!empty($skillsRaw)) {
        $lines = array_filter(array_map('trim', explode("\n", $skillsRaw)));
        foreach ($lines as $line) {
            $skill = json_decode($line, true);
            if (is_array($skill) && isset($skill['name'])) {
                $skills[] = $skill;
            }
        }
    }
    
    return [
        'author' => [
            '左侧' => $leftTags,
            '右侧' => $rightTags,
        ],
        'myinfo' => [
            [
                '标题一' => clarity_opt('about_myinfo_title1', '你好，很高兴认识你👋'),
                '标题二' => clarity_opt('about_myinfo_title2', '我叫'),
                '博主名称' => clarity_opt('about_myinfo_name', '博主'),
                '内容一' => clarity_opt('about_myinfo_content1', '是一名 前端工程师、学生、'),
                '内容二' => clarity_opt('about_myinfo_content2', '博主'),
                '卡片' => [
                    [
                        '标题' => clarity_opt('about_card_tips', '追求'),
                        '内容1' => clarity_opt('about_card_content1', '源于'),
                        '内容2' => clarity_opt('about_card_content2', '热爱而去'),
                        '显示' => clarity_opt('about_card_inlineword', '感受'),
                        '轮播' => $maskWords,
                    ],
                ],
            ],
        ],
        'maxim' => [
            [
                'tip' => clarity_opt('about_maxim_tip', '座右铭'),
                'title1' => clarity_opt('about_maxim_title1', '生活明朗，'),
                'title2' => clarity_opt('about_maxim_title2', '万物可爱。'),
            ],
        ],
        'single' => [
            [
                'tip' => clarity_opt('about_single_tip', '心路历程'),
                'title' => clarity_opt('about_single_title', '为何而建站'),
                'content' => clarity_opt('about_single_content', '本站是综合类型博客，集成文章、说说、友链、留言、装备等栏目。'),
                'lishi' => clarity_opt('about_single_lishi', '『博客』历史进程'),
            ],
        ],
        'skills' => $skills,
    ];
}

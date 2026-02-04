

## 使用
- 将本主题目录放到 `usr/themes/clarity`
- Typecho 后台 → 外观 → 启用 `clarity`
- 外观 → 设置外观 → `clarity` 配置
- 建议创建以下独立页面并选择对应模板：
  - 友链：`page-links.php`
  - 归档：`page-archives.php`
  - 分类：`page-categories.php`
  - 标签云：`page-tags.php`
  - 图库：`page-photos.php`
  - 瞬间：`page-moments.php`
  - 追番：`page-bangumis.php`

## 配置说明
### 友链
- `友链页面标题`：显示在友链页与移动端标题
- `我的博客信息（JSON）`
  - 示例：`{"title":"我的博客","url":"https://example.com","logo":"","description":"一句话","rss":"/feed"}`
- Links 插件已启用时，友链数据自动读取插件表（按分类分组）
- Links 插件中 `sort` 字段作为分组名，未设置则归入“友链”
- `友链数据（JSON）`（当 Links 插件未启用时作为备用）
  - 示例：
    ```json
    [
      {
        "title": "友链",
        "description": "一些朋友",
        "links": [
          { "name": "站点", "url": "https://example.com", "logo": "", "desc": "描述" }
        ]
      }
    ]
    ```

### 图库
- `图库页面标题` / `图库页面描述`
- 图库默认读取当前页面的附件（媒体库上传后关联到该页面）
- 附件仅取图片类型，标题与描述用于卡片信息
- `图库数据（JSON）`（当页面附件为空时作为备用）
  - 示例：
    ```json
    [
      {
        "name": "travel",
        "displayName": "旅行",
        "photos": [
          { "url": "", "cover": "", "displayName": "标题", "description": "说明" }
        ]
      }
    ]
    ```

### 瞬间
- `瞬间页面标题`
- `微语数据（JSON）`（侧边栏与瞬间页共用）
  - 示例：
    ```json
    [
      {
        "id": "m1",
        "content": "<p>今天很棒</p>",
        "time": "2025-01-01 12:00",
        "tags": ["生活"],
        "media": [{"type":"PHOTO","url":""}],
        "likes": 0,
        "comments": 0
      }
    ]
    ```

### 追番
- `追番页面标题`
- `B 站 UID`（填写后自动拉取追番数据）

## 自定义字段
- `cover`：文章/页面封面图
- `toc`：文章/页面目录开关

## 说明
- 浏览量：如需显示真实阅读量，请安装提供 `get_post_view($cid)` 的插件。
- 友链自主提交、瞬间评论/点赞等 Halo 插件能力在 Typecho 中默认不可用，已保留前端样式。
- 天气组件需填写心知天气 API Key。

## 致谢
- Halo 主题 Clarity

<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
clarity_set('showAside', true);
clarity_set('pageTitle', null);
clarity_set('isLinksPage', false);
?>
<?php $this->need('header.php'); ?>

<div class="error-page proper-height">
  <span class="error-icon icon-[solar--ghost-smile-bold-duotone]"></span>
  <div class="error-title">[404] 页面走丢了</div>
  <div class="error-hint">页面走丢了，可能已被移除或链接有误</div>
  <div class="error-actions">
    <a href="<?php echo $this->options->siteUrl; ?>" class="error-btn">
      <span class="icon-[ph--house-bold]"></span>
      <span>返回主页</span>
    </a>
    <button onclick="history.back()" class="error-btn error-btn-secondary">
      <span class="icon-[ph--arrow-left-bold]"></span>
      <span>返回上页</span>
    </button>
  </div>
</div>

<?php $this->need('footer.php'); ?>

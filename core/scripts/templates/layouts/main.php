<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
  <title><?php echo $this->_title; ?></title>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <meta name="description" content="Lorem ipsum" />
  <link rel="stylesheet" href="<?php echo $this->_urlcss; ?>main.css" type="text/css" />
  <?php
      if (is_array($this->_css)) {
        foreach ($this->_css as $css) {
          echo '<link rel="stylesheet" href="'.$this->_urlcss.$css['name'].'.css" type="text/css" />';
        }
      }
  ?>
</head>
<body>

    <h1>Welcome to my site</h1>
    
    <?php echo $this->_action_content; ?>

</body>
</html>
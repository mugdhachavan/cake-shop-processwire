<?php namespace ProcessWire;

$home = $pages->get('/');
?>
<nav id="topnav" class="navbar">
  <div class="nav-inner">
    <a class="brand" href="<?= $home->url ?>"><?= $home->title ?></a>
    <ul id="nav-menu" class="menu">
      <?php foreach($home->and($home->children) as $p): if($p->name === 'messages') continue; ?>
        <li><a href="<?= $p->url ?>"><?= $p->title ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="nav-shadow"></div>
</nav>

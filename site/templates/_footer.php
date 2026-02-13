<?php namespace ProcessWire;

$home = $pages->get('/');
$contact = $pages->get('/contact/');
?>
<footer class="site-footer" id="footer">
  <div class="footer-inner container">
    <div class="footer-brand">
      <a href="<?= $home->url ?>"><?= $home->title ?></a>
      <p>Sweet, premium cakes baked with love.</p>
    </div>
    <ul class="footer-menu">
      <?php foreach($home->and($home->children) as $p): if($p->name === 'messages') continue; ?>
        <li><a href="<?= $p->url ?>"><?= $p->title ?></a></li>
      <?php endforeach; ?>

    </ul>
    <div class="footer-copy">
      <small>&copy; <?= date('Y') ?> <?= $home->title ?></small>
    </div>
  </div>
  <div class="nav-shadow"></div>
</footer>

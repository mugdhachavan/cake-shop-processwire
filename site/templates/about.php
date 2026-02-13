<?php namespace ProcessWire;

?>

<div id="content">
  <section class="about-section">
    <div class="container split">
      <div class="text" data-animate>
        <?php if($page->experience_years): ?>
          <div class="badge"><span><?= (int) $page->experience_years ?></span> Years Experience</div>
        <?php endif; ?>
        <div class="about-body"><?= $page->get('body') ?></div>
      </div>
      <div class="media" data-animate>
        <?php $img = $page->image; if($img instanceof Pageimages) { $img = $img->first(); } ?>
        <?php if($img): ?>
          <img class="about-image slide-right" src="<?= $img->url ?>" alt="<?= $img->description ?: 'About image' ?>" loading="lazy">
        <?php endif; ?>
      </div>
    </div>
  </section>
  <section class="section section--light highlights">
    <h2>Our Values</h2>
    <div class="grid">
      <?php foreach($page->about_highlights as $item): ?>
        <?php $t = $item->about_title ?: $item->title; ?>
        <?php $d = $item->about_description ?: $item->descriptions; ?>
        <div class="card card--icon choose-card" data-animate>
          <?php if($item->icon): ?><i class="<?= $item->icon ?>"></i><?php endif; ?>
          <?php if($t): ?><h3><?= $t ?></h3><?php endif; ?>
          <?php if($d): ?><p><?= $d ?></p><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>

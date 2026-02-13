<?php namespace ProcessWire;

$productsPage = $pages->get('/product-1/');
$contact = $pages->get('/contact/');

?>

<div id="content">
  <?php $hero = $page->hero_image; if($hero instanceof Pageimages) { $hero = $hero->first(); } ?>
  <section class="hero" <?= $hero ? "style=\"background-image:url('{$hero->url}')\"" : "" ?>>
    <div class="overlay">
      <div class="hero-content" data-animate>
        <h1><?= $page->hero_heading ?></h1>
        <p><?= $page->hero_tagline ?></p>
        <div class="cta">
          <a href="<?= ($productsPage && $productsPage->id) ? $productsPage->url : (($cta && $cta instanceof Page) ? $cta->url : '#') ?>" class="btn primary">View Cakes</a>
          <?php $cta = $page->cta_link; if(is_int($cta)) { $cta = $pages->get($cta); } ?>
          <a href="<?= ($contact && $contact->id) ? $contact->url : (($cta && $cta instanceof Page) ? $cta->url : '#') ?>" class="btn secondary"><?= $page->cta_text ?></a>
        </div>
      </div>
    </div>
  </section>
  <section class="section section--light featured" id="featured">
    <h2>Featured Cakes</h2>
    <div class="grid grid--featured">
      <?php foreach($page->featured_products as $item): ?>
        <?php
          $img = $item->image;
          if($img instanceof Pageimages) { $img = $img->first(); }
          if(!$img) {
            $img = $item->product_image;
            if($img instanceof Pageimages) { $img = $img->first(); }
          }
        ?>
        <?php if($img): ?>
          <div class="card card--image" data-animate>
            <img src="<?= $img->url ?>" alt="<?= $img->description ?: 'Featured cake' ?>" loading="lazy">
          </div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </section>
  <section class="section why-us" id="why">
    <h2>Why Choose Us</h2>
    <div class="grid">
      <?php foreach($page->why_choose_us as $item): ?>
        <div class="card card--icon choose-card" data-animate>
          <?php if($item->icon): ?><i class="<?= $item->icon ?>"></i><?php endif; ?>
          <h3><?= $item->choose_title ?: $item->title ?></h3>
          <p><?= $item->choose_desc ?: $item->descriptions ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
  <section class="cta-section" id="cta">
    <div class="cta-inner" data-animate>
      <h2>Make Your Moments Sweeter</h2>
      <?php $cta2 = $page->cta_link; if(is_int($cta2)) { $cta2 = $pages->get($cta2); } ?>
      <a href="<?= ($contact && $contact->id) ? $contact->url : (($cta2 && $cta2 instanceof Page) ? $cta2->url : '#') ?>" class="btn primary"><?= $page->cta_text ?></a>
    </div>
  </section>
</div>

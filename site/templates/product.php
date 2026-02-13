<?php namespace ProcessWire;
$contact = $pages->get('/contact/');
?>

<div id="content">
  <?php $hasRepeater = ($page->cakes instanceof WireArray) && $page->cakes->count(); ?>
  <?php if($hasRepeater): ?>
    <section class="section section--light featured" id="products">
      <?php
        $cats = [];
        $fCat = $fields->get('product_category');
        if($fCat && $fCat->type && method_exists($fCat->type, 'getOptions')) {
          $opts = $fCat->type->getOptions($fCat);
          foreach($opts as $opt){
            $label = ($opt->title ?? '') ?: ($opt->value ?? '');
            $label = trim((string)$label);
            if($label){ $cats[$sanitizer->pageName($label)] = $label; }
          }
        } else {
          foreach($page->cakes as $i){
            $catVal = $i->product_category;
            if($catVal instanceof WireArray){
              foreach($catVal as $opt){
                $label = ($opt->title ?? '') ?: ($opt->value ?? '');
                $label = trim((string)$label);
                if($label){ $cats[$sanitizer->pageName($label)] = $label; }
              }
            } else if(is_object($catVal)) {
              $label = ($catVal->title ?? '') ?: ($catVal->value ?? '');
              if(!$label) { $label = $i->getFormatted('product_category'); }
              $label = trim((string)$label);
              if($label){ $cats[$sanitizer->pageName($label)] = $label; }
            } else {
              $label = $i->getFormatted('product_category');
              if(!$label || is_numeric($label)) { $label = (string)$catVal; }
              $label = trim((string)$label);
              if($label){ $cats[$sanitizer->pageName($label)] = $label; }
            }
          }
        }
      ?>
      <div class="section-header">
        <h2>Our Cakes</h2>
        <?php if(count($cats)): ?>
          <div class="filters" data-animate>
            <span class="chip chip--filter active" data-cat="all">All</span>
            <?php foreach($cats as $slug=>$label): ?>
              <span class="chip chip--filter" data-cat="<?= $slug ?>"><?= $label ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <?php $totalCards = $page->cakes->count(); $gridClass = $totalCards >= 4 ? 'grid grid--wide' : 'grid'; ?>
      <div class="<?= $gridClass ?>">
        <?php foreach($page->cakes as $item): ?>
          <?php
            $catValItem = $item->product_category;
            $labelsForCard = [];
            if($catValItem instanceof WireArray){
              foreach($catValItem as $opt){ $t = ($opt->title ?? '') ?: ($opt->value ?? ''); if($t){ $labelsForCard[] = $t; } }
            } else if(is_object($catValItem)) {
              $t = ($catValItem->title ?? '') ?: ($catValItem->value ?? '');
              if(!$t) { $t = $item->getFormatted('product_category'); }
              if($t){ $labelsForCard[] = $t; }
            } else {
              $t = $item->getFormatted('product_category');
              if(!$t || is_numeric($t)) { $t = (string)$catValItem; }
              if($t){ $labelsForCard[] = $t; }
            }
            $slugsForCard = array_map(function($x) use ($sanitizer){ return $sanitizer->pageName($x); }, $labelsForCard);
            $firstSlug = count($slugsForCard) ? $slugsForCard[0] : '';
            $allSlugs = implode(' ', $slugsForCard);
          ?>
          <article class="card card--product" data-animate data-category="<?= $firstSlug ?>" data-categories="<?= $allSlugs ?>">
            <?php $img = $item->product_image; if($img instanceof Pageimages) { $img = $img->first(); } ?>
            <?php if($img): ?>
              <div class="image-frame">
                <img src="<?= $img->url ?>" alt="<?= $item->cake_title ?: $item->title ?>" loading="lazy">
                <?php if($item->is_featured): ?>
                  <span class="badge badge--featured">Featured</span>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if($item->short_description): ?>
              <h3><cite><small><em><strong><?= $item->short_description ?></strong></em></small></cite></h3>
            <?php else: ?>
              <h3><?= $item->cake_title ?: $item->title ?></h3>
            <?php endif; ?>
            <div class="meta">
              <?php if($item->product_price): ?>
                <span class="chip chip--price"><?= $item->product_price ?></span>
              <?php endif; ?>
              <?php if($item->availability): ?>
                <?php $av = $item->availability; ?>
                <?php if($av instanceof PageArray || $av instanceof WireArray): ?>
                  <?php foreach($av as $a): ?>
                    <?php $t = $a->title ?: $a->value; ?>
                    <?php if($t): ?><span class="chip chip--availability <?= $sanitizer->pageName($t) ?>"><?= $t ?></span><?php endif; ?>
                  <?php endforeach; ?>
                <?php elseif($av instanceof Page): ?>
                  <?php $t = $av->title ?: $av->value; ?>
                  <?php if($t): ?><span class="chip chip--availability <?= $sanitizer->pageName($t) ?>"><?= $t ?></span><?php endif; ?>
                <?php else: ?>
                  <?php $t = (string)$av; ?>
                  <?php if($t): ?><span class="chip chip--availability <?= $sanitizer->pageName($t) ?>"><?= $t ?></span><?php endif; ?>
                <?php endif; ?>
              <?php endif; ?>
            </div>
            <?php if($item->productbody): ?>
              <div class="details"><cite><em><?= $item->productbody ?></em></cite></div>
            <?php endif; ?>
            <a class="btn view" href="<?= $contact && $contact->id ? $contact->url : '#' ?>">Order Now</a>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  <?php else: ?>
    <section class="section section--light featured" id="product">
      <h2><?= $page->title ?></h2>
      <div class="grid">
        <article class="card card--product" data-animate>
          <?php $img = $page->product_image; if($img instanceof Pageimages) { $img = $img->first(); } ?>
          <?php if($img): ?>
            <div class="image-frame">
              <img src="<?= $img->url ?>" alt="<?= $page->title ?>" loading="lazy">
            </div>
          <?php endif; ?>
          <?php if($page->short_description): ?>
            <h3><cite><small><em><strong><?= $page->short_description ?></strong></em></small></cite></h3>
          <?php else: ?>
            <h3><?= $page->title ?></h3>
          <?php endif; ?>
          <div class="meta">
            <?php if($page->product_price): ?>
              <span class="chip chip--price"><?= $page->product_price ?></span>
            <?php endif; ?>
            <?php if($page->availability): ?>
              <?php $av2 = $page->availability; ?>
              <?php if($av2 instanceof PageArray || $av2 instanceof WireArray): ?>
                <?php foreach($av2 as $a2): ?>
                  <?php $t2 = $a2->title ?: $a2->value; ?>
                  <?php if($t2): ?><span class="chip chip--availability <?= $sanitizer->pageName($t2) ?>"><?= $t2 ?></span><?php endif; ?>
                <?php endforeach; ?>
              <?php elseif($av2 instanceof Page): ?>
                <?php $t2 = $av2->title ?: $av2->value; ?>
                <?php if($t2): ?><span class="chip chip--availability <?= $sanitizer->pageName($t2) ?>"><?= $t2 ?></span><?php endif; ?>
              <?php else: ?>
                <?php $t2 = (string)$av2; ?>
                <?php if($t2): ?><span class="chip chip--availability <?= $sanitizer->pageName($t2) ?>"><?= $t2 ?></span><?php endif; ?>
              <?php endif; ?>
            <?php endif; ?>
          </div>
          <?php if($page->productbody): ?>
            <div class="details"><cite><em><?= $page->productbody ?></em></cite></div>
          <?php endif; ?>
          <a class="btn view" href="<?= $contact && $contact->id ? $contact->url : '#' ?>">Order Now</a>
        </article>
      </div>
    </section>
  <?php endif; ?>
</div>

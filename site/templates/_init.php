<?php namespace ProcessWire;

// Optional initialization file, called before rendering any template file.
// This is defined by $config->prependTemplateFile in /site/config.php.
// Use this to define shared variables, functions, classes, includes, etc. 

$cm = $templates->get('contact_message');
if(!$cm){
  $t = new Template();
  $t->name = 'contact_message';
  $t->label = 'Contact Message';
  $templates->add($t);
  $title = $fields->get('title');
  if($title) $t->fields->add($title);
  $f1 = $fields->get('customer_name'); if(!$f1){ $f1 = new Field(); $f1->name='customer_name'; $f1->label='Customer Name'; $f1->type=$modules->get('FieldtypeText'); $fields->add($f1); $f1->save(); }
  $f2 = $fields->get('customer_email'); if(!$f2){ $f2 = new Field(); $f2->name='customer_email'; $f2->label='Customer Email'; $f2->type=$modules->get('FieldtypeEmail'); $fields->add($f2); $f2->save(); }
  $f3 = $fields->get('customer_phone'); if(!$f3){ $f3 = new Field(); $f3->name='customer_phone'; $f3->label='Customer Phone'; $f3->type=$modules->get('FieldtypeText'); $fields->add($f3); $f3->save(); }
  $f4 = $fields->get('customer_message'); if(!$f4){ $f4 = new Field(); $f4->name='customer_message'; $f4->label='Customer Message'; $f4->type=$modules->get('FieldtypeTextarea'); $fields->add($f4); $f4->save(); }
  $f5 = $fields->get('created_at'); if(!$f5){ $f5 = new Field(); $f5->name='created_at'; $f5->label='Created At'; $f5->type=$modules->get('FieldtypeDatetime'); $fields->add($f5); $f5->save(); }
  $t->fields->add($f1); $t->fields->add($f2); $t->fields->add($f3); $t->fields->add($f4); $t->fields->add($f5);
  $t->save();
}
$mc = $templates->get('message_container');
if(!$mc){
  $mc = new Template();
  $mc->name = 'message_container';
  $mc->label = 'Message Container';
  $templates->add($mc);
  $title = $fields->get('title'); if($title) $mc->fields->add($title);
  $mc->save();
}
if($mc && $cm){
  $mc->childTemplates = array($cm->id);
  $mc->save();
}
$messages = $pages->get('/messages/');
if(!$messages || !$messages->id){
  $parentHome = $pages->get('/');
  if($parentHome && $mc){
    $messages = new Page();
    $messages->template = $mc;
    $messages->parent = $parentHome;
    $messages->name = 'messages';
    $messages->title = 'Messages';
    $messages->save();
  }
}

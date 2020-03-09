<?php

require_once 'template.class.php';

$baseDir = str_replace('\\', '/', dirname(__FILE__));
$temp = new template($baseDir.'/source/', $baseDir.'/compiled/');

$temp->assign('test', 'demo');
$temp->assign('pagetitle', 'aaa');

$temp->getSourceTemplate('index');
$temp->compileTemplate();
$temp->display();

///srv/http/CopycatSmarty/complied
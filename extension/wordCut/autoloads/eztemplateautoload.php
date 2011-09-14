<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] =
	array( 'script' => 'extension/wordCut/templateword_cutoperator.php',
	       'class' => 'TemplateWord_cutOperator',
	       'operator_names' => array( 'word_cut' ) );
	       
?>


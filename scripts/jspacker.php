<?php

if ($argc < 3) {
	echo 'you must specify  a source file and a result filename',"\n";
	echo 'example :', "\n", 'php jspacker.php <file1> <file2> <fileN> <output_file>',"\n";
	exit;
}

require dirname(__FILE__).'/jspacker/JavaScriptPacker.php';

#
unset($argv[0]);

#
$out = $argv[count($argv)];

$t1 = microtime(true);

# pack files
foreach ($argv as $js) {	
	if ($js == $out) continue;		
	$jscode .= file_get_contents($js);	
}

$packer = new JavaScriptPacker($jscode, 'Normal', true, false);
$packed = $packer->pack();

file_put_contents($out, $packed);

$t2 = microtime(true);
$time = sprintf('%.4f', ($t2 - $t1) );
echo 'script(s) packed in ' .$out. ' in '. $time. ' s.'. "\n";

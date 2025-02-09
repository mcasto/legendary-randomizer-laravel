<?php
$xml    = file_get_contents(__DIR__ . "/query_result.xml");
$parser = xml_parser_create();

xml_parse_into_struct($parser, $xml, $vals);
xml_parser_free($parser);

$handlerList = glob(dirname(__DIR__) . '/app/Services/Handlers/*_Handler.php');
foreach ($handlerList as $filename) {
 unlink($filename);
}

$recs = [];
$row  = false;
$rec  = [];
foreach ($vals as $val) {
 if ($val['tag'] == 'ROW' && $val['type'] == 'open') {
  $row = true;
  continue;
 }

 if ($val['tag'] == 'FIELD') {
  $name       = $val['attributes']['NAME'];
  $value      = $val['value'];
  $rec[$name] = $value;
 }

 if ($val['tag'] == 'ROW' && $val['type'] == 'close') {
  $recs[] = $rec;
  $rec    = [];
 }
}

$recs = array_filter($recs, function ($rec) {
 return in_array($rec['list'], ['schemes', 'masterminds', 'villains', 'henchmen', 'heroes']);
});

$recs = array_map(function ($row) {
 $rec       = json_decode($row['rec']);
 $rec->list = $row['list'];

 return $rec;
}, $recs);

foreach ($recs as $rec) {
 $list      = $rec->list;
 $name      = $rec->name;
 $expansion = $rec->set;

 print_r(['list' => $list, 'name' => $name, 'expansion' => $expansion]);

 $cmd = "php artisan make:handler \"$list\" \"$name\" \"$expansion\"";
 shell_exec($cmd);
}

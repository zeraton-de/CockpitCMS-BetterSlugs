<?php

/**
 * @file
 * Implements CLI Command for updating collection entries slugs.
 * The update will take in consideration the slug definition of the collection.
 */

if (!COCKPIT_CLI) {
  return;
}

$name = $app->param('name', TRUE);

if (!$name) {
  return CLI::writeln("--name parameter is missing", FALSE);
}

if (!$collection = $app->module('collections')->collection($name)) {
  return CLI::writeln("Collection '{$name}' doesnt exists!", FALSE);
}

$_id = $collection['_id'];

$slugField = FALSE;
foreach ($collection['fields'] as $field) {
  if ($field['type'] === 'slug') {
    $slugField = [
      'name' => $field['name'],
      'format' => $field['options']['format'],
    ];
  }
}

if (!$slugField) {
  return CLI::writeln("Collection '{$name}' doesnt contain a slug field!", FALSE);
}

$start = microtime(TRUE);

$collection_name = $app->storage->getCollection("collections/{$_id}")->getCollectionName();

$entries = $app->storage->getCollection("collections/{$_id}")->find();
$entries = $entries->toArray();
$updated = 0;

CLI::writeln("");
CLI::writeln("Collection '{$name}' - Refreshing slugs...");

foreach ($entries as $idx => $entry) {
  if (isset($entry[$slugField['name']])) {
    $entry[$slugField['name']] = '';
    $entry = $app->module('collections')->save($name, $entry);
    CLI::writeln("Slug for {$entry['_id']} updated to '{$entry[$slugField['name']]}'", TRUE);
    $updated++;
  }
  else {
    CLI::wirteln("${entry._id} - Field {$slugField['name']} not found!");
  }
}

$seconds = round(microtime(TRUE) - $start, 3);
CLI::writeln("Done! {$updated} entries updated in {$seconds}s.");

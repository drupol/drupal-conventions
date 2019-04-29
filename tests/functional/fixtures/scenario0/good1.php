<?php

function foo(array $arg) {
  if ($arg === 'foo') {
    return $arg;
  }

  if ($arg === 'foo') {
    return $arg;
  }
  elseif ($arg === TRUE) {
    return $arg;
  }  
  // do something
  
  $options = array(
    'a',
    'b',
    'c', );

  if (in_array($arg, $options, true)) {
  }

  try {
    // do something dangerous
  } catch (Exception $e) {
    // exception caught
  } finally {
    // do something
  }
}

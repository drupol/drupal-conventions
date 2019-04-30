<?php

function foo(array $arg)
{
  if ($arg == 'foo') {
    return $arg;
  }

  if ($arg == 'foo')
  {
    return $arg;
  } elseif ($arg === true) {
    return $arg;
  } else {
    // do something
  }


      $ArrayMultiline1=[
        'a',
        'b',
        'c'];


  $ArrayMultiline2=array(
    'coin',
    'plop',
  );


  $ArrayMultiline3=['a','b','c'];



  if (in_array($arg, $options)) {}


  try
  {
    // do something dangerous
  }catch (Exception $e) {
    // exception caught
  }
  finally {
    // do something
  }

}


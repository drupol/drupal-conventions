<?php function foo (array $arg)
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

  // Empty comment

  //

      $ArrayMultiline1=[
        'a',
        'b',
        'c'];


  $ArrayMultiline2=array(
    'coin',
    'plop',
  );
  foreach ($ArrayMultiline2 as $index => $value)
  {
    unset($value);
  }

  $ArrayMultiline3=['a','b','c'];


  $concat = "string".'string'."string";


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

  echo "echo";

  $class = new stdClass;

}

?>

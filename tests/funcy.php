<?php
  require '../lib/funcy.php';
  require './asserts.php';
  
  // Helper functions
  function println($str)
  {
    echo $str . PHP_EOL;
  }
  
  // Testing functions
  function test_compose()
  {
    $a = function($x) { return $x . 'a'; };
    $b = function($x) { return $x . 'b'; };
    $c = function($x) { return $x . 'c'; };
    $fn = compose($a, $b, $c);
    
    return is_identical($fn('d'), 'dcba');
  }
  
  function test_sequence()
  {
    $a = function($x) { return $x . 'a'; };
    $b = function($x) { return $x . 'b'; };
    $c = function($x) { return $x . 'c'; };
    $fn = sequence($a, $b, $c);
    
    return is_identical($fn('d'), 'dabc');
  }
  
  function test_curry()
  {
    $a = function($a, $b) { return $a + $b; };
    $add5 = curry($a, 5);
    
    return is_identical($add5(10), 15);
  }
  
  function test_map()
  {
    $a = function($x) { return $x * $x; };
    $range = range(0, 10);
    
    return is_identical(map($a, $range), array_map($a, $range));
  }
  
  function test_reduce()
  {
    $a = function($acc, $x) { return $acc + $x; };
    $range = range(0, 5);
    
    return is_identical(reduce($a, 1, $range), 16); // 1+0+1+2+3+4+5
  }
  
  function test_flip()
  {
    $a = function($a, $b) { return pow($a, $b); };
    $b = function($a, $b, $c) { return $a . $b . $c; };
    
    $af = flip($a);
    $bf = flip($b);
    
    return is_identical($af(10, 2), $a(2, 10))
        && is_identical($bf('a', 'b', 'c'), $b('b', 'a', 'c'));
  }
  
  // Extract tests and run!
  $functions = get_defined_functions();
  $functions = $functions['user'];
  $functions = preg_grep('/^test_/', $functions);
  
  // How many tests succeded/failed
  $success = array();
  
  println('== Starting tests ==');
  println('');
  
  // Run the tests!
  foreach ($functions as $function)
  {
    println("[$function]");
    try
    {
      $function();
    }
    catch (Exception $e)
    {
      println("ERROR");
      println($e->getMessage());
      $success[$function] = FALSE;
      println('');
      continue;
    }
    
    $success[$function] = TRUE;
    println("success!");
    println('');
  }
  
  $nays = array_filter($success, function($x) { return ! $x; });
  $nnays = count($nays);
  $total = count($functions);
  
  echo "{$nnays} failed tests out of {$total} tests";
  
/* End of file funcy.php */
/* Location: ./tests/funcy.php */ 
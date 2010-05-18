<?php
  /**
   * Function composition
   * 
   * Example (pseudocode)
   * --------------------
   *     fa(x): return x + "a"
   *     fb(y): return y + "b"
   *     fc(z): return z + "c"
   *     
   *     print sequence(fa, fb, fc)('❤')
   *     // prints: ❤cba
   * 
   * GOTCHA: Errors triggered by non-existing functions will have their
   * index in reverse order: `arg#0` is actually the last argument. This
   * is due to the fact that `compose` is defined using `sequence`.
   * 
   * @param callback₁, callback₂, … callbackᵪ
   * @return closure ∫(callback₁(callback₂(callbackᵪ(…))))
   */
  function compose()
  {
    $fns = func_get_args();
    return call_user_func_array('sequence', array_reverse($fns));
  }
  
  /**
   * Function composition in sequence
   * 
   * Example (pseudocode)
   * --------------------
   *     fa(x): return x + "a"
   *     fb(y): return y + "b"
   *     fc(z): return z + "c"
   *     
   *     print sequence(fa, fb, fc)('❤')
   *     // prints: ❤abc
   * 
   * @param callback₁, callback₂, … callbackᵪ
   * @return closure ∫(callbackᵪ(callback₂(callback₁(…))))
   */
  function sequence()
  {
    $fns = func_get_args();
    
    foreach ($fns as $i => $fn)
    {
        if ( ! is_callable($fn, FALSE, $name))
        {
          trigger_error("All arguments must be valid callbacks (arg#{$i}: “${name}” failed)", E_USER_ERROR);
        }
    }
    
    return function () use ($fns) {
      $args = func_get_args();
      $acc  = call_user_func_array(array_shift($fns), $args); 
      
      foreach ($fns as $fn)
      {
        $acc = $fn($acc);
      }
      
      return $acc;
    };
  }
  
  /**
   * Partial function application
   * 
   * Example (pseudocode)
   * --------------------
   *     fa(greet, name): return greet + ' ' + name
   *     fx = curry(fa, 'Hello')
   *
   *     fx('Kim') // Hello Kim
   *     fx('Elin') // Hello Elin
   * 
   * @param callback
   * @param a1, a2…
   * @return closure ∫(callback(a1, a2, …))
   */
  function curry($fn)
  {
    $args = func_get_args();
    array_shift($args);
    
    if ( ! is_callable($fn))
    {
      trigger_error('First argument must be a valid callback', E_USER_ERROR);
    }
    
    return function() use ($fn, $args) {
      $xargs = func_get_args();
      return call_user_func_array($fn, array_merge($args, $xargs));
    };
  }
  
  /**
   * Execute the callback on each element in the sequence
   * 
   * Example (pseudocode)
   * --------------------
   *     greet(name): return uppercase(name)
   *     names = 'Kim', 'Elin', 'Kelin'
   * 
   *     map(greet, names) // 'KIM', 'ELIN', 'KELIN'
   * 
   * @see array_map
   * @param callback
   * @param Iterator
   * @return array
   */
  function map($fn, $iterable)
  {
    if ( ! is_callable($fn))
    {
      trigger_error('First argument must be a valid callback', E_USER_ERROR);
    }
    
    $result = array();
    
    foreach ($iterable as $key => $val)
    {
      $result[$key] = $fn($val);
    }
    
    return $result;
  }
  
  /**
   * Executes the callback on `init` and the first element, then it executes
   * the callback on the result of the previous execution and the second element
   * and so on.
   * 
   * Example (pseudocode)
   * --------------------
   *     add(a, b): return a + b
   *     numbers = 0, 1, 2, 3, 4, 5    
   * 
   *     print reduce(add, 0, numbers) // 15
   * 
   * @param callback
   * @param mixed init
   * @param Iterator
   * @return array
   */
  function reduce($fn, $init, $iterable)
  {
    if ( ! is_callable($fn))
    {
      trigger_error('First argument must be a valid callback', E_USER_ERROR);
    }
    
    $acc = $init;
    
    foreach ($iterable as $x)
    {
      $acc = $fn($acc, $x);
    }
    
    return $acc;
  }
  
  /**
   * Flips the (first two) arguments of a function.
   * 
   * Example (pseudocode)
   * --------------------
   *     pow(a, b): return a ** b
   *     wop: flip(pow)
   *     
   *     print wop(10, 2) // 1024
   * 
   * @param callback
   * @return closure
   */
  function flip($fn)
  {
    return function() use ($fn) {
      $args = func_get_args();
      $a = array_shift($args);
      $b = array_shift($args);
      $args = array_merge(array($b, $a), $args);
      return call_user_func_array($fn, $args);
    };
  }

/* End of file funcy.php */
/* Location: ./lib/funcy.php */ 
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

/* End of file funcy.php */
/* Location: ./lib/funcy.php */ 
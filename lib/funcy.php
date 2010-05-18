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

/* End of file funcy.php */
/* Location: ./lib/funcy.php */ 
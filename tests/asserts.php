<?php
  
  function is_identical($a, $b)
  {
    if ($a !== $b)
    {
      $a = var_export($a, TRUE);
      $b = var_export($b, TRUE);
      throw new Exception("not identical:\n{$a}\n{$b}\n\n");
    }
    
    return TRUE;
  }
  
/* End of file asserts.php */
/* Location: ./tests/asserts.php */ 
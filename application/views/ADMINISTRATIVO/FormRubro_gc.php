<aside class="right-side strech">
  
    <?php 
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

  <?php echo "<h4><b>Ingreso de Rubro</b></h4>"; ?>       
  <div style='height:20px;'></div>  
    <div>
    <?php echo $output; ?>
    </div>

</aside>    


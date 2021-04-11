<?php include('server.php') ?>

<?php $errors =[];

//          WE DONT REALLY USE THIS PART BUT ITS FOR THE ERRORS
?>
<?php if(count($errors) > 0) : ?>
  <div>
    <?php foreach ($errors as $error) : ?>{
    <p><?php echo $error ?></p>
    }
    <?php endforeach ?>

  </div>
<?php endif ?>

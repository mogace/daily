<html>
<script src=like.js?t=1573132492></script>
<?php echo $this->value['data']; ?>, <?php echo $this->value['person']; ?>

<ul>
	<?php foreach ((array)$this->value['b'] as $K => $V) { ?> <li> <?php echo $V; ?> </li> <?php } ?>	
</ul>

<?php
	echo $pai*2;
?>

<?php if ($data == 'abc') { ?>
我是abc
<?php }else if ($data == 'def') { ?>
我是def
<?php } else { ?>
我就是我,<?php echo $this->value['data']; ?>
<?php } ?>

{#注释不会出现在编译后的php代码中#}
123456----
</html>
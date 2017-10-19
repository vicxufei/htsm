<?php
/**
 * Created by PhpStorm.
 * User: yefeng
 * Date: 16/9/23
 * Time: 上午10:01
 */
//http://m.htths.com/share.php?id=102819&ref=1309
$good_id = intval($_GET['id']);
$ref_id = !empty($_GET['ref']) ? intval($_GET['ref']) : 0;

$url = "http://m.htths.com/#item?goods_id=".$good_id;
//echo  $url;
?>
<script>
    sessionStorage.setItem('good_id',<?php echo $good_id; ?>);
    sessionStorage.setItem('ref_id',<?php echo $ref_id; ?>);
    localStorage.setItem('ref_id', <?php echo $ref_id; ?>);
    window.location.href="<?php echo $url; ?>";
</script>



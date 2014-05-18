<?php
if(isset($_GET['action']) && $_GET['action'] == 'teste'){
    echo "ALOHA";
}
ob_start();
?>
<h3>ETo para os dias xx</h3>

<?php
echo ob_get_clean();

?>


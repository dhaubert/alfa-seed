<?php
print_r($_POST);
if (isset($_POST['action']) && $_POST['action'] == 'importar') {
    echo "lendo_csv";
    print_r($_FILES);

//    $arquivo = fopen('enapet_inscritos.csv', 'r');
    die;
}
ob_start();
?>
<h3><i class="fa fa-sun-o"></i> Clima</h3>
<form name="clima" id="clima" >
    <p>Ler arquivo csv </p> <input type="file" name="importar" id="importar"/>
    <input type="hidden" id="action" name="action" value='importar'/>
    <input type="button" onclick="enviar_arquivo(); "class="btn btn-success" value="<?php echo _("Enviar"); ?>"/>
</form>
<?php
echo ob_get_clean();
?>
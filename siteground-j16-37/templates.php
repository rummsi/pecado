<?php
use Joomla\CMS\Factory;

$app = Factory::getApplication();
$input = $app->getInput();
$menu = $app->getMenu();

// Obtendo a variável 'view' da URL
$currentView = $input->getCmd('view', '');

// Verifica se estamos na visualização da "frontpage"
$isFrontPage = ($menu->getActive() == $menu->getDefault());

if ($sg == 'banner') :
    if ($currentView == 'frontpage') :
        // Seu código para o banner
    endif;
else :
    // Imprime o nome do site e o link do Joomla
    echo $app->get('sitename') . ', Powered by <a href="http://joomla.org/" class="sgfooter" target="_blank">Joomla!</a>';

    // Supondo que você queira verificar se esta é a página inicial
    if ($isFrontPage) :
        // Seu código para o rodapé
        ?>
        <!-- FOOTER BEGIN --><a href="http://www.siteground.com/joomla-templates.htm" target="_blank">Joomla template by SiteGround</a><!-- FOOTER END -->
        <?php
    endif;
endif;
?>
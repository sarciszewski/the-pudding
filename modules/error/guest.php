<?php
ob_start();

echo $twig->render('wrapper.twig', ['wrapped' => ob_get_clean()]);
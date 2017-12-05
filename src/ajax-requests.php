<?php
/**
 * Les paramètres en entrée ne sont pas validés, c'est voulu considérant
 * que mon but n'était pas de faire quelque chose d'ultra sécuritaire mais
 * seulement de prouver que j'avais juste assez compris Aurelia pour
 * modifier et ajouter du PHP :)
 *
 * ToDo : tout valider ce qui entre
 * ToDo : retourner des erreurs sur mauvaise validation
 * ToDo : essayer de faire "Reflection" pour savoir si
 *      - la classe existe dans le fichier
 *      - la fonction demandée existe pour la classe
 *      - si la signature correspond au nombre et types des paramètres
 *          passés dans $data['params']
 *
 * Validations des paramètres inexistant parce que ce n'était pas dans
 * les buts de l'exercice...
 */

$json = file_get_contents('php://input');
$data = json_decode($json, true, 9, JSON_BIGINT_AS_STRING);

include_once($data['file'].'.php');

$data['params'] = [$data['params']];

$classe = new $data['file'];
$results = $classe->{$data['function']}(...$data['params']);

if(empty($results))
{
    echo json_encode([
        'status' => 'error',
        'response' => [],
        ], JSON_FORCE_OBJECT);
} else {
    echo json_encode([
        'status' => 'ok',
        'response' => $results,
        ], JSON_FORCE_OBJECT);
}

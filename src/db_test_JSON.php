<?php
/**
 * Cette classe simule des accès à une base de donnée
 * pour retourner les Contacts voulus.
 *
 * ToDo : ajouter de la validation de données dans le fichier BD, càd que tout est bien formé et que les ligbes blanches sont ignorée
 * ToDo : validation des paramètres
  */

class db_test_JSON
{
    private $fichier_db = __FILE__ . '.json';
    private $contacts_db = 'contacts.inc.php';

    function __construct()
    {
        $this->fichier_db = str_replace('.php', '.json', __FILE__);

        if(!file_exists($this->fichier_db))
        {
            try
            {
                include_once($this->contacts_db);
                $contenu = json_encode($contacts, JSON_FORCE_OBJECT);
                file_put_contents($this->fichier_db, $contenu);
            } catch(ErrorException $e) {
                var_dump(__METHOD__ . ' :: ' . $e.message);
                return false;
            }
        }
    }

    function getContacts()
    {
        try
        {
            $contenu = file_get_contents($this->fichier_db);
            $contacts = json_decode($contenu, true);
            return $contacts;
        } catch(ErrorException $e) {
            var_dump(__METHOD__ . ' :: ' . $e.message);
            return false;
        }
    }

    function getContactDetails($id)
    {
        try
        {
            $contenu = file_get_contents($this->fichier_db);
            $contacts = json_decode($contenu, true);
            unset($contenu);
            $leContact = [];
            foreach($contacts AS $leContact)
            {
                if($leContact['id'] == $id) break;
            }
            return $leContact;
        } catch(ErrorException $e) {
            var_dump(__METHOD__ . ' :: ' . $e.message);
            return false;
        }
    }

    function saveContact(array $contact)
    {
        try
        {
            // Tout charger
            $contenu = file_get_contents($this->fichier_db);
            $contacts = json_decode($contenu, true);
            unset($contenu);

            // Trouver si le contact passé existe
            $idx = -1;
            $leContact = [];
            $found = -1;
            $nextIdx = -1;
            foreach($contacts AS $idx => $leContact)
            {
                if($leContact['id'] == $contact['id'])
                {
                    $found = $idx;
                }

                // Garantir que l'on trouve l'index le plus haut
                if($leContact['id'] > $nextIdx)
                {
                    $nextIdx = $leContact['id'];
                }
            }

            // Si le contact existe, mettre à jour le tableau ou sinon l'ajouter
            if($found > -1)
            {
                $contacts[$found] = $contact;
            } else {
                $contact['id'] = $nextIdx + 1;
                $contacts[] = $contact;
            }

            // Écrire la nouvelle liste de contacts
            $contenu = json_encode($contacts, JSON_FORCE_OBJECT);
            file_put_contents($this->fichier_db, $contenu);

            return $contact;
        } catch(ErrorException $e) {
            var_dump(__METHOD__ . ' :: ' . $e.message);
            return false;
        }
    }
}

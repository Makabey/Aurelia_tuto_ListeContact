<?php
/**
 * Cette classe simule des accès à une base de donnée
 * pour retourner les Contacts voulus.
 *
 * ToDo : ajouter de la validation de données dans le fichier BD, càd que tout est bien formé et que les ligbes blanches sont ignorée
 * ToDo : validation des paramètres
 * ToDo : pour 'saveContact', changer pour que l'écriture se fasse d'abord dans un STREAM MEMORY et ensuite écrire d'un bloc avec file_put_contents
 */

class db_test_CSV
{
    private $fichier_db = __FILE__ . '.csv';
    private $contacts_db = 'contacts.inc.php';

    function __construct()
    {
        $this->fichier_db = str_replace('.php', '.csv', __FILE__);

        if(!file_exists($this->fichier_db))
        {
            try
            {
                include_once($this->contacts_db);
                $fh = fopen($this->fichier_db, 'w');
                foreach($contacts AS $contact)
                {
                    fputcsv($fh, $contact);
                }
                fclose($fh);
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
			// Tout charger
			$contacts = file($this->fichier_db, FILE_IGNORE_NEW_LINES);

			$entetes = ['id', 'firstName', 'lastName', 'email', 'phoneNumber'];

			$ok = array_walk($contacts, function(&$contact, $cle, $entetes){
				$contact = str_getcsv($contact);
				$contact = array_combine($entetes, $contact);
				$contact['id'] = (int)$contact['id'];
			}, $entetes);
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
			// Tout charger
			$contacts = $this->getContacts();

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
            $contacts = $this->getContacts();

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
			unset($leContact);

            // Si le contact existe, mettre à jour le tableau ou sinon l'ajouter
            if($found > -1)
            {
                $contacts[$found] = $contact;
            } else {
                $contact['id'] = $nextIdx + 1;
                $contacts[] = $contact;
            }

            // Écrire la nouvelle liste de contacts
			$fh = fopen($this->fichier_db, 'w');
			foreach($contacts AS $leContact)
			{
				fputcsv($fh, $leContact);
			}
			fclose($fh);

            return $contact;
        } catch(ErrorException $e) {
            var_dump(__METHOD__ . ' :: ' . $e.message);
            return false;
        }
    }
}

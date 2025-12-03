<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\MVC\Model\AdminModel;
use AlboTelematico\Component\Albotelematico\Administrator\Table\AttoTable;

class AttoModel extends AdminModel
{
    public function getTable($type = 'Atto', $prefix = 'AlboTelematico\\Component\\Albotelematico\\Administrator\\Table\\', $config = [])
    {
        return new AttoTable(Factory::getContainer()->get('DatabaseDriver'));
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm(
            'com_albotelematico.atto',
            'atto',
            ['control' => 'jform', 'load_data' => $loadData]
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState(
            'com_albotelematico.edit.atto.data',
            []
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

            /**
         * Converte una data da d-m-Y a Y-m-d.
         * Se è vuota o non valida, restituisce null.
         * Se è già in Y-m-d la lascia così.
         */
        protected function normalizeDate(?string $value): ?string
        {
            $value = trim((string) $value);

            if ($value === '') {
                return null;
            }

            // Se è già in formato SQL (YYYY-mm-dd), la teniamo così
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                return $value;
            }

            // Proviamo a interpretarla come d-m-Y
            $dt = \DateTime::createFromFormat('d-m-Y', $value);

            if ($dt instanceof \DateTime) {
                return $dt->format('Y-m-d');
            }

            // Formato non riconosciuto
            return null;
        }

    /**
     * Salvataggio con gestione multipli allegati PDF (max 10, uno per volta)
     * + cancellazione allegati selezionati
     */
    public function save($data)
    {
        $app   = Factory::getApplication();
        $input = $app->input;

        // 1) ID atto (se esiste già)
        $id = 0;

        if (!empty($data['id'])) {
            $id = (int) $data['id'];
        } else {
            $id = $input->getInt('id', 0);
        }

        // 2) Recupera gli allegati esistenti dal DB
        $attachments = [];

        if ($id > 0) {
            $existing = $this->getItem($id);

            if (!empty($existing) && !empty($existing->file)) {
                $decoded = json_decode($existing->file, true);

                if (is_array($decoded)) {
                    $attachments = $decoded;
                } else {
                    // vecchio formato: singolo path
                    $attachments = [$existing->file];
                }
            }
        }

        // 3) Gestione cancellazione allegati selezionati
        $toDelete = $input->get('delete_attachments', [], 'array');

        if (!empty($toDelete) && !empty($attachments)) {
            // normalizziamo i percorsi da eliminare
            $toDelete = array_map('strval', $toDelete);

            $newAttachments = [];

            foreach ($attachments as $path) {
                if (in_array($path, $toDelete, true)) {
                    // elimina il file fisico
                    $fullPath = JPATH_ROOT . '/' . $path;
                    if (File::exists($fullPath)) {
                        File::delete($fullPath);
                    }
                    // non aggiungerlo alla nuova lista
                    continue;
                }

                // mantieni il file
                $newAttachments[] = $path;
            }

            $attachments = $newAttachments;
        }

        // 4) Nuovo file dal form (singolo, si aggiunge a quelli rimasti)
        $files = $input->files->get('jform', [], 'array');
        $file  = $files['file'] ?? null;

        if (!empty($file) && !empty($file['name'])) {
            // Controlla limite di 10
            if (count($attachments) >= 10) {
                $app->enqueueMessage('Hai già 10 allegati per questo atto. Non puoi aggiungerne altri.', 'warning');
            } else {
                if ((int) $file['error'] !== UPLOAD_ERR_OK) {
                    $this->setError('Errore upload (codice ' . (int) $file['error'] . ').');
                    return false;
                }

                $filename = $file['name'];
                $ext      = strtolower(File::getExt($filename));

                if ($ext !== 'pdf') {
                    $this->setError('Sono consentiti solo file PDF.');
                    return false;
                }

                // Cartella di destinazione
                $relativePath = 'images/albo_atti';
                $destFolder   = JPATH_ROOT . '/' . $relativePath;

                if (!Folder::exists($destFolder)) {
                    Folder::create($destFolder);
                }

                $safeName = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $filename);
                $safeName = uniqid('atto_', true) . '_' . $safeName;

                $destPath = $destFolder . '/' . $safeName;

                if (!File::upload($file['tmp_name'], $destPath)) {
                    $this->setError('Errore nel caricamento del file PDF.');
                    return false;
                }

                // Aggiungi alla lista allegati
                $attachments[] = $relativePath . '/' . $safeName;
            }
        }

        // 5) Scrivi l'elenco allegati nel campo "file" come JSON (o stringa vuota se vuoto)
        if (!empty($attachments)) {
            $data['file'] = json_encode($attachments);
        } else {
            // IMPORTANTE: usiamo stringa vuota, non null,
            // così Joomla aggiorna davvero il campo nel DB
            $data['file'] = '';
        }

        // --- NORMALIZZAZIONE DATE (da d-m-Y a Y-m-d) ---

        if (isset($data['document_date'])) {
            $data['document_date'] = $this->normalizeDate($data['document_date']);
        }

        if (isset($data['publish_start'])) {
            $data['publish_start'] = $this->normalizeDate($data['publish_start']);
        }

        if (isset($data['publish_end'])) {
            $data['publish_end'] = $this->normalizeDate($data['publish_end']);
        }

        // --- FINE NORMALIZZAZIONE DATE ---

        // --- NUMERAZIONE ALBO PRETORIO PER ANNO ---

        // Se l'ID è vuoto → è un nuovo atto
        $isNew = empty($data['id']);

        // Se vuoi permettere di forzare manualmente il numero,
        // puoi fare: if ($isNew && empty($data['albo_number'])) { ... }
        if ($isNew) {
            // Anno: prendiamo quello della data documento, se presente
            if (!empty($data['document_date'])) {
                // document_date è in formato YYYY-MM-DD
                $year = (int) substr($data['document_date'], 0, 4);
            } else {
                // fallback: anno corrente
                $year = (int) date('Y');
            }

            $db    = Factory::getContainer()->get('DatabaseDriver');
            $query = $db->getQuery(true)
                ->select('MAX(' . $db->quoteName('albo_number') . ')')
                ->from($db->quoteName('#__albo_atti'))
                ->where($db->quoteName('albo_year') . ' = ' . (int) $year);

            $db->setQuery($query);
            $maxNumber = (int) $db->loadResult();

            $nextNumber = $maxNumber + 1;

            // Impostiamo numero e anno
            $data['albo_number'] = $nextNumber;
            $data['albo_year']   = $year;
        }

        // --- FINE NUMERAZIONE ALBO ---

        // 6) Salvataggio standard dell'atto
        return parent::save($data);
    }
}

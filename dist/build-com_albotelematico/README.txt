1. Generare il pacchetto installabile con:
   powershell -ExecutionPolicy Bypass -File .\build-package.ps1

2. Installare lo ZIP creato in dist da Joomla, menu Sistema > Installa > Estensioni.

3. L'installazione crea le tabelle #__albo_atti e #__albo_categorie usando il prefisso del sito Joomla.

4. L'installazione crea, se manca, la cartella images\albo_atti per gli allegati PDF.

5. Per creare un gruppo dedicato:
   - creare il gruppo utenti da Joomla;
   - permettere l'accesso al pannello amministrativo;
   - aprire Componente > Albo telematico > Opzioni > Permessi;
   - consentire almeno "Access Administration Interface" per com_albotelematico.
